<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\JamGuideResource;
use App\Services\JamGuideService;
use Illuminate\Http\Request;

class JamGuideController extends Controller
{
    protected $jamGuideService;

    public function __construct(JamGuideService $jamGuideService)
    {
        $this->jamGuideService = $jamGuideService;
    }

    public function assignGuide(Request $request, $jamId)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:host,guide',
            'contact_info' => 'nullable|string|max:255',
        ]);

        try {
            $guide = $this->jamGuideService->assignGuide($jamId, $data);
            return new JamGuideResource($guide);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}