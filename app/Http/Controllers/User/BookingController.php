<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $bookings = Booking::where('user_id', $user->id)
            ->with(['room', 'equipment', 'user'])
            ->latest()
            ->get();

        $stats = [
            'total' => $bookings->count(),
            'pending' => $bookings->where('status', 'PENDING')->count(),
            'approved' => $bookings->whereIn('status', ['APPROVED', 'ACTIVE'])->count(),
            'completed' => $bookings->where('status', 'COMPLETED')->count(),
        ];

        $rooms = Room::orderBy('name')->get();
        $equipment = Equipment::where('available', '>', 0)->orderBy('name')->get();

        return view('user.bookings.index', compact('user', 'bookings', 'stats', 'rooms', 'equipment'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:room,equipment',
            'item_id' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'purpose' => 'required|string|max:500',
            'notes' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $type = $request->type;
        $itemId = $request->item_id;

        // Validate item exists
        if ($type === 'room') {
            $item = Room::findOrFail($itemId);
        } else {
            $item = Equipment::findOrFail($itemId);
            if ($item->available <= 0) {
                return back()->withErrors(['item_id' => 'Barang tidak tersedia.'])->withInput();
            }
        }

        // Check for scheduling conflicts
        $conflicts = Booking::where('type', $type)
            ->when($type === 'room', fn($q) => $q->where('room_id', $itemId))
            ->when($type === 'equipment', fn($q) => $q->where('equipment_id', $itemId))
            ->whereIn('status', ['PENDING', 'APPROVED', 'ACTIVE'])
            ->where(function ($q) use ($request) {
                $q->where(function ($q2) use ($request) {
                    $q2->where('start_date', '<=', $request->start_date)
                       ->where('end_date', '>=', $request->start_date);
                })->orWhere(function ($q2) use ($request) {
                    $q2->where('start_date', '<=', $request->end_date)
                       ->where('end_date', '>=', $request->end_date);
                })->orWhere(function ($q2) use ($request) {
                    $q2->where('start_date', '>=', $request->start_date)
                       ->where('end_date', '<=', $request->end_date);
                });
            })
            ->exists();

        if ($conflicts) {
            return back()->withErrors(['item_id' => 'Jadwal sudah terisi oleh peminjaman lain.'])->withInput();
        }

        Booking::create([
            'user_id' => $user->id,
            'type' => $type,
            'room_id' => $type === 'room' ? $itemId : null,
            'equipment_id' => $type === 'equipment' ? $itemId : null,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'purpose' => $request->purpose,
            'notes' => $request->notes,
            'status' => 'PENDING',
        ]);

        return redirect()->route('user.bookings')->with('success', 'Peminjaman berhasil diajukan!');
    }
}
