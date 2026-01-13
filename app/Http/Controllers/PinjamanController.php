<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pinjaman;

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
        // Validasi input
        $request->validate([
            'jumlah_pengajuan' => 'required|numeric|min:500000|max:10000000',
            'durasi_bulan'     => 'required|numeric|min:3|max:36',
            'alasan'           => 'required|string|max:255',
        ]);

        // Simpan ke database
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
}