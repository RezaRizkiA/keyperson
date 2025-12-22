<?php

namespace App\Services;

use App\Models\Client;
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

        // Get experts (users with expert relationship) who have this skill
        $experts = User::whereHas('expert', function ($query) use ($skill) {
            $query->whereHas('skills', function ($q) use ($skill) {
                $q->where('skills.id', $skill->id);
            });
        })
        ->with([
            'expert:id,user_id,title,about,price,rating,total_reviews,total_sessions',
            'expert.skills' => function ($query) use ($skill) {
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
     * @param int $expertId
     * @return array
     */
    public function getExpertDetail(int $expertId): array
    {
        $expert = User::whereHas('expert')->with([
            'expert:id,user_id,title,about,price,type,experiences,licenses,gallerys,socials,background,rating,total_reviews,total_sessions',
            'expert.skills.subCategory.category',
            'expert.reviews' => function ($query) {
                $query->with(['user.client:id,user_id,company_name', 'skill:id,name'])
                    ->latest()
                    ->limit(5);
            }
        ])->findOrFail($expertId);

        return [
            'expert' => $expert,
            'reviewsCount' => $expert->expert->reviews()->count(),
        ];
    }
}
