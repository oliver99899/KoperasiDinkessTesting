<?php

namespace App\Http\Controllers;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Converter;
use App\Models\Pinjaman;
use App\Models\Simpanan;
use App\Models\BungaPinjaman;
use App\Support\HmacSigner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class PinjamanController extends Controller
{
    public function index()
    {
        $pinjaman = Pinjaman::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('user.pinjaman', compact('pinjaman'));
    }

    public function create()
    {
        $hasPending = Pinjaman::where('user_id', Auth::id())
            ->whereIn('status', ['diajukan', 'verifikasi'])
            ->exists();

        if ($hasPending) {
            return redirect()->route('pinjaman.index')->with('error', 'Anda masih memiliki pengajuan yang sedang diproses.');
        }

        $totalSimpanan = Simpanan::where('user_id', Auth::id())
            ->whereNotNull('verified_at')
            ->sum('jumlah');

        $bungaList = BungaPinjaman::orderBy('tenor_bulan')->get();
    
        return view('user.pinjaman_create', compact('totalSimpanan', 'bungaList'));
    }

    public function destroy($id)
    {
        $pinjaman = Pinjaman::where('user_id', Auth::id())
                            ->where('status', 'diajukan') 
                            ->findOrFail($id);

        $pinjaman->forceDelete(); 

        return back()->with('success', 'Pengajuan pinjaman berhasil dibatalkan dan dihapus.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jumlah_pengajuan' => 'required|numeric|min:500000|max:50000000',
            'durasi_bulan' => 'required|integer|in:3,6,12,24',
            'alasan_pengajuan' => 'required|string|max:1000',
            'dokumen_pendukung' => 'nullable|array',
            'dokumen_pendukung.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        return DB::transaction(function () use ($request) {
            $hasPending = Pinjaman::where('user_id', Auth::id())
                ->whereIn('status', ['diajukan', 'verifikasi'])
                ->lockForUpdate()
                ->exists();

            if ($hasPending) {
                return redirect()->route('pinjaman.index')->with('error', 'Gagal: Pengajuan ganda tidak diizinkan.');
            }

            $dokumenPaths = [];
            if ($request->hasFile('dokumen_pendukung')) {
                foreach ($request->file('dokumen_pendukung') as $file) {
                    $path = $file->store('dokumen_pinjaman', 'public');
                    $dokumenPaths[] = $path;
                }
            }

            $year = date('Y');
            $count = Pinjaman::whereYear('tanggal_pengajuan', $year)->count() + 1;
            $nomorPinjaman = 'PJ-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

            $bunga = BungaPinjaman::where('tenor_bulan', $request->durasi_bulan)->firstOrFail();

            $pinjaman = Pinjaman::create([
                'user_id' => Auth::id(),
                'nomor_pinjaman' => $nomorPinjaman,
                'jumlah_pengajuan' => $request->jumlah_pengajuan,
                'durasi_bulan' => $request->durasi_bulan,
                'bunga_id' => $bunga->id,
                'bunga_persen' => $bunga->persen,
                'alasan_pengajuan' => $request->alasan_pengajuan,
                'status' => 'diajukan',
                'sisa_pinjaman' => 0,
                'tanggal_pengajuan' => now(),
                'dokumen_syarat' => $dokumenPaths,

            ]);

            $pdf = Pdf::loadView('pdf.surat_pengajuan', ['pinjaman' => $pinjaman]);
            
            $namaFile = 'Surat_Pengajuan_' . str_replace(' ', '_', Auth::user()->name) . '_' . date('dMY') . '.pdf';

            return $pdf->download($namaFile);
        });
    }

    public function downloadSurat($id)
    {
        $pinjaman = Pinjaman::with(['user.profile'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $pdf = Pdf::loadView('pdf.surat_pengajuan', ['pinjaman' => $pinjaman]);

        $namaFile = 'Surat_Pengajuan_' . str_replace(' ', '_', Auth::user()->name) . '_' . date('dMY') . '.pdf';

        return $pdf->download($namaFile);
    }

    public function downloadSuratWord($id)
    {
        $pinjaman = Pinjaman::with(['user.profile'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $phpWord = new PhpWord();
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(12);

        $section = $phpWord->addSection([
            'marginTop'    => Converter::cmToTwip(3),
            'marginBottom' => Converter::cmToTwip(2),
            'marginLeft'   => Converter::cmToTwip(3.5),
            'marginRight'  => Converter::cmToTwip(2),
        ]);

        // KOP SURAT
        $table = $section->addTable(['borderSize' => 0, 'borderColor' => 'FFFFFF']);
        $table->addRow();
        $cell1 = $table->addCell(1500);
        $cell1->addText('');
        $cell2 = $table->addCell(8000, ['borderBottomSize' => 12, 'borderBottomColor' => '000000']);
        $cell2->addText('PEMERINTAH KOTA SEMARANG', ['bold' => true, 'size' => 14], ['alignment' => 'center']);
        $cell2->addText('KOPERASI PEGAWAI DINAS KESEHATAN', ['bold' => true, 'size' => 16], ['alignment' => 'center']);
        $cell2->addText('Jl. Pandanaran 79, Kota Semarang, Jawa Tengah 50241', ['size' => 10, 'italic' => true], ['alignment' => 'center']);

        $section->addTextBreak(1);

        // JUDUL
        $section->addText('SURAT PERMOHONAN PINJAMAN', ['bold' => true, 'size' => 14, 'underline' => 'single'], ['alignment' => 'center']);
        $section->addTextBreak(1);

        // ISI SURAT
        $section->addText('Perihal: Permohonan Pinjaman Koperasi', ['bold' => true]);
        $section->addTextBreak(1);
        $section->addText('Yang bertanda tangan di bawah ini:');
        $section->addTextBreak(1);

        // DATA PEMOHON
        $tabelData = $section->addTable(['borderSize' => 0, 'borderColor' => 'FFFFFF']);
        $rows = [
            ['Nama',            $pinjaman->user->profile->nama_lengkap ?? $pinjaman->user->name],
            ['NIP / NIK',       $pinjaman->user->nip ?? '-'],
            ['No. HP / WA',     $pinjaman->user->profile->no_hp ?? '-'],
        ];
        foreach ($rows as $row) {
            $tabelData->addRow();
            $tabelData->addCell(2500)->addText($row[0]);
            $tabelData->addCell(200)->addText(':');
            $tabelData->addCell(6800)->addText($row[1]);
        }

        $section->addTextBreak(1);
        $section->addText('Dengan ini mengajukan permohonan pinjaman dana kepada Koperasi Dinas Kesehatan Kota Semarang dengan rincian sebagai berikut:');
        $section->addTextBreak(1);

        // DATA PINJAMAN
        $tabelPinjaman = $section->addTable(['borderSize' => 0, 'borderColor' => 'FFFFFF']);
        $rowsPinjaman = [
            ['Besar Pinjaman',      'Rp ' . number_format($pinjaman->jumlah_pengajuan, 0, ',', '.')],
            ['Jangka Waktu (Tenor)', $pinjaman->durasi_bulan . ' Bulan'],
            ['Keperluan',           $pinjaman->alasan_pengajuan],
        ];
        foreach ($rowsPinjaman as $row) {
            $tabelPinjaman->addRow();
            $tabelPinjaman->addCell(2500)->addText($row[0]);
            $tabelPinjaman->addCell(200)->addText(':');
            $tabelPinjaman->addCell(6800)->addText($row[1], $row[0] === 'Besar Pinjaman' ? ['bold' => true] : []);
        }

        $section->addTextBreak(1);
        $section->addText('Saya bersedia mematuhi segala peraturan dan ketentuan yang berlaku di Koperasi Dinas Kesehatan Kota Semarang, termasuk pemotongan gaji setiap bulannya untuk pembayaran angsuran hingga lunas.');
        $section->addTextBreak(1);
        $section->addText('Demikian surat permohonan ini saya buat dengan sebenar-benarnya untuk dapat diproses lebih lanjut.');
        $section->addTextBreak(2);

        // TTD
        $tabelTtd = $section->addTable(['borderSize' => 0, 'borderColor' => 'FFFFFF']);
        $tabelTtd->addRow();
        $tabelTtd->addCell(4500)->addText('Mengetahui,', [], ['alignment' => 'center']);
        $tabelTtd->addCell(5000)->addText('Semarang, ' . \Carbon\Carbon::now()->translatedFormat('d F Y'), [], ['alignment' => 'center']);
        $tabelTtd->addRow();
        $tabelTtd->addCell(4500)->addText('Ketua Koperasi', [], ['alignment' => 'center']);
        $tabelTtd->addCell(5000)->addText('Pemohon,', [], ['alignment' => 'center']);
        $tabelTtd->addRow(Converter::cmToTwip(3));
        $tabelTtd->addCell(4500)->addText('');
        $tabelTtd->addCell(5000)->addText('');
        $tabelTtd->addRow();
        $tabelTtd->addCell(4500)->addText('( ...................................... )', [], ['alignment' => 'center']);
        $cell = $tabelTtd->addCell(5000);
        $cell->addText('( ' . ($pinjaman->user->profile->nama_lengkap ?? $pinjaman->user->name) . ' )', ['bold' => true], ['alignment' => 'center']);

        // DOWNLOAD
        $namaFile = 'Surat_Pengajuan_' . str_replace(' ', '_', Auth::user()->name) . '_' . date('dMY');
        $tempFile = tempnam(sys_get_temp_dir(), 'phpword');
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($tempFile);

      return response()->download($tempFile, $namaFile . '.docx')->deleteFileAfterSend(true);
    }

    public function downloadBukti($id)
    {
        $pinjaman = Pinjaman::with(['user.profile', 'decider.profile'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'dicairkan')
            ->firstOrFail();

        $payload = HmacSigner::normalize([$pinjaman->id, $pinjaman->nomor_pinjaman]);
        $signature = HmacSigner::sign($payload);

        $urlTujuan = route('verifikasi.pinjaman', [
            'hash' => base64_encode($pinjaman->id . '|' . $signature)
        ]);

        $qrcode = base64_encode(QrCode::format('svg')->size(120)->errorCorrection('M')->generate($urlTujuan));

        $pdf = Pdf::loadView('pdf.bukti_pencairan', compact('pinjaman', 'qrcode'));
        return $pdf->download('Bukti-Pinjaman-' . $pinjaman->nomor_pinjaman . '.pdf');
    }

    public function verifikasiQr($hash)
    {
        try {
            $decoded = explode('|', base64_decode($hash));
            if (count($decoded) !== 2) abort(403, 'Hash tidak valid.');

            [$id, $token] = $decoded;
            $pinjaman = Pinjaman::with(['user.profile', 'decider.profile'])->findOrFail($id);
            
            $payload = HmacSigner::normalize([$pinjaman->id, $pinjaman->nomor_pinjaman]);
            
            if (!HmacSigner::verify($payload, $token)) {
                abort(403, 'Tanda tangan digital QR Code tidak valid atau data telah dimanipulasi.');
            }

            return view('general.verifikasi_pinjaman', compact('pinjaman'));
        } catch (\Exception $e) {
            abort(404, 'Data verifikasi tidak ditemukan.');
        }
    }
}