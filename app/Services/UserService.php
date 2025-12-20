<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserService
{
    public function getUserProfile($id = null)
    {
        $userId = $id ?? Auth::id();
        $user = User::with(['profile', 'receivedReviews.reviewer', 'media'])->findOrFail($userId);

        return [
            'user_profile' => [
                'id' => $user->id,
                'name' => $user->name,
                'profile_photo' => $user->profile_photo,
                'username' => $user->username,
                'followers' => $user->followers,
                'following' => $user->following,
                'trips_completed' => $user->trips_completed,
                'rating' => $user->profile->rating ?? 0,
                'location'=>$user->location
            ],
            'reviews' => $user->receivedReviews->map(function ($review) {
                return [
                    'id' => $review->id,
                    'reviewer' => [
                        'id' => $review->reviewer->id,
                        'name' => $review->reviewer->name,
                        'username' => $review->reviewer->username,
                    ],
                    'trip_id' => $review->trip_id,
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'created_at' => $review->created_at->toISOString(),
                ];
            }),
            'gallery_images' => $user->getMedia('gallery')->map(function ($media) {
                return [
                    'id' => $media->id,
                    'url' => $media->getUrl(),
                    'created_at' => $media->created_at->toISOString(),
                ];
            }),
        ];
    }
    public function userslist()
    {
        // $userId = $id ?? Auth::id();
        return $user = User::with(['profile', 'receivedReviews.reviewer', 'media'])->latest()->paginate();

        // return [
        //     'user_profile' => [
        //         'id' => $user->id,
        //         'name' => $user->name,
        //         'username' => $user->username,
        //         'followers' => $user->followers,
        //         'following' => $user->following,
        //         'trips_completed' => $user->trips_completed,
        //         'rating' => $user->profile->rating ?? 0,
        //     ],
        //     'reviews' => $user->receivedReviews->map(function ($review) {
        //         return [
        //             'id' => $review->id,
        //             'reviewer' => [
        //                 'id' => $review->reviewer->id,
        //                 'name' => $review->reviewer->name,
        //                 'username' => $review->reviewer->username,
        //             ],
        //             'trip_id' => $review->trip_id,
        //             'rating' => $review->rating,
        //             'comment' => $review->comment,
        //             'created_at' => $review->created_at->toISOString(),
        //         ];
        //     }),
        //     'gallery_images' => $user->getMedia('gallery')->map(function ($media) {
        //         return [
        //             'id' => $media->id,
        //             'url' => $media->getUrl(),
        //             'created_at' => $media->created_at->toISOString(),
        //         ];
        //     }),
        // ];
    }
}