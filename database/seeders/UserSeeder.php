<?php

namespace Database\Seeders;

use App\Models\User;
// Added
use Faker\Factory as Faker;
use Illuminate\Database\Seeder; // Added
use Illuminate\Support\Facades\Hash;

// Added

class UserSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        // 1. Create Administrator
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@keyperson.com',
            'phone' => '081234567890',
            'address' => 'Jakarta',
            'picture' => 'https://ui-avatars.com/api/?name=Admin&background=0D8ABC&color=fff',
            'password' => Hash::make('password'),
            'roles' => ['administrator'],
        ]);

        // 2. Create 20 Expert Users
        for ($i = 1; $i <= 20; $i++) {
            User::create([
                'name'     => $faker->name,
                'email'    => 'expert' . $i . '@keyperson.com',
                'phone'    => $faker->phoneNumber,
                'gender'   => $faker->randomElement(['L', 'P']),
                'address'  => $faker->address,
                'picture'  => 'https://ui-avatars.com/api/?name=' . urlencode('Expert ' . $i) . '&background=' . substr(md5($i), 0, 6) . '&color=fff',
                'password' => Hash::make('password'),
                'roles'    => ['expert'],
            ]);
        }

        // 3. Create 10 Client Users (Company representatives)
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'name' => $faker->name,
                'email' => 'client'.$i.'@keyperson.com',
                'phone' => $faker->phoneNumber,
                'address' => $faker->address,
                'picture' => 'https://ui-avatars.com/api/?name='.urlencode('Client '.$i).'&background='.substr(md5($i + 100), 0, 6).'&color=fff',
                'password' => Hash::make('password'),
                'roles' => ['client'],
            ]);
        }

        // 4. Create 20 Regular Users (who can book appointments)
        for ($i = 1; $i <= 20; $i++) {
            User::create([
                'name' => $faker->name,
                'email' => 'user'.$i.'@keyperson.com',
                'phone' => $faker->phoneNumber,
                'address' => $faker->address,
                'picture' => 'https://ui-avatars.com/api/?name='.urlencode('User '.$i).'&background='.substr(md5($i + 200), 0, 6).'&color=fff',
                'password' => Hash::make('password'),
                'roles' => ['user'],
            ]);
        }
    }
}
