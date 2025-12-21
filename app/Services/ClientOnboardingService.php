<?php

namespace App\Services;

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
   * Handle Save (Create/Update) Client Data
   * Support S3 Storage (Neo Object Storage)
   */
  public function saveClientData(User $user, array $validatedData)
  {
    DB::beginTransaction();

    // Cek data lama (untuk keperluan update/delete file)
    $client = $this->clientRepo->findByUserId($user->id);

    try {
      // 1. Handle Slug
      $slug = isset($validatedData['company_name'])
        ? $this->generateUniqueSlug($validatedData['company_name'], $client->id ?? null)
        : ($client->slug ?? Str::random(10));

      // 2. Handle Logo (S3 Logic)
      $logoPath = $this->handleFileLogic(
        $validatedData,
        'logo',
        'clients/logos',
        $client ? $client->logo : null
      );

      // 3. Handle Cover (S3 Logic)
      $coverPath = $this->handleFileLogic(
        $validatedData,
        'cover_image',
        'clients/covers',
        $client ? $client->cover_image : null
      );

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
      ];

      // Pertahankan status verified jika update
      $dataToSave['is_verified'] = $client ? $client->is_verified : false;

      // 5. Eksekusi DB
      $savedClient = $this->clientRepo->updateOrCreate($user->id, $dataToSave);

      // 6. Sync Skills
      if (isset($validatedData['skills'])) {
        $savedClient->skills()->sync($validatedData['skills']);
      }

      // 7. Role Assignment
      $this->clientRepo->assignClientRole($user);

      DB::commit();
      return $savedClient;
    } catch (Exception $e) {
      DB::rollBack();

      // CLEANUP S3: Hapus file baru jika DB gagal disimpan
      // Kita cek apakah path baru beda dengan path lama (artinya ada upload baru)
      if (isset($logoPath) && $logoPath !== ($client->logo ?? null)) {
        Storage::disk('s3')->delete($logoPath);
      }
      if (isset($coverPath) && $coverPath !== ($client->cover_image ?? null)) {
        Storage::disk('s3')->delete($coverPath);
      }

      throw $e;
    }
  }

  /**
   * Helper Cerdas untuk Menangani File S3 (Create/Update/Delete)
   */
  private function handleFileLogic(array $data, string $key, string $folder, ?string $oldPath): ?string
  {
    // KASUS 1: Ada upload file baru
    if (isset($data[$key]) && $data[$key] instanceof UploadedFile) {

      // Hapus file lama di S3 jika ada
      if ($oldPath && Storage::disk('s3')->exists($oldPath)) {
        Storage::disk('s3')->delete($oldPath);
      }

      // Upload yang baru ke S3 dengan visibilitas PUBLIC
      // Agar bisa diakses via URL browser
      return $data[$key]->store($folder, [
        'disk' => 's3',
        'visibility' => 'public'
      ]);
    }

    // KASUS 2: User tidak upload (Pertahankan file lama)
    if (!array_key_exists($key, $data)) {
      return $oldPath;
    }

    // KASUS 3: User mengirim NULL (User ingin MENGHAPUS file)
    if (array_key_exists($key, $data) && is_null($data[$key])) {
      // Hapus file lama fisik di S3
      if ($oldPath && Storage::disk('s3')->exists($oldPath)) {
        Storage::disk('s3')->delete($oldPath);
      }
      return null;
    }

    return $oldPath;
  }

  /**
   * Helper: Generate Slug Unik
   */
  private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
  {
    $slug = Str::slug($name);
    $originalSlug = $slug;
    $count = 1;

    // Query check unique slug, exclude ID sendiri jika update
    $query = Client::where('slug', $slug);
    if ($ignoreId) {
      $query->where('id', '!=', $ignoreId);
    }

    while ($query->exists()) {
      $slug = "{$originalSlug}-{$count}";
      $count++;

      // Reset query for loop check
      $query = Client::where('slug', $slug);
      if ($ignoreId) {
        $query->where('id', '!=', $ignoreId);
      }
    }

    return $slug;
  }
}
