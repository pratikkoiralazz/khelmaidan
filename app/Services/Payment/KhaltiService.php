<?php

namespace App\Services\Payment;

use App\Models\Venue;
use Illuminate\Support\Facades\Http;

class KhaltiService
{
    public function initiatePayment(Venue $venue, string $purchaseOrderId, string $purchaseOrderName, int $amountInPaisa): array
    {
        $secretKey = $venue->khalti_secret_key ?? config('services.khalti.secret');
        $endpoint = config('services.khalti.base_url', 'https://a.khalti.com/api/v2/') . 'epayment/initiate/';

        $response = Http::withHeaders([
            'Authorization' => "Key {$secretKey}",
            'Content-Type' => 'application/json',
        ])->post($endpoint, [
            'return_url' => route('payment.khalti.callback'),
            'website_url' => request()->getSchemeAndHttpHost(),
            'amount' => $amountInPaisa,
            'purchase_order_id' => $purchaseOrderId,
            'purchase_order_name' => $purchaseOrderName,
        ]);

        return $response->json();
    }

    public function verifyPayment(Venue $venue, string $pidx): array
    {
        $secretKey = $venue->khalti_secret_key ?? config('services.khalti.secret');
        $endpoint = config('services.khalti.base_url', 'https://a.khalti.com/api/v2/') . 'epayment/lookup/';

        $response = Http::withHeaders([
            'Authorization' => "Key {$secretKey}",
            'Content-Type' => 'application/json',
        ])->post($endpoint, [
            'pidx' => $pidx,
        ]);

        return $response->json();
    }
}