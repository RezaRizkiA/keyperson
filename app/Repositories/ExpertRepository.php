<?php

namespace App\Repositories;

use App\Models\Expert;
use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;

class ExpertRepository
{
    /**
     * Get paginated experts with optional filters
     * Uses eager loading to prevent N+1 queries
     *
     * @param int $perPage
     * @param string|null $search
     * @param string|null $specialization
     * @param string|null $status
     * @return LengthAwarePaginator
     */
    public function getPaginatedExperts(
        int $perPage = 10,
        ?string $search = null,
        ?string $specialization = null,
        ?string $status = null
    ): LengthAwarePaginator {
        return Expert::with(['user', 'skills'])
            ->withCount('appointments')
            // Search by expert name or email
            ->when($search, function ($query, $search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            // Filter by specialization (skill)
            ->when($specialization, function ($query, $specialization) {
                $query->whereHas('skills', function ($q) use ($specialization) {
                    $q->where('id', $specialization);
                });
            })
            // Filter by status
            ->when($status, function ($query, $status) {
                // Filter based on status
                if ($status === 'active') {
                    $query->whereHas('appointments', function ($q) {
                        $q->where('date_time', '>=', now()->subDays(30));
                    });
                } elseif ($status === 'pending') {
                    $query->whereDoesntHave('user');
                }
            })
            ->latest('created_at')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Get expert statistics for dashboard cards
     *
     * @return array
     */
    public function getExpertStats(): array
    {
        $total = Expert::count();

        $activeNow = Expert::whereHas('appointments', function ($q) {
            $q->where('date_time', '>=', now()->subDays(30))
              ->whereIn('status', ['confirmed', 'progress']);
        })->distinct()->count();

        $pendingApproval = Expert::whereDoesntHave('user')->count();

        $suspended = 0;

        return [
            'total_experts' => $total,
            'active_now' => $activeNow,
            'pending_approval' => $pendingApproval,
            'suspended' => $suspended,
        ];
    }

    /**
     * Find expert by ID with relationships
     *
     * @param int $id
     * @return Expert|null
     */
    public function findWithRelations(int $id): ?Expert
    {
        return Expert::with(['user', 'skills', 'appointments'])->find($id);
    }

    /**
     * Find expert with all relationships for detail page
     * Uses eager loading to prevent N+1 queries
     *
     * @param int $id
     * @return Expert|null
     */
    public function findWithAllRelations(int $id): ?Expert
    {
        return Expert::with([
            'user',
            'skills',
            'appointments' => function ($query) {
                $query->latest()->limit(10);
            },
            'reviews'
        ])
        ->withCount('appointments')
        ->withAvg('reviews', 'rating')
        ->find($id);
    }

    /**
     * Delete expert by ID
     *
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function delete(int $id): bool
    {
        $expert = Expert::find($id);
        
        if (!$expert) {
            throw new \Exception('Expert not found');
        }
        
        return $expert->delete();
    }

    /**
     * Update expert data
     *
     * @param int $id
     * @param array $data
     * @return Expert
     * @throws \Exception
     */
    public function update(int $id, array $data): Expert
    {
        $expert = Expert::find($id);
        
        if (!$expert) {
            throw new \Exception('Expert not found');
        }
        
        $expert->update($data);
        
        return $expert->fresh(['user', 'skills']);
    }

    /**
     * Sync skills to expert via pivot table
     * Prevents N+1 by using single sync operation
     *
     * @param Expert $expert
     * @param array $skillIds
     * @return void
     */
    public function syncSkills(Expert $expert, array $skillIds): void
    {
        $expert->skills()->sync($skillIds);
    }

    /**
     * Get all categories with subcategories and skills for expert registration
     *
     * @return array
     */
    public function getCategoriesWithSkills(): array
    {
        return Category::with(['subCategories.skills'])
            ->orderBy('name')
            ->get()
            ->toArray();
    }
}
