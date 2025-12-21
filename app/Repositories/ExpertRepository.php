<?php

namespace App\Repositories;

use App\Models\Expert;
use App\Models\Category;
use App\Models\User;

class ExpertRepository
{
  /**
   * Ambil Data Master Kategori beserta hierarki Skill untuk Form
   */
  public function getCategoriesWithSkills()
  {
    // Eager load 3 Level: Category -> SubCategory -> Skill
    return Category::with(['subCategories.skills' => function ($query) {
      $query->orderBy('name');
    }])->orderBy('name')->get();
  }

  /**
   * Update atau Create data Expert berdasarkan User ID
   */
  public function updateOrCreate(int $userId, array $data): Expert
  {
    return Expert::updateOrCreate(
      ['user_id' => $userId],
      $data
    );
  }

  /**
   * Sync Skill ke Pivot Table (expert_skill)
   */
  public function syncSkills(Expert $expert, array $skillIds): void
  {
    $expert->skills()->sync($skillIds);
  }

  /**
   * Tambahkan Role Expert ke User jika belum ada
   */
  public function assignExpertRole(User $user): void
  {
    $roles = $user->roles ?? [];
    if (!in_array('expert', $roles)) {
      $roles[] = 'expert';
      $user->roles = $roles;
      $user->save();
    }
  }
}
