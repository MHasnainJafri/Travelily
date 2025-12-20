<?php

namespace App\Services;

use App\Models\Post;
use App\Models\PostCheckIn;
use Illuminate\Support\Facades\Auth;

class PostService
{
    public function getAllPosts($perPage = 10, $page = 1)
    {
        return Post::with(['taggedUsers', 'checkIn', 'comments' => function ($query) {
            $query->with('user')->limit(2);
        }, 'comments.replies' => function ($query) {
            $query->with('user')->limit(1);
        }, 'media'])->where('status', 'active')->latest()->paginate($perPage, ['*'], 'page', $page);
    }

    public function getPost($postId)
    {
        return Post::with(['taggedUsers', 'checkIn', 'comments' => function ($query) {
            $query->with('user', 'replies.user');
        }, 'media'])->findOrFail($postId);
    }

    public function createPost($data)
    {
        $post = Post::create([
            'content' => $data['content'],
            'user_id' => Auth::id(),
            'visibility' => $data['visibility'] ?? 'public',
            'status' => $data['status'] ?? 'draft',
        ]);
 
        if (isset($data['media'])) {
            if(is_array($data['media'])){
            foreach ($data['media'] as $media) {
                $post->addMedia($media)->toMediaCollection('post_media');
            }
            }else{
            $post->addMedia($data['media'])->toMediaCollection('post_media');
   
            }
        }
        
        if (isset($data['files'])) {
            if(is_array($data['files'])){
            foreach ($data['files'] as $media) {
                $post->addMedia($media)->toMediaCollection('post_media');
            }
            }else{
            $post->addMedia($data['files'])->toMediaCollection('post_media');
   
            }
        }
        
        
        
       


        if (isset($data['tagged_users'])) {
            $post->taggedUsers()->sync($data['tagged_users']);
        }

        if (isset($data['location_name'])) {
            PostCheckIn::create([
                'post_id' => $post->id,
                'location_name' => $data['location_name'],
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
            ]);
        }

        return $post->load('taggedUsers', 'checkIn', 'media');
    }

    public function updatePost($postId, $data)
    {
        $post = Post::findOrFail($postId);
        $post->update([
            'content' => $data['content'] ?? $post->content,
            'visibility' => $data['visibility'] ?? $post->visibility,
            'status' => $data['status'] ?? $post->status,
        ]);

        if (isset($data['tagged_users'])) {
            $post->taggedUsers()->sync($data['tagged_users']);
        }

        if (isset($data['media'])) {
            $post->clearMediaCollection('post_media');
            foreach ($data['media'] as $media) {
                $post->addMedia($media)->toMediaCollection('post_media');
            }
        }

        return $post->load('taggedUsers', 'checkIn', 'media');
    }

    public function deletePost($postId)
    {
        $post = Post::findOrFail($postId);
        $post->delete();

        return true;
    }
}
