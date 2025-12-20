<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    protected $fillable = ['name'];

    public function listings()
    {
        return $this->belongsToMany(Listing::class, 'amenity_listing');
    }
}
