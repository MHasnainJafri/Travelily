<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Advertisement extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['user_id', 'title', 'duration_days', 'locations', 'age_ranges', 'genders', 'relationships'];

    protected $casts = [
        'locations' => 'array',
        'age_ranges' => 'array',
        'genders' => 'array',
        'relationships' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function interests()
    {
        return $this->belongsToMany(Interest::class, 'advertisement_interest');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('ad_photos')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif'])
            ->withResponsiveImages();
    }
}
