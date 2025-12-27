<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Expert;
use App\Models\Skill;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExpertSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Get all users with 'expert' role created by UserSeeder
        $expertUsers = User::whereJsonContains('roles', 'expert')->get();

        if ($expertUsers->isEmpty()) {
            $this->command->warn('No users with expert role found. Please run UserSeeder first.');
            return;
        }

        // Data Dummy untuk Variasi
        $positions      = ['Software Engineer', 'Backend Developer', 'Frontend Developer', 'System Analyst', 'DevOps Engineer', 'Data Analyst', 'IT Consultant', 'Project Manager', 'Product Manager', 'UX Researcher'];
        $companies      = ['Tokopedia', 'Gojek', 'Bukalapak', 'Traveloka', 'Shopee', 'OVO', 'Ruangguru', 'Zenius', 'Telkom Indonesia', 'Bank Mandiri', 'BRI', 'BCA'];
        $certifications = ['AWS Certified Developer', 'Google Cloud Engineer', 'Laravel Professional', 'Scrum Master', 'Microsoft Azure Associate', 'PMP', 'ITIL 4 Foundation', 'Cisco CCNA'];
        $types          = ['Counselor', 'Psychologist', 'Coach', 'Trainer', 'Consultant', 'Mentor', 'Advisor'];

        // Get all skill IDs
        $allSkillIds = Skill::pluck('id')->toArray();

        DB::transaction(function () use ($faker, $expertUsers, $positions, $companies, $certifications, $types, $allSkillIds) {
            
            foreach ($expertUsers as $user) {
                // Generate professional title
                $selectedPosition = $faker->randomElement($positions);
                $selectedCompany  = $faker->randomElement($companies);
                $professionalTitle = "Senior " . $selectedPosition . " at " . $selectedCompany;

                // Generate slug from user name
                $slug = Str::slug($user->name) . '-' . Str::lower(Str::random(6));

                // Create or update Expert Profile
                $expert = Expert::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        // Slug (unique identifier for URL)
                        'slug'        => $slug,
                        
                        // Title (Headline)
                        'title'       => $professionalTitle,

                        // About
                        'about'       => '<p>' . $faker->paragraph(3) . '</p><p>' . $faker->paragraph(2) . '</p>',

                        // JSON: Experiences
                        'experiences' => [
                            [
                                'position'    => $selectedPosition,
                                'company'     => $selectedCompany,
                                'years'       => (string) $faker->numberBetween(2018, 2024) . ' - Present',
                                'description' => $faker->paragraph(2),
                            ],
                            [
                                'position'    => 'Junior ' . $selectedPosition,
                                'company'     => $faker->randomElement($companies),
                                'years'       => (string) $faker->numberBetween(2015, 2018) . ' - ' . $faker->numberBetween(2018, 2020),
                                'description' => $faker->sentence(10),
                            ],
                        ],

                        // JSON: Licenses
                        'licenses'    => [
                            [
                                'certification' => $faker->randomElement($certifications),
                                'file'          => null,
                            ],
                        ],

                        // JSON: Gallerys
                        'gallerys' => [],

                        // JSON: Socials
                        'socials' => [
                            ['key' => 'linkedin', 'value' => 'linkedin.com/in/' . Str::slug($user->name)],
                            ['key' => 'website', 'value' => 'www.' . Str::slug($user->name) . '.com'],
                        ],

                        'price'          => $faker->numberBetween(10, 50) * 10000,
                        'rating'         => $faker->randomFloat(2, 3.5, 5.0),
                        'total_reviews'  => $faker->numberBetween(5, 100),
                        'total_sessions' => $faker->numberBetween(10, 200),

                        // JSON: Type (Multi-select)
                        'type'        => $faker->randomElements($types, $faker->numberBetween(1, 2)),
                    ]
                );

                // Attach random skills (2-5 skills per expert)
                if (!empty($allSkillIds)) {
                    $selectedSkills = $this->pickRandomIds($allSkillIds, rand(2, 5));
                    $expert->skills()->sync($selectedSkills);
                }

                $this->command->info("Created expert: {$professionalTitle} for user: {$user->email}");
            }
        });

        $this->command->info('ExpertSeeder completed successfully! Created ' . $expertUsers->count() . ' experts.');
    }

    /**
     * Helper to pick N random unique IDs from array
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
