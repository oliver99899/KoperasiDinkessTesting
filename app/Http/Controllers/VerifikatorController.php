<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Simpanan;

class VerifikatorController extends Controller
{
    public function index()
    {
        $totalAnggota = User::where('role', 'user')->where('status_akun', 'active')->count();
        $totalAset    = Simpanan::sum('jumlah'); 
        $pendingLoan  = 0; 

        $recentSimpanan = Simpanan::with('user.profile')->latest()->take(5)->get();

        return view('verifikator.dashboard', compact('totalAnggota', 'totalAset', 'pendingLoan', 'recentSimpanan'));
    }

    // 1. FORM INPUT SIMPANAN
    public function createSimpanan()
    {
        $users = User::with('profile')
                    ->where('role', 'user')
                    ->where('status_akun', 'active')
                    ->get();

        return view('verifikator.simpanan.create', compact('users'));
    }

    // 2. PROSES SIMPAN DATA
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
}