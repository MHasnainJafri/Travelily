<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\BucketListService;
use Illuminate\Http\Request;
use Mhasnainjafri\RestApiKit\API;

class BucketListController extends Controller
{
    protected $service;

    public function __construct(BucketListService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return inertia('buckets/Index');
    }

    public function show($id)
    {
        $record = $this->service->getRecord($id);
        return inertia('buckets/View', ['record' => $record]);
    }

    public function destroy($id)
    {
        $this->service->destroy($id);
        return redirect()->back()->with('success', 'Bucket list deleted successfully.');
    }

    public function getData()
    {
        $data = $this->service->getData();
        if ($data->isEmpty()) {
            return API::notFound('No bucket lists found');
        }

        $data->getCollection()->transform(function ($bucket) {
            return [
                'id' => $bucket->id,
                'name' => $bucket->name,
                'description' => $bucket->description,
                'user' => $bucket->user?->name,
                'images_count' => $bucket->images->count(),
                'created_at' => $bucket->created_at->diffForHumans(),
            ];
        });

        return API::paginated($data);
    }
}
