<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FriendshipCollection extends ResourceCollection
{
    public $collects = FriendshipResource::class;

    public function toArray($request)
    {
        return [
            'data' => $this->collection,
        ];
    }
}
