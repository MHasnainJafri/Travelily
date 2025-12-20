<?php

// app/Models/Subscription.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'card_id',
        'stripe_subscription_id',
        'stripe_status',
        'trial_ends_at',
        'ends_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function card()
    {
        return $this->belongsTo(Card::class);
    }
}
