<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BucketListImageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'bucket_list_id' => $this->bucket_list_id,
            'image_path' => asset('storage/' . $this->image_path),
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];
    }
}