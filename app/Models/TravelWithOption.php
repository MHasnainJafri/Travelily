<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelWithOption extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    // Relationships
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_travel_with_options', 'travel_with_option_id', 'user_id')
            ->withTimestamps();
    }
}
