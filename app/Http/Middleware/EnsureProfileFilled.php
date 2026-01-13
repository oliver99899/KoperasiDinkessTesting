<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileFilled
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user->role !== 'user') {
            return $next($request);
        }

        if ($request->routeIs('profile.setup') || $request->routeIs('profile.store')) {
            return $next($request);
        }

        if ( ! $user->profile || $user->profile->alamat === '-') {
            return redirect()->route('profile.setup');
        }

        return $next($request);
    }
}