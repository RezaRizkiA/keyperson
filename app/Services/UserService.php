<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Get paginated users with stats and filters
     */
    public function getPaginatedUsers(array $filters = []): array
    {
        $users = $this->userRepository->getPaginatedUsers($filters);
        $stats = $this->userRepository->getStats();

        // Transform users for frontend
        $transformedUsers = $users->through(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? '-',
                'address' => $user->address ?? '-',
                'picture' => $user->picture_url,
                'roles' => $user->roles ?? [],
                'email_verified' => $user->email_verified_at !== null,
                'calendar_connected' => $user->calendar_connected ?? false,
                'user_type' => $this->determineUserType($user),
                'company_name' => $user->ownedClient->company_name ?? null,
                'expert_title' => $user->expert->title ?? null,
                'appointments_count' => $user->appointments_count ?? 0,
                'created_at' => $user->created_at->format('M d, Y'),
            ];
        });

        return [
            'users' => $transformedUsers,
            'stats' => $stats,
            'filters' => $filters,
        ];
    }

    /**
     * Get user detail for show page
     *
     * @throws \Exception
     */
    public function getUserDetail(int $id): array
    {
        $user = $this->userRepository->findWithRelations($id);

        if (! $user) {
            throw new \Exception('User not found');
        }

        return [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? '-',
                'address' => $user->address ?? '-',
                'picture' => $user->picture_url,
                'roles' => $user->roles ?? [],
                'email_verified' => $user->email_verified_at !== null,
                'email_verified_at' => $user->email_verified_at?->format('M d, Y H:i'),
                'calendar_connected' => $user->calendar_connected ?? false,
                'user_type' => $this->determineUserType($user),

                // Related data
                'client' => $user->ownedClient ? [
                    'company_name' => $user->ownedClient->company_name,
                    'industry' => $user->ownedClient->industry,
                    'website' => $user->ownedClient->website,
                ] : null,

                'expert' => $user->expert ? [
                    'title' => $user->expert->title,
                    'price' => 'Rp '.number_format($user->expert->price ?? 0, 0, ',', '.'),
                ] : null,

                // Activity
                'appointments_count' => $user->appointments->count(),
                'recent_appointments' => $user->appointments->map(function ($appointment) {
                    $dateTime = $appointment->date_time ? \Carbon\Carbon::parse($appointment->date_time) : null;

                    return [
                        'id' => $appointment->id,
                        'date' => $dateTime ? $dateTime->format('M d, Y') : '-',
                        'time' => $dateTime ? $dateTime->format('H:i') : '-',
                        'status' => $appointment->status,
                        'price' => 'Rp '.number_format($appointment->price ?? 0, 0, ',', '.'),
                    ];
                }),

                // Metadata
                'created_at' => $user->created_at->format('M d, Y H:i'),
                'updated_at' => $user->updated_at->format('M d, Y H:i'),
            ],
        ];
    }

    /**
     * Get user data for edit form
     *
     * @throws \Exception
     */
    public function getUserForEdit(int $id): array
    {
        $user = $this->userRepository->find($id);

        if (! $user) {
            throw new \Exception('User not found');
        }

        return [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? '',
                'address' => $user->address ?? '',
                'picture' => $user->picture_url,
                'roles' => $user->roles ?? [],
                'email_verified' => $user->email_verified_at !== null,
                'calendar_connected' => $user->calendar_connected ?? false,
            ],
        ];
    }

    /**
     * Update user data
     *
     * @throws \Exception
     */
    public function updateUser(int $id, array $data): User
    {
        $user = $this->userRepository->find($id);

        if (! $user) {
            throw new \Exception('User not found');
        }

        // Handle picture upload
        if (isset($data['picture']) && $data['picture'] instanceof \Illuminate\Http\UploadedFile) {
            // Delete old picture if exists
            if ($user->picture) {
                Storage::disk('public')->delete($user->picture);
            }
            $data['picture'] = $data['picture']->store('users/pictures', 'public');
        } else {
            unset($data['picture']);
        }

        // Update user
        return $this->userRepository->update($id, [
            'name' => $data['name'] ?? $user->name,
            'email' => $data['email'] ?? $user->email,
            'phone' => $data['phone'] ?? $user->phone,
            'address' => $data['address'] ?? $user->address,
            'roles' => $data['roles'] ?? $user->roles,
            'picture' => $data['picture'] ?? $user->picture,
        ]);
    }

    /**
     * Delete user
     *
     * @throws \Exception
     */
    public function deleteUser(int $id): bool
    {
        $user = $this->userRepository->find($id);

        if (! $user) {
            throw new \Exception('User not found');
        }

        // Prevent deleting self
        if ($user->id === Auth::id()) {
            throw new \Exception('You cannot delete your own account');
        }

        // Prevent deleting last administrator
        if (in_array('administrator', $user->roles ?? [])) {
            $adminCount = User::whereJsonContains('roles', 'administrator')->count();
            if ($adminCount <= 1) {
                throw new \Exception('Cannot delete the last administrator');
            }
        }

        return $this->userRepository->delete($id);
    }

    /**
     * Determine user type based on roles
     */
    private function determineUserType(User $user): string
    {
        $roles = $user->roles ?? [];

        if (in_array('administrator', $roles)) {
            return 'Administrator';
        }
        if (in_array('expert', $roles)) {
            return 'Expert';
        }
        if (in_array('client', $roles)) {
            return 'Client';
        }

        return 'User';
    }
}
