<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function getUserReviews($userId, Request $request)
    {
        $sort = $request->input('sort', 'newest');
        
        $query = DB::table('reviews')
            ->where('reviewed_user_id', $userId)
            ->join('users', 'reviews.reviewer_id', '=', 'users.id')
            ->select('reviews.*', 'users.name as reviewer_name');

        if ($sort === 'newest') {
            $query->orderBy('reviews.created_at', 'desc');
        } elseif ($sort === 'top_rated') {
            $query->orderBy('reviews.rating', 'desc');
        } elseif ($sort === 'positive') {
            $query->where('reviews.rating', '>', 4);
        }

        $reviews = $query->get();

        return response()->json([
            'status' => true,
            'data' => $reviews
        ]);
    }

    public function getTourReviews($tourId, Request $request)
    {
        $sort = $request->input('sort', 'newest');
        
        $query = DB::table('reviews')
            ->where('reviewable_type', 'experience')
            ->where('reviewable_id', $tourId)
            ->join('users', 'reviews.reviewer_id', '=', 'users.id')
            ->select('reviews.*', 'users.name as reviewer_name');

        if ($sort === 'newest') {
            $query->orderBy('reviews.created_at', 'desc');
        } elseif ($sort === 'top_rated') {
            $query->orderBy('reviews.rating', 'desc');
        }

        $reviews = $query->get();

        return response()->json([
            'status' => true,
            'data' => $reviews
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reviewed_user_id' => 'nullable|exists:users,id',
            'reviewable_type' => 'nullable|string',
            'reviewable_id' => 'nullable|integer',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $reviewId = DB::table('reviews')->insertGetId([
            'reviewer_id' => auth()->id(),
            'reviewed_user_id' => $validated['reviewed_user_id'] ?? null,
            'reviewable_type' => $validated['reviewable_type'] ?? null,
            'reviewable_id' => $validated['reviewable_id'] ?? null,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Review submitted successfully',
            'data' => ['id' => $reviewId]
        ]);
    }
}
