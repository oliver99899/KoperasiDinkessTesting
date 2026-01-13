<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SimpananController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $riwayatSimpanan = $user->simpanan()->orderBy('tanggal_bayar', 'desc')->get();

        $totalSaldo = $riwayatSimpanan->sum('jumlah');

        $saldoPokok    = $riwayatSimpanan->where('jenis_simpanan', 'pokok')->sum('jumlah');
        $saldoWajib    = $riwayatSimpanan->where('jenis_simpanan', 'wajib')->sum('jumlah');
        $saldoSukarela = $riwayatSimpanan->where('jenis_simpanan', 'sukarela')->sum('jumlah');

        return view('user.simpanan', compact(
            'riwayatSimpanan', 
            'totalSaldo', 
            'saldoPokok', 
            'saldoWajib', 
            'saldoSukarela'
        ));
    }
}