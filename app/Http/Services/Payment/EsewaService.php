<?php

namespace App\Services\Payment;

use App\Models\Venue;

class EsewaService
{
    public function generateSignature(string $totalAmount, string $transactionUuid, string $productCode, string $secretKey): string
    {
        $data = "total_amount={$totalAmount},transaction_uuid={$transactionUuid},product_code={$productCode}";
        $rawHmac = hash_hmac('sha256', $data, $secretKey, true);
        return base64_encode($rawHmac);
    }

    public function verifyCallbackSignature(array $data, string $secretKey): bool
    {
        if (!isset($data['signature'], $data['total_amount'], $data['transaction_uuid'], $data['product_code'])) {
            return false;
        }

        $expectedData = "total_amount={$data['total_amount']},transaction_uuid={$data['transaction_uuid']},product_code={$data['product_code']}";
        $calculatedHmac = base64_encode(hash_hmac('sha256', $expectedData, $secretKey, true));

        return hash_equals($calculatedHmac, $data['signature']);
    }

    public function buildPaymentPayload(Venue $venue, string $transactionUuid, int $totalAmount, int $depositAmount): array
    {
        $secret = $venue->esewa_secret_key ?? config('services.esewa.secret');
        $merchantCode = $venue->esewa_merchant_code ?? 'EPAYTEST';
        
        $signature = $this->generateSignature((string) $depositAmount, $transactionUuid, $merchantCode, $secret);

        return [
            'amount' => $depositAmount,
            'tax_amount' => 0,
            'total_amount' => $depositAmount,
            'transaction_uuid' => $transactionUuid,
            'product_code' => $merchantCode,
            'product_service_charge' => 0,
            'product_delivery_charge' => 0,
            'success_url' => route('payment.esewa.success'),
            'failure_url' => route('payment.esewa.failure'),
            'signed_field_names' => 'total_amount,transaction_uuid,product_code',
            'signature' => $signature,
        ];
    }
}