<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'nip' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $throttleKey = Str::transliterate(Str::lower($request->input('nip')) . '|' . $request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()->withErrors([
                'nip' => 'Terlalu banyak percobaan login. Silakan coba lagi dalam ' . $seconds . ' detik.',
            ])->onlyInput('nip');
        }

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::hit($throttleKey);

            return back()->withErrors([
                'nip' => 'Kombinasi NIP dan kata sandi tidak ditemukan dalam sistem.',
            ])->onlyInput('nip');
        }

        RateLimiter::clear($throttleKey);

        $request->session()->regenerate();

        $user = $request->user();

        if (in_array($user->status_akun, ['blocked', 'retired'], true)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'nip' => 'Otoritas akses ditolak. Akun Anda dalam status: ' . strtoupper($user->status_akun) . '.',
            ])->onlyInput('nip');
        }

        if (!in_array($user->status_akun, ['new', 'active'], true)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'nip' => 'Akun Anda belum dapat digunakan. Hubungi Admin.',
            ])->onlyInput('nip');
        }

        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
            'active_session_id' => $request->session()->getId(),
        ]);

        if ($user->status_akun === 'new') {
            return redirect()->route('profile.setup');
        }

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user) {
            $user->update(['active_session_id' => null]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function showActivationForm($token): View
    {
        return view('auth.activation', ['token' => $token]);
    }
}
