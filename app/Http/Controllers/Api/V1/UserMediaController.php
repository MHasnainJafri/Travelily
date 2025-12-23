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

    public function uploadVideo(Request $request)
    {
        $request->validate([
            'video' => 'required|mimes:mp4,mov,avi,wmv|max:51200', // Max 50MB
        ]);

        try {
            $user = Auth::user();
            
            // Remove old video if exists
            $user->clearMediaCollection('short_video');
            
            // Add new video
            $media = $user->addMedia($request->file('video'))
                          ->toMediaCollection('short_video', 'public');

            // Update profile with video URL
            \DB::table('user_profiles')
                ->where('user_id', $user->id)
                ->update([
                    'short_video' => $media->getUrl(),
                    'updated_at' => now()
                ]);

            return response()->json([
                'status' => true,
                'message' => 'Short video uploaded successfully',
                'video_url' => $media->getUrl()
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function deleteVideo()
    {
        try {
            $user = Auth::user();
            
            // Clear video collection
            $user->clearMediaCollection('short_video');
            
            // Clear from profile
            \DB::table('user_profiles')
                ->where('user_id', $user->id)
                ->update([
                    'short_video' => null,
                    'updated_at' => now()
                ]);

            return response()->json([
                'status' => true,
                'message' => 'Short video deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function deleteGalleryItem($mediaId)
    {
        try {
            $user = Auth::user();
            
            // Find and delete the specific media item
            $media = $user->getMedia('gallery')->where('id', $mediaId)->first();
            
            if (!$media) {
                return response()->json([
                    'status' => false,
                    'message' => 'Media not found'
                ], 404);
            }
            
            $media->delete();

            return response()->json([
                'status' => true,
                'message' => 'Gallery item deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}