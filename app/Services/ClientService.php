<?php

namespace App\Services;

use App\Models\Client;
use App\Repositories\ClientRepository;
use Illuminate\Http\Request;

class ClientService
{
    protected ClientRepository $clientRepository;

    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    /**
     * Get clients with stats for admin dashboard
     *
     * @param Request $request
     * @return array
     */
    public function getClientsWithStats(Request $request): array
    {
        $clients = $this->clientRepository->getPaginatedClients(
            perPage: $request->get('per_page', 10),
            search: $request->get('search'),
            status: $request->get('status')
        );

        $stats = $this->clientRepository->getClientStats();

        // Transform clients data for frontend
        $transformedClients = $clients->through(function ($client) {
            return [
                'id' => $client->id,
                'name' => $client->user->name ?? 'Unknown',
                'email' => $client->user->email ?? '',
                'avatar' => $client->user->picture ?? $client->user->picture_url ?? null,
                'client_id' => '#CL-' . str_pad($client->id, 3, '0', STR_PAD_LEFT),
                'company_name' => $client->company_name ?? 'N/A',
                'industry' => $client->industry ?? 'General',
                'status' => $this->determineStatus($client),
                'performance' => [
                    'appointments' => $client->appointments_count ?? 0,
                    'total_spent' => number_format($client->total_spent ?? 0, 0),
                    'label' => $this->getClientLabel($client->appointments_count ?? 0),
                ],
                'is_verified' => $client->verified ?? false,
            ];
        });

        return [
            'clients' => $transformedClients,
            'stats' => $stats,
            'filters' => [
                'search' => $request->get('search'),
                'status' => $request->get('status'),
            ],
        ];
    }

    /**
     * Determine client status
     *
     * @param object $client
     * @return string
     */
    protected function determineStatus($client): string
    {
        if (!$client->user) {
            return 'pending';
        }

        // Check recent activity
        $hasRecentActivity = $client->appointments()
            ->where('date_time', '>=', now()->subDays(30))
            ->exists();

        if ($hasRecentActivity) {
            return 'active';
        }

        // Check if they've ever had appointments
        if ($client->appointments_count > 0) {
            return 'inactive';
        }

        return 'new';
    }

    /**
     * Get client label based on appointments count
     *
     * @param int $appointmentsCount
     * @return string
     */
    protected function getClientLabel(int $appointmentsCount): string
    {
        if ($appointmentsCount >= 50) return 'VIP';
        if ($appointmentsCount >= 20) return 'Premium';
        if ($appointmentsCount >= 10) return 'Regular';
        if ($appointmentsCount > 0) return 'Active';
        return 'New';
    }

    /**
     * Get client detail with all information
     *
     * @param int $id
     * @return array
     * @throws \Exception
     */
    public function getClientDetail(int $id): array
    {
        $client = $this->clientRepository->findWithAllRelations($id);

        if (!$client) {
            throw new \Exception('Client not found');
        }

        return [
            'client' => [
                'id' => $client->id,
                'name' => $client->user->name ?? 'Unknown',
                'email' => $client->user->email ?? '',
                'avatar' => $client->user->picture ?? null,
                'client_id' => '#CL-' . str_pad($client->id, 3, '0', STR_PAD_LEFT),
                'company_name' => $client->company_name ?? 'N/A',
                'industry' => $client->industry ?? 'General',
                'location' => $client->user->address ?? 'Not specified',
                'description' => $client->description ?? 'No description provided.',
                'status' => $this->determineStatus($client),
                'is_verified' => $client->verified ?? false,
                'stats' => [
                    'total_appointments' => $client->appointments_count ?? 0,
                    'appointments_trend' => '+12%',
                    'total_spent' => 'Rp ' . number_format($client->total_spent ?? 0, 0, ',', '.'),
                    'avg_rating' => number_format($client->avg_rating ?? 0, 1),
                ],
                'company_info' => [
                    'website' => $client->website ?? '-',
                    'size' => $client->company_size ?? '-',
                    'founded' => $client->founded_year ?? '-',
                ],
                'skills_needed' => $client->skills->pluck('name')->toArray() ?? [],
                'contact' => [
                    'email' => $client->user->email ?? '',
                    'phone' => $client->user->phone ?? '+62 xxx-xxxx-xxxx',
                    'address' => $client->user->address ?? 'Not specified',
                ],
                'metadata' => [
                    'joined_date' => $client->created_at->format('M d, Y'),
                    'last_login' => 'Recently active',
                ],
            ],
        ];
    }

    /**
     * Get client data for edit form
     *
     * @param int $id
     * @return array
     * @throws \Exception
     */
    public function getClientForEdit(int $id): array
    {
        $client = $this->clientRepository->findWithRelations($id);

        if (!$client) {
            throw new \Exception('Client not found');
        }

        // Split name into first and last
        $nameParts = explode(' ', $client->user->name ?? '');
        $firstName = $nameParts[0] ?? '';
        $lastName = implode(' ', array_slice($nameParts, 1)) ?? '';

        return [
            'client' => [
                'id' => $client->id,
                
                // User fields
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $client->user->email ?? '',
                'phone' => $client->user->phone ?? '',
                'address' => $client->user->address ?? '',
                
                // Client fields
                'company_name' => $client->company_name ?? '',
                'industry' => $client->industry ?? '',
                'website' => $client->website ?? '',
                'employee_count' => $client->employee_count ?? '',
                'about' => $client->about ?? '',
                'logo' => $client->logo ?? null,
                'cover_image' => $client->cover_image ?? null,
                
                // Skills (from pivot table client_skill)
                'selected_skills' => $client->skills->pluck('id')->toArray(),
                
                // Status and metrics (for display)
                'status' => $this->determineStatus($client),
                'stats' => [
                    'total_appointments' => $client->appointments_count ?? 0,
                    'total_spent' => 'Rp ' . number_format($client->total_spent ?? 0, 0, ',', '.'),
                ],
                'joined_date' => $client->created_at->format('M d, Y'),
            ],
        ];
    }

    /**
     * Update client data
     * Updates both user and client tables, syncs skills
     *
     * @param int $id
     * @param array $data
     * @return Client
     * @throws \Exception
     */
    public function updateClient(int $id, array $data): Client
    {
        $client = $this->clientRepository->findWithRelations($id);

        if (!$client || !$client->user) {
            throw new \Exception('Client not found');
        }

        // Update user data
        $client->user->update([
            'name' => trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? '')),
            'email' => $data['email'] ?? $client->user->email,
            'phone' => $data['phone'] ?? $client->user->phone,
            'address' => $data['address'] ?? $client->user->address,
        ]);

        // Handle logo upload
        $logoPath = $client->logo;
        if (isset($data['logo']) && $data['logo'] instanceof \Illuminate\Http\UploadedFile) {
            $logoPath = $data['logo']->store('logos', 'public');
        }

        // Handle cover image upload
        $coverPath = $client->cover_image;
        if (isset($data['cover_image']) && $data['cover_image'] instanceof \Illuminate\Http\UploadedFile) {
            $coverPath = $data['cover_image']->store('covers', 'public');
        }

        // Update client data
        $clientData = [
            'company_name' => $data['company_name'] ?? $client->company_name,
            'industry' => $data['industry'] ?? $client->industry,
            'website' => $data['website'] ?? $client->website,
            'employee_count' => $data['employee_count'] ?? $client->employee_count,
            'about' => $data['about'] ?? $client->about,
            'logo' => $logoPath,
            'cover_image' => $coverPath,
        ];

        $client = $this->clientRepository->update($id, $clientData);

        // Sync skills via pivot table
        if (isset($data['selected_skills']) && is_array($data['selected_skills'])) {
            $this->clientRepository->syncSkills($client, $data['selected_skills']);
        }

        return $client;
    }

    /**
     * Delete client
     *
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function deleteClient(int $id): bool
    {
        $client = $this->clientRepository->findWithRelations($id);

        if (!$client) {
            throw new \Exception('Client not found');
        }

        // Delete client (cascade will handle related data)
        return $this->clientRepository->delete($id);
    }
}
