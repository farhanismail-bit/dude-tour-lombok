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
    /**
     * Dashboard Admin
     */
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | BOOKING STATISTICS
        |--------------------------------------------------------------------------
        */

        $bookingStatistics = [
            'total' => Booking::count(),

            'pending' => Booking::where(
                'status',
                'pending'
            )->count(),

            'confirmed' => Booking::where(
                'status',
                'confirmed'
            )->count(),

            'completed' => Booking::where(
                'status',
                'completed'
            )->count(),

            'cancelled' => Booking::where(
                'status',
                'cancelled'
            )->count(),

            'unpaid' => Booking::where(
                'payment_status',
                'unpaid'
            )->count(),

            'paid' => Booking::where(
                'payment_status',
                'paid'
            )->count(),

            /*
            | Revenue hanya berasal dari booking
            | yang sudah dibayar.
            */

            'revenue' => (float) Booking::where(
                'payment_status',
                'paid'
            )->sum('total_price'),
        ];


        /*
        |--------------------------------------------------------------------------
        | MASTER DATA STATISTICS
        |--------------------------------------------------------------------------
        */

        $masterStatistics = [

            /*
            | Customer
            */

            'customers' => Customer::count(),


            /*
            | Tour Package
            */

            'tour_packages' => TourPackage::count(),

            'active_tour_packages' => TourPackage::where(
                'is_active',
                true
            )->count(),

            'featured_tour_packages' => TourPackage::where(
                'is_featured',
                true
            )->count(),


            /*
            | Vehicle
            */

            'vehicles' => Vehicle::count(),

            'available_vehicles' => Vehicle::where(
                'is_available',
                true
            )
                ->where(
                    'is_active',
                    true
                )
                ->count(),


            /*
            | Destination
            */

            'destinations' => Destination::count(),

            'active_destinations' => Destination::where(
                'is_active',
                true
            )->count(),

            'inactive_destinations' => Destination::where(
                'is_active',
                false
            )->count(),

            /*
            | Destination yang sudah digunakan
            | oleh Tour Package
            */

            'used_destinations' => Destination::has(
                'tourPackages'
            )->count(),

            /*
            | Destination yang belum digunakan
            */

            'unused_destinations' => Destination::doesntHave(
                'tourPackages'
            )->count(),
        ];


        /*
        |--------------------------------------------------------------------------
        | RECENT CUSTOMERS
        |--------------------------------------------------------------------------
        */

        $recentCustomers = Customer::query()
            ->withCount('bookings')
            ->withSum(
                'bookings',
                'total_price'
            )
            ->latest('id')
            ->take(8)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | RECENT BOOKINGS
        |--------------------------------------------------------------------------
        */

        $recentBookings = Booking::query()
            ->with([
                'customer:id,name,email,phone',

                'tourPackage:id,name,slug',

                'destination:id,name',

                'vehicle:id,name',
            ])
            ->latest('id')
            ->take(8)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | RECENT TOUR PACKAGES
        |--------------------------------------------------------------------------
        */

        $recentTourPackages = TourPackage::query()
            ->with(
                'destinations:id,name,slug'
            )
            ->latest('id')
            ->take(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | RECENT DESTINATIONS
        |--------------------------------------------------------------------------
        |
        | Data destination terbaru untuk ditampilkan
        | langsung pada dashboard admin.
        |
        */

        $recentDestinations = Destination::query()
            ->withCount('tourPackages')
            ->latest('id')
            ->take(6)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | BOOKING TREND
        |--------------------------------------------------------------------------
        |
        | Statistik booking selama 7 hari terakhir.
        |
        */

        $bookingTrend = collect(
            range(6, 0)
        )
            ->map(function ($daysAgo) {

                $date = now()
                    ->subDays($daysAgo)
                    ->startOfDay();

                $nextDate = $date
                    ->copy()
                    ->addDay();

                return [
                    'date' => $date->format(
                        'Y-m-d'
                    ),

                    'label' => $date->format(
                        'd M'
                    ),

                    'total' => Booking::whereBetween(
                        'created_at',
                        [
                            $date,
                            $nextDate,
                        ]
                    )->count(),
                ];
            })
            ->values();


        /*
        |--------------------------------------------------------------------------
        | RETURN DASHBOARD
        |--------------------------------------------------------------------------
        */

        return Inertia::render(
            'Admin/Dashboard',
            [

                /*
                |------------------------------------------------------------------
                | BOOKING
                |------------------------------------------------------------------
                */

                'bookingStatistics' => $bookingStatistics,


                /*
                |------------------------------------------------------------------
                | MASTER DATA
                |------------------------------------------------------------------
                */

                'masterStatistics' => $masterStatistics,


                /*
                |------------------------------------------------------------------
                | CUSTOMER TERBARU
                |------------------------------------------------------------------
                */

                'recentCustomers' => $recentCustomers,


                /*
                |------------------------------------------------------------------
                | BOOKING TERBARU
                |------------------------------------------------------------------
                */

                'recentBookings' => $recentBookings,


                /*
                |------------------------------------------------------------------
                | TOUR PACKAGE TERBARU
                |------------------------------------------------------------------
                */

                'recentTourPackages' => $recentTourPackages,


                /*
                |------------------------------------------------------------------
                | DESTINATION TERBARU
                |------------------------------------------------------------------
                */

                'recentDestinations' => $recentDestinations,


                /*
                |------------------------------------------------------------------
                | GRAFIK BOOKING
                |------------------------------------------------------------------
                */

                'bookingTrend' => $bookingTrend,
            ]
        );
    }
}