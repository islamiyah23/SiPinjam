<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $equipment = Equipment::orderBy('name')->get();
        return view('admin.equipment.index', compact('user', 'equipment'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:0',
            'available' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        Equipment::create($request->only(['name', 'category', 'quantity', 'available', 'description']));

        return redirect()->route('admin.equipment.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function update(Request $request, Equipment $equipment)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:0',
            'available' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $equipment->update($request->only(['name', 'category', 'quantity', 'available', 'description']));

        return redirect()->route('admin.equipment.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Equipment $equipment)
    {
        $equipment->delete();
        return redirect()->route('admin.equipment.index')->with('success', 'Barang berhasil dihapus.');
    }
}
