<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    protected $fillable = [
        'venue_id',
        'ground_id',
        'slot_id',
        'player_id',
        'booking_date',
        'transaction_uuid',
        'deposit_amount',
        'total_amount',
        'status',
        'source',
        'locked_until',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'locked_until' => 'datetime',
        'deposit_amount' => 'integer',
        'total_amount' => 'integer',
    ];

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function ground(): BelongsTo
    {
        return $this->belongsTo(Ground::class);
    }

    public function slot(): BelongsTo
    {
        return $this->belongsTo(TimeSlot::class);
    }

    public function teamMatch(): HasOne
    {
        return $this->hasOne(TeamMatch::class);
    }
}