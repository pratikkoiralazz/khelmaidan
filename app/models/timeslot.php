<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeSlot extends Model
{
    protected $fillable = [
        'ground_id',
        'start_time',
        'end_time',
        'base_price',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'base_price' => 'integer',
    ];

    public function ground(): BelongsTo
    {
        return $this->belongsTo(Ground::class);
    }
}