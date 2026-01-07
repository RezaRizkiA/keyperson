<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class EnsureClientAccess
{
    /**
     * Memastikan user yang login memiliki client_id yang cocok dengan client yang diakses.
     * 
     * Flow:
     * 1. Jika belum login → redirect ke login
     * 2. Setelah login, cek client_id cocok dengan client.id
     * 3. Jika tidak cocok → tampilkan halaman akses ditolak
     * 
     * @param  string|null  $clientParam  Route parameter name for client (default: 'client')
     */
    public function handle(Request $request, Closure $next, string $clientParam = 'client'): Response
    {
        $user = Auth::user();
        
        // Jika belum login, redirect ke login dengan intended URL
        if (!$user) {
            return redirect()->guest(route('login'));
        }

        // Dapatkan client dari route parameter
        // Route model binding belum terjadi saat middleware dieksekusi,
        // jadi kita perlu resolve Client secara manual dari slug
        $clientParam = $request->route($clientParam);
        
        // Jika parameter adalah instance Client (sudah di-bind), gunakan langsung
        // Jika string (slug), resolve dari database
        if ($clientParam instanceof Client) {
            $client = $clientParam;
        } else {
            $client = Client::where('slug', $clientParam)->first();
        }
        
        // Jika client tidak ditemukan, biarkan Laravel handle 404
        if (!$client) {
            abort(404);
        }

        // Cek apakah user adalah member dari client yang sama
        if ($user->client_id !== $client->id) {
            return Inertia::render('Error/AccessDenied', [
                'title' => 'Akses Ditolak',
                'message' => 'Anda tidak memiliki akses ke portal perusahaan ini.',
                'description' => 'Halaman ini hanya dapat diakses oleh karyawan yang terdaftar di perusahaan terkait. Jika Anda merasa ini adalah kesalahan, silakan hubungi HRD perusahaan Anda.',
                'clientName' => $client->company_name,
            ])->toResponse($request)->setStatusCode(403);
        }

        return $next($request);
    }
}
