<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerController extends Controller
{
    /**
     * Menampilkan daftar customer.
     */
    public function index(Request $request)
    {
        $query = Customer::query()
            ->withCount('bookings')
            ->withSum('bookings', 'total_price');


        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim(
                $request->input('search')
            );

            $query->where(function ($q) use ($search) {

                $q->where(
                    'name',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'email',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'phone',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'whatsapp',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'address',
                    'like',
                    "%{$search}%"
                );
            });
        }


        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */

        $paginator = $query
            ->latest('id')
            ->paginate(10)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | FORMAT PAGINATION
        |--------------------------------------------------------------------------
        */

        $customers = [
            'data' => $paginator->items(),

            'current_page' => $paginator->currentPage(),

            'from' => $paginator->firstItem(),

            'last_page' => $paginator->lastPage(),

            'per_page' => $paginator->perPage(),

            'to' => $paginator->lastItem(),

            'total' => $paginator->total(),

            'links' => $paginator
                ->linkCollection()
                ->toArray(),
        ];


        /*
        |--------------------------------------------------------------------------
        | STATISTICS
        |--------------------------------------------------------------------------
        */

        $statistics = [

            'total' => Customer::count(),

            'with_booking' => Customer::has(
                'bookings'
            )->count(),

            'without_booking' => Customer::doesntHave(
                'bookings'
            )->count(),

            'total_booking' => Customer::has(
                'bookings'
            )
                ->withCount('bookings')
                ->get()
                ->sum('bookings_count'),
        ];


        /*
        |--------------------------------------------------------------------------
        | RETURN INERTIA
        |--------------------------------------------------------------------------
        */

        return Inertia::render(
            'Admin/Customers/Index',
            [

                'customers' => $customers,

                'statistics' => $statistics,

                'filters' => [

                    'search' => $request->input(
                        'search',
                        ''
                    ),

                ],
            ]
        );
    }


    /**
     * Menampilkan detail customer.
     */
    public function show(Customer $customer)
    {
        $customer->load([
            'bookings' => function ($query) {

                $query
                    ->with([
                        'tourPackage',
                        'service',
                        'vehicle',
                        'destination',
                    ])
                    ->latest('id');
            },
        ]);


        return Inertia::render(
            'Admin/Customers/Show',
            [
                'customer' => $customer,
            ]
        );
    }
}