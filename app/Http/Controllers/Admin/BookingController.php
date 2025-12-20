<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\BookingService;
use Illuminate\Http\Request;
use Mhasnainjafri\RestApiKit\API;

class BookingController extends Controller
{
    protected $service;

    public function __construct(BookingService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return inertia('bookings/Index');
    }

    public function show($id)
    {
        $record = $this->service->getRecord($id);
        return inertia('bookings/View', ['record' => $record]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:pending,approved,rejected,cancelled']);
        $this->service->updateStatus($id, $request->status);
        return redirect()->back()->with('success', 'Booking status updated successfully.');
    }

    public function destroy($id)
    {
        $this->service->destroy($id);
        return redirect()->back()->with('success', 'Booking deleted successfully.');
    }

    public function getData()
    {
        $data = $this->service->getData();
        if ($data->isEmpty()) {
            return API::notFound('No bookings found');
        }

        $data->getCollection()->transform(function ($booking) {
            return [
                'id' => $booking->id,
                'host' => $booking->host?->name,
                'guest' => $booking->guest?->name,
                'start_date' => $booking->start_date?->format('M d, Y'),
                'end_date' => $booking->end_date?->format('M d, Y'),
                'num_people' => $booking->num_people,
                'total_price' => '$' . number_format($booking->total_price, 2),
                'status' => $booking->status,
                'created_at' => $booking->created_at->diffForHumans(),
            ];
        });

        return API::paginated($data);
    }
}
