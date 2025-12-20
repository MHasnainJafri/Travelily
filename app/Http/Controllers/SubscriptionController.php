<?php

// app/Http/Controllers/SubscriptionController.php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Stripe\StripeClient;

class SubscriptionController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'card_id' => 'required|exists:cards,id',
        ]);

        $user = auth()->user();
        $plan = Plan::findOrFail($request->plan_id);
        $card = Card::where('id', $request->card_id)->where('user_id', $user->id)->first();

        if (! $card) {
            return response()->json(['error' => 'Invalid card ID.'], 404);
        }

        $stripe = new StripeClient(env('STRIPE_SECRET'));

        try {
            // Create a Stripe subscription
            $subscription = $stripe->subscriptions->create([
                'customer' => $user->stripe_id,
                'items' => [
                    ['price' => $plan->stripe_price_id],
                ],
                'default_payment_method' => $card->stripe_payment_method_id,
                'trial_period_days' => $plan->trial_days,
            ]);

            // Store the subscription in the database
            $dbSubscription = Subscription::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'card_id' => $card->id,
                'stripe_subscription_id' => $subscription->id,
                'stripe_status' => $subscription->status,
                'trial_ends_at' => $subscription->trial_end ? now()->addSeconds($subscription->trial_end - time()) : null,
                'ends_at' => null,
            ]);

            return response()->json([
                'message' => 'Subscription created successfully.',
                'subscription' => $dbSubscription,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getCurrentSubscription(Request $request)
    {
        $user = $request->user();

        // Get the active subscription
        $subscription = Subscription::where('user_id', $user->id)
            ->with(['plan', 'card'])
            ->whereNull('ends_at')
            ->first();

        if (! $subscription) {
            return response()->json(['error' => 'No active subscription found.'], 404);
        }

        return response()->json([
            'subscription' => $subscription,
            // 'plan' => $subscription->plan,
            // 'card' => $subscription->card,
        ]);
    }
}
