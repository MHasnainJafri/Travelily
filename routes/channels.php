<?php

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('conversation.{conversationId}', function (User $user, int $conversationId) {
    $conversation = Conversation::find($conversationId);
    
    if (!$conversation) {
        return false;
    }
    
    return $conversation->hasParticipant($user->id);
});

Broadcast::channel('user.{userId}.conversations', function (User $user, int $userId) {
    return (int) $user->id === (int) $userId;
});

Broadcast::channel('online', function (User $user) {
    return [
        'id' => $user->id,
        'name' => $user->name,
        'username' => $user->username,
    ];
});

Broadcast::channel('jam.{jamId}', function (User $user, int $jamId) {
    $jam = \App\Models\Jam::find($jamId);
    
    if (!$jam) {
        return false;
    }
    
    return $jam->users()->where('users.id', $user->id)->exists();
});
