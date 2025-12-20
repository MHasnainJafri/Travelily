<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Services\JamService;
use Illuminate\Http\Request;

class JamUserController extends Controller
{
    protected $jamService;

    public function __construct(JamService $jamService)
    {
        $this->jamService = $jamService;
    }

    public function updatePermissions(Request $request, $jamId, $userId)
    {
        $data = $request->validate([
            'can_edit_jamboard' => 'boolean',
            'can_add_travelers' => 'boolean',
            'can_edit_budget' => 'boolean',
            'can_add_destinations' => 'boolean',
        ]);

        try {
            $updatedUser = $this->jamService->updateTripmatePermissions($jamId, $userId, $data);
            return new UserResource($updatedUser);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
    
}