<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Expertise;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function loginView()
    {
        return Inertia::render('Auth/Login', [
            // Kirim status jika ada redirect message (misal: "Silakan login dulu")
            'status' => session('status'),
        ]);
    }

    public function login_post(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|min:4',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();
            $credentials = [
                'user'      => $user->id,
                'token_jwt' => $user->id,
            ];
            $request->session()->put('loggedUser', $credentials);
            return redirect()->route('dashboard');
        }
        return redirect()->back()->withErrors([
            'email' => 'Email atau Password Tidak Valid!!',
        ])->withInput();
    }

    public function registerClient(Request $request)
    {
        $user = Auth::user();

        // Ambil data expertise untuk form Expert (jika nanti digabung)
        $expertises = Expertise::whereNull('parent_id')
            ->orderBy('order')
            ->with('childrensRecursive')
            ->get();

        // Render ke folder Register/Index.vue
        // Kita kirim props 'type' = 'client' agar Vue tahu harus buka tab Client
        return Inertia::render('Register/Index', [
            'user' => $user,
            'client' => $user->client, // Data client user (jika ada)
            'expert' => $user->expert, // Data expert user (jika ada)
            'expertises' => $expertises,
            'initialTab' => 'client', // Penanda untuk Vue
            'isEditMode' => $user->client !== null
        ]);
    }

    public function register_client_post(Request $request)
    {
        // Ambil user yang sedang login
        $user = Auth::user();

        $fileUpload = [
            'file_author_photo'      => [
                'db_column'    => 'author_photo',
                'storage_path' => 'author/profiles/',
            ],
            'file_banner_background' => [
                'db_column'    => 'banner_background',
                'storage_path' => 'author/background/',
            ],
            'file_logo'              => [
                'db_column'    => 'logo',
                'storage_path' => 'author/logo/',
            ],
        ];

        if ($user->client == null) {
            $client = $user->client()->create($request->all()); //data baru
        } else {
            $client = $user->client;          // data lama
            $client->update($request->all()); // data di perbarui
        }

        foreach ($fileUpload as $inputName => $fileInfo) {
            if ($request->hasFile($inputName)) {
                $file              = $request->file($inputName);
                $dbColumn          = $fileInfo['db_column'];
                $storagePathFolder = $fileInfo['storage_path'];

                $filename = $storagePathFolder . uniqid() . '.' . $file->getClientOriginalExtension(); // data baru

                if (! empty($user->client->dbColumn)) { //kossong
                    Storage::disk('s3')->delete($user->client->dbColumn);
                }
                Storage::disk('s3')->put($filename, file_get_contents($file), 'public');
                $client->$dbColumn = $filename;
            }
        }
        $client->save();

        $roles = $user->roles;
        if (!in_array('client', $roles)) {
            $roles[] = 'client';
            $user->roles = $roles;
            $user->save();
        }

        return redirect()->route('profile');
    }
    
    public function profile()
    {
        $user = Auth::user();
        $user->load(['client', 'expert']);

        $roles = $user->roles ?? [];
        $isAdmin = in_array('administrator', $roles, true);
        $isExpert = in_array('expert', $roles, true);
        $isClient = in_array('client', $roles, true);

        // $expertises = [];
        // if ($isAdmin) {
        //     $expertises = Expertise::whereNull('parent_id')
        //         ->orderBy('order')
        //         ->with('childrensRecursive')
        //         ->get();
        // }

        $query = Appointment::latest();

        if ($isExpert && optional($user->expert)->id) {
            $query->where('expert_id', $user->expert->id)
                ->with('user:id,name,email,picture');
        } else {
            $query->where('user_id', $user->id)
                ->with(['expert.user:id,name,email,picture']);
        }

        $appointments = $query->get();
        $appointmentsCount = $appointments->count();

        $calendarEvents = $appointments->map(function ($app) use ($isExpert) {
            $person = $isExpert ? $app->user : optional($app->expert)->user;
            $color = match ($app->status) {
                'confirmed' => '#10b981',
                'cancelled' => '#ef4444',
                default => '#3b82f6',
            };

            return [
                'id' => $app->id,
                'title' => ($person->name ?? 'Unknown') . ' (' . ucfirst($app->status) . ')',
                'start' => $app->date_time,
                'backgroundColor' => $color,
                'borderColor' => $color,
                'extendedProps' => [
                    'status' => $app->status,
                    'topic' => $app->topic
                ]
            ];
        });

        $socialMedias = function_exists('getSocialMedias') ? getSocialMedias($user) : [];

        if ($isExpert && optional($user->expert)->id) {
            // Jika Expert: Cari transaksi yang BERHUBUNGAN dengan appointment milik expert ini
            // (Meskipun user_id di transaksi adalah milik client, kita filter via relasi appointment)
            $transactions = Transaction::whereHas('appointment', function ($q) use ($user) {
                $q->where('expert_id', $user->expert->id);
            })->latest()->get();
        } else {
            // Jika Client: Cari transaksi yang DIBUAT oleh user ini
            $transactions = Transaction::where('user_id', $user->id)
                ->latest()
                ->get();
        }

        return Inertia::render('Profile/Index', [
            'user' => $user,
            'roles' => $roles,
            'isExpert' => $isExpert,
            'isClient' => $isClient,
            'isAdmin' => $isAdmin,
            // Data Tabs
            // 'expertises' => $expertises,
            'appointments' => $appointments,
            'appointmentsCount' => $appointmentsCount,
            'calendarEvents' => $calendarEvents,
            'socialMedias' => $socialMedias,
            'transactions' => $transactions,
        ]);
    }

    public function google_login()
    {
        return Socialite::driver('google')
            ->scopes(['openid', 'email', 'profile'])
            ->with([
                'access_type' => 'offline',
                'prompt' => 'consent',
                'include_granted_scopes' => 'true',
            ])
            ->redirect();
    }

    public function google_calendar_connect(Request $request)
    {
        // Tangkap dan simpan URL asal (jika ada)
        if ($request->has('redirect')) {
            session()->put('calendar_redirect_back', $request->get('redirect'));
        }

        return Socialite::driver('google')
            ->scopes([
                'openid',
                'email',
                'profile',
                'https://www.googleapis.com/auth/calendar.events'
            ])
            ->with([
                'access_type' => 'offline',
                'prompt' => 'consent',
                'include_granted_scopes' => 'true',
            ])
            ->redirect();
    }

    public function google_callback(Request $request)
    {
        try {
            $redirectTo = session()->pull('calendar_redirect_back') ?? session()->pull('url.intended') ?? route('profile');
            $googleUser = Socialite::driver('google')->user();
            $avatarUrl = $googleUser->getAvatar();
            $imageContents = file_get_contents($avatarUrl);
            $filename = 'avatars/' . uniqid() . '.png';

            // Dapatkan user
            $user = User::where('email', $googleUser->email)->first();

            // Ambil scope yang disetujui user
            $accessToken = $googleUser->token;
            $scopeResponse = Http::get('https://www.googleapis.com/oauth2/v1/tokeninfo', [
                'access_token' => $accessToken,
            ]);
            $grantedScopes = explode(' ', $scopeResponse->json('scope') ?? '');

            // Periksa apakah kalender disetujui
            $calendarGranted = in_array('https://www.googleapis.com/auth/calendar.events', $grantedScopes);

            if ($user) {
                $user->update([
                    'google_id' => $googleUser->id,
                    'google_access_token' => $googleUser->token,
                    'google_refresh_token' => $googleUser->refreshToken ?? $user->google_refresh_token,
                    'google_token_expires_at' => now()->addSeconds($googleUser->expiresIn),
                    'google_scopes' => $grantedScopes,
                    'calendar_connected' => $calendarGranted,
                ]);
            } else {
                $user = User::create([
                    'email' => $googleUser->email,
                    'name' => $googleUser->name,
                    'roles' => ['user'],
                    'google_id' => $googleUser->id,
                    'google_access_token' => $googleUser->token,
                    'google_refresh_token' => $googleUser->refreshToken,
                    'google_token_expires_at' => now()->addSeconds($googleUser->expiresIn),
                    'google_scopes' => $grantedScopes,
                    'calendar_connected' => $calendarGranted,
                    'email_verified_at' => now(),
                ]);
            }

            Auth::login($user, true);

            // Simpan foto jika belum ada
            if (!$user->picture) {
                Storage::disk('s3')->put($filename, $imageContents, 'public');
                $user->picture = $filename;
                $user->save();
            }

            $request->session()->regenerate();
            $request->session()->put('loggedUser', ['user' => $user->id]);

            return redirect($redirectTo)->with('success', 'Berhasil login dengan Google.');
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors([
                'email' => 'Google authentication failed: ' . $e->getMessage(),
            ]);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function settings()
    {
        return Inertia::render('Profile/Settings', [
            'user' => Auth::user(),
            // Kirim flash message jika ada (sukses/gagal update)
            'flash' => [
                'success' => session('success'),
                'error' => session('error'),
            ]
        ]);
    }

}
