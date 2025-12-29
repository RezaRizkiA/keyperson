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
        
        // Middleware 'eligible-onboarding' sudah handle blocking Expert/Client/Corporate Employee
        // Method ini sekarang hanya untuk registrasi baru
        
        $categories = $this->expertRepo->getCategoriesWithSkills();

        return Inertia::render('Expert/Register/Index', [
            'user'       => $user,
            'expert'     => null,
            'categories' => $categories,
            'isEditMode' => false,
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
