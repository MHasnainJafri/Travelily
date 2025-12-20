<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Post extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia;

    protected $fillable = ['content', 'user_id', 'visibility', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function taggedUsers()
    {
        return $this->belongsToMany(User::class, 'post_user_tags')
            ->withTimestamps();
    }

    public function taggedBoards()
    {
        return $this->belongsToMany(Jam::class, 'post_board_tags')
            ->withTimestamps();
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'post_likes')
            ->withTimestamps();
    }

    public function labels()
    {
        return $this->belongsToMany(Label::class, 'post_labels')
            ->withTimestamps();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('post_photos')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif'])
            ->withResponsiveImages();
    }

    public function comments()
    {
        return $this->hasMany(PostComment::class);
    }

    public function checkIn()
    {
        return $this->hasOne(PostCheckIn::class);
    }
}
