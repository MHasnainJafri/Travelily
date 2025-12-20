<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JamGuideResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'jam_id' => $this->jam_id,
            'user_id' => $this->user_id,
            'role' => $this->role,
            'contact_info' => $this->contact_info,
            'user' => new UserResource($this->user),
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];
    }
}