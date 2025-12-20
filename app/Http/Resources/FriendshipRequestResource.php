<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FriendshipRequestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $isSender = $this->user_id === auth()->id();

        return [
            'id' => $this->id,
            'type' => $this->type,
            'status' => $this->status,
            'is_sender' => $isSender,
            'user' => $isSender ? [
                'id' => $this->receiver->id,
                'name' => $this->receiver->name,
                'username' => $this->receiver->username,
                'profile_photo' => $this->receiver->profile_photo,
                'roles' => $this->receiver->getRoleNames(),
            ] : [
                'id' => $this->sender->id,
                'name' => $this->sender->name,
                'username' => $this->sender->username,
                'profile_photo' => $this->sender->profile_photo,
                'roles' => $this->sender->getRoleNames(),
            ],
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
