<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Skill;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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

        DB::transaction(function () use ($faker, $clientUsers, $allSkillIds) {

            foreach ($clientUsers as $user) {
                // Generate realistic company name
                $companyPrefix = $faker->randomElement(['PT', 'CV', 'Yayasan', 'UD', 'Koperasi']);
                $companyName = $companyPrefix.' '.$faker->company();

                // Create or update Client Profile (Company Data)
                $client = Client::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'type' => $faker->randomElement(['company', 'agency', 'startup', 'government']),
                        'company_name' => $companyName,
                        'slug' => Str::slug($companyName).'-'.Str::random(4),
                        'logo' => null, // Can be uploaded later or use faker image
                        'cover_image' => null,
                        'is_verified' => $faker->boolean(70), // 70% chance verified
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
                        'employee_count' => $faker->randomElement(['1-10', '11-50', '51-200', '201-500', '500+']),
                        'website' => 'https://www.'.Str::slug($companyName).'.co.id',
                        'about' => $faker->paragraph(3),
                    ]
                );

                // Sync skills if pivot table exists
                if (! empty($allSkillIds) && method_exists($client, 'skills')) {
                    $selectedSkills = $this->pickRandomIds($allSkillIds, rand(2, 5));
                    $client->skills()->sync($selectedSkills);
                }

                $this->command->info("Created client: {$companyName} for user: {$user->email}");
            }
        });

        $this->command->info('ClientSeeder completed successfully! Created '.$clientUsers->count().' clients.');
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
