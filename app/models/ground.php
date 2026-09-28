<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ground extends Model
{
    protected $fillable = [
        'venue_id',
        'ground_name',
        'type',
        'is_indoor',
        'is_available',
    ];

    protected $casts = [
        'is_indoor' => 'boolean',
        'is_available' => 'boolean',
    ];

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function timeSlots(): HasMany
    {
        return $this->hasMany(TimeSlot::class);
    }
}