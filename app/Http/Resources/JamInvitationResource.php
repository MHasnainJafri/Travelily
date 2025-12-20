<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JamInvitationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'jam_id' => $this->jam_id,
            'sender_id' => $this->sender_id,
            'receiver_id' => $this->receiver_id,
            'status' => $this->status,
            'sender' => new UserResource($this->sender),
            'receiver' => new UserResource($this->receiver),
            'jam' => new JamResource($this->jam),
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];
    }
}