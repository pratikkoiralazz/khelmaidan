<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\CounterPOSController;
use App\Http\Controllers\EsewaPaymentController;
use App\Http\Controllers\KhaltiPaymentController;
use App\Http\Controllers\TeamMatchController;
use App\Http\Middleware\TenantResolverMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware([TenantResolverMiddleware::class])->group(function () {
    // Online Slot Reservations
    Route::post('/api/bookings/reserve', [BookingController::class, 'reserveSlot']);

    // Payment Gateway Callbacks
    Route::get('/payment/esewa/success', [EsewaPaymentController::class, 'handleSuccess'])->name('payment.esewa.success');
    Route::get('/payment/esewa/failure', [EsewaPaymentController::class, 'handleFailure'])->name('payment.esewa.failure');
    Route::get('/payment/khalti/callback', [KhaltiPaymentController::class, 'handleCallback'])->name('payment.khalti.callback');

    // Counter Staff POS (Requires auth in production)
    Route::post('/api/pos/walk-in', [CounterPOSController::class, 'storeWalkInBooking']);

    // Matchmaking Hub
    Route::post('/api/bookings/{bookingId}/match-challenge', [TeamMatchController::class, 'createMatchChallenge']);
    Route::post('/api/matches/{matchId}/accept', [TeamMatchController::class, 'acceptChallenge']);
});