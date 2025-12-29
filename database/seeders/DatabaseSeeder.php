<?php
namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            ExpertiseSeeder::class,
            ExpertSeeder::class,
            ClientSeeder::class,
            // AppointmentSeeder::class,
            // ReviewSeeder::class,
            IpaymuSeeder::class,
        ]);
    }
}
