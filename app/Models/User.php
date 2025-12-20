<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

// use Bavix\Wallet\Traits\CanPay as WalletCanPay;
use Bavix\Wallet\Interfaces\Wallet as WalletInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable as CashierBillable;
use Laravel\Passport\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
    // , WalletInterface
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use CashierBillable, HasApiTokens, HasFactory,
        // WalletCanPay,
        HasRoles, InteractsWithMedia, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['id', 'username', 'name', 'email', 'email_verified_at', 'profile_photo', 'password', 'two_factor_secret', 'two_factor_recovery_codes', 'two_factor_confirmed_at', 'remember_token', 'created_at', 'updated_at','location'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'id' => 'integer',
        'email_verified_at' => 'datetime',
        'two_factor_confirmed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'password' => 'hashed',
        'location'=>'array'

    ];

public function getLocationAttribute($value)
{
    $location = json_decode($value, true);

    return [
        'lat' => $location['lat'] ?? null,
        'lng' => $location['lng'] ?? null,
        'accuracy' => $location['accuracy'] ?? null,
        'updated_at' => $location['updated_at'] ?? null,
    ];
}

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->username)) {
                $user->username = self::generateUniqueUsername($user->name);
            }
        });
    }
    
    public function updateLocation($lat, $lng, $accuracy = null)
{
    $this->update([
        'location' => [
            'lat' => $lat,
            'lng' => $lng,
            'accuracy' => $accuracy,
            'updated_at' => now()->toDateTimeString(),
        ]
    ]);
}


    private static function generateUniqueUsername($name)
    {
        $baseUsername = preg_replace('/[^a-zA-Z0-9]/', '', strtolower($name));
        $username = $baseUsername;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = $baseUsername.$counter++;
        }

        return $username;
    }

    public function linkedSocialAccounts()
    {
        return $this->hasMany(SocialAccount::class);
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }
       public function userProfile()
    {
        return $this->hasOne(UserProfile::class);
    }

    // Relationships
    public function interests()
    {
        return $this->belongsToMany(Interest::class, 'user_interests', 'user_id', 'interest_id')
            ->withTimestamps();
    }

    public function buddyInterests()
    {
        return $this->belongsToMany(Interest::class, 'user_buddy_interests', 'user_id', 'buddy_interest_id')
            ->withTimestamps();
    }

    public function travelActivities()
    {
        return $this->belongsToMany(TravelActivity::class, 'user_travel_activities', 'user_id', 'travel_activity_id')
            ->withTimestamps();
    }

    public function travelWithOptions()
    {
        return $this->belongsToMany(TravelWithOption::class, 'user_travel_with_options', 'user_id', 'travel_with_option_id')
            ->withTimestamps();
    }

    public function traveledPlaces(): HasMany
    {
        return $this->hasMany(UserVisitedPlace::class);
    }

    public function recommendedPlaces(): HasMany
    {
        return $this->hasMany(UserRecommendedPlace::class);
    }

    public function review(): HasMany
    {
        return $this->hasMany(Review::class, 'reviewed_user_id', 'id');
    }

    public function friendsOfMine(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_id', 'friend_id')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function friendOf(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friendships', 'friend_id', 'user_id')
            ->withPivot('status')
            ->withTimestamps();
    }

    // This returns only accepted mutual friends
    public function friends(): Collection
    {
        $friendsOfMine = $this->friendsOfMine()->wherePivot('status', 'accepted')->get();
        $friendOf = $this->friendOf()->wherePivot('status', 'accepted')->get();

        return $friendsOfMine->merge($friendOf);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'user_id');
    }

      public function receivedReviews()
    {
        return $this->hasMany(Review::class, 'reviewed_user_id');
    }

    public function writtenReviews()
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class);
    }

    public function advertisements()
    {
        return $this->hasMany(Advertisement::class);
    }

    public function listings()
    {
        return $this->hasMany(Listing::class);
    }

    public function bookingsAsHost()
    {
        return $this->hasMany(Booking::class, 'host_id');
    }

    public function bookingsAsGuest()
    {
        return $this->hasMany(Booking::class, 'guest_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'recipient_id');
    }

    public function sentNotifications()
    {
        return $this->hasMany(Notification::class, 'sender_id');
    }

    public function boards()
    {
        return $this->hasMany(Jam::class, 'creator_id');
    }

    public function boardMemberships()
    {
        return $this->hasMany(JamUser::class);
    }

    // public function messages()
    // {
    //     return $this->hasMany(Message::class);
    // }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function taggedPosts()
    {
        return $this->belongsToMany(Post::class, 'post_user_tags')
            ->withTimestamps();
    }

    public function likedPosts()
    {
        return $this->belongsToMany(Post::class, 'post_likes')
            ->withTimestamps();
    }

    public function visitedPlaces()
    {
        return $this->hasMany(UserVisitedPlace::class);
    }

    public function cards()
    {
        return $this->hasMany(Card::class);
    }
     public function jams()
    {
        return $this->belongsToMany(Jam::class, 'jam_users')
                    ->withPivot('role', 'can_edit_jamboard', 'can_add_travelers', 'can_edit_budget', 'can_add_destinations', 'joined_at')
                    ->withTimestamps();
    }


     public function invitations()
    {
        return $this->hasMany(JamInvitation::class, 'receiver_id');
    }

     public function registerMediaCollections(): void
    {
        $this->addMediaCollection('gallery')
             ->useDisk('public')
             ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif']);
    }

    //   public function friends()
    // {
    //     return $this->belongsToMany(User::class, 'friendships', 'user_id', 'friend_id')
    //                 ->where('status', 'accepted')
    //                 ->withTimestamps();
    // }

    public function conversations()
    {
        return $this->belongsToMany(Conversation::class, 'conversation_participants')
            ->withPivot(['role', 'last_read_at', 'is_muted', 'is_pinned', 'joined_at', 'left_at'])
            ->withTimestamps();
    }

    public function activeConversations()
    {
        return $this->conversations()->whereNull('conversation_participants.left_at');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }
}
