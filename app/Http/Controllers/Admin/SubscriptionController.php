<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\SubscriptionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Mhasnainjafri\RestApiKit\API;

class SubscriptionController extends Controller
{
    protected $service;

    public function __construct(SubscriptionService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return inertia('subscriptions/Index');
    }

    public function show($id)
    {
        $record = $this->service->getRecord($id);
        return inertia('subscriptions/View', ['record' => $record]);
    }

    public function destroy($id)
    {
        $this->service->destroy($id);
        return redirect()->back()->with('success', 'Subscription deleted successfully.');
    }

    public function getData()
    {
        $data = $this->service->getData();
        if ($data->isEmpty()) {
            return API::notFound('No subscriptions found');
        }

        $data->getCollection()->transform(function ($subscription) {
            return [
                'id' => $subscription->id,
                'user' => $subscription->user?->name,
                'plan' => $subscription->plan?->name,
                'stripe_status' => $subscription->stripe_status,
                'trial_ends_at' => $subscription->trial_ends_at ? Carbon::parse($subscription->trial_ends_at)->format('M d, Y') : null,
                'ends_at' => $subscription->ends_at ? Carbon::parse($subscription->ends_at)->format('M d, Y') : null,
                'created_at' => Carbon::parse($subscription->created_at)->diffForHumans(),
            ];
        });

        return API::paginated($data);
    }
}
