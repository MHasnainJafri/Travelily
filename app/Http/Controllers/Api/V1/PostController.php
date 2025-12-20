<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Services\PostService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);
        $posts = $this->postService->getAllPosts($perPage, $page);

        return new PostCollection($posts);
    }

    public function show($id)
    {
        $post = $this->postService->getPost($id);

        return new PostResource($post);
    }

    public function store(Request $request)
    {
       
       
       
        $data = $request->validate([
            'content' => 'required|string',
            'media'=>'nullable',
            'files'=>'nullable',
            'media.*' => 'file|mimes:jpg,jpeg,png,mp4,mov,avi|max:10240',
            'tagged_users' => 'string|nullable',
            'location_name' => 'string|nullable',
            'latitude' => 'numeric|nullable',
            'longitude' => 'numeric|nullable',
            'visibility' => 'in:public,friends,selected',
            'status' => 'in:active,inactive,draft',
        ]);
        
        
      
        
        
        
        

        if (isset($data['tagged_users'])) {
            $data['tagged_users'] = json_decode($data['tagged_users'], true);
        }

        $request->validate([
            'tagged_users.*' => 'exists:users,id',
        ]);
            
        $post = $this->postService->createPost($data);

        return new PostResource($post);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'content' => 'string|nullable',
            'media.*' => 'file|mimes:jpg,jpeg,png,mp4,mov,avi|max:10240',
            'tagged_users' => 'string|nullable',
            'location_name' => 'string|nullable',
            'latitude' => 'numeric|nullable',
            'longitude' => 'numeric|nullable',
            'visibility' => 'in:public,friends,selected|nullable',
            'status' => 'in:active,inactive,draft|nullable',
        ]);

        if (isset($data['tagged_users'])) {
            $data['tagged_users'] = json_decode($data['tagged_users'], true);
        }

        $request->validate([
            'tagged_users.*' => 'exists:users,id',
        ]);

        $post = $this->postService->updatePost($id, $data);

        return new PostResource($post);
    }

    public function destroy($id)
    {
        $this->postService->deletePost($id);

        return response()->json(['message' => 'Post deleted successfully'], 200);
    }
}
