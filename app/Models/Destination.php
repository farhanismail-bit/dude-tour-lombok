<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'description',
        'location',
        'category',
        'image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

   public function tourPackages(): BelongsToMany
{
    return $this->belongsToMany(
        TourPackage::class,
        'tour_package_destinations',
        'destination_id',
        'tour_package_id'
    )
    ->withPivot('sort_order')
    ->withTimestamps();
}
    public function bookings(): HasMany
{
    return $this->hasMany(Booking::class);
}
}