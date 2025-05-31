<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah user sudah login dan apakah role-nya admin
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request); // Lanjutkan jika admin
        }

        // Jika bukan admin, arahkan ke halaman 403 atau halaman lain
        abort(403, 'Access Denied'); // Access Denied jika bukan admin
    }
}
