<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TripResource;
use App\Services\TripService;
use Illuminate\Http\Request;
use App\Http\Resources\JamResource;

class TripController extends Controller
{
    protected $tripService;

    public function __construct(TripService $tripService)
    {
        $this->tripService = $tripService;
        // $this->middleware('auth:api');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'jamboard_name' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'destination_details' => 'nullable',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'time' => 'nullable',
            'looking_for' => 'required|in:tripmate,guide,host',
            'start_from'=>'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5048',
        ]);
        
     

        $data['destination_details'] = json_decode($data['destination_details'], true)??[];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image');
        }

        $trip = $this->tripService->createTrip($data);

                return new JamResource($trip);

    }

    public function getMyTrips()
    {
        $trips = $this->tripService->getMyTrips();
        return TripResource::collection($trips);
    }

    public function searchTrips(Request $request)
    {
        $query = $request->query('q');
        if (!$query) {
            return response()->json(['error' => 'Search query is required'], 400);
        }
        $trips = $this->tripService->searchTrips($query);
        return TripResource::collection($trips);
    }

    public function sendJoinRequest($tripId)
    {
        try {
            $invitation = $this->tripService->sendJoinRequest($tripId);
            return response()->json([
                'message' => 'Join request sent',
                'invitation_id' => $invitation->id
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function getTripDetails($tripId)
    {
        try {
            $trip = $this->tripService->getTripDetails($tripId);
            return new TripResource($trip);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function updatePermissions($tripId, $userId, Request $request)
    {
        $permissions = $request->validate([
            'can_edit_jamboard' => 'sometimes|boolean',
            'can_add_travelers' => 'sometimes|boolean',
            'can_edit_budget' => 'sometimes|boolean',
            'can_add_destinations' => 'sometimes|boolean',
        ]);

        try {
            $updated = $this->tripService->updateTripmatePermissions($tripId, $userId, $permissions);
            return response()->json($updated);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function lockTrip($tripId)
    {
        try {
            $trip = $this->tripService->lockTrip($tripId);
            return new TripResource($trip);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}