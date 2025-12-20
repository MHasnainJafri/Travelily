<?php

namespace App\Services\Admin;

use App\Models\Subscription;
use App\Helper\DataTableActions;

class SubscriptionService
{
    use DataTableActions;

    public function getData()
    {
        $query = Subscription::query()->with(['user', 'plan', 'card']);

        return $this->getProcessedData($query, request()->input('per_page', 10));
    }

    public function getRecord($id)
    {
        return Subscription::with(['user', 'plan', 'card'])->findOrFail($id);
    }

    public function destroy($id)
    {
        $subscription = Subscription::findOrFail($id);
        $subscription->delete();
    }
}
