<?php

namespace App\Http\Controllers;

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