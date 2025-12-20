<?php

namespace App\Services;

use App\Models\BucketList;
use App\Models\BucketListImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BucketListService
{
    public function createBucketList($data)
    {
        $userId = Auth::id();
        $bucketList = BucketList::create([
            'user_id' => $userId,
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        if (isset($data['images'])) {
            foreach ($data['images'] as $image) {
                $path = $image->store('bucket_list_images', 'public');
                BucketListImage::create([
                    'bucket_list_id' => $bucketList->id,
                    'image_path' => $path,
                ]);
            }
        }

        return $bucketList->load('images');
    }

    public function getBucketLists()
    {
        $userId = Auth::id();
        return BucketList::where('user_id', $userId)->with('images')->get();
    }

    public function getBucketList($id)
    {
        $userId = Auth::id();
        $bucketList = BucketList::where('user_id', $userId)->findOrFail($id);
        return $bucketList->load('images');
    }

    public function updateBucketList($id, $data)
    {
        $userId = Auth::id();
        $bucketList = BucketList::where('user_id', $userId)->findOrFail($id);

        $bucketList->update([
            'name' => $data['name'] ?? $bucketList->name,
            'description' => $data['description'] ?? $bucketList->description,
        ]);

        if (isset($data['images'])) {
            // Delete existing images
            $bucketList->images()->delete();
            Storage::disk('public')->deleteDirectory("bucket_list_images/{$bucketList->id}");

            // Upload new images
            foreach ($data['images'] as $image) {
                $path = $image->store("bucket_list_images/{$bucketList->id}", 'public');
                BucketListImage::create([
                    'bucket_list_id' => $bucketList->id,
                    'image_path' => $path,
                ]);
            }
        }

        return $bucketList->load('images');
    }

    public function deleteBucketList($id)
    {
        $userId = Auth::id();
        $bucketList = BucketList::where('user_id', $userId)->findOrFail($id);

        // Delete images
        $bucketList->images()->delete();
        Storage::disk('public')->deleteDirectory("bucket_list_images/{$bucketList->id}");

        $bucketList->delete();
        return true;
    }
}