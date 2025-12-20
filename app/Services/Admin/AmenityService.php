<?php

namespace App\Services\Admin;

use App\Models\Amenity;
use App\Helper\DataTableActions;

class AmenityService
{
    use DataTableActions;

    public function getData()
    {
        $query = Amenity::query()->withCount('listings');

        return $this->getProcessedData($query, request()->input('per_page', 10));
    }

    public function getRecord($id)
    {
        return Amenity::with(['listings'])->findOrFail($id);
    }

    public function store(array $data)
    {
        return Amenity::create($data);
    }

    public function update($id, array $data)
    {
        $amenity = Amenity::findOrFail($id);
        $amenity->update($data);
        return $amenity;
    }

    public function destroy($id)
    {
        $amenity = Amenity::findOrFail($id);
        $amenity->delete();
    }
}
