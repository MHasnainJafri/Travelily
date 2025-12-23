<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ListingController extends Controller
{
    public function myListings()
    {
        $listings = DB::table('listings')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $listings
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'location' => 'required|string',
            'amenities' => 'nullable|array',
            'house_rules' => 'nullable|array',
            'max_guests' => 'required|integer',
            'price_per_night' => 'required|numeric',
            'num_rooms' => 'nullable|integer',
            'min_stay_days' => 'nullable|integer',
        ]);

        $listingId = DB::table('listings')->insertGetId([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'location' => $validated['location'],
            'price' => $validated['price_per_night'],
            'max_people' => $validated['max_guests'],
            'num_rooms' => $validated['num_rooms'] ?? 1,
            'min_stay_days' => $validated['min_stay_days'] ?? 1,
            'approval_status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Sync amenities
        if (!empty($validated['amenities'])) {
            foreach ($validated['amenities'] as $amenityId) {
                DB::table('amenity_listing')->insert([
                    'listing_id' => $listingId,
                    'amenity_id' => $amenityId,
                ]);
            }
        }

        // Sync house rules
        if (!empty($validated['house_rules'])) {
            foreach ($validated['house_rules'] as $ruleId) {
                DB::table('house_rule_listing')->insert([
                    'listing_id' => $listingId,
                    'house_rule_id' => $ruleId,
                ]);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Listing created successfully',
            'data' => ['id' => $listingId]
        ]);
    }

    public function getHostBookings(Request $request)
    {
        $status = $request->input('status');
        
        $query = DB::table('bookings')
            ->join('listings', 'bookings.listing_id', '=', 'listings.id')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->where('listings.user_id', auth()->id())
            ->select('bookings.*', 'users.name as guest_name', 'listings.title as listing_name');

        if ($status) {
            $query->where('bookings.status', $status);
        }

        $bookings = $query->orderBy('bookings.created_at', 'desc')->get();

        return response()->json([
            'status' => true,
            'data' => $bookings
        ]);
    }
}
