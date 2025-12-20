<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserVisitedPlace;
use Illuminate\Database\Seeder;
use MatanYadaev\EloquentSpatial\Objects\Point;

class UserVisitedPlaceSeeder extends Seeder
{
    public function run()
    {
        $jane = User::where('username', 'janecooper')->first();

        if (! $jane) {
            $this->command->error('Required user (janecooper) not found. Please run UserSeeder first.');

            return;
        }

        // Top 5 visited places for Jane Cooper
        $places = [
            [
                'place_name' => 'Lake Louise',
                'address' => 'Lake Louise, Banff National Park, Canada',
                'latitude' => 51.4254,
                'longitude' => -116.1779,
                'rank' => 1,
            ],
            [
                'place_name' => 'Eiffel Tower',
                'address' => 'Champ de Mars, Paris, France',
                'latitude' => 48.8584,
                'longitude' => 2.2945,
                'rank' => 2,
            ],
            [
                'place_name' => 'Santorini',
                'address' => 'Santorini, Greece',
                'latitude' => 36.3932,
                'longitude' => 25.4615,
                'rank' => 3,
            ],
            [
                'place_name' => 'New York City',
                'address' => 'New York, NY, USA',
                'latitude' => 40.7128,
                'longitude' => -74.0060,
                'rank' => 4,
            ],
            [
                'place_name' => 'Sydney Opera House',
                'address' => 'Sydney, NSW, Australia',
                'latitude' => -33.8568,
                'longitude' => 151.2153,
                'rank' => 5,
            ],
        ];

        foreach ($places as $place) {
            UserVisitedPlace::create([
                'user_id' => $jane->id,
                'place_name' => $place['place_name'],
                'address' => $place['address'],
                'coordinates' => new Point($place['latitude'], $place['longitude']),
                'rank' => $place['rank'],
            ]);
        }
    }
}
