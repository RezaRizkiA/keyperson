<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Expert;
use App\Models\Skill;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ExpertSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Data Dummy untuk Variasi
        $positions      = ['Software Engineer', 'Backend Developer', 'Frontend Developer', 'System Analyst', 'DevOps Engineer', 'Data Analyst', 'IT Consultant', 'Project Manager', 'Product Manager', 'UX Researcher'];
        $companies      = ['Tokopedia', 'Gojek', 'Bukalapak', 'Traveloka', 'Shopee', 'OVO', 'Ruangguru', 'Zenius', 'Telkom Indonesia', 'Bank Mandiri', 'BRI', 'BCA'];
        $certifications = ['AWS Certified Developer', 'Google Cloud Engineer', 'Laravel Professional', 'Scrum Master', 'Microsoft Azure Associate', 'PMP', 'ITIL 4 Foundation', 'Cisco CCNA'];

        // Tipe Expert (Controlled Vocabulary)
        $types          = ['Counselor', 'Psychologist', 'Coach', 'Trainer', 'Consultant', 'Mentor', 'Advisor'];

        // Ambil semua Skill (Level 3 - Skill Konkret)
        $allSkills = Skill::all();

        DB::transaction(function () use ($allSkills, $faker, $positions, $companies, $certifications, $types) {

            foreach ($allSkills as $skill) {
                // Target: Minimal 1-2 Expert per Skill agar pencarian tidak kosong
                $targetPerSkill = 2;

                // Cek jumlah expert yang sudah punya skill ini (via Pivot Table)
                $existingCount = Expert::whereHas('skills', function ($q) use ($skill) {
                    $q->where('id', $skill->id);
                })->count();

                $need = max(0, $targetPerSkill - $existingCount);
                if ($need === 0) continue;

                for ($i = 0; $i < $need; $i++) {
                    // Email unik berbasis skill agar mudah didebug
                    $email = Str::slug($skill->name) . "-{$skill->id}-" . Str::random(5) . '@seed.local';
                    $name  = $faker->name();

                    // 1. Buat User
                    $user = User::firstOrCreate(
                        ['email' => $email],
                        [
                            'name'     => $name,
                            'phone'    => '628' . $faker->numerify('##########'),
                            'slug'     => Str::slug($name) . '-' . Str::lower(Str::random(6)),
                            'password' => Hash::make('password'), // Password default
                            'gender'   => $faker->randomElement(['L', 'P']),
                            'address'  => $faker->address,
                            'roles'    => ['user', 'expert'], // Role user + expert
                        ]
                    );

                    // 2. Tentukan Data Expert
                    $selectedPosition = $faker->randomElement($positions);
                    $selectedCompany  = $faker->randomElement($companies);

                    // Title Profesional (Headline)
                    // Contoh: "Senior Backend Developer at Gojek"
                    $professionalTitle = "Senior " . $selectedPosition . " at " . $selectedCompany;

                    // 3. Buat/Update Expert Profile
                    $expert = Expert::updateOrCreate(
                        ['user_id' => $user->id],
                        [
                            // Kolom Baru: Title (Headline)
                            'title'       => $professionalTitle,

                            // Kolom Baru: About (Rename dari biography)
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
                                    'company'     => $faker->randomElement($companies), // Perusahaan sebelumnya
                                    'years'       => (string) $faker->numberBetween(2015, 2018) . ' - ' . $faker->numberBetween(2018, 2020),
                                    'description' => $faker->sentence(10),
                                ],
                            ],

                            // JSON: Licenses (Gunakan key 'file' bukan 'attachment' agar konsisten dengan refactor Vue)
                            'licenses'    => [
                                [
                                    'certification' => $faker->randomElement($certifications),
                                    'file'          => null, // Di seeder biarkan null dulu
                                ],
                            ],

                            // JSON: Gallerys (Dummy images)
                            'gallerys' => [], // Kosongkan dulu atau isi dummy URL jika mau

                            // JSON: Socials
                            'socials' => [
                                ['key' => 'linkedin', 'value' => 'linkedin.com/in/' . Str::slug($name)],
                                ['key' => 'website', 'value' => 'www.' . Str::slug($name) . '.com'],
                            ],

                            'price'       => $faker->numberBetween(10, 50) * 10000, // 100k - 500k

                            // Rating data (dummy for testing)
                            'rating'           => $faker->randomFloat(2, 3.5, 5.0), // 3.50 - 5.00
                            'total_reviews'    => $faker->numberBetween(5, 100),
                            'total_sessions'   => $faker->numberBetween(10, 200),

                            // JSON: Type (Multi-select)
                            'type'        => $faker->randomElements($types, $faker->numberBetween(1, 2)),
                        ]
                    );

                    // 4. ISI PIVOT TABLE (expert_skill)
                    // Wajib punya skill yang sedang di-loop ($skill->id)
                    // Tambahan 1-3 skill random lain agar profil terlihat kaya
                    $randomExtraSkills = Skill::where('id', '!=', $skill->id)
                        ->inRandomOrder()
                        ->limit(rand(1, 3))
                        ->pluck('id')
                        ->toArray();

                    $skillIdsToAttach = array_unique(array_merge([$skill->id], $randomExtraSkills));

                    $expert->skills()->syncWithoutDetaching($skillIdsToAttach);
                }
            }
        });
    }
}
