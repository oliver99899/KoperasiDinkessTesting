<?php

namespace App\Http\Controllers;

use App\Models\Angsuran;
use App\Models\Pinjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AngsuranController extends Controller
{
    public function indexUser()
    {
        $userId = Auth::id();

        $angsuran = Angsuran::with('pinjaman')
            ->whereHas('pinjaman', fn ($q) => $q->where('user_id', $userId))
            ->orderByDesc('tanggal_potong')
            ->orderByDesc('id')
            ->paginate(10);

        return view('user.angsuran.index', compact('angsuran'));
    }

    public function create($pinjaman_id)
    {
        $pinjaman = Pinjaman::where('user_id', Auth::id())
            ->where('id', $pinjaman_id)
            ->firstOrFail();

        if ($pinjaman->sisa_pinjaman <= 0) {
            return redirect()->route('pinjaman.index')->with('success', 'Pinjaman ini sudah lunas.');
        }

        return view('user.angsuran.create', compact('pinjaman'));
    }

    public function store(Request $request, $pinjaman_id)
    {
        $pinjaman = Pinjaman::where('user_id', Auth::id())
            ->where('id', $pinjaman_id)
            ->firstOrFail();

        if ($pinjaman->sisa_pinjaman <= 0) {
            return redirect()->route('pinjaman.index')->with('success', 'Pinjaman ini sudah lunas.');
        }

        $maxBayar = (float) $pinjaman->sisa_pinjaman;

        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:1000|max:' . $maxBayar,
            'tanggal_potong' => 'required|date|before_or_equal:today',
            'keterangan' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $angsuranKe = Angsuran::where('pinjaman_id', $pinjaman->id)
                ->where('status', '!=', 'rejected')
                ->count() + 1;

            $angsuran = new Angsuran();
            $angsuran->pinjaman_id = $pinjaman->id;
            $angsuran->angsuran_ke = $angsuranKe;
            $angsuran->jumlah_bayar = $request->jumlah_bayar;
            $angsuran->metode_bayar = 'potong_gaji';
            $angsuran->status = 'pending';
            $angsuran->tanggal_potong = $request->tanggal_potong;
            $angsuran->keterangan = $request->keterangan;
            $angsuran->created_by = Auth::id();
            $angsuran->save();

            DB::commit();

            return redirect()->route('angsuran.index')->with('success', 'Data angsuran berhasil dikirim untuk divalidasi.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Sistem gagal memproses data: ' . $e->getMessage());
        }
    }

    public function indexVerifikator()
    {
        $angsuran = Angsuran::with(['pinjaman.user.profile'])
            ->orderByRaw("FIELD(status, 'pending', 'verified', 'rejected')")
            ->orderByDesc('tanggal_potong')
            ->orderByDesc('id')
            ->paginate(15);

        return view('verifikator.angsuran.index', compact('angsuran'));
    }

    public function approve($id)
    {
        DB::beginTransaction();

        try {
            $angsuran = Angsuran::lockForUpdate()->findOrFail($id);
            $pinjaman = Pinjaman::lockForUpdate()->findOrFail($angsuran->pinjaman_id);

            if ($angsuran->status === 'verified') {
                DB::rollBack();
                return back()->with('error', 'Integritas data: Transaksi ini sudah terverifikasi sebelumnya.');
            }

            $angsuran->update([
                'status' => 'verified',
                'verified_by' => Auth::id(),
                'verified_at' => now(),
            ]);

            $sisaBaru = (float) $pinjaman->sisa_pinjaman - (float) $angsuran->jumlah_bayar;
            if ($sisaBaru < 0) $sisaBaru = 0;

            $pinjaman->update([
                'sisa_pinjaman' => $sisaBaru,
                'status' => ($sisaBaru <= 0) ? 'lunas' : $pinjaman->status,
            ]);

            DB::commit();
            return back()->with('success', 'Verifikasi berhasil. Sisa pinjaman anggota telah diperbarui secara otomatis.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Kegagalan validasi finansial: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        $angsuran = Angsuran::findOrFail($id);

        if ($angsuran->status !== 'pending') {
            return back()->with('error', 'Transaksi ini tidak dapat ditolak karena sudah diproses.');
        }

        $request->validate([
            'keterangan' => 'required|string|max:255',
        ]);

        $angsuran->update([
            'status' => 'rejected',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'keterangan' => 'Penolakan: ' . $request->keterangan,
        ]);

        return back()->with('success', 'Data angsuran telah ditolak.');
    }
}