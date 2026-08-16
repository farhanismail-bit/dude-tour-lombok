<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Service;
use App\Models\TourPackage;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TourSearchController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'destination' => ['nullable', 'string', 'max:255'],
            'date' => ['nullable', 'date'],
            'people' => ['nullable', 'integer', 'min:1'],
            'service' => ['nullable', 'string', 'max:255'],
        ]);

        $destination = $request->input('destination');
        $date = $request->input('date');
        $people = (int) $request->input('people', 1);
        $service = $request->input('service');

        $packages = TourPackage::query()
            ->where('is_active', true)
            ->with([
                'destinations' => function ($query) {
                    $query->where('is_active', true);
                },
            ])
            ->when($destination, function ($query) use ($destination) {
                $query->whereHas('destinations', function ($destinationQuery) use ($destination) {
                    $destinationQuery
                        ->where('is_active', true)
                        ->where(function ($query) use ($destination) {
                            $query->where('name', $destination)
                                ->orWhere('slug', $destination);
                        });
                });
            })
            ->when($service, function ($query) use ($service) {
                $query->where(function ($query) use ($service) {
                    $query->where('tag', $service)
                        ->orWhere('tag', strtoupper($service));
                });
            })
            ->orderByDesc('is_featured')
            ->orderBy('price')
            ->get();

        $vehicles = Vehicle::query()
            ->where('is_active', true)
            ->where('is_available', true)
            ->where('passenger_capacity', '>=', $people)
            ->orderBy('passenger_capacity')
            ->orderBy('price_per_day')
            ->get();

        return Inertia::render('SearchResults', [
            'filters' => [
                'destination' => $destination,
                'date' => $date,
                'people' => $people,
                'service' => $service,
            ],
            'packages' => $packages,
            'vehicles' => $vehicles,
        ]);
    }
}