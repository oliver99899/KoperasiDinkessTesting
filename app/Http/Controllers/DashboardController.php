<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'verifikator') {
            return redirect()->route('verifikator.dashboard');
        }

        $totalSimpanan = $user->simpanan()->sum('jumlah');

        $sisaPinjaman = $user->pinjaman()->sum('sisa_tagihan');

        $riwayatTransaksi = $user->simpanan()
            ->latest('tanggal_bayar')
            ->take(5)
            ->get();

        return view('dashboard', compact('totalSimpanan', 'sisaPinjaman', 'riwayatTransaksi'));
    }
}