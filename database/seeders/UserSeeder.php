<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    public function run()
    {
        $email = 'kastaraparama.idn@gmail.com';

        // Cek apakah user sudah ada berdasarkan email
        $existingUser = User::where('email', $email)->first();
        if (!$existingUser) {
            // Cek apakah sudah ada user lain yang punya role 'administrator'
            $adminExists = User::whereJsonContains('roles', 'administrator')->exists();

            // Susun role
            $roles = ['user'];
            if (!$adminExists) {
                $roles[] = 'administrator';
            }

            // Buat user baru
            User::create([
                'name' => 'Kastara Parama Nusantara',
                'email' => $email,
                'phone' => '6282299668860',
                'slug' => 'kastara-parama-nusantara',
                'password' => 'KASTARAAPPS', // Otomatis di-hash oleh casts
                'gender' => 'L',
                'address' => 'Jl. Paus Dalam No.37 Rawamangun, Pulo Gadung, Jakarta Timur 13220',
                'roles' => $roles,
            ]);
        }
    }
}
