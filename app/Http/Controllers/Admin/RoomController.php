<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $rooms = Room::orderBy('name')->get();
        return view('admin.rooms.index', compact('user', 'rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'building' => 'nullable|string|max:255',
            'floor' => 'nullable|integer|min:0',
        ]);

        Room::create($request->only(['name', 'capacity', 'description', 'building', 'floor']));

        return redirect()->route('admin.rooms.index')->with('success', 'Ruangan berhasil ditambahkan.');
    }

    public function update(Request $request, Room $room)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'building' => 'nullable|string|max:255',
            'floor' => 'nullable|integer|min:0',
        ]);

        $room->update($request->only(['name', 'capacity', 'description', 'building', 'floor']));

        return redirect()->route('admin.rooms.index')->with('success', 'Ruangan berhasil diperbarui.');
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return redirect()->route('admin.rooms.index')->with('success', 'Ruangan berhasil dihapus.');
    }
}
