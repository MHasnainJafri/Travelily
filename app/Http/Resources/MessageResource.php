<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $userId = auth()->id();

        return [
            'id' => $this->id,
            'conversation_id' => $this->conversation_id,
            'type' => $this->type,
            'body' => $this->body,
            'metadata' => $this->metadata,
            'sender' => [
                'id' => $this->sender->id,
                'name' => $this->sender->name,
                'username' => $this->sender->username,
                'profile_photo' => $this->sender->profile_photo,
            ],
            'is_own_message' => (int) $this->sender_id === (int) $userId,
            'reply_to' => $this->when($this->relationLoaded('replyTo') && $this->replyTo, function () {
                return [
                    'id' => $this->replyTo->id,
                    'body' => $this->replyTo->body,
                    'type' => $this->replyTo->type,
                    'sender' => [
                        'id' => $this->replyTo->sender->id,
                        'name' => $this->replyTo->sender->name,
                    ],
                ];
            }),
            'is_edited' => $this->is_edited,
            'edited_at' => $this->when($this->is_edited, function () {
                return $this->edited_at?->toIso8601String();
            }),
            'read_by' => $this->when($this->relationLoaded('reads'), function () {
                return $this->reads->map(function ($read) {
                    return [
                        'user_id' => $read->user_id,
                        'read_at' => $read->read_at->toIso8601String(),
                    ];
                });
            }),
            'read_count' => $this->when($this->relationLoaded('reads'), function () {
                return $this->reads->count();
            }),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
