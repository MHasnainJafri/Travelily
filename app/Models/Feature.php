<?php

// app/Models/Feature.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    protected $fillable = ['name', 'description'];

    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'plan_feature');
    }
}
