<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TourPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'tag',
        'short_description',
        'description',
        'price',
        'duration_days',
        'duration_nights',
        'image',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'duration_days' => 'integer',
        'duration_nights' => 'integer',
    ];

    /**
     * Destination yang termasuk dalam paket tour.
     */
    public function destinations(): BelongsToMany
    {
        return $this->belongsToMany(
            Destination::class,
            'tour_package_destinations',
            'tour_package_id',
            'destination_id'
        )
            ->withPivot('sort_order')
            ->withTimestamps()
            ->orderBy(
                'tour_package_destinations.sort_order'
            );
    }
}