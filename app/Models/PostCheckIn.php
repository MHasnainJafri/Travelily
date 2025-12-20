<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostCheckIn extends Model
{
    use HasFactory;

    protected $fillable = ['post_id', 'location_name', 'latitude', 'longitude'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
