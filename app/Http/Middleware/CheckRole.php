<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();
        $appMode = session('app_mode');

        // LOGIKA SWITCH MODE: 
        // Jika route minta role 'user' dan user sedang dalam mode 'member', izinkan masuk.
        if (in_array('user', $roles) && $appMode === 'member') {
            return $next($request);
        }

        // LOGIKA NORMAL: 
        // Jika role user ada di dalam daftar role yang diizinkan oleh route.
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // PROTEKSI: 
        // Jika sedang mode 'member', jangan biarkan dia akses rute 'admin' atau 'verifikator'.
        if ($appMode === 'member' && (in_array('admin', $roles) || in_array('verifikator', $roles))) {
            return redirect()->route('dashboard')->with('error', 'Kembalikan ke mode Staff untuk akses ini.');
        }

        // REDIRECT OTOMATIS: Jika tidak punya akses, lempar ke dashboard masing-masing.
        return $this->redirectDefault($user->role);
    }

    protected function redirectDefault($role) {
        if ($role === 'admin') return redirect()->route('admin.dashboard');
        if ($role === 'verifikator') return redirect()->route('verifikator.dashboard');
        return redirect()->route('dashboard');
    }
}