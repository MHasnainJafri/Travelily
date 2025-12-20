<?php

namespace Database\Seeders;

use App\Models\Advertisement;
use App\Models\Interest;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdvertisementSeeder extends Seeder
{
    public function run()
    {
        $user = User::where('username', 'janecooper')->first();

        $advertisement = Advertisement::create([
            'user_id' => $user->id,
            'title' => 'Louvre Museum: Ticket + Luxury Private Tour',
            'duration_days' => 7,
            'locations' => ['Paris', 'London', 'New York'],
            'age_ranges' => ['18-29', '30-45'],
            'genders' => ['Males', 'Females'],
            'relationships' => ['Singles', 'Couples'],
        ]);

        // Attach interests
        $interests = Interest::whereIn('name', ['Shopping', 'Nature & outdoor activities'])->pluck('id');
        $advertisement->interests()->attach($interests);

       
    }
}
