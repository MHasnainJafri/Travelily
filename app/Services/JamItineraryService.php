<?php

namespace App\Services;

use App\Models\Jam;
use App\Models\JamFlight;
use App\Models\Itinerary;
use Illuminate\Support\Facades\Auth;

class JamItineraryService
{

    public function addFlight($jamId, $data)
{
    $jam = Jam::findOrFail($jamId);

    // Check if the Jamboard is locked
    if ($jam->is_locked) {
        throw new \Exception('This Jamboard is locked and cannot be modified');
    }

    // Check if the user has permission (e.g., creator or can_edit_jamboard)
    $userRole = $jam->users()->where('user_id', Auth::id())->first();
   
    if (!$userRole || ($userRole->pivot->role !== 'creator' && !$userRole->pivot->can_edit_jamboard)) {
        throw new \Exception('You do not have permission to add flights to this Jamboard');
    }

    $data = array_merge($data, ['jam_id' => $jamId]);
    return JamFlight::create($data);
}

public function addAccommodation($jamId, $data)
{
    $jam = Jam::findOrFail($jamId);

    // Check if the Jamboard is locked
    if ($jam->is_locked) {
        throw new \Exception('This Jamboard is locked and cannot be modified');
    }

    // Check if the user has permission
    $userRole = $jam->users()->where('user_id', Auth::id())->first();
    if (!$userRole || ($userRole->pivot->role !== 'creator' && !$userRole->pivot->can_edit_jamboard)) {
        throw new \Exception('You do not have permission to add accommodations to this Jamboard');
    }

    $itinerary = Itinerary::create([
        'jam_id' => $jamId,
        'type' => 'accommodation',
        'title' => $data['title'] ?? $data['type'],
        'description' => $data['comments'] ?? null,
        'details' => json_encode([
            'location' => $data['location'] ?? null,
            'type' => $data['type'] ?? null,
            'time' => $data['time'] ?? null,
        ]),
        'date' => $data['date'] ?? null,
    ]);

    return $itinerary;
}

public function addActivity($jamId, $data)
{
    $jam = Jam::findOrFail($jamId);

    // Check if the Jamboard is locked
    if ($jam->is_locked) {
        throw new \Exception('This Jamboard is locked and cannot be modified');
    }

    // Check if the user has permission
    $userRole = $jam->users()->where('user_id', Auth::id())->first();
    if (!$userRole || ($userRole->pivot->role !== 'creator' && !$userRole->pivot->can_edit_jamboard)) {
        throw new \Exception('You do not have permission to add activities to this Jamboard');
    }

    $itinerary = Itinerary::create([
        'jam_id' => $jamId,
        'type' => 'activity',
        'title' => $data['title'] ?? $data['category'],
        'description' => $data['comments'] ?? null,
        'details' => json_encode([
            'location' => $data['location'] ?? null,
            'category' => $data['category'] ?? null,
            'time' => $data['time'] ?? null,
        ]),
        'date' => $data['date'] ?? null,
    ]);

    return $itinerary;
}

public function addExperience($jamId, $data)
{
    $jam = Jam::findOrFail($jamId);

    // Check if the Jamboard is locked
    if ($jam->is_locked) {
        throw new \Exception('This Jamboard is locked and cannot be modified');
    }

    // Check if the user has permission
    $userRole = $jam->users()->where('user_id', Auth::id())->first();
    if (!$userRole || ($userRole->pivot->role !== 'creator' && !$userRole->pivot->can_edit_jamboard)) {
        throw new \Exception('You do not have permission to add experiences to this Jamboard');
    }

    $itinerary = Itinerary::create([
        'jam_id' => $jamId,
        'type' => 'experience',
        'title' => $data['title'] ?? $data['category'],
        'description' => $data['description'] ?? null,
        'details' => json_encode([
            'location' => $data['location'] ?? null,
            'category' => $data['category'] ?? null,
            'time' => $data['time'] ?? null,
        ]),
        'date' => $data['date'] ?? null,
    ]);

    return $itinerary;
}

}