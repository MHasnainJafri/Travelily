<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\StoryCollection;
use App\Http\Resources\StoryResource;
use App\Services\StoryService;
use Illuminate\Http\Request;

class StoryController extends Controller
{
    protected $storyService;

    public function __construct(StoryService $storyService)
    {
        $this->storyService = $storyService;
    }

    public function index()
    {
        $stories = $this->storyService->getActiveStories();

        return new StoryCollection($stories);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'content' => 'string|nullable',
            'media.*' => 'file|mimes:jpg,jpeg,png,mp4,mov,avi|max:10240',
            'visibility' => 'in:public,friends,selected',
            'status' => 'in:active,inactive',
        ]);

        $story = $this->storyService->createStory($data);

        return new StoryResource($story);
    }

    public function show($id)
    {
        $story = Story::with('user', 'media')->findOrFail($id);

        return new StoryResource($story);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'content' => 'string|nullable',
            'media.*' => 'file|mimes:jpg,jpeg,png,mp4,mov,avi|max:10240',
            'visibility' => 'in:public,friends,selected|nullable',
            'status' => 'in:active,inactive|nullable',
        ]);

        $story = $this->storyService->updateStory($id, $data);

        return new StoryResource($story);
    }

    public function destroy($id)
    {
        $this->storyService->deleteStory($id);

        return response()->json(['message' => 'Story deleted successfully'], 200);
    }
}
