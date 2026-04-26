<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDeactivation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $users = User::withCount('bookings')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.users.index', compact('user', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:user,admin',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password ?? 'password123'),
            'role' => $request->role,
            'is_active' => true,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function update(Request $request, User $targetUser)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $targetUser->id,
            'role' => 'required|in:user,admin',
        ]);

        $targetUser->update($request->only(['name', 'email', 'role']));

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Toggle user active/inactive status.
     * Matches Next.js toggleUserStatus() with deactivation logging.
     */
    public function toggleStatus(Request $request, User $targetUser)
    {
        $request->validate([
            'is_active' => 'required|boolean',
            'reason' => 'nullable|string|max:500',
            'duration' => 'nullable|integer|min:1',
        ]);

        $isActive = (bool) $request->is_active;

        DB::transaction(function () use ($targetUser, $isActive, $request) {
            $targetUser->update(['is_active' => $isActive]);

            if (!$isActive) {
                UserDeactivation::create([
                    'user_id' => $targetUser->id,
                    'reason' => $request->reason ?? 'Tanpa alasan',
                    'deactivated_by' => auth()->user()->name,
                    'duration' => $request->duration,
                    'reactivate_at' => $request->duration
                        ? now()->addDays($request->duration)
                        : null,
                ]);
            }
        });

        return redirect()->route('admin.users.index')->with('success', 'Status user berhasil diubah.');
    }

    public function destroy(User $targetUser)
    {
        $targetUser->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}
