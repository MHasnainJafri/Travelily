<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HouseRule extends Model
{
    protected $fillable = ['name'];

    public function listings()
    {
        return $this->belongsToMany(Listing::class, 'house_rule_listing');
    }
}
