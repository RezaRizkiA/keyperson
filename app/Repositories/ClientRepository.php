<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Facades\DB;

class ClientRepository
{
  /**
   * Create or Update Client Profile based on User ID
   */
  public function updateOrCreate(int $userId, array $data): Client
  {
    // Kita gunakan updateOrCreate agar method ini reusable:
    // Bisa untuk Create baru (Onboarding)
    // Bisa untuk Update profil (Edit Profile nanti)
    return Client::updateOrCreate(
      ['user_id' => $userId], // Kunci pencarian
      $data                   // Data yang disimpan/diupdate
    );
  }

  /**
   * Assign role 'client' ke User
   */
  public function assignClientRole(User $user): void
  {
    // Mengikuti logic yang ada di Seeder kamu (Manual Array Roles)
    // Jika kamu pakai Spatie Laravel Permission, ganti jadi: $user->assignRole('client');

    $roles = $user->roles ?? []; // Pastikan di User.php ada cast 'roles' => 'array'

    // Cek jika belum ada role 'client', baru tambahkan
    if (!in_array('client', $roles)) {
      $roles[] = 'client';

      // Simpan kembali ke user
      $user->update([
        'roles' => array_unique($roles)
      ]);
    }
  }

  /**
   * (Opsional) Helper untuk mencari data client
   * Nanti akan berguna saat Dashboard Client
   */
  public function findByUserId(int $userId): ?Client
  {
    return Client::where('user_id', $userId)->first();
  }

  /**
   * Get paginated clients with filters for admin dashboard
   *
   * @param int $perPage
   * @param string|null $search
   * @param string|null $status
   * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
   */
  public function getPaginatedClients(
    int $perPage = 10,
    ?string $search = null,
    ?string $status = null
  ) {
    $query = Client::with([
      'user:id,name,email,phone,picture,address',
    ])
    ->withCount('appointments')
    ->addSelect([
      'total_spent' => DB::table('appointments as a')
        ->selectRaw('COALESCE(SUM(a.price * a.hours), 0)')
        ->join('users as u', 'a.user_id', '=', 'u.id')
        ->whereColumn('u.id', 'clients.user_id')
    ]);

    // Search filter
    if ($search) {
      $query->where(function ($q) use ($search) {
        $q->where('company_name', 'like', "%{$search}%")
          ->orWhere('industry', 'like', "%{$search}%")
          ->orWhereHas('user', function ($userQuery) use ($search) {
            $userQuery->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
          });
      });
    }

    // Status filter (you can customize this based on your needs)
    if ($status) {
      if ($status === 'active') {
        $query->whereHas('appointments', function ($q) {
          $q->where('date_time', '>=', now()->subDays(30));
        });
      } elseif ($status === 'inactive') {
        $query->whereHas('appointments')
          ->whereDoesntHave('appointments', function ($q) {
            $q->where('date_time', '>=', now()->subDays(30));
          });
      } elseif ($status === 'new') {
        $query->doesntHave('appointments');
      }
    }

    return $query->latest()->paginate($perPage);
  }

  /**
   * Get client statistics for dashboard
   *
   * @return array
   */
  public function getClientStats(): array
  {
    $totalClients = Client::count();
    $activeClients = Client::whereHas('appointments', function ($q) {
      $q->where('date_time', '>=', now()->subDays(30));
    })->count();

    $newClients = Client::where('created_at', '>=', now()->subDays(30))->count();

    return [
      'total' => $totalClients,
      'active' => $activeClients,
      'new_this_month' => $newClients,
      'inactive' => $totalClients - $activeClients,
    ];
  }

  /**
   * Find client with all relations
   *
   * @param int $id
   * @return Client|null
   */
  public function findWithAllRelations(int $id): ?Client
  {
    return Client::with([
      'user:id,name,email,phone,picture,address',
      'skills:id,name',
      'appointments'
    ])
    ->withCount('appointments')
    ->addSelect([
      'total_spent' => DB::table('appointments as a')
        ->selectRaw('COALESCE(SUM(a.price * a.hours), 0)')
        ->join('users as u', 'a.user_id', '=', 'u.id')
        ->whereColumn('u.id', 'clients.user_id'),
      'avg_rating' => DB::table('reviews as r')
        ->selectRaw('COALESCE(AVG(r.rating), 0)')
        ->join('appointments as ap', 'r.appointment_id', '=', 'ap.id')
        ->join('users as u2', 'ap.user_id', '=', 'u2.id')
        ->whereColumn('u2.id', 'clients.user_id')
    ])
    ->find($id);
  }

  /**
   * Find client with basic relations for editing
   *
   * @param int $id
   * @return Client|null
   */
  public function findWithRelations(int $id): ?Client
  {
    return Client::with([
      'user:id,name,email,phone,address',
      'skills:id,name'
    ])->find($id);
  }

  /**
   * Update client data
   *
   * @param int $id
   * @param array $data
   * @return Client
   */
  public function update(int $id, array $data): Client
  {
    $client = Client::findOrFail($id);
    $client->update($data);
    return $client->fresh();
  }

  /**
   * Sync skills to client
   *
   * @param Client $client
   * @param array $skillIds
   * @return void
   */
  public function syncSkills(Client $client, array $skillIds): void
  {
    $client->skills()->sync($skillIds);
  }

  /**
   * Delete client
   *
   * @param int $id
   * @return bool
   */
  public function delete(int $id): bool
  {
    $client = Client::findOrFail($id);
    return $client->delete();
  }
}
