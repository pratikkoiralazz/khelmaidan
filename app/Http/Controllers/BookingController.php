<?php

namespace App\Http\Controllers;

use App\Events\SlotLockedEvent;
use App\Models\Booking;
use App\Models\TimeSlot;
use App\Models\Venue;
use App\Services\Payment\EsewaService;
use App\Services\SlotLockManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function reserveSlot(Request $request, SlotLockManager $lockManager, EsewaService $esewaService): JsonResponse
    {
        $validated = $request->validate([
            'ground_id' => 'required|exists:grounds,id',
            'slot_id' => 'required|exists:time_slots,id',
            'booking_date' => 'required|date_format:Y-m-d|after_or_equal:today',
            'deposit_amount' => 'required|integer|min:100',
        ]);

        /** @var Venue $venue */
        $venue = $request->attributes->get('tenant');
        $slot = TimeSlot::findOrFail($validated['slot_id']);

        $lockKey = $lockManager->acquireLock($validated['ground_id'], $validated['slot_id'], $validated['booking_date']);

        if (!$lockKey) {
            return response()->json(['message' => 'Slot is currently being booked or already confirmed.'], 409);
        }

        $transactionUuid = (string) Str::uuid();

        $booking = Booking::create([
            'venue_id' => $venue->id,
            'ground_id' => $validated['ground_id'],
            'slot_id' => $validated['slot_id'],
            'player_id' => auth()->id(),
            'booking_date' => $validated['booking_date'],
            'transaction_uuid' => $transactionUuid,
            'deposit_amount' => $validated['deposit_amount'],
            'total_amount' => $slot->base_price,
            'status' => 'locked',
            'source' => 'online',
            'locked_until' => now()->addMinutes(5),
        ]);

        broadcast(new SlotLockedEvent($booking))->toOthers();

        $paymentPayload = $esewaService->buildPaymentPayload(
            $venue,
            $transactionUuid,
            $slot->base_price,
            $validated['deposit_amount']
        );

        return response()->json([
            'status' => 'locked',
            'transaction_uuid' => $transactionUuid,
            'payment_url' => config('services.esewa.payment_url', 'https://rc-epay.esewa.com.np/api/epay/main/v2/form'),
            'payment_payload' => $paymentPayload,
        ]);
    }
}