<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function globalSearch(Request $request)
    {
        $query = $request->input('query');

        $users = DB::table('users')
            ->where('name', 'like', "%{$query}%")
            ->limit(10)
            ->get(['id', 'name', 'email']);

        $trips = DB::table('jams')
            ->where('title', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->limit(10)
            ->get();

        $posts = DB::table('posts')
            ->where('content', 'like', "%{$query}%")
            ->limit(10)
            ->get();

        return response()->json([
            'status' => true,
            'data' => [
                'users' => $users,
                'trips' => $trips,
                'posts' => $posts
            ]
        ]);
    }

    public function searchJamUsers($jamId, Request $request)
    {
        $query = $request->input('query');

        $users = DB::table('jam_users')
            ->where('jam_id', $jamId)
            ->join('users', 'jam_users.user_id', '=', 'users.id')
            ->where('users.name', 'like', "%{$query}%")
            ->select('users.id', 'users.name', 'users.email')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $users
        ]);
    }

    public function searchFriends(Request $request)
    {
        $query = $request->input('query');

        $friends = DB::table('friendships')
            ->where(function($q) {
                $q->where('user_id', auth()->id())
                  ->orWhere('friend_id', auth()->id());
            })
            ->where('status', 'accepted')
            ->join('users', function($join) {
                $join->on('friendships.user_id', '=', 'users.id')
                     ->orOn('friendships.friend_id', '=', 'users.id');
            })
            ->where('users.id', '!=', auth()->id())
            ->where('users.name', 'like', "%{$query}%")
            ->select('users.id', 'users.name', 'users.email')
            ->distinct()
            ->get();

        return response()->json([
            'status' => true,
            'data' => $friends
        ]);
    }

    public function searchGuides(Request $request)
    {
        $query = $request->input('query');

        $guides = DB::table('users')
            ->join('user_roles', 'users.id', '=', 'user_roles.user_id')
            ->where('user_roles.role', 'guide')
            ->where('users.name', 'like', "%{$query}%")
            ->select('users.id', 'users.name', 'users.email')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $guides
        ]);
    }
}
