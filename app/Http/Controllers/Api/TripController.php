<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Travel\TripService;
use Illuminate\Http\Request;

class TripController extends Controller
{
    protected $tripService;

    public function __construct(TripService $tripService)
    {
        $this->tripService = $tripService;
    }

    public function index()
    {
        $trips = $this->tripService->all();

        return response()->json($trips);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'required|in:planned,ongoing,completed,canceled',
        ]);

        $trip = $this->tripService->create($validated);

        return response()->json($trip, 201);
    }

    public function show($id)
    {
        $trip = $this->tripService->find($id);

        return response()->json($trip);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'sometimes|in:planned,ongoing,completed,canceled',
        ]);

        $trip = $this->tripService->update($id, $validated);

        return response()->json($trip);
    }

    public function destroy($id)
    {
        $this->tripService->delete($id);

        return response()->json(null, 204);
    }
}
