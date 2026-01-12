<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class UserRepository
{
  /**
   * Get paginated users with filters
   *
   * @param array $filters
   * @param int $perPage
   * @return LengthAwarePaginator
   */
  public function getPaginatedUsers(array $filters = [], int $perPage = 15): LengthAwarePaginator
  {
    $query = User::query()
      ->with(['ownedClient:id,user_id,company_name', 'expert:id,user_id'])
      ->withCount('appointments')
      ->select([
        'id',
        'name',
        'email',
        'phone',
        'address',
        'picture',
        'roles',
        'email_verified_at',
        'calendar_connected',
        'created_at',
        'updated_at'
      ]);

    // Search filter
    if (!empty($filters['search'])) {
      $search = $filters['search'];
      $query->where(function ($q) use ($search) {
        $q->where('name', 'like', "%{$search}%")
          ->orWhere('email', 'like', "%{$search}%")
          ->orWhere('phone', 'like', "%{$search}%");
      });
    }

    // Role filter
    if (!empty($filters['role'])) {
      $query->whereJsonContains('roles', $filters['role']);
    }

    // Order by created_at desc
    $query->orderBy('created_at', 'desc');

    return $query->paginate($perPage);
  }

  /**
   * Find user with all relationships
   *
   * @param int $id
   * @return User|null
   */
  public function findWithRelations(int $id): ?User
  {
    return User::with([
      'ownedClient:id,user_id,company_name,industry,website',
      'expert:id,user_id,title,price',
      'appointments' => function ($query) {
        $query->select('id', 'user_id', 'expert_id', 'date_time', 'status', 'price', 'hours')
          ->latest()
          ->limit(5);
      }
    ])->find($id);
  }

  /**
   * Find user by ID (legacy method - kept for compatibility)
   *
   * @param int $id
   * @return User
   */
  public function find($id)
  {
    return User::findOrFail($id);
  }

  /**
   * Update user data
   *
   * @param int $id
   * @param array $data
   * @return User
   */
  public function update(int $id, array $data): User
  {
    $user = User::findOrFail($id);
    $user->update($data);
    return $user->fresh();
  }

  /**
   * Delete user
   *
   * @param int $id
   * @return bool
   */
  public function delete(int $id): bool
  {
    $user = User::findOrFail($id);
    return $user->delete();
  }

  /**
   * Get stats for users
   *
   * @return array
   */
  public function getStats(): array
  {
    return [
      'total' => User::count(),
      'administrators' => User::whereJsonContains('roles', 'administrator')->count(),
      'experts' => User::whereJsonContains('roles', 'expert')->count(),
      'clients' => User::whereJsonContains('roles', 'client')->count(),
    ];
  }
}
