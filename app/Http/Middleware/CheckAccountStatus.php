<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAccountStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        if (in_array($user->status_akun, ['blocked', 'retired'], true)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->withErrors(['nip' => 'Akses ditolak: Status akun ' . $user->status_akun]);
        }

        if ($request->routeIs('profile.setup') || $request->routeIs('profile.store') || $request->routeIs('logout')) {
            return $next($request);
        }

        if (!$user->profile) {
            return redirect()->route('profile.setup');
        }

        if ($user->status_akun === 'new' && is_null($user->profile->activated_at)) {
            return redirect()->route('profile.setup');
        }

        if ($user->active_session_id && $user->active_session_id !== $request->session()->getId()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->withErrors(['nip' => 'Sesi Anda telah aktif di perangkat lain.']);
        }

        return $next($request);
    }
}