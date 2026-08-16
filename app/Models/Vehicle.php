<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'passenger_capacity',
        'luggage_capacity',
        'price_per_day',
        'description',
        'facilities',
        'image',
        'is_available',
        'is_active',
    ];

    protected $casts = [
        'price_per_day' => 'decimal:2',
        'facilities' => 'array',
        'is_available' => 'boolean',
        'is_active' => 'boolean',
    ];
    public function bookings(): HasMany
{
    return $this->hasMany(Booking::class);
}
}