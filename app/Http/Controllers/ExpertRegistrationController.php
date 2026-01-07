<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExpertRequest;
use App\Http\Requests\StoreUserStepOneRequest;
use App\Models\User;
use App\Repositories\ExpertRepository;
use App\Services\ExpertRegistrationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

    /**
     * Step 1: Menampilkan form registrasi user
     */
    public function createStepOne()
    {
        return Inertia::render('Expert/Register/StepOne');
    }

    /**
     * Step 1: Proses registrasi user + assign role expert
     */
    public function storeStepOne(StoreUserStepOneRequest $request)
    {
        try {
            $user = DB::transaction(function () use ($request) {
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'password' => $request->password, // Auto hashed via cast
                    'roles' => ['user', 'expert'],
                    'email_verified_at' => now(),
                ]);
                
                return $user;
            });

            Auth::login($user);
            
            return redirect()->route('expert_onboarding.create')
                ->with('success', 'Akun berhasil dibuat! Silakan lengkapi profil expert Anda.');
                
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Step 2: Menampilkan Form Onboarding Expert
     */
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
     * Step 2: Proses Simpan Data Expert Profile
     */
    public function store(StoreExpertRequest $request)
    {
        try {
            // Panggil Service untuk menangani semua logika bisnis
            $this->expertService->handleRegistration(Auth::user(), $request);

            return redirect()->route('dashboard.index')
                ->with('success', 'Profil Expert berhasil disimpan!');
        } catch (\Exception $e) {
            // Error handling clean
            return back()->withErrors(['message' => 'Gagal menyimpan data: ' . $e->getMessage()]);
        }
    }
}
