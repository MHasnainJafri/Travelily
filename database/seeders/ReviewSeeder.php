<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run()
    {
        // Fetch users and add error handling
        $jane = User::firstOrCreate(
            ['username' => 'janecooper'],
            ['name' => 'Jane Cooper', 'email' => 'jane@example.com', 'password' => bcrypt('password')]
        );

        if (! $jane) {
            $this->command->error('Required user (janecooper) not found or could not be created.');

            return;
        }

        $reviewer1 = User::firstOrCreate(
            ['username' => 'jaylonlipshutz'],
            ['name' => 'Jaylon Lipshutz', 'email' => 'jaylon@example.com', 'password' => bcrypt('password')]
        );
        $reviewer2 = User::firstOrCreate(
            ['username' => 'kaylynrosser'],
            ['name' => 'Kaylyn Rosser', 'email' => 'kaylyn@example.com', 'password' => bcrypt('password')]
        );

        // Review 1: Jaylon Lipshutz
        Review::create([
            'reviewed_user_id' => $jane->id,
            'reviewer_id' => $reviewer1->id,
            'rating' => 5.0,
            'comment' => 'Great stay! Beautiful place, nice views. Great to stay here!',
        ]);

        // Review 2: Kaylyn Rosser
        Review::create([
            'reviewed_user_id' => $jane->id,
            'reviewer_id' => $reviewer2->id,
            'rating' => 4.5,
            'comment' => 'A romantic place, convenient, clean and humble. Satisfied!',
        ]);

        // Review 3: Additional review for variety
        Review::create([
            'reviewed_user_id' => $jane->id,
            'reviewer_id' => $reviewer1->id, // Jaylon reviewing again
            'rating' => 4.8,
            'comment' => 'Amazing experience, loved the scenery! Would definitely come back.',
        ]);
    }
}
