<?php

// database/seeders/PlanSeeder.php

namespace Database\Seeders;

use App\Models\Feature;
use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run()
    {
        // Features
        $basicFeatures = ['Access to Basic Features', 'Email Support'];
        $premiumFeatures = array_merge($basicFeatures, ['Priority Support', 'Advanced Analytics']);

        // Create Plans
        $basic = Plan::create([
            'name' => 'Basic Plan',
            'stripe_price_id' => 'price_1R4cfBFWLM2dH4jV2Do6loEH', // Replace with actual Stripe Price ID
            'price' => 1000, // $10
            'currency' => 'usd',
            'description' => 'Basic subscription plan',
            'trial_days' => 7,
        ]);

        $premium = Plan::create([
            'name' => 'Premium Plan',
            'stripe_price_id' => 'price_1QebBrFWLM2dH4jVbiJYoxTy', // Replace with actual Stripe Price ID
            'price' => 2000, // $20
            'currency' => 'usd',
            'description' => 'Premium subscription plan',
            'trial_days' => 14,
        ]);

        // Attach Features
        foreach ($basicFeatures as $feature) {
            $f = Feature::firstOrCreate(['name' => $feature]);
            $basic->features()->attach($f);
        }

        foreach ($premiumFeatures as $feature) {
            $f = Feature::firstOrCreate(['name' => $feature]);
            $premium->features()->attach($f);
        }
    }
}
