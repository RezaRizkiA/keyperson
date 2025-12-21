<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExpertRequest;
use App\Repositories\ExpertRepository;
use App\Services\ExpertRegistrationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ExpertRegistrationController extends Controller
{
    protected $expertRepo;
    protected $expertService;

    // Dependency Injection
    public function __construct(
        ExpertRepository $expertRepo,
        ExpertRegistrationService $expertService
    ) {
        $this->expertRepo = $expertRepo;
        $this->expertService = $expertService;
    }

    public function create()
    {
        $user = Auth::user();
        $expert = $user->expert ? $user->expert->load('skills') : null;

        if ($expert) {
            $gallerys = $expert->gallerys ?? [];
            $expert->gallerys = collect($gallerys)->map(function ($item) {
                if (!is_array($item)) return $item;

                $path = $item['file'] ?? $item['photos'] ?? null;

                // FIX: Cek apakah path SUDAH berupa URL lengkap?
                if ($path && str_starts_with($path, 'http')) {
                    $item['file'] = $path; // Gunakan apa adanya
                } else {
                    // Kalau belum (masih relative), baru generate URL
                    $item['file'] = $path ? Storage::disk('s3')->url($path) : null;
                }

                return $item;
            })->toArray();

            $licenses = $expert->licenses ?? [];
            $expert->licenses = collect($licenses)->map(function ($item) {
                if (!is_array($item)) return $item;

                $path = $item['file'] ?? $item['attachment'] ?? null;

                // FIX: Cek URL
                if ($path && str_starts_with($path, 'http')) {
                    $item['file'] = $path;
                } else {
                    $item['file'] = $path ? Storage::disk('s3')->url($path) : null;
                }

                return $item;
            })->toArray();
        }

        $categories = $this->expertRepo->getCategoriesWithSkills();

        return Inertia::render('Expert/Register/Index', [
            'user'       => $user,
            'expert'     => $expert,
            'categories' => $categories,
            'isEditMode' => $user->expert !== null,
        ]);
    }

    /**
     * Proses Simpan Data
     */
    public function store(StoreExpertRequest $request)
    {
        try {
            // Panggil Service untuk menangani semua logika bisnis
            $this->expertService->handleRegistration(Auth::user(), $request);

            return redirect()->route('dashboard')
                ->with('success', 'Profil Expert berhasil disimpan!');
        } catch (\Exception $e) {
            // Error handling clean
            return back()->withErrors(['message' => 'Gagal menyimpan data: ' . $e->getMessage()]);
        }
    }
}
