<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class FilamentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
     public function boot()
    {
        // Pastikan pengecekan hanya dilakukan setelah login
        Filament::serving(function () {
            // Cek apakah user sudah login dan apakah role-nya admin
            if (Auth::check() && Auth::user()->role !== 'admin') {
                abort(403, 'Access Denied'); // Hanya user dengan role admin yang bisa akses
            }
        });
    }
}
