<?php
namespace App\Models;
use App\Models\Jam;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trip extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'jamboard_name', 'destination', 'destination_details',
        'start_date', 'end_date', 'time', 'looking_for',
        'image', 'user_id', 'jam_id'
    ];

    protected $casts = [
        'destination_details' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // === CORE RELATIONS ===
    public function user() { return $this->belongsTo(User::class); }
    public function jam()  { return $this->belongsTo(Jam::class); }

    // === DELEGATED JAM RELATIONS (BEST WAY) ===
    public function users()         { return $this->jam->users(); }
    public function members()       { return $this->jam->members(); }
    public function itineraries()   { return $this->jam->itineraries(); }
    public function flights()       { return $this->jam->flights(); }
    public function tasks()         { return $this->jam->tasks(); }
    public function guides()        { return $this->jam->guides(); }
    public function taggedPosts()   { return $this->jam->taggedPosts(); }
    public function creator()       { return $this->jam->creator(); }

    // === MEDIA (from Jam) ===
    public function getBoardPhotos()
    {
        return $this->jam?->getMedia('board_photos') ?? collect();
    }

    public function getFirstBoardPhotoUrl($conversion = '')
    {
        return $this->jam?->getFirstMediaUrl('board_photos', $conversion);
    }

    // === CONVENIENCE ===
    public function isUserInJam($userId)
    {
        return $this->jam?->users()->where('user_id', $userId)->exists();
    }
}