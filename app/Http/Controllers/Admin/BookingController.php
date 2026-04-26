<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Equipment;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $bookings = Booking::with(['user', 'room', 'equipment'])
            ->latest()
            ->get();

        $stats = [
            'total' => $bookings->count(),
            'pending' => $bookings->where('status', 'PENDING')->count(),
            'approved' => $bookings->whereIn('status', ['APPROVED', 'ACTIVE'])->count(),
            'rejected' => $bookings->where('status', 'REJECTED')->count(),
            'completed' => $bookings->where('status', 'COMPLETED')->count(),
        ];

        return view('admin.bookings.index', compact('user', 'bookings', 'stats'));
    }

    /**
     * Update booking status (approve/reject/complete/cancel).
     * Matches Next.js updateBookingStatus() with stock management.
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:APPROVED,REJECTED,COMPLETED,CANCELLED',
            'admin_notes' => 'nullable|string|max:500',
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        $newStatus = $request->status;

        // Stock management for equipment
        if ($booking->type === 'equipment' && $booking->equipment_id) {
            $equipment = Equipment::find($booking->equipment_id);

            if ($equipment) {
                if ($newStatus === 'APPROVED') {
                    $equipment->decrement('available');
                } elseif ($newStatus === 'COMPLETED' || ($booking->status === 'APPROVED' && $newStatus === 'CANCELLED')) {
                    $equipment->increment('available');
                }
            }
        }

        $booking->update([
            'status' => $newStatus,
            'notes' => $request->admin_notes ?? $booking->notes,
            'rejection_reason' => $request->rejection_reason,
            'approved_at' => $newStatus === 'APPROVED' ? now() : $booking->approved_at,
        ]);

        return redirect()->route('admin.bookings.index')->with('success', 'Status peminjaman berhasil diperbarui.');
    }
}
