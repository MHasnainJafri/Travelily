<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveVisitedPlacesRequest;
use App\Models\UserRecommendedPlace;
use App\Models\UserVisitedPlace;
use MatanYadaev\EloquentSpatial\Objects\Point;

class UserPlaceController extends Controller
{
    public function storeTraveledPlaces(SaveVisitedPlacesRequest $request)
    {
        $user = auth()->user();

        // Delete any existing visited places for the user to ensure only the latest 5 are stored
        $user->visitedPlaces()->delete();

        // Save the new visited places
        foreach ($request->visited_places as $index => $place) {
            UserVisitedPlace::create([
                'user_id' => $user->id,
                'place_name' => $place['place_name'],
                'address' => $place['address'] ?? null,
                'coordinates' => new Point($place['latitude'], $place['longitude']),
                'google_place_id' => $place['google_place_id'] ?? null,
            ]);
        }

        return response()->json(['message' => 'Visited places saved successfully'], 200);
    }

    public function storeRecommendedPlaces(SaveVisitedPlacesRequest $request)
    {
        $user = auth()->user();

        // Delete any existing visited places for the user to ensure only the latest 5 are stored
        $user->recommendedPlaces()->delete();

        // Save the new visited places
        foreach ($request->visited_places as $index => $place) {
            UserRecommendedPlace::create([
                'user_id' => $user->id,
                'place_name' => $place['place_name'],
                'address' => $place['address'] ?? null,
                'coordinates' => new Point($place['latitude'], $place['longitude']),
                'google_place_id' => $place['google_place_id'] ?? null,
            ]);
        }

        return response()->json(['message' => 'Visited places saved successfully'], 200);
    }
}
