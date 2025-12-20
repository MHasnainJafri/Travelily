<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['reviewed_user_id', 'reviewer_id', 'trip_id', 'rating', 'comment'];

    public function scopeNewest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function reviewedUser()
    {
        return $this->belongsTo(User::class, 'reviewed_user_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function trip()
    {
        return $this->belongsTo(Jam::class, 'trip_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($review) {
            $reviewedUser = $review->reviewedUser;
            if ($reviewedUser && $reviewedUser->profile) {
                $averageRating = $reviewedUser->receivedReviews()->avg('rating') ?? 0;
                $reviewedUser->profile->update(['rating' => round($averageRating, 1)]);
            }
        });

        static::updated(function ($review) {
            $reviewedUser = $review->reviewedUser;
            if ($reviewedUser && $reviewedUser->profile) {
                $averageRating = $reviewedUser->receivedReviews()->avg('rating') ?? 0;
                $reviewedUser->profile->update(['rating' => round($averageRating, 1)]);
            }
        });
    }
}