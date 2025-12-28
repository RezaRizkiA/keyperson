<?php

namespace App\Http\Controllers;

use App\Services\ClientPortalService;
use Inertia\Inertia;

/**
 * ClientPortalController
 *
 * Handles HTTP for client portal
 * Delegates business logic to ClientPortalService (Dependency Inversion)
 */
class ClientPortalController extends Controller
{
    protected $portalService;

    public function __construct(ClientPortalService $portalService)
    {
        $this->portalService = $portalService;
    }

    /**
     * Display client profile with skills
     *
     * @param  string  $slug
     * @return \Inertia\Response
     */
    public function index($slug)
    {
        $client = $this->portalService->getClientWithSkills($slug);

        return Inertia::render('Client/Portal/ClientProfile', [
            'client' => $client,
        ]);
    }

    /**
     * Display experts for a specific skill
     *
     * @param  string  $slug  Client slug
     * @param  string  $skillSlug  Skill name
     * @return \Inertia\Response
     */
    public function experts($slug, $skillSlug)
    {
        $client = $this->portalService->getClientWithSkills($slug);
        $data = $this->portalService->getExpertsBySkill($client, $skillSlug);

        return Inertia::render('Client/Portal/SkillExperts', [
            'client' => $client,
            'skill' => $data['skill'],
            'experts' => $data['experts'],
            'expertCount' => $data['count'],
        ]);
    }

    /**
     * Show expert detail
     *
     * @param  string  $slug  Expert slug
     * @return \Inertia\Response
     */
    public function show(string $slug)
    {
        $data = $this->portalService->getExpertDetail($slug);
        
        // Build back URL based on authenticated user's client
        $user = auth()->user();
        $backUrl = route('home'); // Default fallback
        
        if ($user && $user->client_id) {
            // Get client slug for portal URL
            $client = $user->company;
            if ($client && $client->slug) {
                $backUrl = route('client.home', $client->slug);
            }
        }
        
        $data['backUrl'] = $backUrl;
        
        // Capture skill_id from query param (passed from SkillExperts page)
        $data['skillId'] = request('skill_id') ? (int) request('skill_id') : null;

        return Inertia::render('Client/Portal/ExpertDetail', $data);
    }
}
