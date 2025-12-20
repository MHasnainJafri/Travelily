<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;

class UserRecommendedPlace extends Model
{
    use HasSpatial;

    protected $fillable = ['user_id', 'place_name', 'address', 'coordinates', 'rank', 'google_place_id'];

    protected $casts = [
        'coordinates' => \MatanYadaev\EloquentSpatial\Objects\Point::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
