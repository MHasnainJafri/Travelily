<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;
    protected $fillable = ['bio', 'facebook', 'tiktok', 'linkedin', 'short_video', 'local_expert_place_name', 'local_expert_google_place_id', 'rating', 'followers_count', 'petals_count', 'trips_count'];
}
