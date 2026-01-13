<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $totalSimpanan = $user->simpanan()->sum('jumlah');

        $sisaPinjaman = 0;

        $riwayatTransaksi = $user->simpanan()
            ->latest('tanggal_bayar')
            ->take(5)
            ->get();

        return view('dashboard', compact('totalSimpanan', 'sisaPinjaman', 'riwayatTransaksi'));
    }
}