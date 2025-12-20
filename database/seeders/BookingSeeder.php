<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Experience;
use App\Models\Listing;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run()
    {
        $host = User::where('username', 'janecooper')->first();
        $guest1 = User::where('username', 'jaylonlipshutz')->first();
        $guest2 = User::where('username', 'kaylynrosser')->first();

        if (! $host || ! $guest1 || ! $guest2) {
            $this->command->error('Required users (janecooper, jaylonlipshutz, kaylynrosser) not found. Please run UserSeeder first.');

            return;
        }

        $listing1 = Listing::latest()->first();
        $listing2 = Listing::latest()->first();
        $experience1 = Experience::latest()->first();
        $experience2 = Experience::latest()->first();

        if (! $listing1 || ! $listing2 || ! $experience1 || ! $experience2) {
            $this->command->error('Required listings (Le Lagore, Luxury Seaside) or experiences (Paris City Tour, Great Pubs near Liverpool Street) not found. Please run ListingSeeder and ExperienceSeeder first.');

            return;
        }

        // Booking 1: Great Pubs near Liverpool Street (Experience), Approved, 12-16 Feb 2023, 4 people
        $startDate1 = Carbon::parse('2023-02-12');
        $endDate1 = Carbon::parse('2023-02-16');
        $totalPrice1 = 2000; // Matches the screen
        Booking::create([
            // 'bookable_type' => Experience::class,
            // 'bookable_id' => $experience2->id,
            'listing_id'=> $listing2->id, 
            'host_id' => $host->id,
            'guest_id' => $guest1->id,
            'start_date' => $startDate1,
            'end_date' => $endDate1,
            'num_people' => 4,
            'total_price' => $totalPrice1,
            'status' => 'approved',
        ]);

        // Booking 2: Great Pubs near Liverpool Street (Experience), Rejected, 18-21 Feb 2023, 5 people
        $startDate2 = Carbon::parse('2023-02-18');
        $endDate2 = Carbon::parse('2023-02-21');
        $totalPrice2 = 2000; // Matches the screen
        Booking::create([
            // 'bookable_type' => Experience::class,
            // 'bookable_id' => $experience2->id,
            'listing_id'=> $listing2->id, 
            'host_id' => $host->id,
            'guest_id' => $guest2->id,
            'start_date' => $startDate2,
            'end_date' => $endDate2,
            'num_people' => 5,
            'total_price' => $totalPrice2,
            'status' => 'rejected',
        ]);

        // Booking 3: Le Lagore (Listing), Approved, 25 Feb-4 Mar 2023, 7 people
        $startDate3 = Carbon::parse('2023-02-25');
        $endDate3 = Carbon::parse('2023-03-04');
        $days3 = $startDate3->diffInDays($endDate3);
        $totalPrice3 = $listing1->price * $days3; // $price per night * number of days
        Booking::create([
            // 'bookable_type' => Listing::class,
            // 'bookable_id' => $listing1->id,
            'listing_id'=> $listing2->id, 
            'host_id' => $host->id,
            'guest_id' => $guest1->id,
            'start_date' => $startDate3,
            'end_date' => $endDate3,
            'num_people' => 7,
            'total_price' => $totalPrice3,
            'status' => 'approved',
        ]);

        // Booking 4: Paris City Tour (Experience), Approved, 17-23 Aug 2023, 2 people
        $startDate4 = Carbon::parse('2023-08-17');
        $endDate4 = Carbon::parse('2023-08-23');
        $totalPrice4 = $experience1->max_price * 2; // Using max_price for the tour, multiplied by number of people
        Booking::create([
            // 'bookable_type' => Experience::class,
            // 'bookable_id' => $experience1->id,
                        'listing_id'=> $listing2->id, 

            'host_id' => $host->id,
            'guest_id' => $guest1->id,
            'start_date' => $startDate4,
            'end_date' => $endDate4,
            'num_people' => 2,
            'total_price' => $totalPrice4,
            'status' => 'approved',
        ]);
    }
}
