<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JamFlight extends Model {
use HasFactory;
    
    // Fillable attributes for mass assignment
    protected $fillable = [
        'jam_id',
        'mode_of_transportation',
        'from',
        'to',
        'date',
        'departure_time',
        'arrival_time',
    ];
      protected $casts = [
        'date' => 'date',
    ];

    // Define the relationship with the Jam model
    public function jam(): BelongsTo
    {
        return $this->belongsTo(Jam::class);
    }
}
