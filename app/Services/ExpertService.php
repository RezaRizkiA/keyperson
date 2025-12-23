<?php

namespace App\Services;

use App\Models\Expert;
use App\Repositories\ExpertRepository;
use Illuminate\Http\Request;

class ExpertService
{
    protected ExpertRepository $expertRepository;

    public function __construct(ExpertRepository $expertRepository)
    {
        $this->expertRepository = $expertRepository;
    }

    /**
     * Get experts with stats for admin dashboard
     *
     * @param Request $request
     * @return array
     */
    public function getExpertsWithStats(Request $request): array
    {
        $experts = $this->expertRepository->getPaginatedExperts(
            perPage: $request->get('per_page', 10),
            search: $request->get('search'),
            specialization: $request->get('specialization'),
            status: $request->get('status')
        );

        $stats = $this->expertRepository->getExpertStats();

        // Transform experts data for frontend
        $transformedExperts = $experts->through(function ($expert) {
            return [
                'id' => $expert->id,
                'name' => $expert->user->name ?? 'Unknown',
                'email' => $expert->user->email ?? '',
                'avatar' => $expert->user->picture ?? $expert->user->picture_url ?? null,
                'expert_id' => '#EXP-' . str_pad($expert->id, 3, '0', STR_PAD_LEFT),
                'specialization' => $expert->skills->first()->name ?? 'General',
                'category' => $expert->skills->first()->category ?? null,
                'experience' => $expert->experiences[0]['years'] ?? '0 years',
                'status' => $this->determineStatus($expert),
                'performance' => [
                    'appointments' => $expert->appointments_count,
                    'rating' => number_format($expert->rating ?? 0, 1),
                    'label' => $this->getPerformanceLabel($expert->rating ?? 0),
                ],
                'is_online' => $this->isExpertOnline($expert),
            ];
        });

        return [
            'experts' => $transformedExperts,
            'stats' => $stats,
            'filters' => [
                'search' => $request->get('search'),
                'specialization' => $request->get('specialization'),
                'status' => $request->get('status'),
            ],
        ];
    }

    /**
     * Determine expert status
     *
     * @param object $expert
     * @return string
     */
    protected function determineStatus($expert): string
    {
        if (!$expert->user) {
            return 'pending';
        }

        // Check recent activity
        $hasRecentActivity = $expert->appointments()
            ->where('date_time', '>=', now()->subDays(30))
            ->exists();

        if ($hasRecentActivity) {
            return 'active';
        }

        return 'inactive';
    }

    /**
     * Get performance label based on rating
     *
     * @param float $rating
     * @return string
     */
    protected function getPerformanceLabel(float $rating): string
    {
        if ($rating >= 4.5) return 'Top 5%';
        if ($rating >= 4.0) return 'High';
        if ($rating >= 3.5) return 'Avg';
        if ($rating > 0) return 'New';
        return 'New';
    }

    /**
     * Check if expert is currently online (placeholder logic)
     *
     * @param object $expert
     * @return bool
     */
    protected function isExpertOnline($expert): bool
    {
        // This is placeholder logic
        // You can implement actual online status tracking
        return rand(0, 1) === 1;
    }

    /**
     * Get expert detail with all information
     *
     * @param int $id
     * @return array
     * @throws \Exception
     */
    public function getExpertDetail(int $id): array
    {
        $expert = $this->expertRepository->findWithAllRelations($id);

        if (!$expert) {
            throw new \Exception('Expert not found');
        }

        return [
            'expert' => [
                'id' => $expert->id,
                'name' => $expert->user->name ?? 'Unknown',
                'email' => $expert->user->email ?? '',
                'avatar' => $expert->user->picture ?? null,
                'expert_id' => '#EXP-' . str_pad($expert->id, 3, '0', STR_PAD_LEFT),
                'specialization' => $expert->skills->first()->name ?? 'General Consultant',
                'category' => $expert->skills->first()->category ?? null,
                'experience' => $expert->experiences[0]['years'] ?? '0 years',
                'location' => $expert->user->address ?? 'Not specified',
                'bio' => $expert->bio ?? 'Board-certified specialist dedicated to providing patient-centered care and utilizing the latest advancements.',
                'status' => $this->determineStatus($expert),
                'is_online' => $this->isExpertOnline($expert),
                'stats' => [
                    'total_appointments' => $expert->appointments_count ?? 0,
                    'appointments_trend' => '+12%',
                    'rating' => number_format($expert->reviews_avg_rating ?? 0, 1),
                    'rating_stars' => round($expert->reviews_avg_rating ?? 0),
                    'avg_duration' => '45m',
                    'response_rate' => '98%',
                ],
                'qualifications' => [
                    'education' => $expert->qualifications['education'] ?? [
                        [
                            'degree' => 'MD, Medicine',
                            'institution' => 'Harvard Medical School',
                            'years' => '2005 - 2009',
                        ]
                    ],
                    'residency' => $expert->qualifications['residency'] ?? [
                        [
                            'program' => 'Internal Medicine',
                            'hospital' => 'Johns Hopkins Hospital',
                            'years' => '2009 - 2012',
                        ]
                    ],
                    'certifications' => $expert->certifications ?? [
                        ['name' => 'Board Certified Cardiologist', 'verified' => true],
                        ['name' => 'ACLS Certified', 'verified' => true],
                        ['name' => 'Medical License #MD12345', 'verified' => false],
                    ],
                ],
                'expertise' => [
                    'core_specializations' => $expert->skills->pluck('name')->toArray(),
                    'languages' => $expert->languages ?? ['English', 'Indonesian'],
                ],
                'contact' => [
                    'email' => $expert->user->email ?? '',
                    'phone' => $expert->user->phone ?? '+1 (555) 123-4567',
                    'timezone' => 'EST (UTC-5)',
                ],
                'metadata' => [
                    'joined_date' => $expert->created_at->format('M d, Y'),
                    'last_login' => $expert->user->last_login_at ? $expert->user->last_login_at->diffForHumans() : '2 hours ago',
                ],
            ],
        ];
    }

    /**
     * Delete expert
     *
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function deleteExpert(int $id): bool
    {
        return $this->expertRepository->delete($id);
    }

    /**
     * Get expert data for edit form
     * Uses eager loading to prevent N+1 queries
     *
     * @param int $id
     * @return array
     * @throws \Exception
     */
    public function getExpertForEdit(int $id): array
    {
        // Get expert with all relationships to prevent N+1
        $expert = $this->expertRepository->findWithAllRelations($id);

        if (!$expert) {
            throw new \Exception('Expert not found');
        }

        // Split name into first and last
        $nameParts = explode(' ', $expert->user->name ?? '');
        $firstName = $nameParts[0] ?? '';
        $lastName = implode(' ', array_slice($nameParts, 1)) ?? '';

        return [
            'expert' => [
                'id' => $expert->id,
                'expert_id' => '#EXP-' . str_pad($expert->id, 3, '0', STR_PAD_LEFT),
                
                // User fields
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $expert->user->email ?? '',
                'phone' => $expert->user->phone ?? '',
                'address' => $expert->user->address ?? '',
                'avatar' => $expert->user->picture ?? null,
                
                // Expert fields
                'title' => $expert->title ?? '',
                'price' => $expert->price ?? 0,
                'about' => $expert->about ?? '',
                'type' => $expert->type ?? [],
                'experiences' => $expert->experiences ?? [],
                
                // Skills (from pivot table expert_skill)
                'selected_skills' => $expert->skills->pluck('id')->toArray(),
                
                // Status and metrics
                'status' => $this->determineStatus($expert),
                'stats' => [
                    'total_appointments' => $expert->appointments_count ?? 0,
                    'rating' => number_format($expert->reviews_avg_rating ?? 0, 1),
                    'completion_rate' => '98%', // Calculate from appointments
                ],
                'joined_date' => $expert->created_at->format('M d, Y'),
                'last_active' => '2 hours ago', // Placeholder
            ],
        ];
    }

    /**
     * Update expert data
     * Updates both user and expert tables, syncs skills
     *
     * @param int $id
     * @param array $data
     * @return Expert
     * @throws \Exception
     */
    public function updateExpert(int $id, array $data): Expert
    {
        $expert = $this->expertRepository->findWithRelations($id);

        if (!$expert || !$expert->user) {
            throw new \Exception('Expert not found');
        }

        // Update user data
        $expert->user->update([
            'name' => trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? '')),
            'email' => $data['email'] ?? $expert->user->email,
            'phone' => $data['phone'] ?? $expert->user->phone,
            'address' => $data['address'] ?? $expert->user->address,
        ]);

        // Handle licenses file uploads
        $licensesData = [];
        if (isset($data['licenses']) && is_array($data['licenses'])) {
            foreach ($data['licenses'] as $license) {
                $licenseItem = [
                    'certification' => $license['certification'] ?? '',
                ];
                
                // Handle file upload if exists
                if (isset($license['file']) && $license['file'] instanceof \Illuminate\Http\UploadedFile) {
                    $path = $license['file']->store('licenses', 'public');
                    $licenseItem['file'] = $path;
                }
                
                $licensesData[] = $licenseItem;
            }
        }

        // Handle gallery file uploads
        $gallerysData = [];
        if (isset($data['gallerys']) && is_array($data['gallerys'])) {
            foreach ($data['gallerys'] as $gallery) {
                if (isset($gallery['file']) && $gallery['file'] instanceof \Illuminate\Http\UploadedFile) {
                    $path = $gallery['file']->store('gallery', 'public');
                    $gallerysData[] = ['file' => $path];
                }
            }
        }

        // Update expert data
        $expertData = [
            'title' => $data['title'] ?? $expert->title,
            'price' => $data['price'] ?? $expert->price,
            'about' => $data['about'] ?? $expert->about,
            'type' => $data['type'] ?? $expert->type,
            'experiences' => $data['experiences'] ?? $expert->experiences,
            'licenses' => !empty($licensesData) ? $licensesData : $expert->licenses,
            'gallerys' => !empty($gallerysData) ? $gallerysData : $expert->gallerys,
        ];

        $expert = $this->expertRepository->update($id, $expertData);

        // Sync skills via pivot table (prevents N+1)
        if (isset($data['selected_skills']) && is_array($data['selected_skills'])) {
            $this->expertRepository->syncSkills($expert, $data['selected_skills']);
        }

        return $expert;
    }
}
