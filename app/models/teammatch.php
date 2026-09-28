<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamMatch extends Model
{
    protected $fillable = [
        'booking_id',
        'host_player_id',
        'opponent_player_id',
        'host_team_name',
        'opponent_team_name',
        'opponent_status',
        'split_fee_per_team',
    ];

    protected $casts = [
        'split_fee_per_team' => 'integer',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}