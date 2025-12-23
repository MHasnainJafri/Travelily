<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdvertisementController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'duration_days' => 'required|integer',
            'locations' => 'required|array',
            'target_audience' => 'required|array',
            'target_audience.age_range' => 'nullable|array',
            'target_audience.gender' => 'nullable|array',
            'target_audience.interests' => 'nullable|array',
            'media' => 'nullable|array',
            'payment_method_id' => 'required|string'
        ]);

        $adId = DB::table('advertisements')->insertGetId([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'duration_days' => $validated['duration_days'],
            'locations' => json_encode($validated['locations']),
            'age_ranges' => json_encode($validated['target_audience']['age_range'] ?? []),
            'genders' => json_encode($validated['target_audience']['gender'] ?? []),
            'start_date' => now(),
            'end_date' => now()->addDays($validated['duration_days']),
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Link interests if provided
        if (!empty($validated['target_audience']['interests'])) {
            foreach ($validated['target_audience']['interests'] as $interestName) {
                $interest = DB::table('interests')->where('name', $interestName)->first();
                if ($interest) {
                    DB::table('advertisement_interest')->insert([
                        'advertisement_id' => $adId,
                        'interest_id' => $interest->id,
                    ]);
                }
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Advertisement created successfully',
            'data' => ['id' => $adId]
        ]);
    }

    public function index()
    {
        $ads = DB::table('advertisements')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $ads
        ]);
    }
}
