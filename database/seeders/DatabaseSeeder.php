<?php

namespace Database\Seeders;

use App\Models\Jam;
use App\Models\Post;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Task;
use App\Models\User;
use App\Models\Itinerary;
use App\Models\JamFlight;
use App\Models\BucketList;
use App\Models\PostComment;
use App\Models\UserProfile;
use App\Models\TaskAssignee;
use App\Models\BucketListImage;
use App\Models\Interest;
use App\Models\TravelActivity;
use App\Models\TravelWithOption;
use Illuminate\Database\Seeder;
use Database\Seeders\PostSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\ReviewSeeder;
use Database\Seeders\AmenitySeeder;
use Database\Seeders\BookingSeeder;
use Database\Seeders\ListingSeeder;
use Database\Seeders\InterestSeeder;
use Database\Seeders\HouseRuleSeeder;
use Database\Seeders\ExperienceSeeder;
use Database\Seeders\UserProfileSeeder;
use Database\Seeders\AdvertisementSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Core Setup
        $this->call([RoleSeeder::class, UserSeeder::class, JamSeeder::class]);

        // Posts and Comments
        // Post::factory(5)->create();

        // Run additional seeders
        $this->call([
            PlanSeeder::class,
            InterestSeeder::class,
            TravelActivitySeeder::class,
            TravelWithOptionSeeder::class,
            ReviewSeeder::class,
            ExperienceSeeder::class,
            AdvertisementSeeder::class,
            AmenitySeeder::class,
            HouseRuleSeeder::class,
            ListingSeeder::class,
            LabelSeeder::class,
            UserVisitedPlaceSeeder::class,
            FriendshipSeeder::class,

            BookingSeeder::class,

            PostSeeder::class,

            //   NotificationSeeder::class,
            //   FollowSeeder::class,
            //   BoardSeeder::class,
            //   BoardMemberSeeder::class,
            //   TaskSeeder::class,
            //   ItinerarySeeder::class,
            //   MessageSeeder::class,
            //  UserSeeder::class
            // UserProfileSeeder::class
        ]);
        PostComment::factory(20)->create();

        BucketList::factory()->count(10)->hasimages(3)->create();
        \App\Models\Story::factory(15)->create();
    }
}
