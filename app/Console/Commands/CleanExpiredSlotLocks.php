<?php

namespace App\Console/Commands;

use App\Models\Booking;
use Illuminate\Console\Command;

class CleanExpiredSlotLocks extends Command
{
    protected $signature = 'bookings:clean-expired';
    protected $description = 'Release slot bookings locked for over 5 minutes without completed digital payments.';

    public function handle(): void
    {
        $affected = Booking::where('status', 'locked')
            ->where('locked_until', '<', now())
            ->update([
                'status' => 'cancelled',
                'locked_until' => null,
            ]);

        $this->info("Expired locks cleanup completed. Released {$affected} abandoned bookings.");
    }
}