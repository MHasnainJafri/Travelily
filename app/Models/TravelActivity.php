<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelActivity extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    // Relationships
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_travel_activities', 'travel_activity_id', 'user_id')
            ->withTimestamps();
    }
}
