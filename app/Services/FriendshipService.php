<?php

namespace App\Services;

use App\Models\Friendship;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FriendshipService
{
    public function getFriends()
    {
        $user = Auth::user();
        return $user->friends();
    }

    public function getFollowers()
    {
        $userId = Auth::id();
        return User::whereHas('friendsOfMine', function ($query) use ($userId) {
            $query->where('friend_id', $userId)
                  ->where('type', Friendship::TYPE_FOLLOW)
                  ->where('status', Friendship::STATUS_ACCEPTED);
        })->get();
    }

    public function getFollowing()
    {
        $userId = Auth::id();
        return User::whereHas('friendOf', function ($query) use ($userId) {
            $query->where('user_id', $userId)
                  ->where('type', Friendship::TYPE_FOLLOW)
                  ->where('status', Friendship::STATUS_ACCEPTED);
        })->get();
    }

    public function getPendingRequests()
    {
        $userId = Auth::id();
        
        return Friendship::with(['sender', 'sender.profile'])
            ->where('friend_id', $userId)
            ->where('status', Friendship::STATUS_PENDING)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getSentRequests()
    {
        $userId = Auth::id();
        
        return Friendship::with(['receiver', 'receiver.profile'])
            ->where('user_id', $userId)
            ->where('status', Friendship::STATUS_PENDING)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function searchUsers($query)
    {
        return User::where(function ($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
              ->orWhere('username', 'like', "%{$query}%")
              ->orWhere('email', 'like', "%{$query}%");
        })
        ->where('id', '!=', Auth::id())
        ->get();
    }

    public function sendRequest($targetUserId)
    {
        $userId = Auth::id();
        $user = Auth::user();
        $targetUser = User::findOrFail($targetUserId);

        if ($userId == $targetUserId) {
            throw new \Exception('Cannot send request to yourself');
        }

        $existing = Friendship::where(function ($query) use ($userId, $targetUserId) {
            $query->where('user_id', $userId)->where('friend_id', $targetUserId);
        })->orWhere(function ($query) use ($userId, $targetUserId) {
            $query->where('user_id', $targetUserId)->where('friend_id', $userId);
        })->first();

        if ($existing) {
            throw new \Exception('Request already exists or relationship already established');
        }

        $type = $this->determineRelationType($user, $targetUser);

        $friendship = Friendship::create([
            'user_id' => $userId,
            'friend_id' => $targetUserId,
            'type' => $type,
            'status' => Friendship::STATUS_PENDING,
        ]);

        return $friendship;
    }

    public function sendFriendRequest($friendId)
    {
        return $this->sendRequest($friendId);
    }

    public function follow($targetUserId)
    {
        $userId = Auth::id();
        $targetUser = User::findOrFail($targetUserId);

        if ($userId == $targetUserId) {
            throw new \Exception('Cannot follow yourself');
        }

        $existing = Friendship::where('user_id', $userId)
            ->where('friend_id', $targetUserId)
            ->first();

        if ($existing) {
            throw new \Exception('Already following or request pending');
        }

        $friendship = Friendship::create([
            'user_id' => $userId,
            'friend_id' => $targetUserId,
            'type' => Friendship::TYPE_FOLLOW,
            'status' => Friendship::STATUS_ACCEPTED,
        ]);

        return $friendship;
    }

    public function unfollow($targetUserId)
    {
        $userId = Auth::id();

        $friendship = Friendship::where('user_id', $userId)
            ->where('friend_id', $targetUserId)
            ->where('type', Friendship::TYPE_FOLLOW)
            ->first();

        if (!$friendship) {
            throw new \Exception('Not following this user');
        }

        $friendship->delete();
        return true;
    }

    public function acceptFriendRequest($senderId)
    {
        $userId = Auth::id();
        $friendship = Friendship::where('user_id', $senderId)
            ->where('friend_id', $userId)
            ->where('status', Friendship::STATUS_PENDING)
            ->firstOrFail();
            
        $friendship->update(['status' => Friendship::STATUS_ACCEPTED]);
        return $friendship;
    }

    public function rejectFriendRequest($senderId)
    {
        $userId = Auth::id();
        $friendship = Friendship::where('user_id', $senderId)
            ->where('friend_id', $userId)
            ->where('status', Friendship::STATUS_PENDING)
            ->firstOrFail();
            
        $friendship->update(['status' => Friendship::STATUS_REJECTED]);
        return $friendship;
    }

    public function cancelRequest($targetUserId)
    {
        $userId = Auth::id();
        $friendship = Friendship::where('user_id', $userId)
            ->where('friend_id', $targetUserId)
            ->where('status', Friendship::STATUS_PENDING)
            ->firstOrFail();

        $friendship->delete();
        return true;
    }

    public function unfriend($friendId)
    {
        $userId = Auth::id();
        
        $friendship = Friendship::where(function ($query) use ($userId, $friendId) {
            $query->where('user_id', $userId)->where('friend_id', $friendId);
        })->orWhere(function ($query) use ($userId, $friendId) {
            $query->where('user_id', $friendId)->where('friend_id', $userId);
        })
        ->where('status', Friendship::STATUS_ACCEPTED)
        ->first();

        if (!$friendship) {
            throw new \Exception('Friendship not found');
        }

        $friendship->delete();
        return true;
    }

    public function getRelationshipStatus($targetUserId)
    {
        $userId = Auth::id();

        $friendship = Friendship::where(function ($query) use ($userId, $targetUserId) {
            $query->where('user_id', $userId)->where('friend_id', $targetUserId);
        })->orWhere(function ($query) use ($userId, $targetUserId) {
            $query->where('user_id', $targetUserId)->where('friend_id', $userId);
        })->first();

        if (!$friendship) {
            return [
                'status' => 'none',
                'type' => null,
                'is_sender' => null,
            ];
        }

        return [
            'status' => $friendship->status,
            'type' => $friendship->type,
            'is_sender' => $friendship->user_id === $userId,
        ];
    }

    protected function determineRelationType(User $sender, User $targetUser): string
    {
        $targetRoles = $targetUser->getRoleNames()->toArray();
        
        if (in_array('host', $targetRoles) || in_array('guide', $targetRoles)) {
            return Friendship::TYPE_FOLLOW;
        }

        return Friendship::TYPE_FRIEND;
    }
}