<?php

namespace App\Services;

use App\Models\Expert;
use App\Models\User;
use App\Repositories\ExpertRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Exception;

class ExpertRegistrationService
{
  protected $expertRepo;

  public function __construct(ExpertRepository $expertRepo)
  {
    $this->expertRepo = $expertRepo;
  }

  /**
   * Proses Utama Registrasi Expert
   */
  public function handleRegistration(User $user, Request $request)
  {
    DB::beginTransaction();

    try {
      // 1. Pisahkan Data Murni vs Data File/Skill
      // Kita ambil data dasar untuk tabel 'experts'
      $expertData = $request->only(['title', 'about', 'price', 'type', 'socials', 'experiences']);

      // 2. Simpan/Update Data Dasar dulu untuk dapat ID Expert
      $expert = $this->expertRepo->updateOrCreate($user->id, $expertData);

      // 3. Handle File Uploads (Logic Kompleks dipisah ke private function)
      // Ini akan mengupdate kolom 'licenses' dan 'gallerys' di $expert
      $this->processJsonFiles($request, $expert, 'licenses');
      $this->processJsonFiles($request, $expert, 'gallerys');

      // 4. Handle Pivot Table Skills
      if ($request->has('skills')) {
        $this->expertRepo->syncSkills($expert, $request->skills);
      }

      // 5. Update Role User
      $this->expertRepo->assignExpertRole($user);

      DB::commit();
      return $expert;
    } catch (Exception $e) {
      DB::rollBack();
      throw $e; // Lempar error ke Controller
    }
  }

  /**
   * Private Helper: Menangani Upload File dalam JSON Array
   * (Logic dari kode lama Anda, dirapikan)
   */
  private function processJsonFiles(Request $request, Expert $expert, string $columnName): void
  {
    $rawData = $request->all();
    $items = $rawData[$columnName] ?? [];

    if (!is_array($items)) {
      $items = [];
    }

    $finalData = [];

    // Ambil data lama dari DB untuk referensi penghapusan file
    $dbData = $expert->{$columnName} ?? [];

    foreach ($items as $index => $item) {
      $cleanItem = collect($item)->except(['file', 'existing_file', 'attachment', 'photos'])->toArray();
      $filePath = null;

      // 2. LOGIC UPLOAD: Cek apakah ada file BARU diupload?
      if ($request->hasFile("{$columnName}.{$index}.file")) {

        // A. Hapus file lama di S3 (jika ada data sebelumnya di index ini)
        // Kita cek dari 'existing_file' yang dikirim frontend atau dari DB
        $oldFile = $item['existing_file'] ?? ($dbData[$index]['file'] ?? null);

        if ($oldFile && Storage::disk('s3')->exists($oldFile)) {
          Storage::disk('s3')->delete($oldFile);
        }

        $file = $request->file("{$columnName}.{$index}.file");
        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
        $path = "experts/{$expert->id}/{$columnName}";

        Storage::disk('s3')->putFileAs($path, $file, $filename, 'public');
        $filePath = "{$path}/{$filename}";
      } else {
        if (!empty($item['existing_file'])) {
          $fullUrl = $item['existing_file'];

          if (str_contains($fullUrl, 'experts/')) {
            $filePath = strstr($fullUrl, 'experts/');
          } else {
            $parsedPath = parse_url($fullUrl, PHP_URL_PATH);
            $filePath = ltrim($parsedPath, '/');
          }
        }
      }

      if ($filePath) {
        $cleanItem['file'] = $filePath;
      }
      $finalData[] = $cleanItem;
    }

    $expert->{$columnName} = $finalData;
    $expert->save();
  }
}
