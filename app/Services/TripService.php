<?php
namespace App\Services;

use App\Models\Trip;
use App\Models\Jam;
use App\Models\TripInvitation; // You'll need this table
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TripService
{
    public function createTrip($data)
    {
        return DB::transaction(function () use ($data) {
            // 1. Create Trip
            // $trip = Trip::create([
            //     'jamboard_name' => $data['jamboard_name'],
            //     'destination' => $data['destination'],
            //     // 'destination_details' => $data['destination_details'],
            //     'start_date' => $data['start_date'] ?? null,
            //     'end_date' => $data['end_date'] ?? null,
            //     'time' => $data['time'] ?? null,
            //     'looking_for' => $data['looking_for'],
            //     'user_id' => Auth::id(),
            // ]);

            // 2. Create Jamboard
            $jam = Jam::create([
                'creator_id' => Auth::id(),
                'name' => $data['jamboard_name'],
                'destination' => $data['destination'],
                'start_from' => $data['start_from'],
                'destination_details' => $data['destination_details']??null,
                'start_date' => $data['start_date'] ?? null,
                'end_date' => $data['end_date'] ?? null,
                'num_persons' => 1,
                'is_locked' => false,
                'status' => 'active',
            ]);

            // 3. Link creator to Jamboard
            $jam->users()->attach(Auth::id(), [
                'role' => 'creator',
                'can_edit_jamboard' => true,
                'can_add_travelers' => true,
                'can_edit_budget' => true,
                'can_add_destinations' => true,
            ]);

            // 4. Link Trip → Jam
            // $trip->update(['jam_id' => $jam->id]);
            

            // 5. Upload image to Jamboard
            if (isset($data['image'])) {
                $media = $jam->addMedia($data['image'])->toMediaCollection('board_photos');

    // Update the 'image' column with the URL (or file name) of the uploaded media
    $jam->update([
        'image' => $media->getUrl(), // or use getPath() if you want the local path
    ]);
            }

            return $jam;
        });
    }

    public function getMyTrips()
    {
        $userId = Auth::id();
        return Trip::where('user_id', $userId)
            ->with('jam', 'jam.users', 'jam.flights', 'jam.itineraries')
            ->get();
    }

    public function searchTrips($query)
    {
        return Trip::where('destination', 'like', "%{$query}%")
            ->orWhere('jamboard_name', 'like', "%{$query}%")
            ->whereHas('jam', fn($q) => $q->where('destination', '!=', null))
            ->with('jam.creator', 'jam.users', 'jam.flights', 'jam.itineraries')
            ->get();
    }

    public function sendJoinRequest($tripId)
    {
        $userId = Auth::id();
        $trip = Trip::findOrFail($tripId);

        if (!$trip->jam) {
            throw new \Exception('This trip has no Jamboard');
        }

        if ($trip->jam->users()->where('user_id', $userId)->exists()) {
            throw new \Exception('You are already a member of this Jamboard');
        }

        $existing = TripInvitation::where('trip_id', $tripId)
            ->where('receiver_id', $userId)
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            throw new \Exception('Join request already sent');
        }

        $creatorId = $trip->jam->creator_id;

        $invitation = TripInvitation::create([
            'trip_id' => $tripId,
            'sender_id' => $userId,
            'receiver_id' => $creatorId,
            'status' => 'pending',
        ]);

        return $invitation;
    }

    public function getTripDetails($tripId)
    {
        $userId = Auth::id();
        $trip = Trip::where('id', $tripId)
            ->whereHas('jam.users', fn($q) => $q->where('user_id', $userId))
            ->with([
                'jam.creator',
                'jam.users' => fn($q) => $q->select('users.id', 'name', 'username', 'email', 'profile_photo')
                    ->withPivot('role', 'can_edit_jamboard', 'can_add_travelers', 'can_edit_budget', 'can_add_destinations', 'joined_at'),
                'jam.flights',
                'jam.itineraries' => fn($q) => $q->whereIn('type', ['accommodation', 'activity', 'experience']),
                'jam.guides',
                'jam.tasks',
            ])
            ->firstOrFail();

        return $trip;
    }

    public function updateTripmatePermissions($tripId, $userId, $permissions)
    {
        $trip = Trip::findOrFail($tripId);
        if (!$trip->jam) throw new \Exception('Jamboard not found');

        $jam = $trip->jam;

        if ($jam->is_locked) {
            throw new \Exception('This Jamboard is locked');
        }

        $currentUser = $jam->users()->where('user_id', Auth::id())->first();
        if (!$currentUser || $currentUser->pivot->role !== 'creator') {
            throw new \Exception('Only the creator can manage permissions');
        }

        $tripmate = $jam->users()->where('user_id', $userId)->first();
        if (!$tripmate) {
            throw new \Exception('User is not a member');
        }

        $jam->users()->updateExistingPivot($userId, [
            'can_edit_jamboard' => $permissions['can_edit_jamboard'] ?? $tripmate->pivot->can_edit_jamboard,
            'can_add_travelers' => $permissions['can_add_travelers'] ?? $tripmate->pivot->can_add_travelers,
            'can_edit_budget' => $permissions['can_edit_budget'] ?? $tripmate->pivot->can_edit_budget,
            'can_add_destinations' => $permissions['can_add_destinations'] ?? $tripmate->pivot->can_add_destinations,
        ]);

        return $jam->users()->find($userId);
    }

    public function lockTrip($tripId)
    {
        $trip = Trip::findOrFail($tripId);
        if (!$trip->jam) throw new \Exception('Jamboard not found');

        $jam = $trip->jam;
        if ($jam->creator_id !== Auth::id()) {
            throw new \Exception('Only the creator can lock the Jamboard');
        }
        if ($jam->is_locked) {
            throw new \Exception('Jamboard is already locked');
        }

        $jam->update(['is_locked' => true]);
        return $trip->load('jam');
    }
}