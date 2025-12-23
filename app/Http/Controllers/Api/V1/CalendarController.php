<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalendarController extends Controller
{
    public function getJamCalendar($jamId)
    {
        $flights = DB::table('jam_flights')
            ->where('jam_id', $jamId)
            ->get()
            ->map(function($flight) {
                return [
                    'date' => $flight->departure_date,
                    'time' => $flight->departure_time,
                    'type' => 'flight',
                    'title' => "Flight {$flight->flight_number}",
                    'details' => $flight
                ];
            });

        $accommodations = DB::table('itineraries')
            ->where('jam_id', $jamId)
            ->where('type', 'accommodation')
            ->get()
            ->map(function($item) {
                return [
                    'date' => $item->date,
                    'time' => $item->time ?? $item->start_time,
                    'type' => 'accommodation',
                    'title' => $item->title,
                    'details' => $item
                ];
            });

        $activities = DB::table('itineraries')
            ->where('jam_id', $jamId)
            ->where('type', 'activity')
            ->get()
            ->map(function($item) {
                return [
                    'date' => $item->date,
                    'time' => $item->time ?? $item->start_time,
                    'type' => 'activity',
                    'title' => $item->title,
                    'details' => $item
                ];
            });

        $events = collect($flights)
            ->merge($accommodations)
            ->merge($activities)
            ->groupBy('date')
            ->map(function($dayEvents) {
                return $dayEvents->sortBy('time')->values();
            });

        return response()->json([
            'status' => true,
            'data' => $events
        ]);
    }

    public function getSchedule($jamId)
    {
        $jam = DB::table('jams')->where('id', $jamId)->first();
        
        $itineraries = DB::table('itineraries')
            ->where('jam_id', $jamId)
            ->orderBy('date')
            ->orderBy('start_time')
            ->get()
            ->groupBy('date');

        $schedule = [];
        foreach ($itineraries as $date => $events) {
            $schedule[] = [
                'date' => $date,
                'events' => $events->map(function($event) {
                    return [
                        'time_range' => $event->start_time && $event->end_time 
                            ? "{$event->start_time} - {$event->end_time}" 
                            : $event->time,
                        'activities' => [$event->title],
                        'details' => $event
                    ];
                })
            ];
        }

        return response()->json([
            'status' => true,
            'data' => [
                'jam_status' => $jam->status ?? 'active',
                'schedule' => $schedule
            ]
        ]);
    }
}
