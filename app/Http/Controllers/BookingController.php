<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Destination;
use App\Models\Service;
use App\Models\TourPackage;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class BookingController extends Controller
{
    /**
     * Menampilkan halaman booking.
     */
    public function create(Request $request)
    {
        $slug = $request->query('tour_package');

        if (!$slug) {
            return redirect()
                ->route('search')
                ->with('error', 'Paket tour belum dipilih.');
        }

        $package = TourPackage::with('destinations')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $people = (int) $request->query('people', 2);

        if ($people < 1) {
            $people = 1;
        }

        $date = $request->query('date', '');

        $vehicles = Vehicle::query()
            ->where('is_active', true)
            ->where('is_available', true)
            ->where('passenger_capacity', '>=', $people)
            ->orderBy('passenger_capacity')
            ->orderBy('price_per_day')
            ->get([
                'id',
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
            ]);

        $services = Service::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'slug',
            ]);

        return Inertia::render('Booking', [
            'package' => $package,
            'vehicles' => $vehicles,
            'services' => $services,

            'filters' => [
                'date' => $date,
                'people' => $people,
                'vehicle' => $request->query('vehicle'),
            ],
        ]);
    }


    /**
     * Menyimpan booking baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tour_package_id' => [
                'required',
                'integer',
                'exists:tour_packages,id',
            ],

            'vehicle_id' => [
                'nullable',
                'integer',
                'exists:vehicles,id',
            ],

            'service_id' => [
                'required',
                'integer',
                'exists:services,id',
            ],

            'destination_id' => [
                'required',
                'integer',
                'exists:destinations,id',
            ],

            'booking_date' => [
                'required',
                'date',
            ],

            'number_of_people' => [
                'required',
                'integer',
                'min:1',
                'max:100',
            ],

            'customer_name' => [
                'required',
                'string',
                'max:255',
            ],

            'customer_email' => [
                'required',
                'email',
                'max:255',
            ],

            'customer_phone' => [
                'required',
                'string',
                'max:30',
            ],

            'customer_whatsapp' => [
                'nullable',
                'string',
                'max:30',
            ],

            'customer_address' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | TOUR PACKAGE
        |--------------------------------------------------------------------------
        */

        $package = TourPackage::query()
            ->where('id', $validated['tour_package_id'])
            ->where('is_active', true)
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | SERVICE
        |--------------------------------------------------------------------------
        */

        $service = Service::query()
            ->where('id', $validated['service_id'])
            ->where('is_active', true)
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | DESTINATION
        |--------------------------------------------------------------------------
        */

        $destination = Destination::query()
            ->where('id', $validated['destination_id'])
            ->where('is_active', true)
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | VEHICLE
        |--------------------------------------------------------------------------
        */

        $vehicle = null;

        if (!empty($validated['vehicle_id'])) {
            $vehicle = Vehicle::query()
                ->where('id', $validated['vehicle_id'])
                ->where('is_active', true)
                ->where('is_available', true)
                ->firstOrFail();

            if (
                $vehicle->passenger_capacity <
                $validated['number_of_people']
            ) {
                return back()
                    ->withErrors([
                        'vehicle_id' =>
                            'Kapasitas kendaraan tidak mencukupi jumlah orang.',
                    ])
                    ->withInput();
            }
        }


        /*
        |--------------------------------------------------------------------------
        | CUSTOMER
        |--------------------------------------------------------------------------
        */

        $customer = Customer::query()
            ->where('email', $validated['customer_email'])
            ->first();

        if (!$customer) {
            $customer = Customer::create([
                'name' => $validated['customer_name'],
                'email' => $validated['customer_email'],
                'phone' => $validated['customer_phone'],
                'whatsapp' => $validated['customer_whatsapp'] ?? null,
                'address' => $validated['customer_address'] ?? null,
            ]);
        } else {
            $customer->update([
                'name' => $validated['customer_name'],
                'phone' => $validated['customer_phone'],
                'whatsapp' =>
                    $validated['customer_whatsapp']
                    ?? $customer->whatsapp,
                'address' =>
                    $validated['customer_address']
                    ?? $customer->address,
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | PRICE
        |--------------------------------------------------------------------------
        */

        $basePrice = (float) $package->price;

        $vehiclePrice = $vehicle
            ? (float) $vehicle->price_per_day
            : 0;

        $totalPrice = $basePrice + $vehiclePrice;


        /*
        |--------------------------------------------------------------------------
        | BOOKING CODE
        |--------------------------------------------------------------------------
        */

        $bookingCode =
            'DTL-' .
            now()->format('YmdHis') .
            '-' .
            random_int(100, 999);


        /*
        |--------------------------------------------------------------------------
        | CREATE BOOKING
        |--------------------------------------------------------------------------
        */

        $booking = DB::transaction(function () use (
            $validated,
            $customer,
            $service,
            $package,
            $vehicle,
            $destination,
            $basePrice,
            $vehiclePrice,
            $totalPrice,
            $bookingCode
        ) {
            return Booking::create([
                'booking_code' => $bookingCode,

                'customer_id' => $customer->id,

                'service_id' => $service->id,

                'tour_package_id' => $package->id,

                'vehicle_id' => $vehicle?->id,

                'destination_id' => $destination->id,

                'booking_date' =>
                    $validated['booking_date'],

                'number_of_people' =>
                    $validated['number_of_people'],

                'base_price' =>
                    $basePrice,

                'vehicle_price' =>
                    $vehiclePrice,

                'total_price' =>
                    $totalPrice,

                'notes' =>
                    $validated['notes'] ?? null,

                'status' =>
                    'pending',

                'payment_status' =>
                    'unpaid',
            ]);
        });


        /*
        |--------------------------------------------------------------------------
        | REDIRECT SUCCESS
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'booking.success',
                $booking->booking_code
            );
    }


    /**
     * Halaman booking berhasil.
     */
    public function success(string $bookingCode)
    {
        $booking = Booking::with([
            'customer',
            'service',
            'tourPackage',
            'vehicle',
            'destination',
        ])
            ->where(
                'booking_code',
                $bookingCode
            )
            ->firstOrFail();

        return Inertia::render('BookingSuccess', [
            'booking' => $booking,
        ]);
    }
}