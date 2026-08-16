<?php

namespace App\Http\Controllers;

use App\Models\TourPackage;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TourController extends Controller
{
    /**
     * Menampilkan detail paket tour.
     */
    public function show(Request $request, string $slug)
    {
        $package = TourPackage::with([
            'destinations',
        ])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | JUMLAH ORANG
        |--------------------------------------------------------------------------
        |
        | Jika user datang dari halaman search, jumlah orang dikirim melalui
        | query parameter ?people=4.
        |
        */
        $people = (int) $request->query('people', 2);

        if ($people < 1) {
            $people = 1;
        }

        /*
        |--------------------------------------------------------------------------
        | KENDARAAN
        |--------------------------------------------------------------------------
        |
        | Ambil kendaraan aktif yang kapasitasnya cukup untuk jumlah orang.
        |
        */
        $vehicles = Vehicle::query()
            ->where('is_active', true)
            ->where('passenger_capacity', '>=', $people)
            ->orderBy('passenger_capacity')
            ->get([
                'id',
                'name',
                'slug',
                'type',
                'passenger_capacity',
                'price_per_day',
                'image',
                'is_active',
            ]);

        return Inertia::render('TourDetail', [
            'package' => $package,
            'vehicles' => $vehicles,
            'filters' => [
                'date' => $request->query('date', ''),
                'people' => $people,
            ],
        ]);
    }
}