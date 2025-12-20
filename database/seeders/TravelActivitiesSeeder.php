<?php

namespace Database\Seeders;

use App\Models\TravelActivity;
use Illuminate\Database\Seeder;

class TravelActivitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activities = [
            'Relaxation',
            'Nature & outdoor activities',
            'Stay at all inclusive hotels & resorts',
            'Immerse in local culture',
            'Shopping',
            'City tour',
        ];

        foreach ($activities as $activity) {
            TravelActivity::create(['name' => $activity]);
        }
    }
}
