<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\Expert;
use App\Models\Skill;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        //Topics for appointments
        $topics = [
            'SEO Strategy Consultation',
            'Content Marketing Review',
            'Leadership Coaching Session',
            'Career Development Planning',
            'Business Growth Strategy',
            'Product Management Workshop',
            'UI/UX Design Review',
            'Digital Marketing Consultation',
            'Team Management Training',
            'Startup Strategy Session',
            'Fundraising Preparation',
            'Sales Optimization Review',
        ];

        // Get all REGULAR USERS (roles contains 'user')
        $regularUsers = User::whereJsonContains('roles', 'user')->get();
        
        if ($regularUsers->isEmpty()) {
            $this->command->warn('No regular users found. Please run UserSeeder first.');
            return;
        }

        // Get all experts with their skills
        $experts = Expert::with('skills')->get();
        
        if ($experts->isEmpty()) {
            $this->command->warn('No experts found. Please run ExpertSeeder first.');
            return;
        }

        // Status options for appointments
        $upcomingStatuses = ['pending', 'need_confirmation', 'confirmed'];
        
        $appointmentCount = 0;

        // Create appointments for each regular user
        foreach ($regularUsers as $user) {
            // 1. Create 2 COMPLETED appointments (past dates) - for reviews
            for ($i = 0; $i < 2; $i++) {
                // Pick random expert
                $expert = $experts->random();
                
                // Skip if expert has no skills
                if ($expert->skills->isEmpty()) {
                    continue;
                }

                // Pick random skill from expert's skills
                $skill = $expert->skills->random();

                // Past date (1-90 days ago)
                $dateTime = Carbon::now()->subDays($faker->numberBetween(1, 90));

                $hours = $faker->randomElement([1, 2, 3]);
                $pricePerHour = $expert->price ?? 100000;
                $totalPrice = $pricePerHour * $hours;

                // Completed appointments
                $type = $faker->randomElement(['individual', 'group']);
                $guests = null;
                
                if ($type === 'group') {
                    $numGuests = $faker->numberBetween(1, 5);
                    $guests = [];
                    for ($g = 0; $g < $numGuests; $g++) {
                        $guests[] = $faker->unique()->safeEmail;
                    }
                }

                Appointment::create([
                    'user_id' => $user->id,
                    'expert_id' => $expert->id,
                    'skill_id' => $skill->id,
                    'topic' => $faker->randomElement($topics),
                    'date_time' => $dateTime,
                    'location_url' => $faker->boolean(70) ? 'https://meet.google.com/' . $faker->uuid : null,
                    'google_calendar_event_id' => $faker->uuid,
                    'hours' => $hours,
                    'price' => $totalPrice,
                    'status' => 'completed',
                    'payment_status' => 'berhasil',
                    'type' => $type,
                    'guests' => $guests,
                    'created_at' => $dateTime->copy()->subDays($faker->numberBetween(1, 7)),
                    'updated_at' => $dateTime->copy()->addDays($faker->numberBetween(1, 3)),
                ]);

                $appointmentCount++;
            }

            // 2. Create 3 UPCOMING appointments - for future bookings
            foreach ($upcomingStatuses as $status) {
                // Pick random expert
                $expert = $experts->random();
                
                // Skip if expert has no skills
                if ($expert->skills->isEmpty()) {
                    continue;
                }

                // Pick random skill from expert's skills
                $skill = $expert->skills->random();

                // Future date (1-30 days ahead)
                $dateTime = Carbon::now()->addDays($faker->numberBetween(1, 30));

                // Payment status - random between pending and berhasil
                $paymentStatus = $faker->randomElement(['pending', 'berhasil']);

                $hours = $faker->randomElement([1, 2, 3]);
                $pricePerHour = $expert->price ?? 100000;
                $totalPrice = $pricePerHour * $hours;

                // Generate guests for group appointments
                $type = $faker->randomElement(['individual', 'group']);
                $guests = null;
                
                if ($type === 'group') {
                    $numGuests = $faker->numberBetween(1, 5);
                    $guests = [];
                    for ($g = 0; $g < $numGuests; $g++) {
                        $guests[] = $faker->unique()->safeEmail;
                    }
                }

                Appointment::create([
                    'user_id' => $user->id,
                    'expert_id' => $expert->id,
                    'skill_id' => $skill->id,
                    'topic' => $faker->randomElement($topics),
                    'date_time' => $dateTime,
                    'location_url' => $faker->boolean(70) ? 'https://meet.google.com/' . $faker->uuid : null,
                    'google_calendar_event_id' => $faker->uuid,
                    'hours' => $hours,
                    'price' => $totalPrice,
                    'status' => $status,
                    'payment_status' => $paymentStatus,
                    'type' => $type,
                    'guests' => $guests,
                    'created_at' => Carbon::now()->subDays($faker->numberBetween(1, 7)),
                    'updated_at' => Carbon::now()->subDays($faker->numberBetween(0, 3)),
                ]);

                $appointmentCount++;
            }
        }

        $this->command->info("Created {$appointmentCount} appointments for regular users (completed + upcoming).");
    }
}
