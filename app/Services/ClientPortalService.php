<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Expert;
use App\Models\Skill;
use App\Models\User;

/**
 * ClientPortalService
 * 
 * Business logic for client portal operations
 * Follows Single Responsibility Principle
 */
class ClientPortalService
{
    /**
     * Get client with eager-loaded skills
     * 
     * @param string $slug
     * @return Client
     */
    public function getClientWithSkills(string $slug): Client
    {
        return Client::where('slug', $slug)
            ->with(['skills.subCategory.category'])
            ->firstOrFail();
    }

    /**
     * Get experts who have a specific skill that belongs to the client
     * 
     * @param Client $client
     * @param string $skillSlug  
     * @return array
     */
    public function getExpertsBySkill(Client $client, string $skillSlug): array
    {
        // First, verify the skill belongs to this client
        $skill = $client->skills()
            ->where('skills.name', $skillSlug)
            ->with('subCategory.category')
            ->firstOrFail();

        // Get experts who have this skill
        $experts = Expert::whereHas('skills', function ($q) use ($skill) {
                $q->where('skills.id', $skill->id);
            })
            ->with([
                'user:id,name,email,picture',
                'skills' => function ($query) use ($skill) {
                    $query->where('skills.id', $skill->id);
                }
            ])
            ->get();

        return [
            'skill' => $skill,
            'experts' => $experts,
            'count' => $experts->count(),
        ];
    }

    /**
     * Get expert detail with all relationships
     * 
     * @param string $slug Expert slug
     * @return array
     */
    public function getExpertDetail(string $slug): array
    {
        $expert = Expert::where('slug', $slug)
            ->with([
                'user:id,name,email,picture,phone,address',
                'skills.subCategory.category',
                'reviews' => function ($query) {
                    $query->with(['user.client:id,user_id,company_name', 'skill:id,name'])
                        ->latest()
                        ->limit(5);
                }
            ])
            ->firstOrFail();

        return [
            'expert' => $expert,
            'reviewsCount' => $expert->reviews()->count(),
        ];
    }
}
