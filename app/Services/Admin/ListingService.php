<?php

namespace App\Services\Admin;

use App\Models\Listing;
use App\Helper\DataTableActions;

class ListingService
{
    use DataTableActions;

    public function getData()
    {
        $query = Listing::query()->with(['user', 'amenities', 'houseRules', 'media']);

        return $this->getProcessedData($query, request()->input('per_page', 10));
    }

    public function getRecord($id)
    {
        return Listing::with(['user', 'amenities', 'houseRules', 'media', 'bookings'])->findOrFail($id);
    }

    public function destroy($id)
    {
        $listing = Listing::findOrFail($id);
        $listing->delete();
    }
}
