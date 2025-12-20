<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JamResource extends JsonResource
{
    protected function isMember()
    {
        $user = auth()->user();
        
        if (!$user) {
            return false;
        }
        
        if ($this->creator_id === $user->id) {
            return true;
        }
        
        if ($this->relationLoaded('users')) {
            return $this->users->contains('id', $user->id);
        }
        
        return $this->resource->users()->where('user_id', $user->id)->exists();
    }

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'creator_id' => $this->creator_id,
            'name' => $this->name,
            'destination' => $this->destination,
            'destination_details'=>$this->destination_details,
            'start_date' => $this->start_date ? $this->start_date->toDateString() : null,
            'end_date' => $this->end_date ? $this->end_date->toDateString() : null,
            'budget_min' => $this->budget_min,
            'budget_max' => $this->budget_max,
            'num_guests' => $this->num_guests,
            'image' => $this->image,
            'num_persons' => $this->num_persons,
            'is_member' => $this->isMember(),
            'creator' => new UserResource($this->whenLoaded('creator')),
            'users' => UserResource::collection($this->whenLoaded('users')),
            'flights' => JamFlightResource::collection($this->whenLoaded('flights')),
            'accommodations' => ItineraryResource::collection(
                $this->whenLoaded(
                    'itineraries',
                    function () {
                        return $this->itineraries->where('type', 'accommodation');
                    },
                    collect(),
                ),
            ),
            'activities' => ItineraryResource::collection(
                $this->whenLoaded(
                    'itineraries',
                    function () {
                        return $this->itineraries->where('type', 'activity');
                    },
                    collect(),
                ),
            ),
            'experiences' => ItineraryResource::collection(
                $this->whenLoaded(
                    'itineraries',
                    function () {
                        return $this->itineraries->where('type', 'experience');
                    },
                    collect(),
                ),
            ),

            'guides' => JamGuideResource::collection($this->whenLoaded('guides')),
            'tasks' => TaskResource::collection($this->whenLoaded('tasks')),
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];
    }
}
