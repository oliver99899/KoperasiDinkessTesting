<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Simpanan;
use App\Models\Pinjaman;

class VerifikatorController extends Controller
{
    public function index()
    {
        $totalAnggota = User::where('role', 'user')->where('status_akun', 'active')->count();
        $totalAset    = Simpanan::sum('jumlah'); 
        $pendingLoan  = Pinjaman::where('status', 'pending')->count(); 

        $recentSimpanan = Simpanan::with('user.profile')->latest()->take(5)->get();

        return view('verifikator.dashboard', compact('totalAnggota', 'totalAset', 'pendingLoan', 'recentSimpanan'));
    }

    public function createSimpanan()
    {
        $users = User::with('profile')
                    ->where('role', 'user')
                    ->where('status_akun', 'active')
                    ->get();

        return view('verifikator.simpanan.create', compact('users'));
    }

    public function storeSimpanan(Request $request)
    {
        $request->validate([
            'user_id'        => 'required|exists:users,id',
            'jenis_simpanan' => 'required|in:pokok,wajib,sukarela',
            'jumlah'         => 'required|numeric|min:1000',
            'tanggal_bayar'  => 'required|date',
            'metode_bayar'   => 'required|string',
            'keterangan'     => 'nullable|string',
        ]);

        Simpanan::create([
            'user_id'        => $request->user_id,
            'jenis_simpanan' => $request->jenis_simpanan,
            'jumlah'         => $request->jumlah,
            'tanggal_bayar'  => $request->tanggal_bayar,
            'metode_bayar'   => $request->metode_bayar,
            'keterangan'     => $request->keterangan,
            'status'         => 'verified',
        ]);

        return redirect()->route('verifikator.dashboard')->with('success', 'Simpanan berhasil dicatat!');
    }

    public function indexPinjaman()
    {
        $pinjaman = Pinjaman::with('user.profile')
                            ->where('status', 'pending')
                            ->orderBy('created_at', 'asc')
                            ->get();

        return view('verifikator.pinjaman.index', compact('pinjaman'));
    }

    public function showPinjaman($id)
    {
        $pinjaman = Pinjaman::with(['user.profile', 'user.simpanan'])->findOrFail($id);

        $totalSimpananUser = $pinjaman->user->simpanan->sum('jumlah');
        
        $pinjamanAktifLain = Pinjaman::where('user_id', $pinjaman->user_id)
                                     ->where('status', 'approved')
                                     ->where('sisa_tagihan', '>', 0)
                                     ->count();

        return view('verifikator.pinjaman.show', compact('pinjaman', 'totalSimpananUser', 'pinjamanAktifLain'));
    }

    public function approvePinjaman($id)
    {
        $pinjaman = Pinjaman::findOrFail($id);
        
        $pinjaman->update([
            'status' => 'approved',
            'tanggal_disetujui' => now(),
        ]);

        return redirect()->route('verifikator.pinjaman.index')->with('success', 'Pinjaman berhasil disetujui.');
    }

    public function rejectPinjaman($id)
    {
        $pinjaman = Pinjaman::findOrFail($id);
        
        $pinjaman->update([
            'status' => 'rejected',
            'tanggal_disetujui' => now(),
        ]);

        return redirect()->route('verifikator.pinjaman.index')->with('success', 'Pinjaman ditolak.');
    }
}