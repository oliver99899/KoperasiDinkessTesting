<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pinjaman;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PinjamanController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $pinjaman = Pinjaman::where('user_id', $user->id)
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('user.pinjaman', compact('pinjaman'));
    }

    public function create()
    {
        return view('user.pinjaman_create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jumlah_pengajuan' => 'required|numeric|min:500000|max:10000000',
            'durasi_bulan'     => 'required|numeric|min:3|max:36',
            'alasan'           => 'required|string|max:255',
        ]);

        Pinjaman::create([
            'user_id'           => Auth::id(),
            'jumlah_pengajuan'  => $request->jumlah_pengajuan,
            'durasi_bulan'      => $request->durasi_bulan,
            'alasan'            => $request->alasan,
            'status'            => 'pending',
            'sisa_tagihan'      => 0,
            'tanggal_pengajuan' => now(),
        ]);

        return redirect()->route('pinjaman.index')->with('success', 'Pengajuan berhasil dikirim! Menunggu persetujuan Admin.');
    }

    public function downloadBukti($id)
    {
        $pinjaman = Pinjaman::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($pinjaman->status != 'approved') {
            return back()->withErrors(['msg' => 'Bukti hanya tersedia untuk pinjaman yang disetujui.']);
        }

        $hash = base64_encode($pinjaman->id . '-' . 'koperasi-secure'); 
        $urlTujuan = route('verifikasi.pinjaman', ['hash' => $hash]);

        $qrcode = base64_encode(QrCode::format('svg')->size(150)->errorCorrection('H')->generate($urlTujuan));

        $pdf = Pdf::loadView('pdf.bukti_pencairan', compact('pinjaman', 'qrcode'));
        
        return $pdf->download('Bukti-Pencairan-Pinjaman-DKK.pdf');
    }

    public function verifikasiQr($hash)
    {
        try {
            $decoded = base64_decode($hash);
            $parts = explode('-', $decoded);
            $id = $parts[0];

            $pinjaman = Pinjaman::with('user.profile')->findOrFail($id);

            return view('general.verifikasi_pinjaman', compact('pinjaman'));

        } catch (\Exception $e) {
            abort(404, 'Data tidak ditemukan atau QR Code tidak valid.');
        }
    }
}