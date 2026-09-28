<?php

namespace App\Events;

use App\Models\Booking;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SlotLockedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Booking $booking) {}

    public function broadcastOn(): array
    {
        return [
            new Channel("venue.{$this->booking->venue_id}"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'slot.locked';
    }

    public function broadcastWith(): array
    {
        return [
            'booking_id' => $this->booking->id,
            'ground_id' => $this->booking->ground_id,
            'slot_id' => $this->booking->slot_id,
            'booking_date' => $this->booking->booking_date->format('Y-m-d'),
            'status' => 'locked',
            'locked_until' => $this->booking->locked_until?->toIso8601String(),
        ];
    }
}