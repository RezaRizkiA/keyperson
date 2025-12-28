<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\ClientQuota;
use App\Models\Skill;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Get all users with 'client' role created by UserSeeder
        $clientUsers = User::whereJsonContains('roles', 'client')->get();

        if ($clientUsers->isEmpty()) {
            $this->command->warn('No users with client role found. Please run UserSeeder first.');

            return;
        }

        // Get all skill IDs for client interests (optional)
        $allSkillIds = Skill::pluck('id')->toArray();

        // Pre-defined company names for 5 clients
        $companyNames = [
            'PT Teknologi Nusantara',
            'CV Mitra Inovasi',
            'PT Solusi Digital Indonesia',
            'PT Karya Cemerlang',
            'CV Prima Konsultan',
        ];

        DB::transaction(function () use ($faker, $clientUsers, $allSkillIds, $companyNames) {

            $clientIndex = 0;
            foreach ($clientUsers as $user) {
                // Use predefined company name or generate random
                $companyName = $companyNames[$clientIndex] ?? 'PT '.$faker->company();

                // Create Client Profile (Company Data)
                $client = Client::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'type' => $faker->randomElement(['company', 'agency', 'startup']),
                        'company_name' => $companyName,
                        'slug' => Str::slug($companyName).'-'.Str::random(4),
                        'logo' => null,
                        'cover_image' => null,
                        'is_verified' => true, // All seeded companies are verified
                        'industry' => $faker->randomElement([
                            'Technology',
                            'Healthcare',
                            'Education',
                            'Finance',
                            'Manufacturing',
                            'Retail',
                            'Consulting',
                            'Media',
                        ]),
                        'address' => $faker->city.', '.$faker->state.', Indonesia',
                        'employee_count' => $faker->randomElement(['11-50', '51-200', '201-500']),
                        'website' => 'https://www.'.Str::slug($companyName).'.co.id',
                        'about' => $faker->paragraph(3),
                    ]
                );

                // === B2B SETUP ===

                // 1. Set HRD user's client_id and company_role
                $user->update([
                    'client_id' => $client->id,
                    'company_role' => 'hrd',
                ]);

                // 2. Create ClientQuota with initial balance (for testing)
                // Rp 5.000.000 per company for testing
                ClientQuota::updateOrCreate(
                    ['client_id' => $client->id],
                    ['balance' => 50000000] // Rp 50.000.000
                );

                // 3. Create 5 employees per company (including HRD = 1 + 4 new)
                for ($emp = 1; $emp <= 10; $emp++) {
                    $employeeEmail = 'employee'.(($clientIndex * 10) + $emp).'@'.Str::slug($companyName).'.com';

                    User::create([
                        'name' => $faker->name,
                        'email' => $employeeEmail,
                        'phone' => $faker->phoneNumber,
                        'address' => $faker->address,
                        'picture' => 'https://ui-avatars.com/api/?name='.urlencode('Employee '.$emp).'&background='.substr(md5($clientIndex.$emp), 0, 6).'&color=fff',
                        'password' => Hash::make('password'),
                        'roles' => ['user'], // Regular user role
                        'client_id' => $client->id, // Linked to company
                        'company_role' => 'employee',
                    ]);
                }

                // === END B2B SETUP ===

                // Sync skills if pivot table exists
                if (! empty($allSkillIds) && method_exists($client, 'skills')) {
                    $selectedSkills = $this->pickRandomIds($allSkillIds, rand(2, 5));
                    $client->skills()->sync($selectedSkills);
                }

                $this->command->info("Created client: {$companyName} for HRD: {$user->email} with 4 employees and Rp 5.000.000 quota");
                $clientIndex++;
            }
        });

        $employeeCount = User::whereNotNull('client_id')->count();
        $quotaCount = ClientQuota::count();

        $this->command->info("ClientSeeder completed! Created {$clientUsers->count()} clients, {$employeeCount} corporate users, {$quotaCount} quotas.");
    }

    /**
     * Helper to pick N random unique IDs from array
     */
    private function pickRandomIds(array $source, int $n): array
    {
        if (empty($source)) {
            return [];
        }
        $n = min($n, count($source));
        if ($n === 0) {
            return [];
        }

        $keys = array_rand($source, $n);

        if (! is_array($keys)) {
            return [$source[$keys]];
        }

        return array_map(fn ($k) => $source[$k], $keys);
    }
}
