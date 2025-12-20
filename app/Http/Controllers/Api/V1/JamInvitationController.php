<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\JamResource;
use App\Http\Resources\JamInvitationResource;
use App\Http\Resources\JamInvitationCollection;
use App\Services\JamInvitationService;
use Illuminate\Http\Request;

class JamInvitationController extends Controller
{
    protected $jamInvitationService;

    public function __construct(JamInvitationService $jamInvitationService)
    {
        $this->jamInvitationService = $jamInvitationService;
    }

    public function sendInvitations(Request $request, $jamId)
    {
        $data = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        try {
            $invitations = $this->jamInvitationService->sendInvitations($jamId, $data['user_ids']);
            return new JamInvitationCollection($invitations);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function acceptInvitation($invitationId)
    {
        try {
            $jam = $this->jamInvitationService->acceptInvitation($invitationId);
            return new JamResource($jam);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function rejectInvitation($invitationId)
    {
        try {
            $this->jamInvitationService->rejectInvitation($invitationId);
            return response()->json(['message' => 'Invitation rejected'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function getSentRequests()
    {
        try {
            $requests = $this->jamInvitationService->getSentRequests();
            return new JamInvitationCollection($requests);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function getReceivedRequests()
    {
        try {
            $requests = $this->jamInvitationService->getReceivedRequests();
            return new JamInvitationCollection($requests);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function getRequestStatus($invitationId)
    {
        try {
            $invitation = $this->jamInvitationService->getRequestStatus($invitationId);
            return new JamInvitationResource($invitation);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function cancelRequest($invitationId)
    {
        try {
            $this->jamInvitationService->cancelRequest($invitationId);
            return response()->json(['message' => 'Request cancelled successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}