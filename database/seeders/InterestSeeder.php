<?php

namespace Database\Seeders;

use App\Models\Interest;
use Illuminate\Database\Seeder;

class InterestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $interests = [
            ['name' => 'Luxury'],
            ['name' => 'Relaxation'],
            ['name' => 'Sports'],
            ['name' => 'Cultural Immersion'],
            ['name' => 'Food and Wine'],
            ['name' => 'Adventure'],
            ['name' => 'Nature and Wildlife'],
            ['name' => 'History and Heritage'],
            ['name' => 'Shopping'],
            ['name' => 'Nightlife'],
            ['name' => 'Nature & outdoor activities'],
            ['name' => 'Immerse in local culture'],
            ['name' => 'Stay at inclusive hotels & resorts'],
        ];

        foreach ($interests as $interest) {
            Interest::create($interest);
        }
    }
}
