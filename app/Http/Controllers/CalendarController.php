<?php

namespace App\Http\Controllers;

use App\Services\CalendarService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CalendarController extends Controller
{
    protected $calendarService;

    public function __construct(CalendarService $calendarService)
    {
        $this->calendarService = $calendarService;
    }

    public function index()
    {
        $user = Auth::user();
        $roles = $user->roles ?? [];

        // 1. ADMINISTRATOR
        if (in_array('administrator', $roles)) {
            return Inertia::render('Administrator/Calendar/Index', [
                'events' => $this->calendarService->getEventsForAdmin()
            ]);
        }

        // 2. EXPERT
        if (in_array('expert', $roles)) {
            $expert = $user->expert;
            if (!$expert) return redirect()->route('dashboard');

            return Inertia::render('Expert/Calendar/Index', [
                'events' => $this->calendarService->getEventsForExpert($expert->id)
            ]);
        }

        // 3. USER (CLIENT)
        return Inertia::render('User/Calendar/Index', [
            'events' => $this->calendarService->getEventsForUser($user->id)
        ]);
    }
}
