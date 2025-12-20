<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch data from database
        $totalUsers = \App\Models\User::count();
        $activeJamboards = \App\Models\Jam::where('status', 'active')->count();
        $monthlyRevenue = \App\Models\Booking::whereMonth('created_at', now()->month)
            ->sum('total_price');
        $bookings = \App\Models\Booking::whereMonth('created_at', now()->month)->count();

        // Example: Calculate changes and trends (replace with real logic as needed)
        $lastMonthUsers = \App\Models\User::whereMonth('created_at', now()->subMonth()->month)->count();
        $userChange = $lastMonthUsers ? (($totalUsers - $lastMonthUsers) / $lastMonthUsers) * 100 : 0;
        $userTrend = $userChange >= 0 ? 'up' : 'down';

        $lastMonthJamboards = \App\Models\Jam::where('status', 'active')
            ->whereMonth('created_at', now()->subMonth()->month)->count();
        $jamboardChange = $lastMonthJamboards ? (($activeJamboards - $lastMonthJamboards) / $lastMonthJamboards) * 100 : 0;
        $jamboardTrend = $jamboardChange >= 0 ? 'up' : 'down';

        $lastMonthRevenue = \App\Models\Booking::whereMonth('created_at', now()->subMonth()->month)
            ->sum('total_price');
        $revenueChange = $lastMonthRevenue ? (($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 : 0;
        $revenueTrend = $revenueChange >= 0 ? 'up' : 'down';

        $lastMonthBookings = \App\Models\Booking::whereMonth('created_at', now()->subMonth()->month)->count();
        $bookingsChange = $lastMonthBookings ? (($bookings - $lastMonthBookings) / $lastMonthBookings) * 100 : 0;
        $bookingsTrend = $bookingsChange >= 0 ? 'up' : 'down';

        $stats = [
            [
                'title' => 'Total Users',
                'value' => number_format($totalUsers),
                'change' => ($userChange >= 0 ? '+' : '') . round($userChange) . '%',
                'trend' => $userTrend,
                'icon' => 'Users',
                'color' => 'bg-blue-500',
            ],
            [
                'title' => 'Active Jamboards',
                'value' => number_format($activeJamboards),
                'change' => ($jamboardChange >= 0 ? '+' : '') . round($jamboardChange) . '%',
                'trend' => $jamboardTrend,
                'icon' => 'Map',
                'color' => 'bg-emerald-500',
            ],
            [
                'title' => 'Monthly Revenue',
                'value' => '$' . number_format($monthlyRevenue),
                'change' => ($revenueChange >= 0 ? '+' : '') . round($revenueChange) . '%',
                'trend' => $revenueTrend,
                'icon' => 'CreditCard',
                'color' => 'bg-violet-500',
            ],
            [
                'title' => 'Bookings',
                'value' => number_format($bookings),
                'change' => ($bookingsChange >= 0 ? '+' : '') . round($bookingsChange) . '%',
                'trend' => $bookingsTrend,
                'icon' => 'Calendar',
                'color' => 'bg-amber-500',
            ],
        ];

        return Inertia::render('dashboard', [
            'stats' => $stats,
        ]);
    }
}
