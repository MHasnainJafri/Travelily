<?php

namespace App\Events\Chat;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Message $message;

    public function __construct(Message $message)
    {
        $this->message = $message->load(['sender', 'replyTo.sender']);
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('conversation.' . $this->message->conversation_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'conversation_id' => $this->message->conversation_id,
            'sender' => [
                'id' => $this->message->sender->id,
                'name' => $this->message->sender->name,
                'username' => $this->message->sender->username,
                'profile_photo' => $this->message->sender->profile_photo,
            ],
            'type' => $this->message->type,
            'body' => $this->message->body,
            'metadata' => $this->message->metadata,
            'reply_to' => $this->message->replyTo ? [
                'id' => $this->message->replyTo->id,
                'body' => $this->message->replyTo->body,
                'sender_name' => $this->message->replyTo->sender->name,
            ] : null,
            'created_at' => $this->message->created_at->toIso8601String(),
        ];
    }
}
