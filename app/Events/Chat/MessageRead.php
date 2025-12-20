<?php

namespace App\Events\Chat;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageRead implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $conversationId;
    public int $userId;
    public array $messageIds;
    public string $readAt;

    public function __construct(int $conversationId, int $userId, array $messageIds)
    {
        $this->conversationId = $conversationId;
        $this->userId = $userId;
        $this->messageIds = $messageIds;
        $this->readAt = now()->toIso8601String();
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('conversation.' . $this->conversationId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.read';
    }

    public function broadcastWith(): array
    {
        $user = User::find($this->userId);
        
        return [
            'conversation_id' => $this->conversationId,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
            ],
            'message_ids' => $this->messageIds,
            'read_at' => $this->readAt,
        ];
    }
}
