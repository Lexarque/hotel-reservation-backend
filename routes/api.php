<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BookingOrder\BookingOrderController;
use App\Http\Controllers\HotelRoom\HotelRoomController;
use App\Http\Controllers\HotelRoom\HotelRoomTypeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/me', [AuthController::class, 'me'])->name('me');
});

Route::get('/', [HotelRoomController::class, 'index'])->name('rooms.index');
Route::get('/{id}', [HotelRoomController::class, 'show'])->name('rooms.show');
Route::get('/', [HotelRoomTypeController::class, 'index'])->name('room.types.index');
Route::get('/{id}', [HotelRoomTypeController::class, 'show'])->name('room.types.show');

Route::middleware('jwt.verify')->group(function () {

    // Hotel Rooms api group
    Route::prefix('rooms')->group(function () {
        Route::middleware(['role.check:admin|receptionist|verified-guest'])->group(function () {
            Route::post('/create', [HotelRoomController::class, 'store'])->name('rooms.store');
            Route::get('/vacant-rooms', [HotelRoomController::class, 'vacantRoom'])->name('rooms.vacant.rooms');
        });
        Route::middleware(['role.check:admin|receptionist'])->group(function () {
            Route::put('/{id}/update', [HotelRoomController::class, 'update'])->name('rooms.update');
        });
        Route::middleware(['role.check:admin'])->group(function () {
            Route::delete('/{id}/delete', [HotelRoomController::class, 'destroy'])->name('rooms.destroy');
        });
    });

    // Hotel Room Types api group
    Route::prefix('room-types')->group(function () {
        Route::middleware(['role.check:admin'])->group(function () {
            Route::post('/create', [HotelRoomTypeController::class, 'store'])->name('room.types.store');
            Route::put('/{id}/update', [HotelRoomTypeController::class, 'update'])->name('room.types.update');
            Route::delete('/{id}/delete', [HotelRoomTypeController::class, 'destroy'])->name('room.types.destroy');
        });
    });

    Route::prefix('booking-orders')->group(function () {
        Route::middleware(['role.check:admin|receptionist|verified-guest'])->group(function () {
            Route::post('/create', [BookingOrderController::class, 'store'])->name('booking.orders.store');
            Route::get('/{id}', [BookingOrderController::class, 'show'])->name('booking.orders.show');
        });
        Route::middleware(['role.check:admin|receptionist'])->group(function () {
            Route::get('/', [BookingOrderController::class, 'index'])->name('booking.orders.index');
            Route::put('/{id}/update', [BookingOrderController::class, 'update'])->name('booking.orders.update');
        });
        Route::middleware(['role.check:admin'])->group(function () {
            Route::delete('/{id}/delete', [BookingOrderController::class, 'destroy'])->name('booking.orders.destroy');
        });
    });

    Route::prefix('users')->group(function () {
        Route::middleware(['role.check:admin'])->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('users.index');
            Route::get('/{id}', [UserController::class, 'show'])->name('users.show');
            Route::put('/{id}/update', [UserController::class, 'update'])->name('users.update');
            Route::delete('/{id}/delete', [UserController::class, 'destroy'])->name('users.destroy');
        });
    });
    
});
