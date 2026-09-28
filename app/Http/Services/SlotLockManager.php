<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\TimeSlot;
use Illuminate\Support\Facades\Cache;

class SlotLockManager
{
    public function acquireLock(int $groundId, int $slotId, string $date, int $ttlSeconds = 300): ?string
    {
        $lockKey = "slot_lock_{$groundId}_{$slotId}_{$date}";
        $lock = Cache::lock($lockKey, $ttlSeconds);

        if (!$lock->get()) {
            return null;
        }

        $existingBooking = Booking::where('ground_id', $groundId)
            ->where('slot_id', $slotId)
            ->where('booking_date', $date)
            ->whereIn('status', ['locked', 'confirmed'])
            ->first();

        if ($existingBooking) {
            $lock->release();
            return null;
        }

        return $lockKey;
    }

    public function releaseLock(string $lockKey): void
    {
        Cache::lock($lockKey)->release();
    }
}