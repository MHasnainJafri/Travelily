<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JamFlightResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'jam_id' => $this->jam_id,
            'mode_of_transportation' => $this->mode_of_transportation,
            'from' => $this->from,
            'to' => $this->to,
            'date' => $this->date->toDateString(),
            'departure_time' => $this->departure_time,
            'arrival_time' => $this->arrival_time,
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];
    }
}