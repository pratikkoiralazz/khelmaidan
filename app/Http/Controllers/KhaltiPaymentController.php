<?php

namespace App\Http\Controllers;

use App\Events\BookingConfirmedEvent;
use App\Models\Booking;
use App\Models\Venue;
use App\Services\Payment\KhaltiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KhaltiPaymentController extends Controller
{
    public function handleCallback(Request $request, KhaltiService $khaltiService): JsonResponse
    {
        $pidx = $request->query('pidx');
        $transactionUuid = $request->query('purchase_order_id');

        if (!$pidx || !$transactionUuid) {
            return response()->json(['error' => 'Missing transaction verification parameter.'], 400);
        }

        $booking = Booking::where('transaction_uuid', $transactionUuid)->firstOrFail();
        /** @var Venue $venue */
        $venue = Venue::findOrFail($booking->venue_id);

        $verification = $khaltiService->verifyPayment($venue, $pidx);

        if (isset($verification['status']) && $verification['status'] === 'Completed') {
            $booking->update([
                'status' => 'confirmed',
                'locked_until' => null,
            ]);

            broadcast(new BookingConfirmedEvent($booking))->toOthers();

            return response()->json([
                'message' => 'Khalti payment verified successfully.',
                'booking_id' => $booking->id,
            ]);
        }

        $booking->update(['status' => 'cancelled']);
        return response()->json(['error' => 'Khalti payment verification failed or pending.'], 422);
    }
}