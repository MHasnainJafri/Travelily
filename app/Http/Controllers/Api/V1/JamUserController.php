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

    public function removeUser($jamId, $userId)
    {
        try {
            \DB::table('jam_users')
                ->where('jam_id', $jamId)
                ->where('user_id', $userId)
                ->update([
                    'status' => 'removed',
                    'updated_at' => now()
                ]);

            return response()->json([
                'status' => true,
                'message' => 'User removed from jam successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function getRemovedUsers($jamId)
    {
        try {
            $removedUsers = \DB::table('jam_users')
                ->where('jam_id', $jamId)
                ->where('status', 'removed')
                ->join('users', 'jam_users.user_id', '=', 'users.id')
                ->select('users.id', 'users.name', 'users.email', 'jam_users.updated_at as removed_at')
                ->get();

            return response()->json([
                'status' => true,
                'data' => $removedUsers
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
    
}