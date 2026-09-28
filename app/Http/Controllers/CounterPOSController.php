<?php

namespace App\Http\Controllers;

use App\Events\BookingConfirmedEvent;
use App\Models\Booking;
use App\Models\TimeSlot;
use App\Models\Venue;
use App\Services\SlotLockManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CounterPOSController extends Controller
{
    public function storeWalkInBooking(Request $request, SlotLockManager $lockManager): JsonResponse
    {
        $validated = $request->validate([
            'ground_id' => 'required|exists:grounds,id',
            'slot_id' => 'required|exists:time_slots,id',
            'booking_date' => 'required|date_format:Y-m-d',
            'collected_amount' => 'required|integer|min:0',
        ]);

        /** @var Venue $venue */
        $venue = $request->attributes->get('tenant');
        $slot = TimeSlot::findOrFail($validated['slot_id']);

        $lockKey = $lockManager->acquireLock($validated['ground_id'], $validated['slot_id'], $validated['booking_date'], 10);

        if (!$lockKey) {
            return response()->json(['message' => 'Slot is already booked or reserved by an online player.'], 409);
        }

        $booking = Booking::create([
            'venue_id' => $venue->id,
            'ground_id' => $validated['ground_id'],
            'slot_id' => $validated['slot_id'],
            'player_id' => null, // Walk-in cash booking
            'booking_date' => $validated['booking_date'],
            'transaction_uuid' => (string) Str::uuid(),
            'deposit_amount' => $validated['collected_amount'],
            'total_amount' => $slot->base_price,
            'status' => 'confirmed',
            'source' => 'walk_in',
            'locked_until' => null,
        ]);

        $lockManager->releaseLock($lockKey);

        broadcast(new BookingConfirmedEvent($booking))->toOthers();

        return response()->json([
            'message' => 'Walk-in cash booking confirmed.',
            'booking' => $booking,
        ], 201);
    }
}