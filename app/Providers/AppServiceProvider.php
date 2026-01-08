<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Global Password Validation Defaults
        // Wajib: huruf, huruf besar & kecil, angka, simbol, minimal 8 karakter
        Password::defaults(function () {
            return Password::min(8)
                ->letters()        // Wajib mengandung huruf
                ->mixedCase()      // Wajib huruf besar & kecil
                ->numbers()        // Wajib mengandung angka
                ->symbols();       // Wajib mengandung simbol (!@#$%^&* dll)
        });

        // Atur locale Carbon secara global untuk bahasa Indonesia
        Carbon::setLocale('id');

        // Fallback jika setLocale tidak berhasil mengenali
        try {
            setlocale(LC_TIME, 'id_ID.utf8', 'id_ID', 'id');
        } catch (\Exception $e) {
            // Log error atau abaikan jika locale tidak ditemukan di sistem
        }
    }
}
