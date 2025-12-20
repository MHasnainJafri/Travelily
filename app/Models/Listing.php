<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Listing extends Model implements HasMedia
{
    use InteractsWithMedia,HasFactory;

    protected $fillable = ['user_id', 'title', 'location', 'description', 'max_people', 'min_stay_days', 'num_rooms', 'price'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'amenity_listing');
    }

    public function houseRules()
    {
        return $this->belongsToMany(HouseRule::class, 'house_rule_listing');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('listing_photos')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif'])
            ->withResponsiveImages();
    }
}
