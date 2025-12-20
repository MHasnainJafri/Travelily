<?php

namespace App\Services\Admin;

use App\Models\BucketList;
use App\Helper\DataTableActions;

class BucketListService
{
    use DataTableActions;

    public function getData()
    {
        $query = BucketList::query()->with(['user', 'images']);

        return $this->getProcessedData($query, request()->input('per_page', 10));
    }

    public function getRecord($id)
    {
        return BucketList::with(['user', 'images'])->findOrFail($id);
    }

    public function destroy($id)
    {
        $bucketList = BucketList::findOrFail($id);
        $bucketList->delete();
    }
}
