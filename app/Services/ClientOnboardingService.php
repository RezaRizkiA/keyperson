<?php

namespace App\Services;

use App\Models\ClientQuota;
use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Repositories\ClientRepository;
use Exception;

class ClientOnboardingService
{
  protected $clientRepo;

  public function __construct(ClientRepository $clientRepo)
  {
    $this->clientRepo = $clientRepo;
  }

  /**
   * Handle Create New Client Data
   * Support S3 Storage (Neo Object Storage)
   */
  public function saveClientData(User $user, array $validatedData)
  {
    DB::beginTransaction();

    $logoPath = null;
    $coverPath = null;

    try {
      // 1. Handle Slug
      $slug = $this->generateUniqueSlug($validatedData['company_name']);

      // 2. Handle Logo Upload (S3)
      $logoPath = $this->uploadFile($validatedData, 'logo', 'clients/logos');

      // 3. Handle Cover Upload (S3)
      $coverPath = $this->uploadFile($validatedData, 'cover_image', 'clients/covers');

      // 4. Prepare Data
      $dataToSave = [
        'type'           => $validatedData['type'],
        'company_name'   => $validatedData['company_name'],
        'slug'           => $slug,
        'industry'       => $validatedData['industry'],
        'employee_count' => $validatedData['employee_count'],
        'address'        => $validatedData['address'],
        'website'        => $validatedData['website'] ?? null,
        'about'          => $validatedData['about'],
        'logo'           => $logoPath,
        'cover_image'    => $coverPath,
        'status'         => 'pending', // Default status untuk client baru
      ];

      // 5. Create Client
      $savedClient = $this->clientRepo->create($user->id, $dataToSave);

      // 6. Sync Skills
      if (isset($validatedData['skills'])) {
        $savedClient->skills()->sync($validatedData['skills']);
      }

      // === B2B SETUP ===
      // 8. Set client_id pada user HRD agar terhubung sebagai karyawan juga
      $user->update([
        'client_id' => $savedClient->id,
        'company_role' => 'hrd'
      ]);

      // 9. Buat ClientQuota jika belum ada (saldo 0)
      ClientQuota::firstOrCreate(
        ['client_id' => $savedClient->id],
        ['balance' => 0]
      );
      // === END B2B SETUP ===

      DB::commit();
      return $savedClient;
    } catch (Exception $e) {
      DB::rollBack();

      // CLEANUP S3: Hapus file yang sudah diupload jika DB gagal
      if ($logoPath) {
        Storage::disk('s3')->delete($logoPath);
      }
      if ($coverPath) {
        Storage::disk('s3')->delete($coverPath);
      }

      throw $e;
    }
  }

  /**
   * Helper untuk Upload File ke S3
   */
  private function uploadFile(array $data, string $key, string $folder): ?string
  {
    if (isset($data[$key]) && $data[$key] instanceof UploadedFile) {
      return $data[$key]->store($folder, [
        'disk' => 's3',
        'visibility' => 'public'
      ]);
    }

    return null;
  }

  /**
   * Helper: Generate Slug Unik untuk Create
   */
  private function generateUniqueSlug(string $name): string
  {
    $slug = Str::slug($name);
    $originalSlug = $slug;
    $count = 1;

    while (Client::where('slug', $slug)->exists()) {
      $slug = "{$originalSlug}-{$count}";
      $count++;
    }

    return $slug;
  }
}
