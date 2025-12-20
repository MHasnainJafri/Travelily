<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Experience extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['user_id', 'title', 'description', 'location', 'start_date', 'end_date', 'min_price', 'max_price'];

    protected $dates = ['start_date', 'end_date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Optional: Define a media collection for photos
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('experience_photos')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif'])
            ->withResponsiveImages(); // Optional: Generate responsive images
    }
}
