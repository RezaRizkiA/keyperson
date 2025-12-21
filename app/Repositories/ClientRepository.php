<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Client;

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
}
