<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function getProfile($id = null)
    { 
        try {
            $profile = $this->userService->getUserProfile($id);
            return response()->json($profile);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
    public function userslist()
    { 
        try {
            $profile = $this->userService->userslist();
            return response()->json($profile);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
    public function updateLocation(Request $request)
    { 
        try {
            $user= \Auth::user();
            
            $user->updateLocation($request->lat, $request->lng, $request->accuracy);

            return response()->json($user->location);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
}