<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  Allowed roles (e.g., 'administrator', 'client')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        // User must be authenticated
        if (! $user) {
            return redirect()->route('login');
        }

        // Get user roles (assuming roles is an array in the users table)
        $userRoles = $user->roles ?? [];

        // Check if user has any of the required roles
        foreach ($roles as $role) {
            if (in_array($role, $userRoles)) {
                return $next($request);
            }
        }

        // User doesn't have required role - return 403
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Forbidden. You do not have permission to access this resource.',
            ], 403);
        }

        // For Inertia requests, render an error page
        return inertia('Error/AccessDenied', [
            'message' => 'You do not have permission to access this page.',
            'required_roles' => $roles,
        ])->toResponse($request)->setStatusCode(403);
    }
}
