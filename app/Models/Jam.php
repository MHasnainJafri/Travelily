<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Jam extends Model implements HasMedia
{
    use InteractsWithMedia,HasFactory;

    protected $fillable = ['creator_id', 'name', 'title', 'description', 'theme', 'start_date', 'end_date', 'budget', 'num_persons', 'is_locked', 'status','image','start_from','destination','destination_details'];

    protected $dates = ['start_date', 'end_date'];
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'destination_details'=>'json'
        
        
        
    ];

   

    public function members()
    {
        return $this->hasMany(JamUser::class);
    }

   

    // public function messages()
    // {
    //     return $this->hasMany(Message::class);
    // }

    public function taggedPosts()
    {
        return $this->belongsToMany(Post::class, 'post_board_tags')->withTimestamps();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('board_photos')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif'])
            ->withResponsiveImages();
    }
    public function guides()
    {
        return $this->hasMany(JamGuide::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'jam_users')
                    ->withPivot('role', 'can_edit_jamboard', 'can_add_travelers', 'can_edit_budget', 'can_add_destinations', 'joined_at')
                    ->withTimestamps();
    }

    public function itineraries()
    {
        return $this->hasMany(Itinerary::class);
    }

    public function flights()
    {
        return $this->hasMany(JamFlight::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
    // Add this to your existing Jam model
public function trip()
{
    return $this->hasOne(Trip::class);
}

public function conversation()
{
    return $this->hasOne(Conversation::class);
}
}
