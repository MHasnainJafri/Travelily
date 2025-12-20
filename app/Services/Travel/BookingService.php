<?php

namespace App\Services\Travel;

use App\Models\Booking;
use Carbon\Carbon;

class BookingService
{
    public function create(array $data)
    {
        return Booking::create([
            'listing_id' => $data['listing_id'],
            'host_id' => $data['host_id'],
            'guest_id' => $data['guest_id'],
            'start_date' => Carbon::parse($data['start_date']),
            'end_date' => Carbon::parse($data['end_date']),
            'num_people' => $data['num_people'],
            'total_price' => $data['total_price'],
        ]);
    }

    public function updateStatus(Booking $booking, string $status)
    {
        return $booking->update(['status' => $status]);
    }

    public function getBookingsByGuest($guestId)
    {
        return Booking::where('guest_id', $guestId)->get();
    }

    public function getBookingsByHost($hostId)
    {
        return Booking::where('host_id', $hostId)->get();
    }
}
