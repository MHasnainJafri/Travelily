<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserCollection extends \Illuminate\Http\Resources\Json\ResourceCollection
{
    public $collects = UserResource::class;

    public function toArray($request)
    {
        return [
            'data' => $this->collection,
        ];
    }
}
