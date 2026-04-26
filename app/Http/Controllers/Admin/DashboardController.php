<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Booking;
use App\Models\Room;
use App\Models\Equipment;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $totalUsers = User::count();
        $totalBookings = Booking::count();
        $totalRooms = Room::count();
        $totalEquipment = Equipment::count();

        $pendingBookings = Booking::pending()->count();
        $approvedBookings = Booking::approved()->count();
        $rejectedBookings = Booking::rejected()->count();
        $completedBookings = Booking::completed()->count();

        $availableRooms = Room::count();
        $availableEquipment = Equipment::sum('available');

        // Trend data for the last 6 months
        $trendData = [];
        for ($i = 5; $i >= 0; $i--) {
            $d = Carbon::now()->subMonths($i)->startOfMonth();
            $monthName = $d->translatedFormat('M');
            $nextMonth = $d->copy()->addMonth();

            $count = Booking::whereBetween('created_at', [$d, $nextMonth])->count();
            $trendData[] = ['month' => $monthName, 'bookings' => $count];
        }

        // Recent bookings
        $recentBookings = Booking::with(['user', 'room', 'equipment'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'user', 'totalUsers', 'totalBookings', 'totalRooms', 'totalEquipment',
            'pendingBookings', 'approvedBookings', 'rejectedBookings', 'completedBookings',
            'availableRooms', 'availableEquipment', 'trendData', 'recentBookings'
        ));
    }
}
