<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards)
    {
        $guard = $guards[0] ?? null;
        if (Auth::guard($guard)->check()) {
            return redirect()->route('dashboard.index');
        }

        return $next($request);
    }
}
