<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExperienceController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'location' => 'required|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'min_price' => 'required|numeric',
            'max_price' => 'required|numeric',
            'images' => 'nullable|array',
            'offerings' => 'nullable|array',
            'dates' => 'nullable|array',
        ]);

        $experienceId = DB::table('experiences')->insertGetId([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'location' => $validated['location'],
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'min_price' => $validated['min_price'],
            'max_price' => $validated['max_price'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Experience created successfully',
            'data' => ['id' => $experienceId]
        ]);
    }

    public function index()
    {
        $experiences = DB::table('experiences')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $experiences
        ]);
    }

    public function show($id)
    {
        $experience = DB::table('experiences')
            ->where('id', $id)
            ->first();

        if (!$experience) {
            return response()->json([
                'status' => false,
                'message' => 'Experience not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $experience
        ]);
    }

    public function getUserExperiences($userId)
    {
        $experiences = DB::table('experiences')
            ->where('user_id', $userId)
            ->select('id', 'title', 'location', 'min_price', 'max_price')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $experiences
        ]);
    }
}
