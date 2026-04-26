<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Room;

class RoomController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $rooms = Room::orderBy('name')->get();
        return view('user.rooms.index', compact('user', 'rooms'));
    }
}
