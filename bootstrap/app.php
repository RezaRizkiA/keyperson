<?php

use Illuminate\Foundation\Application;
use Illuminate\Auth\Middleware\Authenticate;
use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use App\Http\Middleware\EnsureGoogleCalendarConnected;

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
        ]);
        $middleware->validateCsrfTokens(except: [
            '/payment-notify',
        ]);
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class, // <--- TAMBAHKAN INI
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
