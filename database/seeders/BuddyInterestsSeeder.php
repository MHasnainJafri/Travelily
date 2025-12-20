<?php

namespace Database\Seeders;

use App\Models\BuddyInterest;
use Illuminate\Database\Seeder;

class BuddyInterestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $interests = [
            'Luxury',
            'Relaxation',
            'Adventure',
            'Sports',
            'Philanthropy',
            'Cultural immersion',
        ];

        foreach ($interests as $interest) {
            BuddyInterest::create(['name' => $interest]);
        }
    }
}
