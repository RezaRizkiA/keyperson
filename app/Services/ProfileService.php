<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ProfileService
{
    protected $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    /**
     * Update Data Diri (Nama, Gender, Phone, Address, Slug)
     * Logic: update data hanya jika ada input, slug disimpan ke tabel expert/client
     */
    public function updateProfile($user, array $data)
    {
        // Persiapkan data yang akan diupdate di tabel users
        $updateData = [
            'name'    => $data['name'] ?? $user->name,
            'gender'  => $data['gender'] ?? $user->gender,
            'phone'   => $data['phone'] ?? $user->phone,
            'address' => $data['address'] ?? $user->address,
        ];

        // Update user data
        $this->userRepo->update($user->id, $updateData);

        // Handle slug update - simpan ke tabel expert atau client, bukan users
        if (!empty($data['slug_name'])) {
            $roles = $user->roles ?? [];
            
            if (in_array('expert', $roles) && $user->expert) {
                $user->expert->update(['slug' => $data['slug_name']]);
            } elseif (in_array('client', $roles) && $user->ownedClient) {
                $user->ownedClient->update(['slug' => $data['slug_name']]);
            }
        }

        return $user->fresh(['expert', 'ownedClient']);
    }

    /**
     * Update Password
     * Logic: Cek current password (jika user punya password), hash password baru.
     */
    public function updatePassword($user, $currentPassword, $newPassword)
    {
        // Jika user punya password lama, cek apakah cocok
        if ($user->password !== null) {
            if (!Hash::check($currentPassword, $user->password)) {
                // Lempar exception agar bisa ditangkap controller sebagai error validasi
                throw ValidationException::withMessages([
                    'current_password' => 'The current password is incorrect.',
                ]);
            }
        }

        // Simpan password baru
        return $this->userRepo->update($user->id, [
            'password' => Hash::make($newPassword),
        ]);
    }

    /**
     * Update Foto Profile (S3 Version)
     */
    public function updateAvatar($user, $imageFile)
    {
        // 1. Hapus foto lama di S3 jika ada
        if ($user->picture) {
            Storage::disk('s3')->delete($user->picture);
        }

        // 2. Upload foto baru ke S3
        // Generate nama file unik
        $filename = 'avatars/' . uniqid() . '.' . $imageFile->getClientOriginalExtension();

        // Upload dengan visibility public
        Storage::disk('s3')->put($filename, file_get_contents($imageFile), 'public');

        // 3. Update database
        return $this->userRepo->update($user->id, [
            'picture' => $filename
        ]);
    }
}
