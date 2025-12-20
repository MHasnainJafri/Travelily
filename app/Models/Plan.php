<?php

// app/Models/Plan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = ['name', 'stripe_price_id', 'price', 'currency', 'description', 'trial_days'];

    public function features()
    {
        return $this->belongsToMany(Feature::class, 'plan_feature');
    }
}
