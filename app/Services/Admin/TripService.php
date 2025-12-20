<?php

namespace App\Services\Admin;

use App\Models\Trip;
use App\Helper\DataTableActions;

class TripService
{
    use DataTableActions;

    public function getData()
    {
        $query = Trip::query()->with(['user', 'jam']);

        return $this->getProcessedData($query, request()->input('per_page', 10));
    }

    public function getRecord($id)
    {
        return Trip::with(['user', 'jam.members', 'jam.itineraries', 'jam.flights', 'jam.tasks'])->findOrFail($id);
    }

    public function destroy($id)
    {
        $trip = Trip::findOrFail($id);
        $trip->delete();
    }
}
