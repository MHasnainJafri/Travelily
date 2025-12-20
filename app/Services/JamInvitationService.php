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
        $invitation = JamInvitation::findOrFail($invitationId);

        // Check if invitation is pending
        if ($invitation->status !== 'pending') {
            throw new \Exception('This invitation is no longer pending');
        }

        // Case 1: User accepting an invitation sent to them (receiver accepts)
        // Case 2: Creator accepting a join request (receiver is creator, sender is requester)
        if ($invitation->receiver_id !== $userId) {
            throw new \Exception('You do not have permission to accept this invitation');
        }

        $invitation->update(['status' => 'accepted']);

        $jam = $invitation->jam;
        
        // If this is a join request (receiver is creator, sender requested to join), add the sender
        // If this is an invitation (sender is creator/member, receiver is invited), add the receiver
        $userToAdd = ($invitation->receiver_id === $jam->creator_id) ? $invitation->sender_id : $invitation->receiver_id;

        // Check if user is already a member
        if (!$jam->users()->where('user_id', $userToAdd)->exists()) {
            $jam->users()->attach($userToAdd, [
                'role' => 'participant',
                'can_edit_jamboard' => false,
                'can_add_travelers' => false,
                'can_edit_budget' => false,
                'can_add_destinations' => false,
                'joined_at' => now(),
            ]);

            $chatService = app(ChatService::class);
            $conversation = $chatService->findOrCreateJamConversation($jam->id, $userToAdd);
            
            if (!$conversation->hasParticipant($userToAdd)) {
                $chatService->addParticipantToConversation($conversation->id, $userToAdd);
            }
        }

        return $jam;
    }

    public function rejectInvitation($invitationId)
    {
        $userId = Auth::id();
        $invitation = JamInvitation::findOrFail($invitationId);

        // Check if invitation is pending
        if ($invitation->status !== 'pending') {
            throw new \Exception('This invitation is no longer pending');
        }

        // Only the receiver can reject (either the invited user or the creator receiving a join request)
        if ($invitation->receiver_id !== $userId) {
            throw new \Exception('You do not have permission to reject this invitation');
        }

        $invitation->update(['status' => 'rejected']);
        return true;
    }

    public function getSentRequests()
    {
        $userId = Auth::id();
        return JamInvitation::where('sender_id', $userId)
            ->with(['jam', 'sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getReceivedRequests()
    {
        $userId = Auth::id();
        return JamInvitation::where('receiver_id', $userId)
            ->with(['jam', 'sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getRequestStatus($invitationId)
    {
        $userId = Auth::id();
        $invitation = JamInvitation::where('id', $invitationId)
            ->where(function($query) use ($userId) {
                $query->where('sender_id', $userId)
                      ->orWhere('receiver_id', $userId);
            })
            ->with(['jam', 'sender', 'receiver'])
            ->firstOrFail();

        return $invitation;
    }

    public function cancelRequest($invitationId)
    {
        $userId = Auth::id();
        $invitation = JamInvitation::findOrFail($invitationId);

        // Only the sender can cancel a request
        if ($invitation->sender_id !== $userId) {
            throw new \Exception('You can only cancel requests that you sent');
        }

        // Only pending requests can be cancelled
        if ($invitation->status !== 'pending') {
            throw new \Exception('Only pending requests can be cancelled');
        }

        $invitation->update(['status' => 'cancelled']);
        return true;
    }
    
}