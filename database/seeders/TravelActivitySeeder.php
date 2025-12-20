<?php

namespace Database\Seeders;

use App\Models\TravelActivity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TravelActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $travelActivities = [
            'Relaxation',
            'Nature & outdoor activities',
            'Stay at inclusive hotels/resorts',
            'Immerse in culture',
            'Shopping',
            'City',
        ];
        foreach ($travelActivities as $activityName) {
            TravelActivity::firstOrCreate(['name' => $activityName]);
        }
    }
}
