<?php

namespace App\Http\Controllers;

use App\Models\Angsuran;
use App\Models\Pinjaman;
use App\Models\Simpanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $sidebarMode = session('sidebar_mode');

        if ($sidebarMode === 'admin' && $user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($sidebarMode === 'verifikator' && $user->hasRole('verifikator')) {
            return redirect()->route('verifikator.dashboard');
        }

        $totalSimpanan = Simpanan::query()
            ->where('user_id', $user->id)
            ->sum('jumlah');

        $totalSisaPinjaman = Pinjaman::query()
            ->where('user_id', $user->id)
            ->whereIn('status', ['disetujui', 'dicairkan', 'lunas'])
            ->sum('sisa_pinjaman');

        $simpananTerbaru = Simpanan::query()
            ->where('user_id', $user->id)
            ->orderByDesc('tanggal_potong')
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        $pinjamanTerbaru = Pinjaman::query()
            ->where('user_id', $user->id)
            ->orderByDesc('tanggal_pengajuan')
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        $angsuranTerbaru = Angsuran::query()
            ->whereHas('pinjaman', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->with('pinjaman')
            ->orderByDesc('tanggal_potong')
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        $statusKeamanan = [
            'identitas' => !is_null($user->profile?->activated_at),
            'integritas_data' => true,
            'sesi_aktif' => $sidebarMode ? strtoupper($sidebarMode) : 'ANGGOTA',
        ];

        return view('dashboard', compact(
            'user',
            'totalSimpanan',
            'totalSisaPinjaman',
            'simpananTerbaru',
            'pinjamanTerbaru',
            'angsuranTerbaru',
            'statusKeamanan'
        ));
    }
}
