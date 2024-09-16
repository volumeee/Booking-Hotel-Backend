<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingPage\HomeController;
use App\Http\Controllers\LandingPage\SearchController;
use App\Http\Controllers\LandingPage\HotelController;
use App\Http\Controllers\LandingPage\BookingController;
use App\Http\Controllers\LandingPage\PaymentController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ActivationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Models\Role;

Route::prefix('v1')->name('api.v1.')->group(function () {
    // route role id
    Route::middleware('auth:sanctum')->get('/user-role', function (Request $request) {
        $user = $request->user();
        $role = Role::find($user->role_id);
        return response()->json([
            'role' => $user->role_id,
            'role_name' => $role->name
        ]);
    });

    // Public routes
    Route::get('home', [HomeController::class, 'index'])->name('home');
    Route::get('/clear-cache', [HomeController::class, 'clearCache']);
    Route::get('search', [SearchController::class, 'search'])->name('search');
    Route::get('filter', [SearchController::class, 'filter'])->name('filter');
    Route::get('hotel/{id}', [HotelController::class, 'show'])->name('hotel.show');
    Route::get('hotel/{id}/rooms', [HotelController::class, 'showRooms'])->name('hotel.rooms');
    Route::get('booking/create', [BookingController::class, 'create'])->name('booking.create');

    // Authentication routes
    Route::post('register', [RegisterController::class, 'register'])->name('register');
    Route::get('activate/{token}', [ActivationController::class, 'activate'])->name('activate');
    Route::post('login', [LoginController::class, 'login'])->name('login');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::post('reset-password/{token}', [ForgotPasswordController::class, 'reset'])->name('password.reset');


    // Routes that require authentication
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [LoginController::class, 'logout'])->name('logout');
        Route::post('booking/store', [BookingController::class, 'store'])->name('booking.store');
        Route::get('payment/{bookingId}', [PaymentController::class, 'create'])->name('payment.create');
        Route::post('payment/{bookingId}', [PaymentController::class, 'process'])->name('payment.process');
        Route::get('payment/{bookingId}/status', [PaymentController::class, 'status'])->name('payment.status');
    });
});
