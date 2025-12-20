<?php

namespace App\Services\Admin;

use App\Models\Interest;
use App\Helper\DataTableActions;

class InterestService
{
    use DataTableActions;

    public function getData()
    {
        $query = Interest::query()->withCount(['users', 'buddyUsers', 'advertisements']);

        return $this->getProcessedData($query, request()->input('per_page', 10));
    }

    public function getRecord($id)
    {
        return Interest::with(['users', 'buddyUsers', 'advertisements'])->findOrFail($id);
    }

    public function store(array $data)
    {
        return Interest::create($data);
    }

    public function update($id, array $data)
    {
        $interest = Interest::findOrFail($id);
        $interest->update($data);
        return $interest;
    }

    public function destroy($id)
    {
        $interest = Interest::findOrFail($id);
        $interest->delete();
    }
}
