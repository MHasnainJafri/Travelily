<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\TripService;
use Illuminate\Http\Request;
use Mhasnainjafri\RestApiKit\API;

class TripController extends Controller
{
    protected $service;

    public function __construct(TripService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return inertia('trips/Index');
    }

    public function show($id)
    {
        $record = $this->service->getRecord($id);
        return inertia('trips/View', ['record' => $record]);
    }

    public function destroy($id)
    {
        $this->service->destroy($id);
        return redirect()->back()->with('success', 'Trip deleted successfully.');
    }

    public function getData()
    {
        $data = $this->service->getData();
        if ($data->isEmpty()) {
            return API::notFound('No trips found');
        }

        $data->getCollection()->transform(function ($trip) {
            return [
                'id' => $trip->id,
                'jamboard_name' => $trip->jamboard_name,
                'destination' => $trip->destination,
                'start_date' => $trip->start_date?->format('M d, Y'),
                'end_date' => $trip->end_date?->format('M d, Y'),
                'looking_for' => $trip->looking_for,
                'user' => $trip->user?->name,
                'created_at' => $trip->created_at->diffForHumans(),
            ];
        });

        return API::paginated($data);
    }
}
