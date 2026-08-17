<script setup>

import { computed } from 'vue'

import {
    Head,
    Link,
} from '@inertiajs/vue3'

import {
    ArrowRight,
    CalendarDays,
    CheckCircle2,
    Clock3,
    CarFront,
    Eye,
    MapPin,
    Package,
    Star,
    Users,
} from 'lucide-vue-next'


/*
|--------------------------------------------------------------------------
| PROPS
|--------------------------------------------------------------------------
*/

const props = defineProps({

    /*
    |--------------------------------------------------------------------------
    | BOOKING STATISTICS
    |--------------------------------------------------------------------------
    */

    bookingStatistics: {
        type: Object,
        default: () => ({})
    },


    /*
    |--------------------------------------------------------------------------
    | MASTER DATA STATISTICS
    |--------------------------------------------------------------------------
    */

    masterStatistics: {
        type: Object,
        default: () => ({})
    },


    /*
    |--------------------------------------------------------------------------
    | RECENT CUSTOMERS
    |--------------------------------------------------------------------------
    */

    recentCustomers: {
        type: Array,
        default: () => []
    },


    /*
    |--------------------------------------------------------------------------
    | RECENT BOOKINGS
    |--------------------------------------------------------------------------
    */

    recentBookings: {
        type: Array,
        default: () => []
    },


    /*
    |--------------------------------------------------------------------------
    | RECENT TOUR PACKAGES
    |--------------------------------------------------------------------------
    */

    recentTourPackages: {
        type: Array,
        default: () => []
    },


    /*
    |--------------------------------------------------------------------------
    | RECENT DESTINATIONS
    |--------------------------------------------------------------------------
    */

    recentDestinations: {
        type: Array,
        default: () => []
    },


    /*
    |--------------------------------------------------------------------------
    | BOOKING TREND
    |--------------------------------------------------------------------------
    */

    bookingTrend: {
        type: Array,
        default: () => []
    },

})


/*
|--------------------------------------------------------------------------
| FORMAT RUPIAH
|--------------------------------------------------------------------------
*/

const rupiah = (value) => {

    return new Intl.NumberFormat(
        'id-ID',
        {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0,
        }
    ).format(
        Number(value ?? 0)
    )

}


/*
|--------------------------------------------------------------------------
| FORMAT NUMBER
|--------------------------------------------------------------------------
*/

const number = (value) => {

    return new Intl.NumberFormat(
        'id-ID'
    ).format(
        Number(value ?? 0)
    )

}


/*
|--------------------------------------------------------------------------
| FORMAT DATE
|--------------------------------------------------------------------------
*/

const date = (value) => {

    if (!value) {
        return '-'
    }

    return new Intl.DateTimeFormat(
        'id-ID',
        {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
        }
    ).format(
        new Date(value)
    )

}


/*
|--------------------------------------------------------------------------
| STATUS LABEL
|--------------------------------------------------------------------------
*/

const statusLabel = (value) => {

    return {
        pending: 'Pending',
        confirmed: 'Confirmed',
        completed: 'Completed',
        cancelled: 'Cancelled',
    }[value] ?? value

}


/*
|--------------------------------------------------------------------------
| STATUS CLASS
|--------------------------------------------------------------------------
*/

const statusClass = (value) => {

    return {
        pending:
            'bg-amber-50 text-amber-700 border-amber-200',

        confirmed:
            'bg-blue-50 text-blue-700 border-blue-200',

        completed:
            'bg-emerald-50 text-emerald-700 border-emerald-200',

        cancelled:
            'bg-red-50 text-red-700 border-red-200',

    }[value]
        ?? 'bg-slate-50 text-slate-600 border-slate-200'

}


/*
|--------------------------------------------------------------------------
| MAX TREND
|--------------------------------------------------------------------------
*/

const maxTrend = computed(() => {

    const values = props.bookingTrend.map(
        item => Number(item.total ?? 0)
    )

    return Math.max(
        ...values,
        1
    )

})


/*
|--------------------------------------------------------------------------
| BAR HEIGHT
|--------------------------------------------------------------------------
*/

const barHeight = (value) => {

    return Math.max(
        8,
        Math.round(
            (
                Number(value ?? 0)
                / maxTrend.value
            ) * 100
        )
    )

}


/*
|--------------------------------------------------------------------------
| CUSTOMER INITIAL
|--------------------------------------------------------------------------
*/

const customerInitial = (name) => {

    if (!name) {
        return 'C'
    }

    return name
        .charAt(0)
        .toUpperCase()

}

</script>


<template>

    <Head title="Admin Dashboard" />


    <div
        class="min-h-screen bg-[#f6f8fb] text-[#172331]"
    >


        <!--
        |--------------------------------------------------------------------------
        | HEADER
        |--------------------------------------------------------------------------
        -->

        <header
            class="border-b border-slate-200 bg-white"
        >

            <div
                class="mx-auto flex max-w-[1500px] items-center justify-between px-6 py-5 lg:px-10"
            >

                <div>

                    <p
                        class="text-xs font-extrabold tracking-[0.25em] text-[#b87a28]"
                    >
                        ADMINISTRATION
                    </p>

                    <h1
                        class="mt-1 text-3xl font-black"
                    >
                        Dashboard
                    </h1>

                    <p
                        class="mt-1 text-sm text-slate-500"
                    >
                        Ringkasan aktivitas Dude Tour & Trans Lombok.
                    </p>

                </div>


                <div
                    class="flex gap-3"
                >

                    <Link
                        href="/"
                        target="_blank"
                        class="hidden rounded-xl border border-slate-200 px-5 py-3 text-sm font-bold sm:inline-flex"
                    >
                        Lihat Website
                    </Link>


                    <Link
                        href="/admin/bookings"
                        class="inline-flex items-center gap-2 rounded-xl bg-[#172331] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#b87a28]"
                    >

                        <CalendarDays :size="18" />

                        Booking

                    </Link>

                </div>

            </div>

        </header>


        <!--
        |--------------------------------------------------------------------------
        | MAIN
        |--------------------------------------------------------------------------
        -->

        <main
            class="mx-auto max-w-[1500px] px-6 py-8 lg:px-10"
        >


            <!--
            |--------------------------------------------------------------------------
            | WELCOME
            |--------------------------------------------------------------------------
            -->

            <section
                class="mb-7 rounded-[26px] bg-[#172331] p-7 text-white shadow-xl lg:p-9"
            >

                <p
                    class="text-xs font-extrabold tracking-[0.25em] text-[#e5bd73]"
                >
                    DUDE TOUR & TRANS LOMBOK
                </p>

                <h2
                    class="mt-3 text-3xl font-black lg:text-4xl"
                >
                    Selamat Datang di Dashboard Admin
                </h2>

                <p
                    class="mt-3 max-w-2xl leading-7 text-white/65"
                >
                    Pantau booking, pembayaran, customer,
                    paket tour, kendaraan, dan destinasi
                    dari satu tempat.
                </p>

            </section>


            <!--
            |--------------------------------------------------------------------------
            | BOOKING STATISTICS
            |--------------------------------------------------------------------------
            -->

            <section
                class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4"
            >


                <!-- TOTAL BOOKING -->

                <div class="stat-card">

                    <div
                        class="icon bg-[#fff4df] text-[#b87a28]"
                    >
                        <CalendarDays :size="23" />
                    </div>

                    <div>

                        <p class="label">
                            Total Booking
                        </p>

                        <p class="value">
                            {{ bookingStatistics.total ?? 0 }}
                        </p>

                    </div>

                </div>


                <!-- PENDING -->

                <div class="stat-card">

                    <div
                        class="icon bg-amber-50 text-amber-600"
                    >
                        <Clock3 :size="23" />
                    </div>

                    <div>

                        <p class="label">
                            Pending
                        </p>

                        <p class="value">
                            {{ bookingStatistics.pending ?? 0 }}
                        </p>

                    </div>

                </div>


                <!-- CONFIRMED -->

                <div class="stat-card">

                    <div
                        class="icon bg-blue-50 text-blue-600"
                    >
                        <CheckCircle2 :size="23" />
                    </div>

                    <div>

                        <p class="label">
                            Confirmed
                        </p>

                        <p class="value">
                            {{ bookingStatistics.confirmed ?? 0 }}
                        </p>

                    </div>

                </div>


                <!-- COMPLETED -->

                <div class="stat-card">

                    <div
                        class="icon bg-emerald-50 text-emerald-600"
                    >
                        <CheckCircle2 :size="23" />
                    </div>

                    <div>

                        <p class="label">
                            Completed
                        </p>

                        <p class="value">
                            {{ bookingStatistics.completed ?? 0 }}
                        </p>

                    </div>

                </div>

            </section>


            <!--
            |--------------------------------------------------------------------------
            | REVENUE & PAYMENT
            |--------------------------------------------------------------------------
            -->

            <section
                class="mt-5 grid gap-5 lg:grid-cols-3"
            >


                <!-- REVENUE -->

                <div
                    class="rounded-[22px] bg-white p-6 shadow-sm lg:col-span-2"
                >

                    <p class="label">
                        TOTAL PENDAPATAN
                    </p>

                    <p
                        class="mt-1 text-3xl font-black text-[#b87a28]"
                    >
                        {{ rupiah(bookingStatistics.revenue) }}
                    </p>

                    <p
                        class="mt-1 text-sm text-slate-400"
                    >
                        Booking dengan pembayaran sudah lunas.
                    </p>

                </div>


                <!-- PAYMENT -->

                <div
                    class="rounded-[22px] bg-white p-6 shadow-sm"
                >

                    <p class="label">
                        PEMBAYARAN
                    </p>


                    <div
                        class="mt-4 grid grid-cols-2 gap-3"
                    >

                        <div
                            class="rounded-2xl bg-emerald-50 p-4"
                        >

                            <p
                                class="text-xs font-bold text-emerald-600"
                            >
                                Sudah Bayar
                            </p>

                            <p
                                class="mt-1 text-2xl font-black text-emerald-700"
                            >
                                {{ bookingStatistics.paid ?? 0 }}
                            </p>

                        </div>


                        <div
                            class="rounded-2xl bg-amber-50 p-4"
                        >

                            <p
                                class="text-xs font-bold text-amber-600"
                            >
                                Belum Bayar
                            </p>

                            <p
                                class="mt-1 text-2xl font-black text-amber-700"
                            >
                                {{ bookingStatistics.unpaid ?? 0 }}
                            </p>

                        </div>

                    </div>

                </div>

            </section>


            <!--
            |--------------------------------------------------------------------------
            | MASTER DATA
            |--------------------------------------------------------------------------
            -->

            <section
                class="mt-7"
            >

                <p class="section-label">
                    MASTER DATA
                </p>

                <h2
                    class="mt-1 text-2xl font-black"
                >
                    Data Sistem
                </h2>


                <div
                    class="mt-4 grid gap-5 sm:grid-cols-2 xl:grid-cols-4"
                >


                    <!-- CUSTOMER -->

                    <Link
                        href="/admin/customers"
                        class="master-card"
                    >

                        <div
                            class="master-icon bg-blue-50 text-blue-600"
                        >
                            <Users :size="23" />
                        </div>

                        <div>

                            <p class="label">
                                Customer
                            </p>

                            <p class="number">
                                {{ masterStatistics.customers ?? 0 }}
                            </p>

                            <p class="caption">
                                Customer terdaftar
                            </p>

                        </div>

                        <ArrowRight
                            class="ml-auto text-slate-300"
                        />

                    </Link>


                    <!-- TOUR PACKAGE -->

                    <Link
                        href="/admin/tour-packages"
                        class="master-card"
                    >

                        <div
                            class="master-icon bg-[#fff4df] text-[#b87a28]"
                        >
                            <Package :size="23" />
                        </div>

                        <div>

                            <p class="label">
                                Tour Package
                            </p>

                            <p class="number">
                                {{ masterStatistics.tour_packages ?? 0 }}
                            </p>

                            <p class="caption">
                                {{ masterStatistics.active_tour_packages ?? 0 }}
                                aktif ·
                                {{ masterStatistics.featured_tour_packages ?? 0 }}
                                featured
                            </p>

                        </div>

                        <ArrowRight
                            class="ml-auto text-slate-300"
                        />

                    </Link>


                    <!-- VEHICLE -->

                    <Link
                        href="/admin/bookings"
                        class="master-card"
                    >

                        <div
                            class="master-icon bg-purple-50 text-purple-600"
                        >
                            <CarFront :size="23" />
                        </div>

                        <div>

                            <p class="label">
                                Kendaraan
                            </p>

                            <p class="number">
                                {{ masterStatistics.vehicles ?? 0 }}
                            </p>

                            <p class="caption">
                                {{ masterStatistics.available_vehicles ?? 0 }}
                                tersedia
                            </p>

                        </div>

                        <ArrowRight
                            class="ml-auto text-slate-300"
                        />

                    </Link>


                    <!-- DESTINATION -->

                    <Link
                        href="/admin/destinations"
                        class="master-card"
                    >

                        <div
                            class="master-icon bg-emerald-50 text-emerald-600"
                        >
                            <MapPin :size="23" />
                        </div>

                        <div>

                            <p class="label">
                                Destinasi
                            </p>

                            <p class="number">
                                {{ masterStatistics.destinations ?? 0 }}
                            </p>

                            <p class="caption">
                                {{ masterStatistics.active_destinations ?? 0 }}
                                aktif
                            </p>

                        </div>

                        <ArrowRight
                            class="ml-auto text-slate-300"
                        />

                    </Link>

                </div>

            </section>


            <!--
            |--------------------------------------------------------------------------
            | CUSTOMER TERBARU
            |--------------------------------------------------------------------------
            -->

            <section
                class="mt-7 overflow-hidden rounded-[22px] bg-white shadow-sm"
            >

                <!-- HEADER -->

                <div
                    class="flex items-center justify-between border-b border-slate-100 px-6 py-5"
                >

                    <div>

                        <p class="section-label">
                            CUSTOMER
                        </p>

                        <h2
                            class="mt-1 text-xl font-black"
                        >
                            Customer Terbaru
                        </h2>

                        <p
                            class="mt-1 text-sm text-slate-400"
                        >
                            Customer yang baru terdaftar di sistem.
                        </p>

                    </div>


                    <Link
                        href="/admin/customers"
                        class="inline-flex items-center gap-2 text-sm font-bold text-[#b87a28]"
                    >

                        Lihat semua

                        <ArrowRight :size="17" />

                    </Link>

                </div>


                <!-- TABLE -->

                <div
                    v-if="recentCustomers.length"
                    class="overflow-x-auto"
                >

                    <table
                        class="w-full min-w-[1000px]"
                    >

                        <thead
                            class="bg-slate-50"
                        >

                            <tr>

                                <th class="th">
                                    CUSTOMER
                                </th>

                                <th class="th">
                                    KONTAK
                                </th>

                                <th class="th">
                                    BOOKING
                                </th>

                                <th class="th">
                                    TOTAL TRANSAKSI
                                </th>

                                <th class="th">
                                    TERDAFTAR
                                </th>

                                <th class="th text-right">
                                    AKSI
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <tr
                                v-for="customer in recentCustomers"
                                :key="customer.id"
                                class="border-t border-slate-100 transition hover:bg-slate-50"
                            >

                                <!-- CUSTOMER -->

                                <td class="td">

                                    <div
                                        class="flex items-center gap-3"
                                    >

                                        <div
                                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#fff4df] font-black text-[#b87a28]"
                                        >
                                            {{
                                                customerInitial(
                                                    customer.name
                                                )
                                            }}
                                        </div>


                                        <div>

                                            <b>
                                                {{ customer.name ?? '-' }}
                                            </b>

                                            <small>
                                                Customer #{{ customer.id }}
                                            </small>

                                        </div>

                                    </div>

                                </td>


                                <!-- KONTAK -->

                                <td class="td">

                                    <b>
                                        {{ customer.email ?? '-' }}
                                    </b>

                                    <small>
                                        {{ customer.phone ?? '-' }}
                                    </small>

                                </td>


                                <!-- BOOKING -->

                                <td class="td">

                                    <span
                                        class="inline-flex rounded-full border border-blue-200 bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700"
                                    >

                                        {{ number(customer.bookings_count) }}

                                        Booking

                                    </span>

                                </td>


                                <!-- TOTAL TRANSAKSI -->

                                <td class="td">

                                    <b
                                        class="text-[#b87a28]"
                                    >
                                        {{
                                            rupiah(
                                                customer.bookings_sum_total_price
                                            )
                                        }}
                                    </b>

                                </td>


                                <!-- TERDAFTAR -->

                                <td class="td">

                                    {{ date(customer.created_at) }}

                                </td>


                                <!-- DETAIL -->

                                <td
                                    class="td text-right"
                                >

                                    <Link
                                        :href="`/admin/customers/${customer.id}`"
                                        class="inline-flex items-center gap-2 rounded-lg bg-[#172331] px-4 py-2.5 text-xs font-bold text-white transition hover:bg-[#b87a28]"
                                    >

                                        <Eye :size="16" />

                                        Detail

                                    </Link>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>


                <!-- EMPTY -->

                <div
                    v-else
                    class="px-6 py-14 text-center text-slate-400"
                >
                    Belum ada customer.
                </div>

            </section>


            <!--
            |--------------------------------------------------------------------------
            | BOOKING TREND + STATUS
            |--------------------------------------------------------------------------
            -->

            <section
                class="mt-7 grid gap-5 lg:grid-cols-3"
            >


                <!-- TREND -->

                <div
                    class="rounded-[22px] bg-white p-6 shadow-sm lg:col-span-2"
                >

                    <div
                        class="flex items-center justify-between"
                    >

                        <div>

                            <p class="section-label">
                                ACTIVITY
                            </p>

                            <h2
                                class="mt-1 text-xl font-black"
                            >
                                Booking 7 Hari Terakhir
                            </h2>

                        </div>


                        <Link
                            href="/admin/bookings"
                            class="text-sm font-bold text-[#b87a28]"
                        >
                            Lihat semua
                        </Link>

                    </div>


                    <div
                        class="mt-8 flex h-52 items-end gap-3 sm:gap-5"
                    >

                        <div
                            v-for="item in bookingTrend"
                            :key="item.date"
                            class="flex min-w-0 flex-1 flex-col items-center justify-end gap-2"
                        >

                            <span
                                class="text-xs font-bold text-slate-500"
                            >
                                {{ item.total }}
                            </span>


                            <div
                                class="flex h-36 w-full items-end justify-center"
                            >

                                <div
                                    class="w-full max-w-10 rounded-t-xl bg-gradient-to-t from-[#b87a28] to-[#e5bd73]"
                                    :style="{
                                        height: `${barHeight(item.total)}%`
                                    }"
                                ></div>

                            </div>


                            <span
                                class="text-[11px] font-semibold text-slate-400"
                            >
                                {{ item.label }}
                            </span>

                        </div>

                    </div>

                </div>


                <!-- STATUS -->

                <div
                    class="rounded-[22px] bg-white p-6 shadow-sm"
                >

                    <p class="section-label">
                        BOOKING STATUS
                    </p>

                    <h2
                        class="mt-1 text-xl font-black"
                    >
                        Ringkasan Status
                    </h2>


                    <div
                        class="mt-5 space-y-3"
                    >

                        <div class="status-row">

                            <i
                                class="bg-amber-400"
                            ></i>

                            <span>
                                Pending
                            </span>

                            <b>
                                {{ bookingStatistics.pending ?? 0 }}
                            </b>

                        </div>


                        <div class="status-row">

                            <i
                                class="bg-blue-500"
                            ></i>

                            <span>
                                Confirmed
                            </span>

                            <b>
                                {{ bookingStatistics.confirmed ?? 0 }}
                            </b>

                        </div>


                        <div class="status-row">

                            <i
                                class="bg-emerald-500"
                            ></i>

                            <span>
                                Completed
                            </span>

                            <b>
                                {{ bookingStatistics.completed ?? 0 }}
                            </b>

                        </div>


                        <div class="status-row">

                            <i
                                class="bg-red-500"
                            ></i>

                            <span>
                                Cancelled
                            </span>

                            <b>
                                {{ bookingStatistics.cancelled ?? 0 }}
                            </b>

                        </div>

                    </div>

                </div>

            </section>


            <!--
            |--------------------------------------------------------------------------
            | RECENT BOOKINGS
            |--------------------------------------------------------------------------
            -->

            <section
                class="mt-7 overflow-hidden rounded-[22px] bg-white shadow-sm"
            >

                <div
                    class="flex items-center justify-between border-b border-slate-100 px-6 py-5"
                >

                    <div>

                        <p class="section-label">
                            RESERVATIONS
                        </p>

                        <h2
                            class="mt-1 text-xl font-black"
                        >
                            Booking Terbaru
                        </h2>

                    </div>


                    <Link
                        href="/admin/bookings"
                        class="inline-flex items-center gap-2 text-sm font-bold text-[#b87a28]"
                    >

                        Lihat semua

                        <ArrowRight :size="17" />

                    </Link>

                </div>


                <div
                    v-if="recentBookings.length"
                    class="overflow-x-auto"
                >

                    <table
                        class="w-full min-w-[1000px]"
                    >

                        <thead
                            class="bg-slate-50"
                        >

                            <tr>

                                <th class="th">
                                    BOOKING
                                </th>

                                <th class="th">
                                    CUSTOMER
                                </th>

                                <th class="th">
                                    PAKET
                                </th>

                                <th class="th">
                                    TANGGAL
                                </th>

                                <th class="th">
                                    TOTAL
                                </th>

                                <th class="th">
                                    STATUS
                                </th>

                                <th class="th">
                                    PEMBAYARAN
                                </th>

                                <th class="th text-right">
                                    AKSI
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <tr
                                v-for="booking in recentBookings"
                                :key="booking.id"
                                class="border-t border-slate-100 hover:bg-slate-50"
                            >

                                <td class="td">

                                    <b>
                                        {{ booking.booking_code }}
                                    </b>

                                    <small>
                                        ID #{{ booking.id }}
                                    </small>

                                </td>


                                <td class="td">

                                    <b>
                                        {{ booking.customer?.name ?? '-' }}
                                    </b>

                                    <small>
                                        {{
                                            booking.customer?.phone
                                                ?? booking.customer?.email
                                                ?? '-'
                                        }}
                                    </small>

                                </td>


                                <td class="td">

                                    <b>
                                        {{
                                            booking.tour_package?.name
                                                ?? '-'
                                        }}
                                    </b>

                                </td>


                                <td class="td">

                                    {{
                                        date(
                                            booking.booking_date
                                        )
                                    }}

                                </td>


                                <td class="td">

                                    <b
                                        class="text-[#b87a28]"
                                    >
                                        {{
                                            rupiah(
                                                booking.total_price
                                            )
                                        }}
                                    </b>

                                </td>


                                <td class="td">

                                    <span
                                        class="badge"
                                        :class="
                                            statusClass(
                                                booking.status
                                            )
                                        "
                                    >
                                        {{
                                            statusLabel(
                                                booking.status
                                            )
                                        }}
                                    </span>

                                </td>


                                <td class="td">

                                    <span
                                        class="badge"
                                        :class="
                                            booking.payment_status === 'paid'
                                                ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
                                                : 'bg-amber-50 text-amber-700 border-amber-200'
                                        "
                                    >

                                        {{
                                            booking.payment_status === 'paid'
                                                ? 'Sudah Bayar'
                                                : 'Belum Bayar'
                                        }}

                                    </span>

                                </td>


                                <td
                                    class="td text-right"
                                >

                                    <Link
                                        :href="`/admin/bookings/${booking.id}`"
                                        class="inline-flex items-center gap-2 rounded-lg bg-[#172331] px-4 py-2.5 text-xs font-bold text-white transition hover:bg-[#b87a28]"
                                    >

                                        <Eye :size="16" />

                                        Detail

                                    </Link>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>


                <div
                    v-else
                    class="px-6 py-14 text-center text-slate-400"
                >
                    Belum ada booking.
                </div>

            </section>


            <!--
            |--------------------------------------------------------------------------
            | RECENT TOUR PACKAGES
            |--------------------------------------------------------------------------
            -->

            <section
                class="mt-7 rounded-[22px] bg-white p-6 shadow-sm"
            >

                <div
                    class="flex items-center justify-between"
                >

                    <div>

                        <p class="section-label">
                            TOUR PACKAGES
                        </p>

                        <h2
                            class="mt-1 text-xl font-black"
                        >
                            Paket Tour Terbaru
                        </h2>

                    </div>


                    <Link
                        href="/admin/tour-packages"
                        class="text-sm font-bold text-[#b87a28]"
                    >
                        Kelola
                    </Link>

                </div>


                <div
                    v-if="recentTourPackages.length"
                    class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-5"
                >

                    <Link
                        v-for="tour in recentTourPackages"
                        :key="tour.id"
                        href="/admin/tour-packages"
                        class="rounded-2xl border border-slate-100 bg-slate-50 p-4 transition hover:border-[#e5bd73] hover:bg-white"
                    >

                        <div
                            class="flex items-start justify-between"
                        >

                            <div
                                class="master-icon bg-[#fff4df] text-[#b87a28]"
                            >

                                <Package :size="19" />

                            </div>


                            <Star
                                v-if="tour.is_featured"
                                :size="17"
                                class="text-[#b87a28]"
                                fill="currentColor"
                            />

                        </div>


                        <h3
                            class="mt-4 line-clamp-2 font-black"
                        >
                            {{ tour.name }}
                        </h3>


                        <p
                            class="mt-2 line-clamp-2 text-xs text-slate-400"
                        >
                            {{ tour.short_description }}
                        </p>


                        <div
                            class="mt-4 flex justify-between"
                        >

                            <b
                                class="text-sm text-[#b87a28]"
                            >
                                {{ rupiah(tour.price) }}
                            </b>

                            <span
                                class="text-xs text-slate-400"
                            >
                                {{ tour.duration_days }} Hari
                            </span>

                        </div>

                    </Link>

                </div>


                <div
                    v-else
                    class="py-10 text-center text-slate-400"
                >
                    Belum ada paket tour.
                </div>

            </section>


            <!--
            |--------------------------------------------------------------------------
            | RECENT DESTINATIONS
            |--------------------------------------------------------------------------
            -->

            <section
                class="mt-7 rounded-[22px] bg-white p-6 shadow-sm"
            >

                <!-- HEADER -->

                <div
                    class="flex items-center justify-between"
                >

                    <div>

                        <p class="section-label">
                            DESTINATIONS
                        </p>

                        <h2
                            class="mt-1 text-xl font-black"
                        >
                            Destinasi Terbaru
                        </h2>

                        <p
                            class="mt-1 text-sm text-slate-400"
                        >
                            Destination wisata yang tersedia di sistem.
                        </p>

                    </div>


                    <Link
                        href="/admin/destinations"
                        class="inline-flex items-center gap-2 text-sm font-bold text-[#b87a28]"
                    >

                        Kelola

                        <ArrowRight :size="17" />

                    </Link>

                </div>


                <!-- DESTINATION LIST -->

                <div
                    v-if="recentDestinations.length"
                    class="mt-5 grid gap-5 md:grid-cols-2 xl:grid-cols-3"
                >

                    <Link
                        v-for="destination in recentDestinations"
                        :key="destination.id"
                        :href="`/admin/destinations/${destination.id}`"
                        class="group overflow-hidden rounded-2xl border border-slate-100 bg-slate-50 transition duration-200 hover:-translate-y-1 hover:border-[#e5bd73] hover:bg-white hover:shadow-md"
                    >

                        <!-- IMAGE -->

                        <div
                            class="relative h-44 overflow-hidden bg-slate-100"
                        >

                            <img
                                v-if="destination.image"
                                :src="`/storage/${destination.image}`"
                                :alt="destination.name"
                                class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                            />

                            <div
                                v-else
                                class="flex h-full w-full items-center justify-center bg-gradient-to-br from-emerald-50 to-slate-100 text-emerald-500"
                            >

                                <MapPin :size="42" />

                            </div>


                            <!-- STATUS -->

                            <span
                                class="absolute right-3 top-3 inline-flex items-center rounded-full border px-3 py-1 text-[11px] font-bold"
                                :class="
                                    destination.is_active
                                        ? 'border-emerald-200 bg-emerald-50 text-emerald-700'
                                        : 'border-red-200 bg-red-50 text-red-700'
                                "
                            >

                                {{
                                    destination.is_active
                                        ? 'Aktif'
                                        : 'Nonaktif'
                                }}

                            </span>

                        </div>


                        <!-- CONTENT -->

                        <div
                            class="p-5"
                        >

                            <div
                                class="flex items-start justify-between gap-3"
                            >

                                <div>

                                    <h3
                                        class="text-lg font-black text-[#172331]"
                                    >
                                        {{ destination.name }}
                                    </h3>


                                    <p
                                        v-if="destination.location"
                                        class="mt-1 flex items-center gap-1 text-xs font-semibold text-slate-400"
                                    >

                                        <MapPin :size="13" />

                                        {{ destination.location }}

                                    </p>

                                </div>


                                <ArrowRight
                                    :size="18"
                                    class="mt-1 shrink-0 text-slate-300 transition group-hover:text-[#b87a28]"
                                />

                            </div>


                            <!-- CATEGORY -->

                            <div
                                v-if="destination.category"
                                class="mt-3"
                            >

                                <span
                                    class="inline-flex rounded-full bg-[#fff4df] px-3 py-1 text-[11px] font-bold text-[#b87a28]"
                                >

                                    {{ destination.category }}

                                </span>

                            </div>


                            <!-- DESCRIPTION -->

                            <p
                                v-if="destination.short_description"
                                class="mt-3 line-clamp-2 text-xs leading-5 text-slate-400"
                            >

                                {{ destination.short_description }}

                            </p>


                            <!-- PACKAGE COUNT -->

                            <div
                                class="mt-4 flex items-center justify-between border-t border-slate-100 pt-4"
                            >

                                <span
                                    class="text-xs font-semibold text-slate-400"
                                >
                                    Paket Tour
                                </span>


                                <span
                                    class="inline-flex rounded-full border border-blue-200 bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700"
                                >

                                    {{
                                        destination.tour_packages_count ?? 0
                                    }}

                                    Paket

                                </span>

                            </div>

                        </div>

                    </Link>

                </div>


                <!-- EMPTY -->

                <div
                    v-else
                    class="py-12 text-center text-slate-400"
                >

                    <MapPin
                        :size="40"
                        class="mx-auto mb-3 text-slate-300"
                    />

                    <p class="font-semibold">
                        Belum ada destinasi.
                    </p>

                    <p class="mt-1 text-sm">
                        Tambahkan destinasi melalui menu Destination.
                    </p>

                    <Link
                        href="/admin/destinations/create"
                        class="mt-4 inline-flex items-center gap-2 rounded-lg bg-[#172331] px-4 py-2.5 text-xs font-bold text-white transition hover:bg-[#b87a28]"
                    >

                        Tambah Destinasi

                        <ArrowRight :size="15" />

                    </Link>

                </div>

            </section>


        </main>

    </div>

</template>


<style scoped>

.stat-card {

    display: flex;

    align-items: center;

    gap: 1rem;

    border-radius: 22px;

    background: #fff;

    padding: 1.5rem;

    box-shadow:
        0 10px 35px
        rgba(20, 35, 49, .06);

}


.icon,
.master-icon {

    display: flex;

    align-items: center;

    justify-content: center;

    flex-shrink: 0;

    border-radius: 1rem;

    height: 3.5rem;

    width: 3.5rem;

}


.label {

    font-size: .75rem;

    font-weight: 800;

    color: #94a3b8;

}


.value {

    margin-top: .25rem;

    font-size: 1.875rem;

    font-weight: 900;

}


.master-card {

    display: flex;

    align-items: center;

    gap: 1rem;

    border-radius: 20px;

    background: #fff;

    padding: 1.25rem;

    box-shadow:
        0 10px 35px
        rgba(20, 35, 49, .06);

    transition: .2s;

}


.master-card:hover {

    transform: translateY(-2px);

}


.number {

    margin-top: .15rem;

    font-size: 1.7rem;

    font-weight: 900;

}


.caption {

    margin-top: .3rem;

    font-size: .7rem;

    color: #94a3b8;

}


.section-label {

    font-size: .7rem;

    font-weight: 900;

    letter-spacing: .2em;

    color: #b87a28;

}


.status-row {

    display: flex;

    align-items: center;

    gap: .75rem;

    border-radius: .9rem;

    background: #f8fafc;

    padding: .85rem 1rem;

    font-size: .85rem;

}


.status-row i {

    height: .6rem;

    width: .6rem;

    border-radius: 9999px;

}


.status-row b {

    margin-left: auto;

}


.th {

    padding: .9rem 1.25rem;

    text-align: left;

    font-size: .68rem;

    font-weight: 900;

    color: #64748b;

}


.td {

    padding: 1rem 1.25rem;

    font-size: .82rem;

    color: #475569;

}


.td small {

    display: block;

    margin-top: .25rem;

    color: #94a3b8;

    font-size: .7rem;

}


.badge {

    display: inline-flex;

    border: 1px solid;

    border-radius: 9999px;

    padding: .35rem .7rem;

    font-size: .7rem;

    font-weight: 800;

}

</style>