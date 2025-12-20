<?php

namespace App\Services;

use App\Models\Jam;
use App\Models\JamGuide;
use Illuminate\Support\Facades\Auth;

class JamGuideService
{
   public function assignGuide($jamId, $data)
{
    $jam = Jam::findOrFail($jamId);

    // Check if the Jamboard is locked
    if ($jam->is_locked) {
        throw new \Exception('This Jamboard is locked and cannot be modified');
    }

    // Check if the authenticated user has permission (creator or admin)
    $userRole = $jam->users()->where('user_id', Auth::id())->first();
    if (!$userRole || !in_array($userRole->pivot->role, ['creator', 'admin'])) {
        throw new \Exception('Only the creator or admin can assign a host or guide');
    }

    $userId = $data['user_id'];
    $role = $data['role'];

    // Check if the user exists and has the appropriate role in jam_users
    $targetUser = $jam->users()->where('user_id', $userId)->first();
    if (!$targetUser) {
        throw new \Exception('User is not a member of this Jamboard');
    }

    if (!in_array($targetUser->pivot->role, ['guide', 'host'])) {
        // Update role in jam_users if necessary
        $jam->users()->updateExistingPivot($userId, ['role' => $role]);
    }

    // Check if the user is already assigned as a guide/host
    $existingGuide = JamGuide::where('jam_id', $jamId)->where('user_id', $userId)->first();
    if ($existingGuide) {
        throw new \Exception('User is already assigned as a host or guide for this Jamboard');
    }

    $guide = JamGuide::create([
        'jam_id' => $jamId,
        'user_id' => $userId,
        'role' => $role,
        'contact_info' => $data['contact_info'] ?? null,
    ]);

    return $guide;
}
}