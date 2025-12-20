<?php
namespace App\Repositories;

use App\Models\Trip;
use App\Models\Jam;

class TripRepository
{
    public function create(array $data, $userId)
    {
        // 1. Create Trip
        $trip = Trip::create(array_merge($data, ['user_id' => $userId]));

        // 2. Create Jamboard
        $jam = Jam::create([
            'creator_id' => $userId,
            'name' => $data['jamboard_name'],
            'destination' => $data['destination'],
            'start_date' => $data['start_date'] ?? null,
            'end_date' => $data['end_date'] ?? null,
            'num_persons' => 1,
            'is_locked' => false,
            'status' => 'active',
        ]);

        // 3. Link creator
        $jam->users()->attach($userId, [
            'role' => 'creator',
            'can_edit_jamboard' => true,
            'can_add_travelers' => true,
            'can_edit_budget' => true,
            'can_add_destinations' => true,
        ]);

        // 4. Link trip → jam
        $trip->update(['jam_id' => $jam->id]);

        // 5. Upload image to Jamboard (if provided)
        if (isset($data['image'])) {
            $jam->addMedia($data['image'])->toMediaCollection('board_photos');
        }

        return $trip->load('jam');
    }

    public function find($id)
    {
        return Trip::with('jam', 'jam.users', 'jam.itineraries', 'jam.flights')->findOrFail($id);
    }

    public function update(Trip $trip, array $data)
    {
        $trip->update($data);

        // Sync Jamboard name/dates if changed
        if ($trip->jam) {
            $trip->jam->update([
                'name' => $data['jamboard_name'] ?? $trip->jam->name,
                'start_date' => $data['start_date'] ?? $trip->jam->start_date,
                'end_date' => $data['end_date'] ?? $trip->jam->end_date,
            ]);
        }

        return $trip->load('jam');
    }

    public function delete(Trip $trip)
    {
        // Optional: delete Jamboard too?
        // $trip->jam?->delete();
        return $trip->delete();
    }
}