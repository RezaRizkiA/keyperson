<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia; // <--- Import Inertia

class LandingController extends Controller
{
    /**
     * Halaman Home
     */
    public function home()
    {
        return Inertia::render('Home', [
            // Hanya data KHUSUS halaman Home yang dikirim di sini.
            // Data User, Logo, Routes sudah otomatis dikirim via Middleware.
            'heroImages' => [
                'banner1' => asset('assets/images/hero-img/bannerimg1.png'),
                'banner2' => asset('assets/images/hero-img/bannerimg2.png'),
            ],
        ]);
    }

    /**
     * Halaman About
     */
    public function about()
    {
        return Inertia::render('About');
    }

    /**
     * Halaman Support
     */
    public function support()
    {
        return Inertia::render('Support');
    }

    /**
     * Halaman Pricing
     */
    public function pricing()
    {
        return Inertia::render('Pricing');
    }

    /**
     * Halaman Terms
     */
    public function terms()
    {
        return Inertia::render('Terms');
    }

    /**
     * Halaman Privacy
     */
    public function privacy()
    {
        return Inertia::render('Privacy');
    }
}
