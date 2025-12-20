<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Story extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['user_id', 'content', 'visibility', 'status', 'expires_at'];

    protected $dates = ['expires_at'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('story_media')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'video/mp4', 'video/quicktime', 'video/x-msvideo']);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
