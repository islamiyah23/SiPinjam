<?php

use Illuminate\Support\Facades\Route;

// ── User Controllers ──
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\BookingController as UserBookingController;
use App\Http\Controllers\User\RoomController as UserRoomController;
use App\Http\Controllers\User\EquipmentController as UserEquipmentController;
use App\Http\Controllers\User\RuleController as UserRuleController;

// ── Admin Controllers ──
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\RoomController as AdminRoomController;
use App\Http\Controllers\Admin\EquipmentController as AdminEquipmentController;
use App\Http\Controllers\Admin\UserController as AdminUserController;

// ── Profile ──
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Root → redirect to login
Route::redirect('/', '/login');

// ── User Routes ──
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    Route::get('/bookings', [UserBookingController::class, 'index'])->name('bookings');
    Route::post('/bookings', [UserBookingController::class, 'store'])->name('bookings.store');

    Route::get('/rooms', [UserRoomController::class, 'index'])->name('rooms');
    Route::get('/equipment', [UserEquipmentController::class, 'index'])->name('equipment');
    Route::get('/rules', [UserRuleController::class, 'index'])->name('rules');
});

// ── Admin Routes ──
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::patch('/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.status');

    Route::get('/rooms', [AdminRoomController::class, 'index'])->name('rooms.index');
    Route::post('/rooms', [AdminRoomController::class, 'store'])->name('rooms.store');
    Route::put('/rooms/{room}', [AdminRoomController::class, 'update'])->name('rooms.update');
    Route::delete('/rooms/{room}', [AdminRoomController::class, 'destroy'])->name('rooms.destroy');

    Route::get('/equipment', [AdminEquipmentController::class, 'index'])->name('equipment.index');
    Route::post('/equipment', [AdminEquipmentController::class, 'store'])->name('equipment.store');
    Route::put('/equipment/{equipment}', [AdminEquipmentController::class, 'update'])->name('equipment.update');
    Route::delete('/equipment/{equipment}', [AdminEquipmentController::class, 'destroy'])->name('equipment.destroy');

    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
    Route::put('/users/{targetUser}', [AdminUserController::class, 'update'])->name('users.update');
    Route::patch('/users/{targetUser}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::delete('/users/{targetUser}', [AdminUserController::class, 'destroy'])->name('users.destroy');
});

// ── Profile ──
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';