<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\StoreUserStepOneRequest;
use App\Models\Category;
use App\Models\User;
use App\Services\ClientOnboardingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ClientRegistrationController extends Controller
{
    protected $clientService;

    // Inject Service ke Constructor
    public function __construct(ClientOnboardingService $clientService)
    {
        $this->clientService = $clientService;
    }

    /**
     * Step 1: Menampilkan form registrasi user
     */
    public function createStepOne()
    {
        return Inertia::render('Client/Onboarding/StepOne');
    }

    /**
     * Step 1: Proses registrasi user + assign role client
     */
    public function storeStepOne(StoreUserStepOneRequest $request)
    {
      $data = $request->validated();
        try {
            $user = DB::transaction(function () use ($data) {
                $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'password' => $data['password'],
                    'roles' => ['user', 'client'],
                    'email_verified_at' => now(),
                ]);

                return $user;
            });

            Auth::login($user);

            return redirect()->route('client_onboarding.create')
                ->with('success', 'Akun berhasil dibuat! Silakan lengkapi profil perusahaan Anda.');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Terjadi kesalahan: '.$e->getMessage())
                ->withInput();
        }
    }

    /**
     * Step 2: Menampilkan Form Onboarding Client (Profile Perusahaan)
     */
    public function create()
    {
        $user = Auth::user();

        // Middleware 'eligible-onboarding' sudah handle blocking Expert/Client/Corporate Employee
        // Method ini sekarang hanya untuk registrasi baru

        // 2. Siapkan Data Opsi (Source of Truth)
        // Data ini dikirim ke Vue untuk mengisi <select> options.
        // Tips Senior: Idealnya ini ditaruh di Enum atau Config file, tapi array disini cukup untuk sekarang.
        $formOptions = [
            'types' => [
                ['value' => 'company', 'label' => 'Perusahaan (PT/CV)'],
                ['value' => 'agency', 'label' => 'Agency / Consultant'],
                ['value' => 'startup', 'label' => 'Startup'],
                ['value' => 'government', 'label' => 'Instansi Pemerintah'],
                ['value' => 'ngo', 'label' => 'NGO / Yayasan'],
            ],
            'employee_counts' => [
                '1-10 Karyawan',
                '11-50 Karyawan',
                '51-200 Karyawan',
                '201-500 Karyawan',
                '500-1000 Karyawan',
                '1000+ Karyawan',
            ],
            'industries' => [
                'Technology & Software',
                'Finance & Banking',
                'Healthcare & Medical',
                'Education & Training',
                'Retail & E-Commerce',
                'Manufacturing',
                'Construction',
                'Media & Advertising',
                'Transportation & Logistics',
                'Other',
            ],
        ];

        // Get categories with subcategories and skills (hierarchical)
        $categories = Category::with(['subCategories.skills'])
            ->orderBy('name')
            ->get()
            ->toArray();

        // 3. Render View dengan Inertia
        return Inertia::render('Client/Onboarding/Create', [
            // Kirim data user basic (nama/email) untuk auto-fill form jika perlu
            'user_defaults' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
            'options' => $formOptions,
            'categories' => $categories,
        ]);
    }

    /**
     * Step 2: Proses simpan data profil perusahaan
     */
    public function store(StoreClientRequest $request)
    {
        try {
            $this->clientService->saveClientData(
                Auth::user(),
                $request->validated()
            );

            \Log::info('Client Onboarding SUCCESS for user: '.Auth::id());

            return redirect()->route('dashboard.index')
                ->with('success', 'Selamat! Profil perusahaan Anda berhasil dibuat.');
        } catch (\Exception $e) {
            \Log::error('Client Onboarding Error: '.$e->getMessage().' | Trace: '.$e->getTraceAsString());

            return back()
                ->with('error', 'Terjadi kesalahan: '.$e->getMessage())
                ->withInput(); // JANGAN LUPA INI biar form gak kereset
        }
    }
}
