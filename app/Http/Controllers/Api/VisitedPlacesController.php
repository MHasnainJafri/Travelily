<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveVisitedPlacesRequest;
use App\Models\UserVisitedPlace;
use MatanYadaev\EloquentSpatial\Objects\Point;

class VisitedPlacesController extends Controller
{
    public function store(SaveVisitedPlacesRequest $request)
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
                'rank' => $index + 1, // Rank from 1 to 5
            ]);
        }

        return response()->json(['message' => 'Visited places saved successfully'], 200);
    }
}
