<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsBendahara
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user terautentikasi dan rolenya adalah admin
        if (Auth::check() && Auth::user()->role === 'bendahara') {
            return $next($request);
        }

        // Jika bukan admin, redirect ke dashboard dengan pesan error
        return redirect('/dashboard')->withErrors('Anda tidak memiliki akses ke halaman ini.');
    }
}
