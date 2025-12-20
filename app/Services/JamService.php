<?php

namespace App\Services;

use App\Models\Jam;
use App\Models\JamInvitation;
use Illuminate\Support\Facades\Auth;

class JamService
{
    public function createJam($data)
    {
        $jam = Jam::create([
            'creator_id' => Auth::id(),
            'name' => $data['name'],
            'destination' => $data['destination'] ?? null,
            'destination_details' => $data['destination_details'] ?? null,
            'start_date' => $data['start_date'] ?? null,
            'end_date' => $data['end_date'] ?? null,
            'budget_min' => $data['budget_min'] ?? null,
            'budget_max' => $data['budget_max'] ?? null,
            'num_guests' => $data['num_guests'] ?? 1,
            'image' => $data['image'] ?? null,
            'num_persons' => $data['num_persons'] ?? 1,
        ]);

        $jam->users()->attach(Auth::id(), [
            'role' => 'creator',
            'can_edit_jamboard' => true,
            'can_add_travelers' => true,
            'can_edit_budget' => true,
            'can_add_destinations' => true,
        ]);

        if (isset($data['participants'])) {
            foreach ($data['participants'] as $participantId) {
                if ($participantId != Auth::id()) {
                    $jam->users()->attach($participantId, [
                        'role' => 'participant',
                        'can_edit_jamboard' => false,
                        'can_add_travelers' => false,
                        'can_edit_budget' => false,
                        'can_add_destinations' => false,
                    ]);
                }
            }
        }

        $chatService = app(ChatService::class);
        $chatService->findOrCreateJamConversation($jam->id, Auth::id());

        return $jam->load('users');
    }

    public function getMyJams()
    {
        $userId = Auth::id();
        return Jam::whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->with('users', 'flights', 'itineraries')
            ->get();
    }
    public function getJams()
    {
        $userId = Auth::id();
        return Jam::whereHas('users', function ($query) use ($userId) {
            // $query->where('user_id', $userId);
        })
            ->with('users', 'flights', 'itineraries')
            ->paginate();
    }

    public function searchJams($query)
    {
        return Jam::where('name', 'like', "%{$query}%")
            ->where('destination', '!=', null) // Assuming public Jamboards have a destination
            ->with('creator', 'users', 'flights', 'itineraries')
            ->get();
    }

    public function sendJoinRequest($jamId)
    {
        $userId = Auth::id();
        $jam = Jam::findOrFail($jamId);

        // Check if user is not already a member
        if ($jam->users()->where('user_id', $userId)->exists()) {
            throw new \Exception('You are already a member of this Jamboard');
        }

        // Check if a request already exists (using jam_invitations table)
        $existingRequest = JamInvitation::where('jam_id', $jamId)->where('receiver_id', $userId)->where('status', 'pending')->first();
        if ($existingRequest) {
            throw new \Exception('Join request already sent');
        }

        // Send join request to the creator
        $creatorId = $jam->creator_id;
        $invitation = JamInvitation::create([
            'jam_id' => $jamId,
            'sender_id' => $userId,
            'receiver_id' => $creatorId,
            'status' => 'pending',
        ]);

        return $invitation;
    }
    public function getJamDetails($jamId)
    {
        $userId = Auth::id();
        $jam = Jam::where('id', $jamId)
            // ->whereHas('users', function ($query) use ($userId) {
            //     $query->where('user_id', $userId);
            // })
            ->with([
                'creator',
                'users' => function ($query) {
                    $query->select('users.id', 'name', 'username', 'email', 'profile_photo')->withPivot('role', 'can_edit_jamboard', 'can_add_travelers', 'can_edit_budget', 'can_add_destinations', 'joined_at');
                },
                'flights',
                'itineraries' => function ($query) {
                    $query->whereIn('type', ['accommodation', 'activity', 'experience']);
                },
                'guides',
                'tasks',
            ])
            ->firstOrFail();

        return $jam;
    }
    public function updateTripmatePermissions($jamId, $userId, $permissions)
    {
        $jam = Jam::findOrFail($jamId);

        // Check if the Jamboard is locked
        if ($jam->is_locked) {
            throw new \Exception('This Jamboard is locked and cannot be modified');
        }

        // Check if the authenticated user has permission to manage permissions (e.g., creator)
        $currentUserRole = $jam->users()->where('user_id', Auth::id())->first();
        if (!$currentUserRole || $currentUserRole->pivot->role !== 'creator') {
            throw new \Exception('Only the creator can manage tripmate permissions');
        }

        // Check if the user is part of the Jamboard
        $tripmate = $jam->users()->where('user_id', $userId)->first();
        if (!$tripmate) {
            throw new \Exception('User is not a member of this Jamboard');
        }

        // Update permissions
        $jam->users()->updateExistingPivot($userId, [
            'can_edit_jamboard' => $permissions['can_edit_jamboard'] ?? $tripmate->pivot->can_edit_jamboard,
            'can_add_travelers' => $permissions['can_add_travelers'] ?? $tripmate->pivot->can_add_travelers,
            'can_edit_budget' => $permissions['can_edit_budget'] ?? $tripmate->pivot->can_edit_budget,
            'can_add_destinations' => $permissions['can_add_destinations'] ?? $tripmate->pivot->can_add_destinations,
        ]);

        return $jam->users()->find($userId);
    }
    public function lockJam($jamId)
    {
        $jam = Jam::findOrFail($jamId);

        // Check if the authenticated user is the creator
        if ($jam->creator_id !== Auth::id()) {
            throw new \Exception('Only the creator can lock the Jamboard');
        }

        // Check if already locked
        if ($jam->is_locked) {
            throw new \Exception('Jamboard is already locked');
        }

        $jam->update(['is_locked' => true]);
        return $jam;
    }
}
