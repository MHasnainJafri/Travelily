<?php

// app/Http/Controllers/PlanController.php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::with('features')->get();

        return response()->json(['plan' => $plans]);
    }

    public function show($id)
    {
        $plan = Plan::with('features')->findOrFail($id);

        return response()->json($plan);
    }

    public function store(Request $request)
    {
        $plan = Plan::create($request->only(['name', 'stripe_price_id', 'price', 'currency', 'description', 'trial_days']));
        $plan->features()->sync($request->input('features', []));

        return response()->json($plan);
    }

    public function update(Request $request, $id)
    {
        $plan = Plan::findOrFail($id);
        $plan->update($request->only(['name', 'stripe_price_id', 'price', 'currency', 'description', 'trial_days']));
        $plan->features()->sync($request->input('features', []));

        return response()->json($plan);
    }

    public function destroy($id)
    {
        $plan = Plan::findOrFail($id);
        $plan->delete();

        return response()->json(['message' => 'Plan deleted successfully.']);
    }
}
