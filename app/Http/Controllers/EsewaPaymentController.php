<?php

namespace App\Http\Controllers;

use App\Events\BookingConfirmedEvent;
use App\Models\Booking;
use App\Models\Venue;
use App\Services\Payment\EsewaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EsewaPaymentController extends Controller
{
    public function handleSuccess(Request $request, EsewaService $esewaService): JsonResponse
    {
        $encodedData = $request->query('data');
        if (!$encodedData) {
            return response()->json(['error' => 'Missing payment response payload.'], 400);
        }

        $decodedJson = base64_decode($encodedData);
        $payload = json_decode($decodedJson, true);

        if (!$payload || !isset($payload['transaction_uuid'])) {
            return response()->json(['error' => 'Invalid payment payload structure.'], 400);
        }

        $booking = Booking::where('transaction_uuid', $payload['transaction_uuid'])->firstOrFail();
        /** @var Venue $venue */
        $venue = Venue::findOrFail($booking->venue_id);

        $secret = $venue->esewa_secret_key ?? config('services.esewa.secret');
        if (!$esewaService->verifyCallbackSignature($payload, $secret)) {
            $booking->update(['status' => 'cancelled']);
            return response()->json(['error' => 'Cryptographic signature mismatch.'], 422);
        }

        $booking->update([
            'status' => 'confirmed',
            'locked_until' => null,
        ]);

        broadcast(new BookingConfirmedEvent($booking))->toOthers();

        return response()->json([
            'message' => 'Payment verified and booking confirmed successfully.',
            'booking_id' => $booking->id,
        ]);
    }

    public function handleFailure(Request $request): JsonResponse
    {
        $transactionUuid = $request->query('transaction_uuid');
        if ($transactionUuid) {
            Booking::where('transaction_uuid', $transactionUuid)->update(['status' => 'cancelled']);
        }

        return response()->json(['message' => 'Payment failed or cancelled by user.'], 200);
    }
}