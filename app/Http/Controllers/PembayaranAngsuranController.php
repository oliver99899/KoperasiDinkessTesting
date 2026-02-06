<?php

namespace App\Http\Controllers;

use App\Models\Angsuran;
use App\Models\PembayaranAngsuran;
use App\Models\Pinjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // Tambahkan ini

class PembayaranAngsuranController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Ambil pinjaman aktif milik user untuk ditampilkan di kartu pembayaran
        $pinjamanAktif = Pinjaman::where('user_id', $user->id)
            ->where('sisa_pinjaman', '>', 0)
            ->where('status', 'dicairkan')
            ->withCount('angsuran')
            ->get();

        // Ambil riwayat pengajuan transfer
        $items = PembayaranAngsuran::query()
            ->where('submitted_by', $user->id)
            ->latest('id')
            ->paginate(20);

        return view('pembayaran.transfer.index', compact('items', 'pinjamanAktif'));
    }

    public function create(Request $request, int $pinjaman_id, int $angsuran_ke)
    {
        $user = Auth::user();

        $pinjaman = Pinjaman::query()
            ->where('id', $pinjaman_id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $this->ensureTransferAllowed($pinjaman, $angsuran_ke);

        $nominal = $this->expectedNominal($pinjaman);

        return view('pembayaran.transfer.create', compact('pinjaman', 'angsuran_ke', 'nominal'));
    }

    public function store(Request $request, int $pinjaman_id, int $angsuran_ke)
    {
        $user = Auth::user();

        $pinjaman = Pinjaman::query()
            ->where('id', $pinjaman_id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $this->ensureTransferAllowed($pinjaman, $angsuran_ke);

        $data = $request->validate([
            'tanggal_transfer' => ['required', 'date'],
            'bukti' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        $path = $request->file('bukti')->store('bukti-transfer', 'public');

        DB::transaction(function () use ($pinjaman, $angsuran_ke, $user, $data, $path) {
            // Batalkan pengajuan pending sebelumnya untuk angsuran yang sama jika ada
            PembayaranAngsuran::query()
                ->where('pinjaman_id', $pinjaman->id)
                ->where('angsuran_ke', $angsuran_ke)
                ->where('submitted_by', $user->id)
                ->where('status', 'pending')
                ->update([
                    'status' => 'rejected',
                    'alasan_penolakan' => 'Diganti dengan unggahan baru',
                    'reviewed_by' => $user->id,
                    'reviewed_at' => now(),
                ]);

            PembayaranAngsuran::create([
                'pinjaman_id' => $pinjaman->id,
                'angsuran_ke' => $angsuran_ke,
                'tanggal_transfer' => $data['tanggal_transfer'],
                'bukti_path' => $path,
                'status' => 'pending',
                'submitted_by' => $user->id,
            ]);
        });

        return redirect()->route('pembayaran.transfer.index')->with('success', 'Bukti transfer berhasil dikirim.');
    }

    private function ensureTransferAllowed(Pinjaman $pinjaman, int $angsuran_ke): void
    {
        if (! in_array($pinjaman->status, ['dicairkan'], true)) {
            abort(403);
        }

        if ($angsuran_ke < 1 || $angsuran_ke > (int) $pinjaman->durasi_bulan) {
            abort(404);
        }

        $paid = Angsuran::query()
            ->where('pinjaman_id', $pinjaman->id)
            ->where('angsuran_ke', $angsuran_ke)
            ->exists();

        if ($paid) {
            abort(403, 'Angsuran bulan ini sudah lunas.');
        }
    }

    private function expectedNominal(Pinjaman $pinjaman): string
    {
        $total = (float) $pinjaman->cicilan_pokok_bulanan + (float) $pinjaman->cicilan_bunga_bulanan;
        return number_format($total, 2, '.', '');
    }
}