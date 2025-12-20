<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ItineraryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'jam_id' => $this->jam_id,
            'type' => $this->type,
            'title' => $this->title,
            'description' => $this->description,
            'details' => json_decode($this->details, true),
            'date' => $this->date ? $this->date->toDateString() : null,
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];
    }
}