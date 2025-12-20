<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripInvitation extends Model
{
    protected $fillable = ['trip_id', 'sender_id', 'receiver_id', 'status'];
}
