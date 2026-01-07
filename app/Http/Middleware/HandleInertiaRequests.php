<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            // 1. Data User Login
            'auth' => [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                    'roles' => $request->user()->roles,
                    'picture_url' => $request->user()->picture_url,
                    'client_id' => $request->user()->client_id,
                    'company' => $request->user()->client_id ? [
                        'slug' => $request->user()->company?->slug,
                        'company_name' => $request->user()->company?->company_name,
                    ] : null,
                    // Token Google JANGAN dimasukkan disini
                ] : null,
            ],

            // 2. Data Asset Global (Logo)
            'assets' => [
                'logoUrl' => asset('assets/images/logos/key-person.png'),
                'logoWhiteUrl' => asset('assets/images/logos/key-person-white.png'),
                'logoSmallUrl' => asset('assets/images/logos/key-color.png'),
                'googleIconUrl' => asset('assets/images/svgs/google-icon.svg'),
                'bannerUrl' => asset('image/banner-detail-expert.jpg'),
                'userPlaceholderUrl' => asset('assets/images/profile/user-1.jpg'),
                'bannerPlaceholderUrl' => asset('assets/images/backgrounds/login-bg.jpg'),
                'clientLogoPlaceholderUrl' => asset('assets/images/logos/white-key.png'),
                'blogPlaceholderUrl' => asset('assets/images/frontend-pages/blog-1.jpg'),
            ],

            // 3. Definisi Route untuk Navbar/Footer (Agar bisa dipanggil di Vue)
            'routes' => [
                'home' => route('home'),
                'about' => route('about'),
                'support' => route('support'),
                'pricing' => route('pricing'),
                'login' => route('login'),
                'terms' => route('terms'),
                'privacy' => route('privacy'),
            ],

            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
                'message' => fn () => $request->session()->get('message'),
            ],
        ]);
    }
}
