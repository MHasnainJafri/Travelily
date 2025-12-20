<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\JamFlightResource;
use App\Http\Resources\ItineraryResource;
use App\Services\JamItineraryService;
use Illuminate\Http\Request;

class JamItineraryController extends Controller
{
    protected $jamItineraryService;

    public function __construct(JamItineraryService $jamItineraryService)
    {
        $this->jamItineraryService = $jamItineraryService;
    }

    public function addFlight(Request $request, $jamId)
    {
        $data = $request->validate([
            'mode_of_transportation' => 'required|in:airplane,bus,train,car,boat,motorcycle,other',
            'from' => 'required|string|max:255',
            'to' => 'required|string|max:255',
            'date' => 'required|date',
            'departure_time' => 'required|date_format:H:i',
            'arrival_time' => 'required|date_format:H:i',
        ]);

        try {
            $flight = $this->jamItineraryService->addFlight($jamId, $data);
            return new JamFlightResource($flight);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function addAccommodation(Request $request, $jamId)
    {
        $data = $request->validate([
            'type' => 'required|in:hotel,guesthouse',
            'location' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'nullable|date_format:H:i',
            'comments' => 'nullable|string',
        ]);

        try {
            $accommodation = $this->jamItineraryService->addAccommodation($jamId, $data);
            return new ItineraryResource($accommodation);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function addActivity(Request $request, $jamId)
    {
        $data = $request->validate([
            'category' => 'required|in:backpacking,tent camping,dry rooftop camping,canoe camping,camping,bonfire,trekking,music,concert,sky diving',
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'nullable|date_format:H:i',
            'comments' => 'nullable|string',
        ]);

        try {
            $activity = $this->jamItineraryService->addActivity($jamId, $data);
            return new ItineraryResource($activity);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
    public function addExperience(Request $request, $jamId)
{
    $data = $request->validate([
        'category' => 'required|in:backpacking,tent camping,dry rooftop camping,canoe camping,camping,bonfire,trekking,music,concert,sky diving',
        'title' => 'required|string|max:255',
        'location' => 'required|string|max:255',
        'date' => 'required|date',
        'time' => 'nullable|date_format:H:i',
        'description' => 'nullable|string',
    ]);

    try {
        $experience = $this->jamItineraryService->addExperience($jamId, $data);
        return new ItineraryResource($experience);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 400);
    }
}
}