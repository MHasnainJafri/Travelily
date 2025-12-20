<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\GoogleCalendarService;
use Illuminate\Http\Request;

class GoogleCalendarController extends Controller
{
    protected $googleCalendarService;

    public function __construct(GoogleCalendarService $googleCalendarService)
    {
        $this->googleCalendarService = $googleCalendarService;
    }

    public function redirectToGoogle()
    {
        $authUrl = $this->googleCalendarService->getAuthUrl();
        return response()->json(['auth_url' => $authUrl]);
    }

    public function handleGoogleCallback(Request $request)
    {
        $code = $request->query('code');
        if (!$code) {
            return response()->json(['error' => 'Authorization code is required'], 400);
        }

        try {
            $token = $this->googleCalendarService->handleCallback($code);
            return response()->json(['message' => 'Google Calendar linked successfully', 'token' => $token]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function syncJamToCalendar($jamId)
    {
        try {
            $events = $this->googleCalendarService->syncJamToCalendar($jamId);
            return response()->json(['message' => 'Jamboard synced to Google Calendar', 'events' => $events]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function getCalendarEvents(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        try {
            $events = $this->googleCalendarService->getCalendarEvents($request->start_date, $request->end_date);
            return response()->json(['events' => $events]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}