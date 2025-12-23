<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = ['bookable_type', 'bookable_id', 'host_id', 'guest_id', 'start_date', 'end_date', 'num_people', 'total_price', 'status'];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::updated(function ($booking) {
            if ($booking->status === 'approved' && $booking->getOriginal('status') !== 'approved') {
                $host = $booking->host;

                // Credit the host's wallet with the booking amount
                $host->deposit($booking->total_price, [
                    'description' => "Payment for booking #{$booking->id}",
                    'booking_id' => $booking->id,
                ]);
            }
        });
    }

    public function bookable()
    {
        return $this->morphTo();
    }

    public function host()
    {
        return $this->belongsTo(User::class, 'host_id');
    }

    public function guest()
    {
        return $this->belongsTo(User::class, 'guest_id');
    }
}
