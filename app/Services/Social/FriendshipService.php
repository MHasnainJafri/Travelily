<?php

namespace App\Services\Social;

use App\Models\Friendship;

class FriendshipService
{
    public function sendRequest(int $userId, int $friendId)
    {
        return Friendship::create([
            'user_id' => $userId,
            'friend_id' => $friendId,
            'status' => 'pending',
        ]);
    }

    public function acceptRequest(Friendship $friendship)
    {
        return $friendship->update(['status' => 'accepted']);
    }

    public function rejectRequest(Friendship $friendship)
    {
        return $friendship->update(['status' => 'rejected']);
    }

    public function getFriends(int $userId)
    {
        return Friendship::where('user_id', $userId)
            ->where('status', 'accepted')
            ->with('friend')
            ->get();
    }
}
