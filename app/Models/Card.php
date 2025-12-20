<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'stripe_payment_method_id',
        'brand',
        'last4',
        'exp_month',
        'exp_year',
        'is_default',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'exp_month' => 'integer',
        'exp_year' => 'integer',
        'is_default' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
