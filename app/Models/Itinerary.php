<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Itinerary extends Model
{
    use HasFactory;
    protected $fillable = ['jam_id', 'type', 'title', 'description', 'details', 'date'];

    protected $dates = ['date'];
   protected $casts = [
        'date' => 'date',
    ];
    public function board()
    {
        return $this->belongsTo(Jam::class);
    }
}
