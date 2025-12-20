<?php

namespace Database\Seeders;

use App\Models\TravelWithOption;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TravelWithOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $travelWithOptions = [
            'Friends',
            'Family',
            'Travel Buddies',
            'Colleagues',
            'Boyfriend/Girlfriend',
        ];
        foreach ($travelWithOptions as $optionName) {
            TravelWithOption::firstOrCreate(['name' => $optionName]);
        }

    }
}
