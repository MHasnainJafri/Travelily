<?php

namespace App\Services;

use App\Models\PostComment;
use Illuminate\Support\Facades\Auth;

class PostCommentService
{
    public function getAllComments($postId, $perPage = 10, $page = 1)
    {
        return PostComment::where('post_id', $postId)
            ->whereNull('parent_id') // Only top-level comments
            ->with(['user', 'replies' => function ($query) {
                $query->with('user')->limit(2); // Limit to 2 replies per comment
            }])
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function getComment($commentId)
    {
        return PostComment::with('user', 'replies.user')->findOrFail($commentId);
    }

    public function createComment($data)
    {
        $comment = PostComment::create([
            'post_id' => $data['post_id'],
            'user_id' => Auth::id(),
            'parent_id' => $data['parent_id'] ?? null,
            'content' => $data['content'],
        ]);

        return $comment->load('user', 'replies.user');
    }

    public function updateComment($commentId, $data)
    {
        $comment = PostComment::findOrFail($commentId);
        $comment->update([
            'content' => $data['content'] ?? $comment->content,
        ]);

        return $comment->load('user', 'replies.user');
    }

    public function deleteComment($commentId)
    {
        $comment = PostComment::findOrFail($commentId);
        $comment->delete(); // Cascades to replies due to migration setup

        return true;
    }
}
