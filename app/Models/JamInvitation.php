<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JamInvitation extends Model
{
    use HasFactory;

    protected $fillable = ['jam_id', 'sender_id', 'receiver_id', 'status'];

    public function jam()
    {
        return $this->belongsTo(Jam::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}