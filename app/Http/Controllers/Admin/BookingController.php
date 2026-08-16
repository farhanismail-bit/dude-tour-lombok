<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookingController extends Controller
{
    /**
     * Menampilkan daftar booking.
     */
    public function index(Request $request)
    {
        $query = Booking::query()
            ->with([
                'customer',
                'service',
                'tourPackage',
                'vehicle',
                'destination',
            ]);

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {

                $q->where(
                    'booking_code',
                    'like',
                    "%{$search}%"
                );

                $q->orWhereHas('customer', function ($customer) use ($search) {

                    $customer->where(
                        'name',
                        'like',
                        "%{$search}%"
                    );

                    $customer->orWhere(
                        'email',
                        'like',
                        "%{$search}%"
                    );

                    $customer->orWhere(
                        'phone',
                        'like',
                        "%{$search}%"
                    );
                });

                $q->orWhereHas('tourPackage', function ($tour) use ($search) {

                    $tour->where(
                        'name',
                        'like',
                        "%{$search}%"
                    );
                });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS FILTER
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('status')
            && $request->input('status') !== 'all'
        ) {

            $query->where(
                'status',
                $request->input('status')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PAYMENT STATUS FILTER
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('payment_status')
            && $request->input('payment_status') !== 'all'
        ) {

            $query->where(
                'payment_status',
                $request->input('payment_status')
            );
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

        $bookings = [
            'data' => $paginator->items(),
            'current_page' => $paginator->currentPage(),
            'from' => $paginator->firstItem(),
            'last_page' => $paginator->lastPage(),
            'per_page' => $paginator->perPage(),
            'to' => $paginator->lastItem(),
            'total' => $paginator->total(),
            'links' => $paginator->linkCollection()->toArray(),
        ];

        /*
        |--------------------------------------------------------------------------
        | STATISTICS
        |--------------------------------------------------------------------------
        */

        $statistics = [

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
        ];

        /*
        |--------------------------------------------------------------------------
        | RETURN
        |--------------------------------------------------------------------------
        */

        return Inertia::render(
            'Admin/Bookings/Index',
            [
                'bookings' => $bookings,

                'statistics' => $statistics,

                'filters' => [
                    'search' => $request->input(
                        'search',
                        ''
                    ),

                    'status' => $request->input(
                        'status',
                        'all'
                    ),

                    'payment_status' => $request->input(
                        'payment_status',
                        'all'
                    ),
                ],
            ]
        );
    }


    /**
     * Menampilkan detail booking.
     */
    public function show(Booking $booking)
    {
        $booking->load([
            'customer',
            'service',
            'tourPackage',
            'vehicle',
            'destination',
        ]);

        return Inertia::render(
            'Admin/Bookings/Show',
            [
                'booking' => $booking,
            ]
        );
    }


    /**
     * Mengubah status booking.
     *
     * Alur:
     *
     * pending + unpaid
     *      ↓
     * confirmed + unpaid
     *
     * pending + paid
     *      ↓
     * pending + paid
     *
     * confirmed + paid
     *      ↓
     * completed + paid
     */
    public function updateStatus(
        Request $request,
        Booking $booking
    ) {
        $validated = $request->validate([
            'status' => [
                'required',
                'in:pending,confirmed,cancelled',
            ],
        ]);

        $newStatus = $validated['status'];

        /*
        |--------------------------------------------------------------------------
        | CANCELLED
        |--------------------------------------------------------------------------
        |
        | Jika admin membatalkan booking, langsung cancelled.
        |
        */

        if ($newStatus === 'cancelled') {

            $booking->update([
                'status' => 'cancelled',
            ]);

            return back()->with(
                'success',
                'Booking berhasil dibatalkan.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | CONFIRMED + PAID = COMPLETED
        |--------------------------------------------------------------------------
        |
        | Jika admin mengonfirmasi booking dan pembayaran sudah lunas,
        | otomatis status menjadi completed.
        |
        */

        if (
            $newStatus === 'confirmed'
            && $booking->payment_status === 'paid'
        ) {

            $booking->update([
                'status' => 'completed',
            ]);

            return back()->with(
                'success',
                'Booking dikonfirmasi dan pembayaran sudah lunas. Booking otomatis menjadi selesai.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | CONFIRMED + UNPAID
        |--------------------------------------------------------------------------
        |
        | Booking baru dikonfirmasi tetapi belum dibayar.
        |
        */

        if ($newStatus === 'confirmed') {

            $booking->update([
                'status' => 'confirmed',
            ]);

            return back()->with(
                'success',
                'Booking berhasil dikonfirmasi. Menunggu pembayaran customer.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PENDING
        |--------------------------------------------------------------------------
        */

        $booking->update([
            'status' => 'pending',
        ]);

        return back()->with(
            'success',
            'Status booking berhasil diperbarui.'
        );
    }


    /**
     * Mengubah status pembayaran.
     *
     * Jika:
     *
     * status = confirmed
     * payment_status = paid
     *
     * maka otomatis:
     *
     * status = completed
     */
    public function updatePaymentStatus(
        Request $request,
        Booking $booking
    ) {
        $validated = $request->validate([
            'payment_status' => [
                'required',
                'in:unpaid,paid',
            ],
        ]);

        $newPaymentStatus = $validated['payment_status'];

        /*
        |--------------------------------------------------------------------------
        | SUDAH BAYAR + SUDAH CONFIRMED
        |--------------------------------------------------------------------------
        |
        | Otomatis menjadi COMPLETED.
        |
        */

        if (
            $newPaymentStatus === 'paid'
            && $booking->status === 'confirmed'
        ) {

            $booking->update([
                'payment_status' => 'paid',
                'status' => 'completed',
            ]);

            return back()->with(
                'success',
                'Pembayaran berhasil dikonfirmasi. Booking otomatis menjadi selesai.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SUDAH BAYAR TAPI BELUM CONFIRMED
        |--------------------------------------------------------------------------
        |
        | Pembayaran boleh dibayar terlebih dahulu.
        | Status booking tetap pending.
        |
        */

        if (
            $newPaymentStatus === 'paid'
            && $booking->status === 'pending'
        ) {

            $booking->update([
                'payment_status' => 'paid',
                'status' => 'pending',
            ]);

            return back()->with(
                'success',
                'Pembayaran berhasil dikonfirmasi. Booking masih menunggu konfirmasi admin.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PEMBAYARAN BELUM BAYAR
        |--------------------------------------------------------------------------
        */

        if ($newPaymentStatus === 'unpaid') {

            /*
            | Jika sebelumnya completed lalu pembayaran dikembalikan
            | menjadi unpaid, status booking dikembalikan menjadi confirmed.
            */

            $newBookingStatus = $booking->status;

            if ($booking->status === 'completed') {
                $newBookingStatus = 'confirmed';
            }

            $booking->update([
                'payment_status' => 'unpaid',
                'status' => $newBookingStatus,
            ]);

            return back()->with(
                'success',
                'Status pembayaran berhasil diubah menjadi belum bayar.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FALLBACK
        |--------------------------------------------------------------------------
        */

        $booking->update([
            'payment_status' => $newPaymentStatus,
        ]);

        return back()->with(
            'success',
            'Status pembayaran berhasil diperbarui.'
        );
    }
}