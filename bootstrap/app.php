<?php

use Illuminate\Foundation\Application;
use Illuminate\Auth\Middleware\Authenticate;
use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use App\Http\Middleware\EnsureGoogleCalendarConnected;
use App\Http\Middleware\NotExpert;
use App\Http\Middleware\EligibleForOnboarding;
use App\Http\Middleware\EnsureClientAccess;
use App\Http\Middleware\CheckRole;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'auth' => Authenticate::class,
            'guest' => RedirectIfAuthenticated::class,
            'calendar.connected' => EnsureGoogleCalendarConnected::class,
            'verified' => EnsureEmailIsVerified::class,
            'not-expert' => NotExpert::class,
            'eligible-onboarding' => EligibleForOnboarding::class,
            'client-access' => EnsureClientAccess::class,
            'role' => CheckRole::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            'payment/notify',
        ]);
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class, // <--- TAMBAHKAN INI
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
