<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;
use Stripe\Customer;
use Stripe\PaymentMethod;
use Stripe\Stripe;

class CardController extends Controller
{
    public function addCard(Request $request)
    {
        $request->validate([
            'stripe_payment_method_id' => 'required|string',
        ]);

        $user = $request->user();

        // Initialize Stripe
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // Check if the user has a Stripe customer ID
        if (empty($user->stripe_id)) {
            try {
                // Attempt to find an existing Stripe customer by email
                $existingCustomers = Customer::all(['email' => $user->email]);
                if (! empty($existingCustomers->data)) {
                    $customer = $existingCustomers->data[0];
                    $user->stripe_id = $customer->id;
                } else {
                    // Create a new Stripe customer
                    $customer = Customer::create([
                        'email' => $user->email,
                    ]);
                    $user->stripe_id = $customer->id;
                }
                $user->save();
            } catch (\Exception $e) {
                return response()->json(['error' => 'Failed to create or retrieve Stripe customer: '.$e->getMessage()], 500);
            }
        }

        try {
            // Attach the payment method to the customer
            $paymentMethod = PaymentMethod::retrieve($request->stripe_payment_method_id);
            $paymentMethod->attach(['customer' => $user->stripe_id]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to attach payment method: '.$e->getMessage()], 400);
        }

        // Save card details to the database
        $card = Card::create([
            'user_id' => $user->id,
            'stripe_payment_method_id' => $paymentMethod->id,
            'brand' => $paymentMethod->card->brand,
            'last4' => $paymentMethod->card->last4,
            'exp_month' => $paymentMethod->card->exp_month,
            'exp_year' => $paymentMethod->card->exp_year,
            'is_default' => false,
        ]);

        return response()->json(['card' => $card], 201);
    }

    public function deleteCard(Card $card)
    {
        $user = auth()->user();

        if ($card->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Initialize Stripe
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // Detach the payment method from the customer
        PaymentMethod::retrieve($card->stripe_payment_method_id)->detach();

        // Delete the card from the database
        $card->delete();

        return response()->json(['message' => 'Card deleted successfully']);
    }

    public function listCards(Request $request)
    {
        $cards = $request->user()->cards;

        return response()->json(['cards' => $cards]);
    }
}
