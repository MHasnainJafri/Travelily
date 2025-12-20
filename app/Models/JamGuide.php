<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JamGuide extends Model
{
    use HasFactory;

    protected $fillable = ['jam_id', 'user_id', 'role', 'contact_info'];

    public function jam()
    {
        return $this->belongsTo(Jam::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}