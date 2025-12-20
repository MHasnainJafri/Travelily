<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\PlanService;
use Illuminate\Http\Request;
use Mhasnainjafri\RestApiKit\API;

class PlanController extends Controller
{
    protected $service;

    public function __construct(PlanService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return inertia('plans/Index');
    }

    public function create()
    {
        return inertia('plans/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'stripe_price_id' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'description' => 'nullable|string',
            'trial_days' => 'nullable|integer|min:0',
        ]);

        $this->service->store($request->all());
        return redirect()->route('admin.plans.index')->with('success', 'Plan created successfully.');
    }

    public function show($id)
    {
        $record = $this->service->getRecord($id);
        return inertia('plans/View', ['record' => $record]);
    }

    public function edit($id)
    {
        $record = $this->service->getRecord($id);
        return inertia('plans/Edit', ['record' => $record]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'stripe_price_id' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'description' => 'nullable|string',
            'trial_days' => 'nullable|integer|min:0',
        ]);

        $this->service->update($id, $request->all());
        return redirect()->route('admin.plans.index')->with('success', 'Plan updated successfully.');
    }

    public function destroy($id)
    {
        $this->service->destroy($id);
        return redirect()->back()->with('success', 'Plan deleted successfully.');
    }

    public function getData()
    {
        $data = $this->service->getData();
        if ($data->isEmpty()) {
            return API::notFound('No plans found');
        }

        $data->getCollection()->transform(function ($plan) {
            return [
                'id' => $plan->id,
                'name' => $plan->name,
                'price' => $plan->currency . ' ' . number_format($plan->price, 2),
                'trial_days' => $plan->trial_days ?? 0,
                'features_count' => $plan->features->count(),
                'created_at' => $plan->created_at->diffForHumans(),
            ];
        });

        return API::paginated($data);
    }
}
