<?php

namespace App\Services\Admin;

use App\Models\Advertisement;
use App\Helper\DataTableActions;

class AdvertisementService
{
    use DataTableActions;
    public function getData()
    {
        $query = Advertisement::query();

        return $data = $this->getProcessedData($query, request()->input('per_page', 10));
    }
}
