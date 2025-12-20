<?php

namespace Database\Seeders;

use App\Models\Amenity;
use Illuminate\Database\Seeder;

class AmenitySeeder extends Seeder
{
    public function run()
    {
        $amenities = [
            'Wi-Fi',
            'Breakfast',
            'Babysitting',
            'Towel Service',
        ];

        foreach ($amenities as $amenity) {
            Amenity::create(['name' => $amenity]);
        }
    }
}
