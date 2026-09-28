<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venue extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'custom_domain',
        'theme_settings',
        'esewa_merchant_code',
        'esewa_secret_key',
        'khalti_secret_key',
        'is_active',
    ];

    protected $casts = [
        'theme_settings' => 'array',
        'is_active' => 'boolean',
        'esewa_secret_key' => 'encrypted',
        'khalti_secret_key' => 'encrypted',
    ];

    public function grounds(): HasMany
    {
        return $this->hasMany(Ground::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}