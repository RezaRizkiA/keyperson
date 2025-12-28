<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\ClientQuota;
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

        // Topics for appointments
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

        // Get all experts with their skills
        $experts = Expert::with('skills')->get();
        
        if ($experts->isEmpty()) {
            $this->command->warn('No experts found. Please run ExpertSeeder first.');
            return;
        }

        $corporateCount = 0;
        $retailCount = 0;

        // === 1. B2B CORPORATE APPOINTMENTS ===
        // Get ONLY employees (users with client_id but NOT HRD/owner role)
        // HRD users have both 'client' role AND are the owner of the client, they shouldn't book
        $corporateEmployees = User::whereNotNull('client_id')
            ->where(function ($q) {
                // Exclude HRD (company_role = 'hrd' or has 'client' role)
                $q->where('company_role', '!=', 'hrd')
                  ->orWhereNull('company_role');
            })
            ->whereJsonDoesntContain('roles', 'client') // Exclude users with client role (HRD/owner)
            ->with('company.quota')
            ->get();

        if ($corporateEmployees->isNotEmpty()) {
            $this->command->info('Creating B2B Corporate appointments (employees only, excluding HRD)...');
            
            foreach ($corporateEmployees as $user) {
                // Skip if company doesn't have quota
                if (!$user->company || !$user->company->quota) {
                    continue;
                }

                $quota = $user->company->quota;

                // Each employee gets 2 appointments (1 completed, 1 upcoming)

                // --- A. 1 COMPLETED Appointment (past) ---
                $expert = $experts->random();
                if ($expert->skills->isEmpty()) continue;
                
                $skill = $expert->skills->random();
                $dateTime = Carbon::now()->subDays($faker->numberBetween(7, 60));
                $hours = $faker->randomElement([1, 2]);
                $pricePerHour = $expert->price ?? 500000;
                $totalPrice = $pricePerHour * $hours;

                // Deduct quota
                if ($quota->balance >= $totalPrice) {
                    $quota->decrement('balance', $totalPrice);

                    Appointment::create([
                        'user_id' => $user->id,
                        'expert_id' => $expert->id,
                        'skill_id' => $skill->id,
                        'topic' => $faker->randomElement($topics),
                        'date_time' => $dateTime,
                        'location_url' => 'https://meet.google.com/' . $faker->uuid,
                        'google_calendar_event_id' => $faker->uuid,
                        'hours' => $hours,
                        'price' => $totalPrice,
                        'status' => 'completed',
                        'payment_status' => 'paid', // B2B: paid via quota
                        'type' => 'individual',
                        'guests' => null,
                        'created_at' => $dateTime->copy()->subDays(3),
                        'updated_at' => $dateTime->copy()->addHours(2),
                    ]);
                    $corporateCount++;
                }

                // --- B. 1 UPCOMING Appointment (future) ---
                $expert = $experts->random();
                if ($expert->skills->isEmpty()) continue;
                
                $skill = $expert->skills->random();
                $dateTime = Carbon::now()->addDays($faker->numberBetween(3, 21));
                $hours = $faker->randomElement([1, 2]);
                $pricePerHour = $expert->price ?? 500000;
                
                // Sometimes create group booking for corporate training
                $isGroup = $faker->boolean(30); // 30% are group bookings
                $guestsCount = 0;
                $guests = null;
                
                if ($isGroup) {
                    $guestsCount = $faker->numberBetween(2, 5);
                    $guests = [];
                    for ($g = 0; $g < $guestsCount; $g++) {
                        $guests[] = $faker->unique()->companyEmail;
                    }
                    $faker->unique(true); // Reset unique
                }
                
                // Price calculation: per pax (1 user + guests)
                $totalPax = 1 + $guestsCount;
                $totalPrice = $pricePerHour * $hours * $totalPax;

                // Deduct quota
                if ($quota->balance >= $totalPrice) {
                    $quota->decrement('balance', $totalPrice);

                    Appointment::create([
                        'user_id' => $user->id,
                        'expert_id' => $expert->id,
                        'skill_id' => $skill->id,
                        'topic' => $isGroup ? $faker->randomElement(['Team Training Session', 'Division Workshop', 'Department Mentoring']) : $faker->randomElement($topics),
                        'date_time' => $dateTime,
                        'location_url' => null,
                        'google_calendar_event_id' => $faker->uuid,
                        'hours' => $hours,
                        'price' => $totalPrice,
                        'status' => 'confirmed', // B2B: langsung confirmed
                        'payment_status' => 'paid', // B2B: paid via quota
                        'type' => $isGroup ? 'group' : 'individual',
                        'guests' => $guests,
                        'created_at' => Carbon::now()->subDays($faker->numberBetween(1, 5)),
                        'updated_at' => Carbon::now()->subDays($faker->numberBetween(0, 2)),
                    ]);
                    $corporateCount++;
                }
            }
        }

        // === 2. RETAIL USER APPOINTMENTS ===
        // Get retail users (users without client_id, with 'user' role)
        $retailUsers = User::whereNull('client_id')
            ->whereJsonContains('roles', 'user')
            ->take(10) // Limit to 10 retail users
            ->get();

        if ($retailUsers->isNotEmpty()) {
            $this->command->info('Creating Retail appointments...');
            
            foreach ($retailUsers as $user) {
                // Each retail user gets 1 appointment
                $expert = $experts->random();
                if ($expert->skills->isEmpty()) continue;
                
                $skill = $expert->skills->random();
                
                // Mix of past and future
                $isPast = $faker->boolean(40); // 40% completed, 60% upcoming
                
                if ($isPast) {
                    $dateTime = Carbon::now()->subDays($faker->numberBetween(7, 45));
                    $status = 'completed';
                    $paymentStatus = 'paid'; // Consistent: paid for completed
                } else {
                    $dateTime = Carbon::now()->addDays($faker->numberBetween(3, 21));
                    $status = $faker->randomElement(['pending', 'confirmed']);
                    $paymentStatus = $status === 'confirmed' ? 'paid' : 'pending';
                }

                $hours = $faker->randomElement([1, 2, 3]);
                $pricePerHour = $expert->price ?? 500000;
                
                $type = $faker->randomElement(['individual', 'group']);
                $guests = null;
                $guestsCount = 0;
                
                if ($type === 'group') {
                    $guestsCount = $faker->numberBetween(1, 3);
                    $guests = [];
                    for ($g = 0; $g < $guestsCount; $g++) {
                        $guests[] = $faker->unique()->safeEmail;
                    }
                    $faker->unique(true);
                }
                
                // Price per pax
                $totalPax = 1 + $guestsCount;
                $totalPrice = $pricePerHour * $hours * $totalPax;

                Appointment::create([
                    'user_id' => $user->id,
                    'expert_id' => $expert->id,
                    'skill_id' => $skill->id,
                    'topic' => $faker->randomElement($topics),
                    'date_time' => $dateTime,
                    'location_url' => $isPast ? 'https://meet.google.com/' . $faker->uuid : null,
                    'google_calendar_event_id' => $faker->uuid,
                    'hours' => $hours,
                    'price' => $totalPrice,
                    'status' => $status,
                    'payment_status' => $paymentStatus,
                    'type' => $type,
                    'guests' => $guests,
                    'created_at' => $isPast ? $dateTime->copy()->subDays(3) : Carbon::now()->subDays($faker->numberBetween(1, 5)),
                    'updated_at' => $isPast ? $dateTime->copy()->addHours(2) : Carbon::now(),
                ]);
                $retailCount++;
            }
        }

        // === SUMMARY ===
        $totalQuotaUsed = ClientQuota::get()->sum(function ($q) {
            return 50000000 - $q->balance; // Initial was 50M
        });

        $this->command->info("Created {$corporateCount} B2B appointments (employees only, HRD excluded).");
        $this->command->info("Created {$retailCount} Retail appointments.");
        $this->command->info("Total quota used by B2B: Rp " . number_format($totalQuotaUsed));
    }
}
