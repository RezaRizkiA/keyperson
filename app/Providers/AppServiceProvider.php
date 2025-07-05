<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

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
        // Atur locale Carbon secara global untuk bahasa Indonesia
        // 'id_ID' adalah locale standar untuk Indonesia
        // Jika tidak bekerja, coba 'id', 'id_ID.utf8', atau 'Indonesian' tergantung sistem operasi server
        Carbon::setLocale('id'); // Coba ini pertama
        // Carbon::setLocale('id_ID'); // Atau ini
        // Carbon::setLocale('id_ID.utf8'); // Atau ini jika di Linux

        // Fallback jika setLocale tidak berhasil mengenali
        try {
            setlocale(LC_TIME, 'id_ID.utf8', 'id_ID', 'id');
        } catch (\Exception $e) {
            // Log error atau abaikan jika locale tidak ditemukan di sistem
        }
    }
}
