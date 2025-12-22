<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class LandingController extends Controller
{
    public function home()
    {
        return Inertia::render('Home', [
            // 1. HERO: The Big Promise
            'hero' => [
                'title' => 'Find the Right Person, Set the Right Time',
                'subtitle' => 'Integrated platform connecting Companies and Individuals with a curated network of Experts. From counseling to executive mentoring, everything is automatically scheduled in your Google Calendar.',
                'image' => asset('image/dashboard-timeline.png'),
            ],

            // 2. FEATURE BLOCKS (Zig-Zag): Addressing 3 Personas
            'features' => [
                // PERSONA 1: USER / EMPLOYEE (Focus: Convenience & Growth)
                [
                    'title' => 'Personal Development, As Easy As Ordering a Ride',
                    'description' => 'No more back-and-forth emails to find a schedule. Choose an Expert, click an available calendar slot, and the meeting link is automatically added to your Google Calendar. Focus on your session, not the administration.',
                    'image' => 'calendar_ui',
                    'align' => 'right',
                ],
                // PERSONA 2: CLIENT / COMPANY (Focus: Management & Impact)
                [
                    'title' => 'Build Your Company\'s "Super Team"',
                    'description' => 'Provide exclusive access for employees to hundreds of top mentors & psychologists. Monitor quota usage, analyze topics of interest, and pay with a simple corporate invoice.',
                    'image' => 'dashboard_ui',
                    'align' => 'left',
                ],
                // PERSONA 3: EXPERT (Focus: Automation & Income)
                [
                    'title' => 'You\'re the Expert, Let Us Handle the Schedule',
                    'description' => 'Forget about "no-shows" and stuck invoice collections. Keyperson handles upfront payments, automatic client reminders, and timezone synchronization. You just show up and deliver your best impact.',
                    'image' => 'meeting_ui',
                    'align' => 'right',
                ],
            ],

            // 3. BENTO GRID: Technical Features (Trust Signals)
            'bentoFeatures' => [
                [
                    'title' => 'Google Calendar Sync',
                    'desc' => 'No conflicts. Schedule automatically appears on your phone.',
                    'icon' => 'calendar',
                    'color' => 'bg-blue-50 text-blue-600'
                ],
                [
                    'title' => 'Verified Experts',
                    'desc' => 'All experts go through rigorous curation & interview process.',
                    'icon' => 'badge-check',
                    'color' => 'bg-violet-50 text-violet-600'
                ],
                [
                    'title' => 'Secure Payment',
                    'desc' => 'Escrow system. Funds are safe until session completes.',
                    'icon' => 'shield-check',
                    'color' => 'bg-emerald-50 text-emerald-600'
                ],
                [
                    'title' => 'Video Call Ready',
                    'desc' => 'Meeting links (Zoom/GMeet) generated automatically.',
                    'icon' => 'video',
                    'color' => 'bg-fuchsia-50 text-fuchsia-600'
                ],
            ]
        ]);
    }

    // ... method about, support, pricing, terms, privacy tetap sama ...
    public function about()
    {
        return Inertia::render('About');
    }
    public function support()
    {
        return Inertia::render('Support');
    }
    public function pricing()
    {
        return Inertia::render('Pricing');
    }
    public function terms()
    {
        return Inertia::render('Terms');
    }
    public function privacy()
    {
        return Inertia::render('Privacy');
    }
    
    public function services()
    {
        return Inertia::render('Services');
    }
    
    public function contact()
    {
        return Inertia::render('Contact');
    }
}
