<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class NotExpert
{
    /**
     * Handle an incoming request.
     * Block users with 'expert' role from accessing certain routes.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->hasRole('expert')) {
            abort(403, 'Expert tidak dapat mengakses halaman ini.');
        }

        return $next($request);
    }
}
