<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'customer_id',
        'service_id',
        'tour_package_id',
        'vehicle_id',
        'destination_id',
        'booking_date',
        'number_of_people',
        'base_price',
        'vehicle_price',
        'total_price',
        'notes',
        'status',
        'payment_status',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'base_price' => 'decimal:2',
        'vehicle_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function tourPackage(): BelongsTo
    {
        return $this->belongsTo(TourPackage::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }
}