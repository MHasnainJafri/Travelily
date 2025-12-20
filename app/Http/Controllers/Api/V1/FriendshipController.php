<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\FriendshipRequestResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;
use App\Services\FriendshipService;
use Illuminate\Http\Request;
use Mhasnainjafri\RestApiKit\API;

class FriendshipController extends Controller
{
    protected $friendshipService;

    public function __construct(FriendshipService $friendshipService)
    {
        $this->friendshipService = $friendshipService;
    }

    public function getFriends()
    {
        $friends = $this->friendshipService->getFriends();
        return new UserCollection($friends);
    }

    public function getFollowers()
    {
        $followers = $this->friendshipService->getFollowers();
        return API::success(
            new UserCollection($followers),
            'Followers retrieved successfully'
        );
    }

    public function getFollowing()
    {
        $following = $this->friendshipService->getFollowing();
        return API::success(
            new UserCollection($following),
            'Following list retrieved successfully'
        );
    }

    public function getPendingRequests()
    {
        $requests = $this->friendshipService->getPendingRequests();
        return API::success(
            FriendshipRequestResource::collection($requests),
            'Pending requests retrieved successfully'
        );
    }

    public function getSentRequests()
    {
        $requests = $this->friendshipService->getSentRequests();
        return API::success(
            FriendshipRequestResource::collection($requests),
            'Sent requests retrieved successfully'
        );
    }

    public function searchUsers(Request $request)
    {
        $query = $request->query('q');
        if (!$query) {
            return response()->json(['error' => 'Search query is required'], 400);
        }

        $users = $this->friendshipService->searchUsers($query);
        return new UserCollection($users);
    }

    public function sendRequest($targetUserId)
    {
        try {
            $friendship = $this->friendshipService->sendRequest($targetUserId);
            $message = $friendship->type === 'follow' 
                ? 'Follow request sent' 
                : 'Friend request sent';
            return API::success(
                new FriendshipRequestResource($friendship),
                $message,
                201
            );
        } catch (\Exception $e) {
            return API::error($e->getMessage(), 400);
        }
    }

    public function follow($targetUserId)
    {
        try {
            $friendship = $this->friendshipService->follow($targetUserId);
            return API::success(
                new FriendshipRequestResource($friendship),
                'Now following user',
                201
            );
        } catch (\Exception $e) {
            return API::error($e->getMessage(), 400);
        }
    }

    public function unfollow($targetUserId)
    {
        try {
            $this->friendshipService->unfollow($targetUserId);
            return API::success(null, 'Unfollowed successfully');
        } catch (\Exception $e) {
            return API::error($e->getMessage(), 400);
        }
    }

    public function acceptRequest($senderId)
    {
        try {
            $friendship = $this->friendshipService->acceptFriendRequest($senderId);
            $message = $friendship->type === 'follow' 
                ? 'Follow request accepted' 
                : 'Friend request accepted';
            return API::success(null, $message);
        } catch (\Exception $e) {
            return API::error($e->getMessage(), 400);
        }
    }

    public function rejectRequest($senderId)
    {
        try {
            $friendship = $this->friendshipService->rejectFriendRequest($senderId);
            $message = $friendship->type === 'follow' 
                ? 'Follow request rejected' 
                : 'Friend request rejected';
            return API::success(null, $message);
        } catch (\Exception $e) {
            return API::error($e->getMessage(), 400);
        }
    }

    public function cancelRequest($targetUserId)
    {
        try {
            $this->friendshipService->cancelRequest($targetUserId);
            return API::success(null, 'Request cancelled');
        } catch (\Exception $e) {
            return API::error($e->getMessage(), 400);
        }
    }

    public function unfriend($friendId)
    {
        try {
            $this->friendshipService->unfriend($friendId);
            return API::success(null, 'Unfriended successfully');
        } catch (\Exception $e) {
            return API::error($e->getMessage(), 400);
        }
    }

    public function getRelationshipStatus($targetUserId)
    {
        $status = $this->friendshipService->getRelationshipStatus($targetUserId);
        return API::success($status, 'Relationship status retrieved');
    }
}