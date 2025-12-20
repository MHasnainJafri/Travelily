<?php

namespace Database\Seeders;

use App\Models\TravelWithOption;
use Illuminate\Database\Seeder;

class TravelWithOptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $options = [
            'Friends',
            'Family',
            'Travel Buddies',
            'Colleagues',
            'Boyfriend/Girlfriend',
        ];

        foreach ($options as $option) {
            TravelWithOption::create(['name' => $option]);
        }
    }
}
