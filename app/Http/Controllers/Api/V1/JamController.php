<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\JamResource;
use App\Services\JamService;
use Illuminate\Http\Request;

class JamController extends Controller
{
    protected $jamService;

    public function __construct(JamService $jamService)
    {
        $this->jamService = $jamService;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'destination' => 'nullable|string|max:255',
             'destination_details' => 'nullable',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'budget_min' => 'nullable|numeric|min:0',
            'budget_max' => 'nullable|numeric|min:0|gte:budget_min',
            'num_guests' => 'integer|min:1',
            'num_persons' => 'integer|min:1',
            'image' => 'nullable|string|max:255',
            'participants' => 'nullable|array',
            'participants.*' => 'exists:users,id',
        ]);
                $data['destination_details'] = json_decode($data['destination_details'], true)??[];

 if ($request->hasFile('image')) {
            $data['image'] = $request->file('image');
        }

        $jam = $this->jamService->createJam($data);
        return new JamResource($jam);
    }
    public function getMyJams()
{
    $jams = $this->jamService->getMyJams();
    return JamResource::collection($jams);
}
    public function getJams()
{
    $jams = $this->jamService->getJams();
    return JamResource::collection($jams);
}

public function searchJams(Request $request)
{
    $query = $request->query('q');
    if (!$query) {
        return response()->json(['error' => 'Search query is required'], 400);
    }

    $jams = $this->jamService->searchJams($query);
    return JamResource::collection($jams);
}

public function sendJoinRequest($jamId)
{
    try {
        $invitation = $this->jamService->sendJoinRequest($jamId);
        return response()->json(['message' => 'Join request sent', 'invitation_id' => $invitation->id], 201);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 400);
    }
}
public function getJamDetails($jamId)
{
    try {
        $jam = $this->jamService->getJamDetails($jamId);
        return new JamResource($jam);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 404);
    }
}
public function lockJam($jamId)
{
    try {
        $jam = $this->jamService->lockJam($jamId);
        return new JamResource($jam);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 400);
    }
}
}