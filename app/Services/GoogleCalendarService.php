<?php

namespace App\Services;

use App\Models\GoogleToken;
use App\Models\Jam;
use Carbon\Carbon;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Illuminate\Support\Facades\Auth;

class GoogleCalendarService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setApplicationName('Travelily');
        $this->client->setScopes(Google_Service_Calendar::CALENDAR);
        $this->client->setAuthConfig([
            'client_id' => env('GOOGLE_CLIENT_ID'),
            'client_secret' => env('GOOGLE_CLIENT_SECRET'),
            'redirect_uri' => env('GOOGLE_REDIRECT_URI'),
        ]);
        $this->client->setAccessType('offline');
        $this->client->setPrompt('select_account consent');
    }

    public function getAuthUrl()
    {
        return $this->client->createAuthUrl();
    }

    public function handleCallback($code)
    {
        $token = $this->client->fetchAccessTokenWithAuthCode($code);
        if (isset($token['error'])) {
            throw new \Exception('Error fetching Google token: ' . $token['error']);
        }

        $userId = Auth::id();
        GoogleToken::updateOrCreate(
            ['user_id' => $userId],
            [
                'access_token' => $token['access_token'],
                'refresh_token' => $token['refresh_token'] ?? null,
                'expires_at' => Carbon::now()->addSeconds($token['expires_in']),
            ]
        );

        return $token;
    }

    protected function getClientWithToken()
    {
        $userId = Auth::id();
        $token = GoogleToken::where('user_id', $userId)->first();

        if (!$token) {
            throw new \Exception('Google account not linked. Please authenticate first.');
        }

        // Refresh token if expired
        if ($token->expires_at->isPast()) {
            $this->client->refreshToken($token->refresh_token);
            $newToken = $this->client->getAccessToken();
            $token->update([
                'access_token' => $newToken['access_token'],
                'expires_at' => Carbon::now()->addSeconds($newToken['expires_in']),
            ]);
        }

        $this->client->setAccessToken($token->access_token);
        return new Google_Service_Calendar($this->client);
    }

    public function syncJamToCalendar($jamId)
    {
        $calendarService = $this->getClientWithToken();
        $jam = Jam::with(['flights', 'itineraries'])->findOrFail($jamId);

        // Check if the user is a member of the Jamboard
        $userRole = $jam->users()->where('user_id', Auth::id())->first();
        if (!$userRole) {
            throw new \Exception('You are not a member of this Jamboard');
        }

        $calendarId = 'primary';
        $events = [];

        // Sync Flights
        foreach ($jam->flights as $flight) {
            $startDateTime = Carbon::parse($flight->date . ' ' . $flight->departure_time);
            $endDateTime = Carbon::parse($flight->date . ' ' . $flight->arrival_time);

            $event = new Google_Service_Calendar_Event([
                'summary' => "Flight: {$flight->from} to {$flight->to}",
                'location' => "{$flight->from} to {$flight->to}",
                'description' => "Mode of Transportation: {$flight->mode_of_transportation}",
                'start' => [
                    'dateTime' => $startDateTime->toRfc3339String(),
                    'timeZone' => 'UTC',
                ],
                'end' => [
                    'dateTime' => $endDateTime->toRfc3339String(),
                    'timeZone' => 'UTC',
                ],
            ]);

            $event = $calendarService->events->insert($calendarId, $event);
            $events[] = $event;
        }

        // Sync Accommodations, Activities, and Experiences
        foreach ($jam->itineraries as $itinerary) {
            $details = json_decode($itinerary->details, true);
            $startDateTime = Carbon::parse($itinerary->date . ' ' . ($details['time'] ?? '00:00'));
            $endDateTime = $startDateTime->copy()->addHour(); // Assume 1-hour duration if no end time

            $event = new Google_Service_Calendar_Event([
                'summary' => "{$itinerary->type}: {$itinerary->title}",
                'location' => $details['location'] ?? '',
                'description' => $itinerary->description ?? '',
                'start' => [
                    'dateTime' => $startDateTime->toRfc3339String(),
                    'timeZone' => 'UTC',
                ],
                'end' => [
                    'dateTime' => $endDateTime->toRfc3339String(),
                    'timeZone' => 'UTC',
                ],
            ]);

            $event = $calendarService->events->insert($calendarId, $event);
            $events[] = $event;
        }

        return $events;
    }

    public function getCalendarEvents($startDate, $endDate)
    {
        $calendarService = $this->getClientWithToken();
        $calendarId = 'primary';

        $events = $calendarService->events->listEvents($calendarId, [
            'timeMin' => Carbon::parse($startDate)->toRfc3339String(),
            'timeMax' => Carbon::parse($endDate)->toRfc3339String(),
            'singleEvents' => true,
            'orderBy' => 'startTime',
        ]);

        return $events->getItems();
    }
}