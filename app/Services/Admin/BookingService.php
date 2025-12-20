<?php

namespace App\Services\Admin;

use App\Models\Booking;
use App\Helper\DataTableActions;

class BookingService
{
    use DataTableActions;

    public function getData()
    {
        $query = Booking::query()->with(['host', 'guest', 'bookable']);

        return $this->getProcessedData($query, request()->input('per_page', 10));
    }

    public function getRecord($id)
    {
        return Booking::with(['host', 'guest', 'bookable'])->findOrFail($id);
    }

    public function updateStatus($id, $status)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => $status]);
        return $booking;
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();
    }
}
