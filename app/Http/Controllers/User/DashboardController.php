<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userId = $user->id;

        $total = Booking::where('user_id', $userId)->count();
        $active = Booking::where('user_id', $userId)->active()->count();
        $pending = Booking::where('user_id', $userId)->pending()->count();
        $completed = Booking::where('user_id', $userId)->completed()->count();

        // Trend data for the last 6 months
        $trendData = [];
        for ($i = 5; $i >= 0; $i--) {
            $d = Carbon::now()->subMonths($i)->startOfMonth();
            $monthName = $d->translatedFormat('M');
            $nextMonth = $d->copy()->addMonth();

            $count = Booking::where('user_id', $userId)
                ->whereBetween('created_at', [$d, $nextMonth])
                ->count();

            $trendData[] = ['month' => $monthName, 'bookings' => $count];
        }

        return view('user.dashboard', compact(
            'user', 'total', 'active', 'pending', 'completed', 'trendData'
        ));
    }
}
