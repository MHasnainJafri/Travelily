<?php

namespace App\Services\Admin;

use App\Models\Plan;
use App\Helper\DataTableActions;

class PlanService
{
    use DataTableActions;

    public function getData()
    {
        $query = Plan::query()->with(['features']);

        return $this->getProcessedData($query, request()->input('per_page', 10));
    }

    public function getRecord($id)
    {
        return Plan::with(['features'])->findOrFail($id);
    }

    public function store(array $data)
    {
        return Plan::create($data);
    }

    public function update($id, array $data)
    {
        $plan = Plan::findOrFail($id);
        $plan->update($data);
        return $plan;
    }

    public function destroy($id)
    {
        $plan = Plan::findOrFail($id);
        $plan->delete();
    }
}
