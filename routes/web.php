<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TourController;
use App\Http\Controllers\TourSearchController;

// ADMIN CONTROLLERS
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\TourPackageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DestinationController;

// MODELS
use App\Models\TourPackage;

// LARAVEL
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    $tourPackages = TourPackage::query()
        ->where('is_active', true)
        ->where('is_featured', true)
        ->with([
            'destinations:id,name,slug',
        ])
        ->latest('id')
        ->take(6)
        ->get();

    return Inertia::render('Home', [

        'canLogin' => Route::has('login'),

        'canRegister' => Route::has('register'),

        'laravelVersion' => Application::VERSION,

        'phpVersion' => PHP_VERSION,

        'tourPackages' => $tourPackages,
    ]);
});


/*
|--------------------------------------------------------------------------
| SEARCH
|--------------------------------------------------------------------------
*/

Route::get(
    '/search',
    [TourSearchController::class, 'index']
)->name('search');


/*
|--------------------------------------------------------------------------
| TOUR DETAIL
|--------------------------------------------------------------------------
*/

Route::get(
    '/tours/{slug}',
    [TourController::class, 'show']
)->name('tours.show');


/*
|--------------------------------------------------------------------------
| BOOKING
|--------------------------------------------------------------------------
*/

Route::get(
    '/booking/create',
    [BookingController::class, 'create']
)->name('booking.create');

Route::post(
    '/booking',
    [BookingController::class, 'store']
)->name('booking.store');

Route::get(
    '/booking/success/{bookingCode}',
    [BookingController::class, 'success']
)->name('booking.success');


/*
|--------------------------------------------------------------------------
| DASHBOARD USER
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {

    return Inertia::render('Dashboard');

})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get(
        '/profile',
        [ProfileController::class, 'edit']
    )->name('profile.edit');

    Route::patch(
        '/profile',
        [ProfileController::class, 'update']
    )->name('profile.update');

    Route::delete(
        '/profile',
        [ProfileController::class, 'destroy']
    )->name('profile.destroy');

});


/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
|
| Semua route di dalam group ini otomatis memiliki:
|
| URL  : /admin/...
| NAME : admin....
|
*/

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {

    /*
|--------------------------------------------------------------------------
| DESTINATIONS ADMIN
|--------------------------------------------------------------------------
*/

Route::resource(
    'destinations',
    DestinationController::class
)->except([
    'show',
]);

Route::get(
    'destinations/{destination}',
    [DestinationController::class, 'show']
)->name(
    'destinations.show'
);

Route::patch(
    'destinations/{destination}/toggle-active',
    [
        DestinationController::class,
        'toggleActive',
    ]
)->name(
    'destinations.toggle-active'
);


        /*
        |--------------------------------------------------------------------------
        | ADMIN DASHBOARD
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/dashboard',
            [DashboardController::class, 'index']
        )->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | ADMIN BOOKING
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/bookings',
            [AdminBookingController::class, 'index']
        )->name('bookings.index');

        Route::get(
            '/bookings/{booking}',
            [AdminBookingController::class, 'show']
        )->name('bookings.show');

        Route::patch(
            '/bookings/{booking}/status',
            [AdminBookingController::class, 'updateStatus']
        )->name('bookings.status');

        Route::patch(
            '/bookings/{booking}/payment-status',
            [AdminBookingController::class, 'updatePaymentStatus']
        )->name('bookings.payment-status');


        /*
        |--------------------------------------------------------------------------
        | ADMIN CUSTOMER
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/customers',
            [CustomerController::class, 'index']
        )->name('customers.index');

        Route::get(
            '/customers/{customer}',
            [CustomerController::class, 'show']
        )->name('customers.show');


        /*
        |--------------------------------------------------------------------------
        | ADMIN TOUR PACKAGES
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'tour-packages',
            TourPackageController::class
        )->except([
            'show',
        ]);

        /*
        |--------------------------------------------------------------------------
        | TOGGLE TOUR PACKAGE ACTIVE
        |--------------------------------------------------------------------------
        */

        Route::patch(
            'tour-packages/{tourPackage}/toggle-active',
            [
                TourPackageController::class,
                'toggleActive',
            ]
        )->name(
            'tour-packages.toggle-active'
        );

        /*
        |--------------------------------------------------------------------------
        | TOGGLE TOUR PACKAGE FEATURED
        |--------------------------------------------------------------------------
        */

        Route::patch(
            'tour-packages/{tourPackage}/toggle-featured',
            [
                TourPackageController::class,
                'toggleFeatured',
            ]
        )->name(
            'tour-packages.toggle-featured'
        );
    });


/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';