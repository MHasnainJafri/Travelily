<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        
        // This would typically integrate with Google Places API
        // For now, return a mock response structure
        $locations = [
            [
                'place_id' => 'ChIJs...',
                'name' => 'Park View, Canada',
                'formatted_address' => 'Park View, Canada',
                'latitude' => 45.4215,
                'longitude' => -75.6972
            ]
        ];

        return response()->json([
            'status' => true,
            'data' => $locations
        ]);
    }
}
