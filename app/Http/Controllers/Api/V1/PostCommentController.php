<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Http\Resources\CommentsCollection;
use App\Services\PostCommentService;
use Illuminate\Http\Request;

class PostCommentController extends Controller
{
    protected $commentService;

    public function __construct(PostCommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function index(Request $request, $postId)
    {
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);
        $comments = $this->commentService->getAllComments($postId, $perPage, $page);

        return new CommentsCollection($comments);
    }

    public function show($id)
    {
        $comment = $this->commentService->getComment($id);

        return new CommentResource($comment);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'post_id' => 'required|exists:posts,id',
            'parent_id' => 'nullable|exists:post_comments,id',
            'content' => 'required|string|max:5000',
        ]);

        $comment = $this->commentService->createComment($data);

        return new CommentResource($comment);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'content' => 'required|string|max:5000',
        ]);

        $comment = $this->commentService->updateComment($id, $data);

        return new CommentResource($comment);
    }

    public function destroy($id)
    {
        $this->commentService->deleteComment($id);

        return response()->json(['message' => 'Comment deleted successfully'], 200);
    }
}
