<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function choosePath()
    {
        return Inertia::render('Auth/ChoosePath');
    }

    public function login_post(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
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
                'user' => $user->id,
                'token_jwt' => $user->id,
            ];
            $request->session()->put('loggedUser', $credentials);

            return redirect()->intended(route('dashboard.index'));
        }

        return redirect()->back()->withErrors([
            'email' => 'Email atau Password Tidak Valid!!',
        ])->withInput();
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

    /**
     * Google login specifically for client/HRD registration
     * This will assign 'client' role upon successful registration
     */
    public function google_login_as_client()
    {
        // Store intent in session to be checked in callback
        session()->put('google_registration_intent', 'client');

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
                'https://www.googleapis.com/auth/calendar.events',
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
            $redirectTo = session()->pull('calendar_redirect_back') ?? session()->pull('url.intended') ?? route('dashboard.index');
            $googleUser = Socialite::driver('google')->user();
            $avatarUrl = $googleUser->getAvatar();
            $imageContents = file_get_contents($avatarUrl);
            $filename = 'avatars/'.uniqid().'.png';

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

                // If existing user is registering as client, add client role
                $registrationIntent = session()->pull('google_registration_intent');
                if ($registrationIntent === 'client' && ! in_array('client', $user->roles ?? [])) {
                    $user->roles = array_unique(array_merge($user->roles ?? [], ['client']));
                    $user->save();
                }
            } else {
                // Determine roles based on registration intent
                $registrationIntent = session()->pull('google_registration_intent');
                $roles = ['user'];
                if ($registrationIntent === 'client') {
                    $roles = ['user', 'client'];
                }

                $user = User::create([
                    'email' => $googleUser->email,
                    'name' => $googleUser->name,
                    'roles' => $roles,
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
            if (! $user->picture) {
                Storage::disk('s3')->put($filename, $imageContents, 'public');
                $user->picture = $filename;
                $user->save();
            }

            $request->session()->regenerate();
            $request->session()->put('loggedUser', ['user' => $user->id]);

            return redirect($redirectTo)->with('success', 'Berhasil login dengan Google.');
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors([
                'email' => 'Google authentication failed: '.$e->getMessage(),
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
}
