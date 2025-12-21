<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Client;
use App\Models\Skill; // Pastikan Model Skill ada
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID'); // Pakai locale Indonesia

        // Ambil semua ID Skill (jika fitur Client punya Skill Interest masih ada)
        // Jika tidak ada tabel pivot client_skill, hapus bagian ini
        $allSkillIds = Skill::pluck('id')->toArray();

        DB::transaction(function () use ($faker, $allSkillIds) {

            // Buat 2 Client Dummy
            for ($i = 1; $i <= 2; $i++) {
                // Generate Nama Perusahaan yang Realistis
                $companyPrefix = $faker->randomElement(['PT', 'CV', 'Yayasan', 'UD']);
                $companyName   = $companyPrefix . ' ' . $faker->company();
                $email         = "client{$i}@keyperson.id";

                // 1. Buat User (Login sebagai perwakilan perusahaan)
                $user = User::firstOrCreate(
                    ['email' => $email],
                    [
                        'name'     => 'HRD ' . $companyName, // Nama User Login
                        'phone'    => '628' . $faker->numerify('##########'),
                        'slug'     => Str::slug('hrd-' . $companyName) . '-' . Str::lower(Str::random(6)),
                        'password' => Hash::make('password'),
                        'gender'   => $faker->randomElement(['L', 'P']),
                        'address'  => $faker->address,
                        'roles'    => ['user', 'client'],
                    ]
                );

                // Pastikan role tersimpan (logika array manual)
                $roles = (array) ($user->roles ?? []);
                if (!in_array('client', $roles)) {
                    $roles[] = 'client';
                    $user->update(['roles' => array_unique($roles)]);
                }

                // 2. Buat Client Profile (Data Perusahaan)
                // Sesuai Migrasi Baru (LinkedIn Style)
                $client = Client::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'type'           => $faker->randomElement(['company', 'agency', 'startup']),
                        'company_name'   => $companyName,
                        'slug'           => Str::slug($companyName),
                        'logo'           => null, // Nanti diupload manual atau pake faker image url
                        'cover_image'    => null,
                        'is_verified'    => $faker->boolean(70), // 70% chance verified
                        'industry'       => $faker->randomElement(['Technology', 'Healthcare', 'Education', 'Finance']),
                        'address'        => $faker->city . ', Indonesia',
                        'employee_count' => $faker->randomElement(['1-10', '11-50', '51-200', '201-500', '500+']),
                        'website'        => 'https://www.' . Str::slug($companyName) . '.co.id',
                        'about'          => $faker->paragraph(3), // Deskripsi panjang
                    ]
                );

                // 3. ISI PIVOT TABLE (client_skill) - OPSIONAL
                // Jika tabel pivot `client_skill` masih ada di database
                if (!empty($allSkillIds) && method_exists($client, 'skills')) {
                    $selectedSkills = $this->pickRandomIds($allSkillIds, rand(3, 5));
                    $client->skills()->sync($selectedSkills);
                }
            }
        });
    }

    /**
     * Helper untuk ambil N ID unik acak
     */
    private function pickRandomIds(array $source, int $n): array
    {
        if (empty($source)) return [];
        $n = min($n, count($source));
        if ($n === 0) return [];

        $keys = array_rand($source, $n);

        if (!is_array($keys)) {
            return [$source[$keys]];
        }

        return array_map(fn($k) => $source[$k], $keys);
    }
}
