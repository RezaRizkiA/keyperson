<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Expert;
use Faker\Factory as Faker;

class ExpertSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        $positions = ['Software Engineer', 'Backend Developer', 'Frontend Developer', 'System Analyst', 'DevOps Engineer'];
        $companies = ['Tokopedia', 'Gojek', 'Bukalapak', 'Traveloka', 'Shopee', 'OVO', 'Ruangguru', 'Zenius'];
        $certifications = ['AWS Certified Developer', 'Google Cloud Engineer', 'Laravel Professional', 'Scrum Master', 'Microsoft Azure Associate'];
        $expertiseSummaries = [
            'Keahlian dalam pengembangan backend dan sistem API.',
            'Ahli dalam manajemen proyek dan DevOps.',
            'Berpengalaman dalam frontend development dan UI/UX.',
            'Spesialis dalam keamanan siber dan cloud architecture.',
            'Fokus pada data analytics dan machine learning.',
            'Ahli dalam pengembangan aplikasi Laravel dan RESTful API.',
            'Pakar dalam digital transformation dan integrasi sistem.',
        ];

        for ($i = 1; $i <= 20; $i++) {
            $name = $faker->name();
            $email = "user{$i}@example.com";

            // Skip jika email sama dengan admin
            if ($email === 'kastaraparama.idn@gmail.com') continue;

            $user = User::create([
                'name' => $name,
                'email' => $email,
                'phone' => '628' . rand(1000000000, 9999999999),
                'slug' => Str::slug($name),
                'password' => Hash::make('password'),
                'gender' => 'L',
                'address' => $faker->address,
                'roles' => ['user', 'expert'],
            ]);

            Expert::create([
                'user_id' => $user->id,
                'biography' => '<p>' . $faker->paragraph(5) . '</p>',
                'experiences' => [
                    [
                        'position' => $positions[array_rand($positions)],
                        'company' => $companies[array_rand($companies)],
                        'years' => strval(rand(2016, 2023)),
                        'description' => $faker->paragraph(3),
                    ],
                    [
                        'position' => 'IT Consultant',
                        'company' => 'Freelance',
                        'years' => strval(rand(2018, 2024)),
                        'description' => $faker->paragraph(2),
                    ]
                ],
                'licenses' => [
                    [
                        'certification' => $certifications[array_rand($certifications)],
                        'attachment' => 'kosong saja',
                    ]
                ],
                'gallerys' => [],
                'background' => null,
                'expertise' => $expertiseSummaries[array_rand($expertiseSummaries)],
                'expertise_id' => array_map('strval', array_rand(array_flip(range(1, 59)), 4)),
            ]);
        }
    }
}
