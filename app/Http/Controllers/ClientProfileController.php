<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Penting untuk standar profesional
use App\Http\Requests\StoreClientRequest;
use App\Models\Skill;
use App\Services\ClientOnboardingService;

class ClientProfileController extends Controller
{
    protected $clientService;

    // Dependency Injection
    public function __construct(ClientOnboardingService $clientService)
    {
        $this->clientService = $clientService;
    }

    /**
     * Menampilkan Form Edit Profile
     */
    public function edit()
    {
        $user = Auth::user();

        // Defensive Check: Jika user belum punya profil client, redirect ke onboarding
        if (!$user->client) {
            return redirect()->route('client_onboarding.create');
        }

        // Load data client dengan relasi skills (untuk populate form)
        // Kita gunakan $user->client->load('skills') agar skill yang dipilih bisa muncul
        $client = $user->client->load('skills');

        // Reuse options (ideally disimpan di Enum/Config/Trait biar DRY)
        $formOptions = [
            'types' => [
                ['value' => 'company', 'label' => 'Perusahaan (PT/CV)'],
                ['value' => 'agency', 'label' => 'Agency / Consultant'],
                ['value' => 'startup', 'label' => 'Startup'],
                ['value' => 'government', 'label' => 'Instansi Pemerintah'],
                ['value' => 'ngo', 'label' => 'NGO / Yayasan'],
                ['value' => 'individual', 'label' => 'Perorangan'],
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
            'skill' => Skill::select('id', 'name')->orderBy('name')->get()
        ];

        return Inertia::render('Client/Dashboard/Profile/Edit', [
            'client'  => $client,
            'options' => $formOptions,
            'user_defaults' => [
                'name' => $user->name,
                'email' => $user->email,
            ]
        ]);
    }

    /**
     * Update Profile Client (Standar Profesional)
     */
    public function update(StoreClientRequest $request)
    {
        try {
            // 1. Panggil Service (Reusability Logic)
            $this->clientService->saveClientData(
                Auth::user(),
                $request->validated() // Data sudah bersih & aman
            );

            // 2. Return Back (Stay on Page) dengan Flash Message Sukses
            // Untuk Update, biasanya user tetap di halaman edit untuk melihat perubahannya
            return back()->with('success', 'Profil perusahaan berhasil diperbarui.');
        } catch (\Exception $e) {
            // 3. LOG ERROR (Internal Use)
            // Ini ciri khas Enterprise. Developer harus tau error aslinya apa.
            Log::error("Client Profile Update Error [User ID: " . Auth::id() . "]: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            // 4. Return Back dengan Error Friendly (User Facing)
            // withInput() penting agar form tidak kosong lagi (reset)
            return back()
                ->with('error', 'Gagal memperbarui profil. Silakan coba lagi atau hubungi support.')
                ->withInput();
        }
    }
}
