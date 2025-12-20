<?php

namespace App\Services;

use App\Models\Jam;
use App\Models\JamInvitation;
use Illuminate\Support\Facades\Auth;

class JamInvitationService
{
public function sendInvitations($jamId, $userIds)
{
    $jam = Jam::findOrFail($jamId);

    // Check if the Jamboard is locked
    if ($jam->is_locked) {
        throw new \Exception('This Jamboard is locked and cannot be modified');
    }

    // Check if the sender has permission to invite (e.g., is the creator or has can_add_travelers permission)
    $senderId = Auth::id();
    $senderRole = $jam->users()->where('user_id', $senderId)->first();
    if (!$senderRole || ($senderRole->pivot->role !== 'creator' && !$senderRole->pivot->can_add_travelers)) {
        throw new \Exception('You do not have permission to invite users to this Jamboard');
    }

    $invitations = [];
    foreach ($userIds as $userId) {
        if ($userId == $senderId) {
            continue; // Skip inviting self
        }

        // Check if user is already in the Jamboard
        if ($jam->users()->where('user_id', $userId)->exists()) {
            continue;
        }

        // Check if an invitation already exists
        $existingInvitation = JamInvitation::where('jam_id', $jamId)
                                           ->where('receiver_id', $userId)
                                           ->where('status', 'pending')
                                           ->first();
        if ($existingInvitation) {
            continue;
        }

        $invitation = JamInvitation::create([
            'jam_id' => $jamId,
            'sender_id' => $senderId,
            'receiver_id' => $userId,
            'status' => 'pending',
        ]);

        $invitations[] = $invitation;
    }

    return $invitations;
}

    public function acceptInvitation($invitationId)
    {
        $userId = Auth::id();
        $invitation = JamInvitation::where('id', $invitationId)
                                   ->where('receiver_id', $userId)
                                   ->where('status', 'pending')
                                   ->firstOrFail();

        $invitation->update(['status' => 'accepted']);

        // Add the user to the jam_users table
        $jam = $invitation->jam;
        $jam->users()->attach($userId, [
            'role' => 'participant',
            'can_edit_jamboard' => false,
            'can_add_travelers' => false,
            'can_edit_budget' => false,
            'can_add_destinations' => false,
            'joined_at' => now(),
        ]);

        return $jam;
    }

    public function rejectInvitation($invitationId)
    {
        $userId = Auth::id();
        $invitation = JamInvitation::where('id', $invitationId)
                                   ->where('receiver_id', $userId)
                                   ->where('status', 'pending')
                                   ->firstOrFail();

        $invitation->update(['status' => 'rejected']);
        return true;
    }
    
}