<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\InterestService;
use Illuminate\Http\Request;
use Mhasnainjafri\RestApiKit\API;

class InterestController extends Controller
{
    protected $service;

    public function __construct(InterestService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return inertia('interests/Index');
    }

    public function create()
    {
        return inertia('interests/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:interests,name',
        ]);

        $this->service->store($request->all());
        return redirect()->route('admin.interests.index')->with('success', 'Interest created successfully.');
    }

    public function show($id)
    {
        $record = $this->service->getRecord($id);
        return inertia('interests/View', ['record' => $record]);
    }

    public function edit($id)
    {
        $record = $this->service->getRecord($id);
        return inertia('interests/Edit', ['record' => $record]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:interests,name,' . $id,
        ]);

        $this->service->update($id, $request->all());
        return redirect()->route('admin.interests.index')->with('success', 'Interest updated successfully.');
    }

    public function destroy($id)
    {
        $this->service->destroy($id);
        return redirect()->back()->with('success', 'Interest deleted successfully.');
    }

    public function getData()
    {
        $data = $this->service->getData();
        if ($data->isEmpty()) {
            return API::notFound('No interests found');
        }

        $data->getCollection()->transform(function ($interest) {
            return [
                'id' => $interest->id,
                'name' => $interest->name,
                'users_count' => $interest->users_count,
                'buddy_users_count' => $interest->buddy_users_count,
                'advertisements_count' => $interest->advertisements_count,
                'created_at' => $interest->created_at->diffForHumans(),
            ];
        });

        return API::paginated($data);
    }
}
