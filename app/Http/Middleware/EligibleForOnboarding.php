<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EligibleForOnboarding
{
    /**
     * Handle an incoming request.
     * Block users who are already Expert, Client, or Corporate Employee.
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

        // Block jika sudah jadi Expert
        if ($user->hasRole('expert')) {
            return redirect()->route('dashboard.index')
                ->with('info', 'Anda sudah terdaftar sebagai Expert.');
        }

        // Block jika sudah jadi Client
        if ($user->hasRole('client')) {
            return redirect()->route('dashboard.index')
                ->with('info', 'Anda sudah terdaftar sebagai Client.');
        }

        return $next($request);
    }
}
