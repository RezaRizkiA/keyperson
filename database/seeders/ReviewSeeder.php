<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\Appointment;
use App\Models\Expert;
use App\Models\Client;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Review templates for variety
        $reviewTemplates = [
            "Excellent session! {name} really helped me understand {topic}. Highly recommend!",
            "Very insightful consultation. {name} provided practical advice on {topic}.",
            "Great expertise in {topic}. {name} was patient and thorough in explaining concepts.",
            "Best session I've had! {name}'s knowledge of {topic} is outstanding.",
            "Professional and helpful. The {topic} session exceeded my expectations.",
            "Clear and concise explanations on {topic}. Would definitely book again!",
            "Fantastic mentor! {name} made {topic} easy to understand.",
            "Valuable insights on {topic}. {name} is very knowledgeable."
        ];

        $topics = [
            'SEO strategy', 'content marketing', 'leadership coaching', 'career development',
            'business growth', 'product management', 'UI/UX design', 'digital marketing',
            'team management', 'startup strategy', 'fundraising', 'sales optimization'
        ];

        // Get all experts with appointments
        $experts = Expert::with(['appointments' => function($q) {
            $q->where('status', 'completed')->latest()->limit(10);
        }])->get();

        foreach ($experts as $expert) {
            // Create 5-15 reviews per expert
            $reviewCount = $faker->numberBetween(5, 15);
            $appointments = $expert->appointments;

            if ($appointments->isEmpty()) {
                continue; // Skip if no appointments
            }

            for ($i = 0; $i < $reviewCount; $i++) {
                // Pick random appointment
                $appointment = $appointments->random();
                
                // Check if review already exists for this appointment
                if (Review::where('appointment_id', $appointment->id)->exists()) {
                    continue;
                }

                $rating = $faker->numberBetween(3, 5); // 3-5 stars
                $topic = $faker->randomElement($topics);
                $template = $faker->randomElement($reviewTemplates);
                
                $reviewText = str_replace(
                    ['{name}', '{topic}'],
                    [$expert->user->name, $topic],
                    $template
                );

                Review::create([
                    'appointment_id' => $appointment->id,
                    'user_id' => $appointment->user_id,  // User who booked & writes review
                    'expert_id' => $expert->id,
                    'skill_id' => $appointment->skill_id,
                    'rating' => $rating,
                    'review_text' => $reviewText,
                    'helpful_count' => $faker->numberBetween(0, 20),
                    'created_at' => $appointment->created_at->addDays($faker->numberBetween(1, 7)),
                ]);
            }

            // Update expert's aggregated rating
            $this->updateExpertRating($expert);
        }
    }

    private function updateExpertRating(Expert $expert): void
    {
        $reviews = Review::where('expert_id', $expert->id)->get();
        
        if ($reviews->isEmpty()) {
            return;
        }

        $avgRating = round($reviews->avg('rating'), 2);
        $totalReviews = $reviews->count();
        
        // Assuming sessions = reviews for now (can be adjusted)
        $expert->update([
            'rating' => $avgRating,
            'total_reviews' => $totalReviews,
            'total_sessions' => $totalReviews, // or calculate differently
        ]);
    }
}
