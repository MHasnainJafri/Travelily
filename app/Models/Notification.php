<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['recipient_id', 'sender_id', 'type', 'notifiable_type', 'notifiable_id', 'message', 'read'];

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function notifiable()
    {
        return $this->morphTo();
    }
}
