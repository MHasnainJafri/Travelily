<?php

namespace Database\Seeders;

use App\Models\Amenity;
use App\Models\HouseRule;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Database\Seeder;

class ListingSeeder extends Seeder
{
    public function run()
    {
        $users = ['janecooper', 'jaylonlipshutz', 'kaylynrosser'];

        $listingsData = [
            [
                'title' => 'Luxury Seaside',
                'location' => 'Bologna, Italy',
                'description' => 'Beautiful ocean-view home with cozy amenities.',
                'max_people' => 6,
                'min_stay_days' => 2,
                'num_rooms' => 3,
                'price' => 200,
            ],
            [
                'title' => 'Mountain Retreat',
                'location' => 'Zermatt, Switzerland',
                'description' => 'Peaceful cabin nestled in the Alps.',
                'max_people' => 4,
                'min_stay_days' => 3,
                'num_rooms' => 2,
                'price' => 250,
            ],
            [
                'title' => 'City Apartment',
                'location' => 'Paris, France',
                'description' => 'Modern apartment in the heart of the city.',
                'max_people' => 2,
                'min_stay_days' => 1,
                'num_rooms' => 1,
                'price' => 150,
            ],
        ];

        foreach ($users as $index => $username) {
            $user = User::where('username', $username)->first();

            if (!$user) {
                echo "User not found: $username\n";
                continue;
            }

            $data = $listingsData[$index];

            $listing = Listing::create([
                'user_id' => $user->id,
                'title' => $data['title'],
                'location' => $data['location'],
                'description' => $data['description'],
                'max_people' => $data['max_people'],
                'min_stay_days' => $data['min_stay_days'],
                'num_rooms' => $data['num_rooms'],
                'price' => $data['price'],
            ]);

            // Attach common amenities
            $amenities = Amenity::whereIn('name', ['Wi-Fi', 'Breakfast', 'Babysitting', 'Towel Service'])->pluck('id');
            $listing->amenities()->attach($amenities);

            // Attach common house rules
            $rules = HouseRule::whereIn('name', ['No Pets', 'No Littering', 'No Drugs', 'No Smoking'])->pluck('id');
            $listing->houseRules()->attach($rules);

            // Add media from local public folder
            $filePath = public_path('sample.png');
            if (file_exists($filePath)) {
                $listing->addMedia($filePath)->preservingOriginal()->toMediaCollection('listing_photos');
            } else {
                echo "Image file not found: $filePath\n";
            }
        }
    }
}
