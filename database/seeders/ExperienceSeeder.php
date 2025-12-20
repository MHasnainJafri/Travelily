<?php

namespace Database\Seeders;

use App\Models\Experience;
use App\Models\User;
use Illuminate\Database\Seeder;

class ExperienceSeeder extends Seeder
{
    public function run()
    {
        $user = User::where('username', 'janecooper')->first();

        if (! $user) {
            $this->command->error('User janecooper not found. Please run UserSeeder first.');

            return;
        }

        // Experience 1: Paris City Tour
        Experience::create([
            'user_id' => $user->id,
            'title' => 'Paris City Tour',
            'description' => 'A wonderful tour of Paris, including the Louvre and Eiffel Tower.',
            'location' => 'Paris, France',
            'start_date' => '2023-08-17',
            'end_date' => '2023-08-23',
            'min_price' => 0,
            'max_price' => 15,
        ]);

        // Experience 2: Great Pubs near Liverpool Street
        Experience::create([
            'user_id' => $user->id,
            'title' => 'Great Pubs near Liverpool Street',
            'description' => 'Explore the best pubs near Liverpool Street in Paris.',
            'location' => 'Paris, France',
            'start_date' => '2023-02-12',
            'end_date' => '2023-02-21',
            'min_price' => 0,
            'max_price' => 2000, // Matches the booking amount
        ]);
    }
}
