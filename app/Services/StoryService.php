<?php

namespace App\Services;

use App\Models\Story;
use Illuminate\Support\Facades\Auth;

class StoryService
{
    public function getActiveStories()
    {
        $userId = Auth::id();

        return Story::with('user', 'media')
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->where(function ($query) use ($userId) {
                $query->where('visibility', 'public')
                    ->orWhere(function ($q) use ($userId) {
                        $q->where('visibility', 'friends')
                            ->whereHas('user', function ($subQuery) use ($userId) {
                                $subQuery->whereHas('friends', function ($friendQuery) use ($userId) {
                                    $friendQuery->where('friend_id', $userId)
                                        ->where('status', 'accepted');
                                });
                            });
                    })
                    ->orWhere('visibility', 'selected'); // Extend with selected_users logic later
            })
            ->get();
    }

    public function createStory($data)
    {
        $story = Story::create([
            'user_id' => Auth::id(),
            'content' => $data['content'] ?? null,
            'visibility' => $data['visibility'] ?? 'public',
            'status' => $data['status'] ?? 'active',
            'expires_at' => now()->addHours(24),
        ]);

        if (isset($data['media'])) {
            foreach ($data['media'] as $media) {
                $story->addMedia($media)->toMediaCollection('story_media');
            }
        }

        return $story->load('user', 'media');
    }

    public function updateStory($storyId, $data)
    {
        $story = Story::findOrFail($storyId);
        $story->update([
            'content' => $data['content'] ?? $story->content,
            'visibility' => $data['visibility'] ?? $story->visibility,
            'status' => $data['status'] ?? $story->status,
        ]);

        if (isset($data['media'])) {
            $story->clearMediaCollection('story_media');
            foreach ($data['media'] as $media) {
                $story->addMedia($media)->toMediaCollection('story_media');
            }
        }

        return $story->load('user', 'media');
    }

    public function deleteStory($storyId)
    {
        $story = Story::findOrFail($storyId);
        $story->delete();

        return true;
    }
}
