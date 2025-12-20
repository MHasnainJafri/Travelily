<?php

namespace App\Events\Chat;

use App\Models\Conversation;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ConversationUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Conversation $conversation;
    public string $action;

    public function __construct(Conversation $conversation, string $action = 'updated')
    {
        $this->conversation = $conversation;
        $this->action = $action;
    }

    public function broadcastOn(): array
    {
        $channels = [];
        
        foreach ($this->conversation->activeParticipants as $participant) {
            $channels[] = new PrivateChannel('user.' . $participant->id . '.conversations');
        }
        
        return $channels;
    }

    public function broadcastAs(): string
    {
        return 'conversation.' . $this->action;
    }

    public function broadcastWith(): array
    {
        return [
            'conversation_id' => $this->conversation->id,
            'type' => $this->conversation->type,
            'name' => $this->conversation->name,
            'image' => $this->conversation->image,
            'last_message_at' => $this->conversation->last_message_at?->toIso8601String(),
            'action' => $this->action,
        ];
    }
}
