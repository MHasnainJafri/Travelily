<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray($request)
    {
        return [
          'id' => $this->id,
        'jam_id' => $this->jam_id,
        'title' => $this->title,
        'description' => $this->description,
        'status' => $this->status,
        'due_date' => $this->due_date ? $this->due_date : null,
        'assignees' => UserResource::collection($this->whenLoaded('assignees')),
        'created_at' => $this->created_at->toISOString(),
        'updated_at' => $this->updated_at->toISOString(),
        ];
    }
}