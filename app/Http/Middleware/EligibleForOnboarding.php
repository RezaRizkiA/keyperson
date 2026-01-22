<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class EligibleForOnboarding
{
    /**
     * Handle an incoming request.
     * Block users who are already Expert, Client, or Corporate Employee
     * AND have completed their profile.
     * 
     * Logic:
     * - hasRole('client') + has Client record = BLOCK (sudah lengkap)
     * - hasRole('client') + NO Client record = ALLOW (belum lengkap, perlu Step 2)
     * - hasRole('expert') + has Expert record = BLOCK (sudah lengkap)
     * - hasRole('expert') + NO Expert record = ALLOW (belum lengkap, perlu Step 2)
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Block Corporate Employee (sudah terikat dengan perusahaan)
        if ($user->isCorporateMember()) {
            abort(403, 'Anda sudah terdaftar sebagai karyawan perusahaan. Hubungi HRD untuk informasi lebih lanjut.');
        }

        // Block jika sudah jadi Expert DAN sudah punya profil Expert
        if ($user->hasRole('expert') && $user->expert) {
            return redirect()->route('dashboard.index')
                ->with('info', 'Anda sudah terdaftar sebagai Expert.');
        }

        // Block jika sudah jadi Client DAN sudah punya profil Client
        if ($user->hasRole('client') && $user->ownedClient) {
            return redirect()->route('dashboard.client.index')
                ->with('info', 'Anda sudah terdaftar sebagai Client.');
        }
        return $next($request);
    }
}

