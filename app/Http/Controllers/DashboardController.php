<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    protected $dashboardService;

    // Inject Service ke Controller
    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        $user = Auth::user();
        $roles = $user->roles ?? []; // Pastikan $roles array

        // --- 1. LOGIKA UNTUK ADMINISTRATOR ---
        if (in_array('administrator', $roles)) {
            $stats = $this->dashboardService->getAdminStats();

            return Inertia::render('Administrator/Dashboard/Index', [
                'stats' => $stats,
            ]);
        }

        // --- 2. LOGIKA UNTUK EXPERT ---
        if (in_array('expert', $roles)) {
            // Ambil data expert yang terhubung dengan user ini
            $expert = $user->expert;

            // Guard: Jika user punya role expert tapi belum isi profile expert (belum ada di table experts)
            if (!$expert) {
                // Redirect ke halaman setup profile atau tampilkan dashboard kosong
                return Inertia::render('Expert/Dashboard/Index', [
                    'stats' => [
                        'total_appointments' => 0,
                        'upcoming_appointments' => 0,
                        'need_confirmation' => 0,
                        'total_revenue' => 0,
                    ],
                    'flash' => [
                        'warning' => 'Please complete your expert profile to start accepting appointments.'
                    ]
                ]);
            }

            // Ambil stats menggunakan ID dari tabel experts, BUKAN user_id
            $stats = $this->dashboardService->getExpertStats($expert->id);

            return Inertia::render('Expert/Dashboard/Index', [
                'stats' => $stats,
            ]);
        }

        // --- 3. LOGIKA UNTUK USER BIASA / FALLBACK ---
        // Bisa redirect ke home atau dashboard user biasa
        return Inertia::render('User/Dashboard/Index');
    }
}
