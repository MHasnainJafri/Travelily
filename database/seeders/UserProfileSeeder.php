<?php

namespace Database\Seeders;

use App\Models\Interest;
use App\Models\TravelActivity;
use App\Models\TravelWithOption;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserProfileSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Interests
       
        // 4. Create Users and Attach Relationships
        $usersData = [
            [
                'name' => 'Alice Smith',
                'email' => 'alice@example.com',
                'password' => Hash::make('password'),
                'rating' => 5.0,
                'followers_count' => 2765,
                'petals_count' => 100,
                'trips_count' => 1500,
                'bio' => 'I love exploring new cultures and relaxing on beaches.',
                'interests' => ['Cultural Immersion', 'Relaxation'],
                'buddy_interests' => ['Adventure', 'Sports'],
                'travel_activities' => ['Immerse in culture', 'Relaxation'],
                'travel_with' => ['Friends', 'Boyfriend/Girlfriend'],
            ],
            [
                'name' => 'Bob Johnson',
                'email' => 'bob@example.com',
                'password' => Hash::make('password'),
                'rating' => 5.0,
                'followers_count' => 2765,
                'petals_count' => 100,
                'trips_count' => 1500,
                'bio' => 'I enjoy luxury trips and outdoor adventures.',
                'interests' => ['Luxury', 'Adventure'],
                'buddy_interests' => ['Philanthropy'],
                'travel_activities' => ['Nature & outdoor activities', 'Stay at inclusive hotels/resorts'],
                'travel_with' => ['Family'],
            ],
            [
                'name' => 'Clara Davis',
                'email' => 'clara@example.com',
                'password' => Hash::make('password'),
                'rating' => 5.0,
                'followers_count' => 2765,
                'petals_count' => 100,
                'trips_count' => 1500,
                'bio' => 'I’m all about city life and shopping.',
                'interests' => ['Sports'],
                'buddy_interests' => ['Luxury', 'Relaxation'],
                'travel_activities' => ['City', 'Shopping'],
                'travel_with' => ['Colleagues', 'Travel Buddies'],
            ],
        ];

        foreach ($usersData as $userData) {
            // Create or update user
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => $userData['password'],

                ]
            );
            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'bio' => $userData['bio'],
                    'rating' => $userData['rating'],
                    'followers_count' => $userData['followers_count'],
                    'petals_count' => $userData['petals_count'],
                    'trips_count' => $userData['trips_count'],
                ]
            );

            // Attach interests
            $interestIds = Interest::whereIn('name', $userData['interests'])->pluck('id');
            $user->interests()->sync($interestIds);

            // Attach buddy interests
            $buddyInterestIds = Interest::whereIn('name', $userData['buddy_interests'])->pluck('id');
            $user->buddyInterests()->sync($buddyInterestIds);

            // Attach travel activities
            $activityIds = TravelActivity::whereIn('name', $userData['travel_activities'])->pluck('id');
            $user->travelActivities()->sync($activityIds);

            // Attach travel with options
            $travelWithIds = TravelWithOption::whereIn('name', $userData['travel_with'])->pluck('id');
            $user->travelWithOptions()->sync($travelWithIds);
        }
    }
}
