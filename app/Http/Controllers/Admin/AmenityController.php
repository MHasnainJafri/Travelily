<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\AmenityService;
use Illuminate\Http\Request;
use Mhasnainjafri\RestApiKit\API;

class AmenityController extends Controller
{
    protected $service;

    public function __construct(AmenityService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return inertia('amenities/Index');
    }

    public function create()
    {
        return inertia('amenities/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:amenities,name',
        ]);

        $this->service->store($request->all());
        return redirect()->route('admin.amenities.index')->with('success', 'Amenity created successfully.');
    }

    public function show($id)
    {
        $record = $this->service->getRecord($id);
        return inertia('amenities/View', ['record' => $record]);
    }

    public function edit($id)
    {
        $record = $this->service->getRecord($id);
        return inertia('amenities/Edit', ['record' => $record]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:amenities,name,' . $id,
        ]);

        $this->service->update($id, $request->all());
        return redirect()->route('admin.amenities.index')->with('success', 'Amenity updated successfully.');
    }

    public function destroy($id)
    {
        $this->service->destroy($id);
        return redirect()->back()->with('success', 'Amenity deleted successfully.');
    }

    public function getData()
    {
        $data = $this->service->getData();
        if ($data->isEmpty()) {
            return API::notFound('No amenities found');
        }

        $data->getCollection()->transform(function ($amenity) {
            return [
                'id' => $amenity->id,
                'name' => $amenity->name,
                'listings_count' => $amenity->listings_count,
                'created_at' => $amenity->created_at->diffForHumans(),
            ];
        });

        return API::paginated($data);
    }
}
