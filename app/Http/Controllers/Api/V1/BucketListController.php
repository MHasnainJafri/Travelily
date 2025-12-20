<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\BucketListResource;
use App\Services\BucketListService;
use Illuminate\Http\Request;

class BucketListController extends Controller
{
    protected $bucketListService;

    public function __construct(BucketListService $bucketListService)
    {
        $this->bucketListService = $bucketListService;
    }

    public function getBucketLists()
    {
        try {
            $bucketLists = $this->bucketListService->getBucketLists();
            return BucketListResource::collection($bucketLists);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function getBucketList($id)
    {
        try {
            $bucketList = $this->bucketListService->getBucketList($id);
            return new BucketListResource($bucketList);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function createBucketList(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048', // Max 2MB per image
        ]);

        try {
            $bucketList = $this->bucketListService->createBucketList($data);
            return new BucketListResource($bucketList);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function updateBucketList(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048', // Max 2MB per image
        ]);

        try {
            $bucketList = $this->bucketListService->updateBucketList($id, $data);
            return new BucketListResource($bucketList);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function deleteBucketList($id)
    {
        try {
            $this->bucketListService->deleteBucketList($id);
            return response()->json(['message' => 'Bucket list deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
}