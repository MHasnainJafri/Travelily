<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserMediaController extends Controller
{
    public function addToGallery(Request $request)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,gif|max:2048', // Max 2MB per image
        ]);

        try {
            $user = Auth::user();

            foreach ($request->file('images') as $image) {
                $user->addMedia($image)
                     ->toMediaCollection('gallery', 'public');
            }

            return response()->json([
                'message' => 'Images added to gallery successfully',
                'gallery_images' => $user->getMedia('gallery')->map(function ($media) {
                    return [
                        'id' => $media->id,
                        'url' => $media->getUrl(),
                        'created_at' => $media->created_at->toISOString(),
                    ];
                }),
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}