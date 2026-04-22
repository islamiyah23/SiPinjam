<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RuanganController extends Controller
{
    public function index()
    {
        $rooms = Ruangan::all();
        
        $buildings = Ruangan::select('gedung')->distinct()->pluck('gedung');

        return view('user.ruangan', compact('rooms', 'buildings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:ruangans,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'purpose' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        Peminjaman::create([
            'user_id' => Auth::id(),
            'ruangan_id' => $request->room_id,
            'tanggal_mulai' => $request->start_date,
            'tanggal_selesai' => $request->end_date,
            'tujuan' => $request->purpose,
            'catatan' => $request->notes,
            'status' => 'menunggu',
        ]);

        return redirect()->back()->with('success', 'Peminjaman berhasil diajukan! Admin akan meninjau permintaan Anda.');
    }
}