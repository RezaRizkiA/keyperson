<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureGoogleCalendarConnected
{
    public function handle($request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->calendar_connected) {
            return redirect()->route('google.calendar.connect')
                ->with('warning', 'Silakan hubungkan Google Calendar Anda terlebih dahulu.');
        }

        return $next($request);
    }
}
