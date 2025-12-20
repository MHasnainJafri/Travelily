<?php

namespace App\Services;

use App\Models\Jam;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class MapService
{
    protected $geocodingApiKey;

    public function __construct()
    {
        $this->geocodingApiKey = env('GOOGLE_MAPS_API_KEY');
    }

    protected function geocodeAddress($address)
    {
        if (!$address) {
            return null;
        }

        $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
            'address' => $address,
            'key' => $this->geocodingApiKey,
        ]);

        $data = $response->json();
        if ($data['status'] !== 'OK' || empty($data['results'])) {
            return null;
        }

        $location = $data['results'][0]['geometry']['location'];
        return [
            'latitude' => $location['lat'],
            'longitude' => $location['lng'],
        ];
    }

    public function getJamMapData($jamId)
    {
        $userId = Auth::id();
        $jam = Jam::where('id', $jamId)
                  ->whereHas('users', function ($query) use ($userId) {
                      $query->where('user_id', $userId);
                  })
                  ->with(['flights', 'itineraries', 'tasks'])
                  ->firstOrFail();

        $mapData = [];

        // Add Jamboard Destination
        if ($jam->destination) {
            $coordinates = $this->geocodeAddress($jam->destination);
            if ($coordinates) {
                $mapData[] = [
                    'type' => 'destination',
                    'title' => $jam->name,
                    'location' => $jam->destination,
                    'coordinates' => $coordinates,
                    'date' => $jam->start_date ? $jam->start_date->toDateString() : null,
                ];
            }
        }

        // Add Flights (from and to locations)
        foreach ($jam->flights as $flight) {
            // From location
            $fromCoordinates = $this->geocodeAddress($flight->from);
            if ($fromCoordinates) {
                $mapData[] = [
                    'type' => 'flight_from',
                    'title' => "Flight Departure: {$flight->from}",
                    'location' => $flight->from,
                    'coordinates' => $fromCoordinates,
                    'date' => $flight->date ? $flight->date->toDateString() : null,
                ];
            }

            // To location
            $toCoordinates = $this->geocodeAddress($flight->to);
            if ($toCoordinates) {
                $mapData[] = [
                    'type' => 'flight_to',
                    'title' => "Flight Arrival: {$flight->to}",
                    'location' => $flight->to,
                    'coordinates' => $toCoordinates,
                    'date' => $flight->date ? $flight->date->toDateString() : null,
                ];
            }
        }

        // Add Accommodations, Activities, and Experiences
        foreach ($jam->itineraries as $itinerary) {
            $details = json_decode($itinerary->details, true);
            $location = $details['location'] ?? null;
            $coordinates = $this->geocodeAddress($location);
            if ($coordinates) {
                $mapData[] = [
                    'type' => $itinerary->type,
                    'title' => "{$itinerary->type}: {$itinerary->title}",
                    'location' => $location,
                    'coordinates' => $coordinates,
                    'date' => $itinerary->date ? $itinerary->date->toDateString() : null,
                ];
            }
        }

        // Add Tasks (if they have a location)
        foreach ($jam->tasks as $task) {
            // Assuming tasks might have a location field in the future; for now, we'll skip unless you add a location field
            // If you add a location field to tasks, you can geocode it here
        }

        return $mapData;
    }
}