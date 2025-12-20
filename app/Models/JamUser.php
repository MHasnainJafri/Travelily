<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JamUser extends Model
{
    use HasFactory;
    protected $fillable = [
        'jam_id',
        'user_id',
        'role',
        'can_edit_jamboard',
        'can_add_travellers',
        'can_edit_budget',
        'can_add_destinations',
    ];

    public function board()
    {
        return $this->belongsTo(Jam::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
