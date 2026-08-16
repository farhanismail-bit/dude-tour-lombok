<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TourController;
use App\Http\Controllers\TourSearchController;

// ADMIN
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\TourPackageController;
use App\Http\Controllers\Admin\DashboardController;

// MODEL
use App\Models\TourPackage;

// FUNCTION
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

Route::get('/search', [TourSearchController::class, 'index'])
    ->name('search');


/*
|--------------------------------------------------------------------------
| TOUR DETAIL
|--------------------------------------------------------------------------
*/

Route::get('/tours/{slug}', [TourController::class, 'show'])
    ->name('tours.show');


/*
|--------------------------------------------------------------------------
| BOOKING
|--------------------------------------------------------------------------
*/

Route::get('/booking/create', [BookingController::class, 'create'])
    ->name('booking.create');

Route::post('/booking', [BookingController::class, 'store'])
    ->name('booking.store');

Route::get('/booking/success/{bookingCode}', [BookingController::class, 'success'])
    ->name('booking.success');


/*
|--------------------------------------------------------------------------
| DASHBOARD
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

    Route::get('/profile', [
        ProfileController::class,
        'edit'
    ])->name('profile.edit');

    Route::patch('/profile', [
        ProfileController::class,
        'update'
    ])->name('profile.update');

    Route::delete('/profile', [
        ProfileController::class,
        'destroy'
    ])->name('profile.destroy');

});
Route::prefix('admin')
    ->name('admin.')
    ->group(function () {

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
    });
    /*
/*
|--------------------------------------------------------------------------
| ADMIN - TOUR PACKAGES
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::resource(
            'tour-packages',
            TourPackageController::class
        )->except([
            'show',
        ]);

        Route::patch(
            'tour-packages/{tourPackage}/toggle-active',
            [
                TourPackageController::class,
                'toggleActive',
            ]
        )->name(
            'tour-packages.toggle-active'
        );

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