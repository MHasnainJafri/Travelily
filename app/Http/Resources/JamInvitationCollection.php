<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JamInvitationCollection extends \Illuminate\Http\Resources\Json\ResourceCollection
{public $collects = JamInvitationResource::class;

    public function toArray($request)
    {
        return [
            'data' => $this->collection,
        ];
    }
}