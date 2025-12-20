<?php
// app/Http/Resources/TripResource.php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TripResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'jamboard_name' => $this->jamboard_name,
            'destination' => $this->destination,
            'destination_details' => $this->destination_details,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'time' => $this->time,
            'looking_for' => $this->looking_for,
            'board_photo' => $this->jam?->getFirstMediaUrl('board_photos'),

            'jam' => $this->whenLoaded('jam', fn() => [
                'id' => $this->jam->id,
                'creator' => $this->jam->creator,
                'users' => $this->jam->users,
                'itineraries' => $this->jam->itineraries,
                'flights' => $this->jam->flights,
                'tasks' => $this->jam->tasks,
                'guides' => $this->jam->guides,
                'is_locked' => $this->jam->is_locked,
            ]),
        ];
    }
}