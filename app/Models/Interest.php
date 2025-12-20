<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interest extends Model
{
    protected $fillable = ['name'];

    public function advertisements()
    {
        return $this->belongsToMany(Advertisement::class, 'advertisement_interest');
    }

    // Relationships
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_interests', 'interest_id', 'user_id')
            ->withTimestamps();
    }

    public function buddyUsers()
    {
        return $this->belongsToMany(User::class, 'user_buddy_interests', 'buddy_interest_id', 'user_id')
            ->withTimestamps();
    }
}
