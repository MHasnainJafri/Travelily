<?php

namespace App\Services\Travel;

use App\Models\Trip;
use App\Services\BaseService;

class TripService extends BaseService
{
    public function __construct(Trip $trip)
    {
        $this->model = $trip;
    }

    public function getUserTrips($userId)
    {
        return $this->model->where('guide_id', $userId)->get();
    }

    public function updateStatus($tripId, $status)
    {
        return $this->update($tripId, ['status' => $status]);
    }
}
