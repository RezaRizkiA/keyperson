<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class LandingController extends Controller
{
    public function home()
    {
        return Inertia::render('Home', [
            // 1. HERO: The Big Promise (Menyasar Semua Pihak)
            'hero' => [
                'title' => 'keyPerson – Find the Right Person, Set the Right Time',
                'subtitle' => 'Platform terintegrasi yang menghubungkan Perusahaan dan Individu dengan jaringan Expert terkurasi. Dari konseling hingga mentoring eksekutif, semua terjadwal otomatis di Google Calendar Anda.',
                'image' => asset('assets/images/hero-mockup.webp'), // Pastikan aset ini ada
            ],

            // 2. FEATURE BLOCKS (Zig-Zag): Menjawab 3 Persona
            'features' => [
                // PERSONA 1: USER / KARYAWAN (Focus: Convenience & Growth)
                [
                    'title' => 'Pengembangan Diri, Semudah Memesan Ojek Online',
                    'description' => 'Tidak perlu lagi email bolak-balik untuk cari jadwal. Pilih Expert, klik slot kosong di kalender, dan link meeting langsung masuk ke Google Calendar Anda. Fokus pada sesi Anda, bukan administrasinya.',
                    'image' => 'calendar_ui', // Trigger UI Mockup Kalender di Vue
                    'align' => 'right',
                ],
                // PERSONA 2: CLIENT / PERUSAHAAN (Focus: Management & Impact)
                [
                    'title' => 'Bangun "Super Team" di Perusahaan Anda',
                    'description' => 'Berikan akses eksklusif bagi karyawan ke ratusan mentor & psikolog terbaik. Pantau penggunaan kuota, analisa topik yang diminati tim, dan bayar dengan satu invoice korporat yang simpel.',
                    'image' => 'dashboard_ui', // Nanti kita buat mockup dashboard sederhana
                    'align' => 'left',
                ],
                // PERSONA 3: EXPERT (Focus: Automation & Income)
                [
                    'title' => 'Anda Ahlinya, Biar Kami yang Urus Jadwalnya',
                    'description' => 'Lupakan "no-show" dan penagihan invoice yang macet. Keyperson mengatur pembayaran di muka, reminder otomatis ke klien, dan sinkronisasi zona waktu. Anda cukup hadir dan berikan dampak terbaik.',
                    'image' => 'meeting_ui', // Trigger UI Mockup Meeting
                    'align' => 'right',
                ],
            ],

            // 3. BENTO GRID: Fitur Teknis (Trust Signals)
            'bentoFeatures' => [
                [
                    'title' => 'Google Calendar Sync',
                    'desc' => 'Anti bentrok. Jadwal terisi otomatis di HP Anda.',
                    'icon' => 'calendar', // Icon Lucide
                    'color' => 'bg-blue-50 text-blue-600'
                ],
                [
                    'title' => 'Verified Experts',
                    'desc' => 'Semua expert melalui proses kurasi & wawancara ketat.',
                    'icon' => 'badge-check',
                    'color' => 'bg-violet-50 text-violet-600'
                ],
                [
                    'title' => 'Secure Payment',
                    'desc' => 'Escrow system. Dana aman hingga sesi selesai.',
                    'icon' => 'shield-check',
                    'color' => 'bg-emerald-50 text-emerald-600'
                ],
                [
                    'title' => 'Video Call Ready',
                    'desc' => 'Link meeting (Zoom/GMeet) tergenerate otomatis.',
                    'icon' => 'video',
                    'color' => 'bg-fuchsia-50 text-fuchsia-600'
                ],
            ]
        ]);
    }

    // ... method about, support, pricing, terms, privacy tetap sama ...
    public function about()
    {
        return Inertia::render('About');
    }
    public function support()
    {
        return Inertia::render('Support');
    }
    public function pricing()
    {
        return Inertia::render('Pricing');
    }
    public function terms()
    {
        return Inertia::render('Terms');
    }
    public function privacy()
    {
        return Inertia::render('Privacy');
    }
}
