<?php

namespace App\Http\Controllers;

use App\Models\Angsuran;
use App\Models\PembayaranAngsuran;
use App\Models\Pinjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VerifikatorPembayaranAngsuranController extends Controller
{
    public function index()
    {
        $items = PembayaranAngsuran::with(['pinjaman.user.profile']) 
            ->where('status', 'pending')
            ->latest()
            ->paginate(15);

        return view('verifikator.pembayaran.transfer.index', compact('items'));
    }

    public function show($id)
    {
        $item = PembayaranAngsuran::with(['pinjaman.user.profile', 'submitter.profile'])->findOrFail($id);
        return view('verifikator.pembayaran.transfer.show', compact('item'));
    }

    public function approve(Request $request, $id)
    {
        return DB::transaction(function () use ($id) {
            $pembayaran = PembayaranAngsuran::lockForUpdate()->findOrFail($id);
            $pinjaman = Pinjaman::lockForUpdate()->findOrFail($pembayaran->pinjaman_id);

            $nominalTotal = round($pinjaman->cicilan_pokok_bulanan + $pinjaman->cicilan_bunga_bulanan);

            Angsuran::create([
                'pinjaman_id' => $pinjaman->id,
                'angsuran_ke' => $pembayaran->angsuran_ke,
                'pokok_bayar' => $pinjaman->cicilan_pokok_bulanan,
                'bunga_bayar' => $pinjaman->cicilan_bunga_bulanan,
                'jumlah_bayar' => $nominalTotal,
                'metode_bayar' => 'transfer',
                'tanggal_potong' => $pembayaran->tanggal_transfer,
                'created_by' => Auth::id(),
                'verified_at' => now(),
            ]);

            $pembayaran->update([
                'status' => 'approved',
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
            ]);

            $sisaBaru = max(0, $pinjaman->sisa_pinjaman - $nominalTotal);
            
            $pinjaman->update([
                'sisa_pinjaman' => $sisaBaru,
                'status' => ($sisaBaru <= 0) ? 'lunas' : $pinjaman->status,
                'jatuh_tempo_berikutnya' => ($sisaBaru > 0) ? Carbon::parse($pinjaman->jatuh_tempo_berikutnya)->addMonth() : null,
            ]);

            return redirect()->route('verifikator.angsuran.index')->with('success', 'Pembayaran transfer berhasil divalidasi.');
        });
    }

    public function reject(Request $request, $id)
    {
        $request->validate(['alasan_penolakan' => 'required|string|max:255']);
        
        $pembayaran = PembayaranAngsuran::findOrFail($id);
        $pembayaran->update([
            'status' => 'rejected',
            'alasan_penolakan' => $request->alasan_penolakan,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->route('verifikator.angsuran.index')->with('success', 'Pembayaran ditolak.');
    }
}