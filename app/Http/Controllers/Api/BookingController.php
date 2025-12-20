<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\Travel\BookingService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'listing_id' => 'required|exists:listings,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'num_people' => 'required|integer|min:1',
            'total_price' => 'required|numeric',
        ]);

        $booking = $this->bookingService->create([
            ...$validated,
            'guest_id' => auth()->id(),
            'host_id' => $request->host_id,
        ]);

        return response()->json($booking, 201);
    }

    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $this->bookingService->updateStatus($booking, $request->status);

        return response()->json($booking);
    }
}
