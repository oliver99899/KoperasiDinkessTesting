<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SwitchRoleController extends Controller
{
    public function switch(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->hasAnyRole(['admin', 'verifikator'])) {
            return back()->with('error', 'Akses ditolak: Anda tidak memiliki otoritas ganda.');
        }

        if (session('app_mode') === 'member') {
            session()->forget('app_mode');
            $request->session()->regenerate();

            if ($user->hasRole('admin')) {
                return redirect()->route('admin.dashboard')->with('success', 'Otoritas penuh Administrator telah dipulihkan.');
            }

            return redirect()->route('verifikator.dashboard')->with('success', 'Otoritas Verifikator telah dipulihkan.');
        }

        session(['app_mode' => 'member']);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', 'Mode Anggota aktif. Anda sekarang melihat sistem sebagai pengguna biasa.');
    }
}