<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\ListingService;
use Illuminate\Http\Request;
use Mhasnainjafri\RestApiKit\API;

class ListingController extends Controller
{
    protected $service;

    public function __construct(ListingService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return inertia('listings/Index');
    }

    public function show($id)
    {
        $record = $this->service->getRecord($id);
        return inertia('listings/View', ['record' => $record]);
    }

    public function destroy($id)
    {
        $this->service->destroy($id);
        return redirect()->back()->with('success', 'Listing deleted successfully.');
    }

    public function getData()
    {
        $data = $this->service->getData();
        if ($data->isEmpty()) {
            return API::notFound('No listings found');
        }

        $data->getCollection()->transform(function ($listing) {
            return [
                'id' => $listing->id,
                'title' => $listing->title,
                'location' => $listing->location,
                'price' => $listing->price,
                'max_people' => $listing->max_people,
                'num_rooms' => $listing->num_rooms,
                'host' => $listing->user?->name,
                'image' => $listing->getFirstMediaUrl('listing_photos'),
                'created_at' => $listing->created_at->diffForHumans(),
            ];
        });

        return API::paginated($data);
    }
}
