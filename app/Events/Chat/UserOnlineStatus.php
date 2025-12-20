<?php

namespace App\Events\Chat;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserOnlineStatus implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public bool $isOnline;

    public function __construct(User $user, bool $isOnline)
    {
        $this->user = $user;
        $this->isOnline = $isOnline;
    }

    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('online'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'user.status';
    }

    public function broadcastWith(): array
    {
        return [
            'user_id' => $this->user->id,
            'is_online' => $this->isOnline,
            'last_seen' => now()->toIso8601String(),
        ];
    }
}
