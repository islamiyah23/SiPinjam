<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Equipment;

class EquipmentController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $equipment = Equipment::orderBy('name')->get();
        return view('user.equipment.index', compact('user', 'equipment'));
    }
}
