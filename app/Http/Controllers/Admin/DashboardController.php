<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Destination;
use App\Models\TourPackage;
use App\Models\Vehicle;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $bookingStatistics = [
            'total' => Booking::count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'completed' => Booking::where('status', 'completed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
            'unpaid' => Booking::where('payment_status', 'unpaid')->count(),
            'paid' => Booking::where('payment_status', 'paid')->count(),
            'revenue' => (float) Booking::where('payment_status', 'paid')->sum('total_price'),
        ];

        $masterStatistics = [
            'customers' => Customer::count(),
            'tour_packages' => TourPackage::count(),
            'active_tour_packages' => TourPackage::where('is_active', true)->count(),
            'featured_tour_packages' => TourPackage::where('is_featured', true)->count(),
            'vehicles' => Vehicle::count(),
            'available_vehicles' => Vehicle::where('is_available', true)->where('is_active', true)->count(),
            'destinations' => Destination::count(),
            'active_destinations' => Destination::where('is_active', true)->count(),
        ];

        $recentBookings = Booking::with([
            'customer:id,name,email,phone',
            'tourPackage:id,name,slug',
            'destination:id,name',
            'vehicle:id,name',
        ])->latest('id')->take(8)->get();

        $recentTourPackages = TourPackage::with('destinations:id,name,slug')
            ->latest('id')
            ->take(5)
            ->get();

        $bookingTrend = collect(range(6, 0))->map(function ($daysAgo) {
            $date = now()->subDays($daysAgo)->startOfDay();

            return [
                'date' => $date->format('Y-m-d'),
                'label' => $date->format('d M'),
                'total' => Booking::whereBetween('created_at', [
                    $date,
                    $date->copy()->addDay(),
                ])->count(),
            ];
        })->values();

        return Inertia::render('Admin/Dashboard', [
            'bookingStatistics' => $bookingStatistics,
            'masterStatistics' => $masterStatistics,
            'recentBookings' => $recentBookings,
            'recentTourPackages' => $recentTourPackages,
            'bookingTrend' => $bookingTrend,
        ]);
    }
}