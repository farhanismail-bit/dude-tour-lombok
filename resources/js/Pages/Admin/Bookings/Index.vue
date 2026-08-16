<script setup>
import { computed, ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'

import {
    Search,
    RefreshCw,
    Eye,
    CalendarDays,
    Users,
    ClipboardList,
    Clock3,
    CheckCircle2,
    CircleDollarSign,
} from 'lucide-vue-next'


/*
|--------------------------------------------------------------------------
| PROPS
|--------------------------------------------------------------------------
*/

const props = defineProps({
    bookings: {
        type: Object,
        required: true,
    },

    statistics: {
        type: Object,
        default: () => ({
            total: 0,
            pending: 0,
            confirmed: 0,
            completed: 0,
            cancelled: 0,
            unpaid: 0,
            paid: 0,
        }),
    },

    filters: {
        type: Object,
        default: () => ({
            search: '',
            status: 'all',
            payment_status: 'all',
        }),
    },
})


/*
|--------------------------------------------------------------------------
| DATA BOOKING
|--------------------------------------------------------------------------
*/

const bookingRows = computed(() => {
    if (!props.bookings) {
        return []
    }

    if (!Array.isArray(props.bookings.data)) {
        return []
    }

    return props.bookings.data
})


/*
|--------------------------------------------------------------------------
| FILTER
|--------------------------------------------------------------------------
*/

const search = ref(
    props.filters?.search ?? ''
)

const status = ref(
    props.filters?.status ?? 'all'
)

const paymentStatus = ref(
    props.filters?.payment_status ?? 'all'
)


/*
|--------------------------------------------------------------------------
| APPLY FILTER
|--------------------------------------------------------------------------
*/

const applyFilter = () => {
    router.get(
        '/admin/bookings',
        {
            search: search.value,
            status: status.value,
            payment_status: paymentStatus.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    )
}


/*
|--------------------------------------------------------------------------
| RESET FILTER
|--------------------------------------------------------------------------
*/

const resetFilter = () => {
    search.value = ''
    status.value = 'all'
    paymentStatus.value = 'all'

    router.get(
        '/admin/bookings',
        {},
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    )
}


/*
|--------------------------------------------------------------------------
| STATUS LABEL
|--------------------------------------------------------------------------
*/

const statusLabel = (value) => {
    const labels = {
        pending: 'Pending',
        confirmed: 'Confirmed',
        completed: 'Completed',
        cancelled: 'Cancelled',
    }

    return labels[value] ?? value ?? '-'
}


/*
|--------------------------------------------------------------------------
| PAYMENT LABEL
|--------------------------------------------------------------------------
*/

const paymentStatusLabel = (value) => {
    const labels = {
        unpaid: 'Belum Bayar',
        paid: 'Sudah Bayar',
        refunded: 'Refund',
    }

    return labels[value] ?? value ?? '-'
}


/*
|--------------------------------------------------------------------------
| FORMAT PRICE
|--------------------------------------------------------------------------
*/

const formatPrice = (value) => {
    return new Intl.NumberFormat(
        'id-ID',
        {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0,
        }
    ).format(Number(value ?? 0))
}


/*
|--------------------------------------------------------------------------
| FORMAT DATE
|--------------------------------------------------------------------------
*/

const formatDate = (value) => {
    if (!value) {
        return '-'
    }

    const date = new Date(value)

    if (Number.isNaN(date.getTime())) {
        return '-'
    }

    return new Intl.DateTimeFormat(
        'id-ID',
        {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
        }
    ).format(date)
}


/*
|--------------------------------------------------------------------------
| STATUS CLASS
|--------------------------------------------------------------------------
*/

const statusClass = (value) => {
    const classes = {
        pending:
            'bg-amber-50 text-amber-700 border-amber-200',

        confirmed:
            'bg-blue-50 text-blue-700 border-blue-200',

        completed:
            'bg-emerald-50 text-emerald-700 border-emerald-200',

        cancelled:
            'bg-red-50 text-red-700 border-red-200',
    }

    return (
        classes[value] ??
        'bg-slate-50 text-slate-600 border-slate-200'
    )
}


/*
|--------------------------------------------------------------------------
| PAYMENT CLASS
|--------------------------------------------------------------------------
*/

const paymentClass = (value) => {
    const classes = {
        unpaid:
            'bg-amber-50 text-amber-700 border-amber-200',

        paid:
            'bg-emerald-50 text-emerald-700 border-emerald-200',

        refunded:
            'bg-purple-50 text-purple-700 border-purple-200',
    }

    return (
        classes[value] ??
        'bg-slate-50 text-slate-600 border-slate-200'
    )
}


/*
|--------------------------------------------------------------------------
| PAGINATION
|--------------------------------------------------------------------------
*/

const paginationLinks = computed(() => {
    return props.bookings?.links ?? []
})


/*
|--------------------------------------------------------------------------
| STATISTICS SAFE VALUE
|--------------------------------------------------------------------------
*/

const totalBooking = computed(() => {
    return props.statistics?.total ?? 0
})

const pendingBooking = computed(() => {
    return props.statistics?.pending ?? 0
})

const confirmedBooking = computed(() => {
    return props.statistics?.confirmed ?? 0
})

const completedBooking = computed(() => {
    return props.statistics?.completed ?? 0
})


/*
|--------------------------------------------------------------------------
| PAGINATION INFO
|--------------------------------------------------------------------------
*/

const from = computed(() => {
    return props.bookings?.from ?? 0
})

const to = computed(() => {
    return props.bookings?.to ?? 0
})

const total = computed(() => {
    return props.bookings?.total ?? 0
})

</script>


<template>

    <Head title="Booking - Admin" />


    <div class="min-h-screen bg-[#f7f9fb] text-slate-800">

        <!-- =========================================================
             HEADER
        ========================================================== -->

        <header class="border-b border-slate-200 bg-white">

            <div
                class="mx-auto flex min-h-[76px] max-w-[1500px] items-center justify-between px-6 lg:px-10"
            >

                <div>

                    <p
                        class="text-xs font-bold tracking-[0.22em] text-[#b87a28]"
                    >
                        ADMINISTRATION
                    </p>

                    <h1
                        class="mt-1 text-2xl font-black text-[#172331]"
                    >
                        Booking Management
                    </h1>

                </div>


                <Link
                    href="/"
                    class="rounded-lg border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:border-[#b87a28] hover:text-[#b87a28]"
                >
                    Lihat Website
                </Link>

            </div>

        </header>


        <!-- =========================================================
             MAIN
        ========================================================== -->

        <main
            class="mx-auto max-w-[1500px] px-6 py-8 lg:px-10"
        >


            <!-- =====================================================
                 STATISTICS
            ====================================================== -->

            <div
                class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4"
            >

                <!-- TOTAL -->

                <div class="stat-card">

                    <div class="stat-icon">

                        <ClipboardList :size="23" />

                    </div>

                    <div>

                        <p class="stat-label">
                            Total Booking
                        </p>

                        <p class="stat-value">
                            {{ totalBooking }}
                        </p>

                    </div>

                </div>


                <!-- PENDING -->

                <div class="stat-card">

                    <div
                        class="stat-icon bg-amber-50 text-amber-600"
                    >

                        <Clock3 :size="23" />

                    </div>

                    <div>

                        <p class="stat-label">
                            Pending
                        </p>

                        <p class="stat-value">
                            {{ pendingBooking }}
                        </p>

                    </div>

                </div>


                <!-- CONFIRMED -->

                <div class="stat-card">

                    <div
                        class="stat-icon bg-blue-50 text-blue-600"
                    >

                        <CheckCircle2 :size="23" />

                    </div>

                    <div>

                        <p class="stat-label">
                            Confirmed
                        </p>

                        <p class="stat-value">
                            {{ confirmedBooking }}
                        </p>

                    </div>

                </div>


                <!-- COMPLETED -->

                <div class="stat-card">

                    <div
                        class="stat-icon bg-emerald-50 text-emerald-600"
                    >

                        <CircleDollarSign :size="23" />

                    </div>

                    <div>

                        <p class="stat-label">
                            Completed
                        </p>

                        <p class="stat-value">
                            {{ completedBooking }}
                        </p>

                    </div>

                </div>

            </div>


            <!-- =====================================================
                 FILTER
            ====================================================== -->

            <section
                class="mt-7 rounded-2xl bg-white p-5 shadow-sm"
            >

                <div
                    class="flex flex-col gap-4 lg:flex-row lg:items-end"
                >

                    <!-- SEARCH -->

                    <div class="flex-1">

                        <label class="filter-label">
                            Cari Booking
                        </label>

                        <div class="relative">

                            <Search
                                :size="19"
                                class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"
                            />

                            <input
                                v-model="search"
                                type="text"
                                class="filter-input pl-11"
                                placeholder="Kode booking, nama, email, atau paket..."
                                @keyup.enter="applyFilter"
                            />

                        </div>

                    </div>


                    <!-- STATUS -->

                    <div class="w-full lg:w-[210px]">

                        <label class="filter-label">
                            Status Booking
                        </label>

                        <select
                            v-model="status"
                            class="filter-input"
                        >

                            <option value="all">
                                Semua Status
                            </option>

                            <option value="pending">
                                Pending
                            </option>

                            <option value="confirmed">
                                Confirmed
                            </option>

                            <option value="completed">
                                Completed
                            </option>

                            <option value="cancelled">
                                Cancelled
                            </option>

                        </select>

                    </div>


                    <!-- PAYMENT -->

                    <div class="w-full lg:w-[210px]">

                        <label class="filter-label">
                            Pembayaran
                        </label>

                        <select
                            v-model="paymentStatus"
                            class="filter-input"
                        >

                            <option value="all">
                                Semua Pembayaran
                            </option>

                            <option value="unpaid">
                                Belum Bayar
                            </option>

                            <option value="paid">
                                Sudah Bayar
                            </option>

                            <option value="refunded">
                                Refund
                            </option>

                        </select>

                    </div>


                    <!-- SEARCH BUTTON -->

                    <button
                        type="button"
                        class="flex h-[48px] items-center justify-center gap-2 rounded-lg bg-[#172331] px-6 text-sm font-bold text-white transition hover:bg-[#b87a28]"
                        @click="applyFilter"
                    >

                        <Search :size="18" />

                        Cari

                    </button>


                    <!-- RESET -->

                    <button
                        type="button"
                        class="flex h-[48px] items-center justify-center rounded-lg border border-slate-200 px-4 text-slate-500 transition hover:border-[#b87a28] hover:text-[#b87a28]"
                        title="Reset Filter"
                        @click="resetFilter"
                    >

                        <RefreshCw :size="18" />

                    </button>

                </div>

            </section>


            <!-- =====================================================
                 BOOKING TABLE
            ====================================================== -->

            <section
                class="mt-7 overflow-hidden rounded-2xl bg-white shadow-sm"
            >

                <!-- HEADER -->

                <div
                    class="flex flex-col gap-2 border-b border-slate-100 px-6 py-5 md:flex-row md:items-center md:justify-between"
                >

                    <div>

                        <h2
                            class="text-lg font-bold text-[#172331]"
                        >
                            Daftar Booking
                        </h2>

                        <p
                            class="mt-1 text-sm text-slate-400"
                        >
                            Semua reservasi yang masuk dari website.
                        </p>

                    </div>


                    <div
                        class="text-sm text-slate-400"
                    >

                        Menampilkan

                        <span class="font-bold text-slate-700">
                            {{ from }}
                        </span>

                        -

                        <span class="font-bold text-slate-700">
                            {{ to }}
                        </span>

                        dari

                        <span class="font-bold text-slate-700">
                            {{ total }}
                        </span>

                    </div>

                </div>


                <!-- =================================================
                     DESKTOP
                ================================================== -->

                <div class="hidden overflow-x-auto lg:block">

                    <table class="w-full min-w-[1200px]">

                        <thead>

                            <tr
                                class="border-b border-slate-100 bg-slate-50"
                            >

                                <th class="table-heading">
                                    Booking
                                </th>

                                <th class="table-heading">
                                    Customer
                                </th>

                                <th class="table-heading">
                                    Paket
                                </th>

                                <th class="table-heading">
                                    Perjalanan
                                </th>

                                <th class="table-heading">
                                    Total
                                </th>

                                <th class="table-heading">
                                    Status
                                </th>

                                <th class="table-heading">
                                    Pembayaran
                                </th>

                                <th class="table-heading text-right">
                                    Aksi
                                </th>

                            </tr>

                        </thead>


                        <tbody
                            v-if="bookingRows.length > 0"
                            class="divide-y divide-slate-100"
                        >

                            <tr
                                v-for="booking in bookingRows"
                                :key="booking.id"
                                class="transition hover:bg-slate-50"
                            >

                                <!-- BOOKING -->

                                <td class="table-cell">

                                    <p
                                        class="font-bold text-[#172331]"
                                    >
                                        {{ booking.booking_code ?? '-' }}
                                    </p>

                                    <p
                                        class="mt-1 text-xs text-slate-400"
                                    >
                                        ID #{{ booking.id }}
                                    </p>

                                </td>


                                <!-- CUSTOMER -->

                                <td class="table-cell">

                                    <p
                                        class="font-semibold text-slate-700"
                                    >
                                        {{ booking.customer?.name ?? '-' }}
                                    </p>

                                    <p
                                        class="mt-1 text-xs text-slate-400"
                                    >
                                        {{ booking.customer?.phone ?? '-' }}
                                    </p>

                                </td>


                                <!-- PACKAGE -->

                                <td class="table-cell">

                                    <p
                                        class="max-w-[220px] font-semibold text-slate-700"
                                    >
                                        {{
                                            booking.tour_package?.name
                                            ?? 'Custom Trip'
                                        }}
                                    </p>

                                    <p
                                        class="mt-1 text-xs text-slate-400"
                                    >
                                        {{ booking.service?.name ?? '-' }}
                                    </p>

                                </td>


                                <!-- TRIP -->

                                <td class="table-cell">

                                    <div
                                        class="flex items-center gap-2"
                                    >

                                        <CalendarDays
                                            :size="16"
                                            class="shrink-0 text-[#b87a28]"
                                        />

                                        <span
                                            class="text-sm font-medium text-slate-700"
                                        >
                                            {{
                                                formatDate(
                                                    booking.booking_date
                                                )
                                            }}
                                        </span>

                                    </div>


                                    <div
                                        class="mt-2 flex items-center gap-2 text-xs text-slate-400"
                                    >

                                        <Users :size="14" />

                                        {{
                                            booking.number_of_people ?? 0
                                        }}

                                        orang

                                    </div>

                                </td>


                                <!-- TOTAL -->

                                <td class="table-cell">

                                    <p
                                        class="font-bold text-[#b87a28]"
                                    >
                                        {{
                                            formatPrice(
                                                booking.total_price
                                            )
                                        }}
                                    </p>

                                </td>


                                <!-- STATUS -->

                                <td class="table-cell">

                                    <span
                                        class="inline-flex rounded-full border px-3 py-1.5 text-xs font-bold"
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


                                <!-- PAYMENT -->

                                <td class="table-cell">

                                    <span
                                        class="inline-flex rounded-full border px-3 py-1.5 text-xs font-bold"
                                        :class="
                                            paymentClass(
                                                booking.payment_status
                                            )
                                        "
                                    >
                                        {{
                                            paymentStatusLabel(
                                                booking.payment_status
                                            )
                                        }}
                                    </span>

                                </td>


                                <!-- ACTION -->

                                <td class="table-cell text-right">

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


                        <!-- EMPTY -->

                        <tbody v-else>

                            <tr>

                                <td
                                    colspan="8"
                                    class="px-6 py-20 text-center"
                                >

                                    <div
                                        class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-slate-400"
                                    >

                                        <ClipboardList
                                            :size="28"
                                        />

                                    </div>

                                    <h3
                                        class="mt-5 text-lg font-bold text-slate-700"
                                    >
                                        Belum Ada Booking
                                    </h3>

                                    <p
                                        class="mt-2 text-sm text-slate-400"
                                    >
                                        Booking dari website akan muncul
                                        di sini.
                                    </p>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>


                <!-- =================================================
                     MOBILE
                ================================================== -->

                <div
                    class="divide-y divide-slate-100 lg:hidden"
                >

                    <div
                        v-for="booking in bookingRows"
                        :key="booking.id"
                        class="p-5"
                    >

                        <div
                            class="flex items-start justify-between gap-4"
                        >

                            <div>

                                <p
                                    class="font-bold text-[#172331]"
                                >
                                    {{ booking.booking_code ?? '-' }}
                                </p>

                                <p
                                    class="mt-1 text-sm text-slate-500"
                                >
                                    {{ booking.customer?.name ?? '-' }}
                                </p>

                            </div>


                            <span
                                class="inline-flex rounded-full border px-3 py-1 text-xs font-bold"
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

                        </div>


                        <div
                            class="mt-5 grid grid-cols-2 gap-4"
                        >

                            <div>

                                <p class="mobile-label">
                                    Paket
                                </p>

                                <p class="mobile-value">
                                    {{
                                        booking.tour_package?.name
                                        ?? 'Custom Trip'
                                    }}
                                </p>

                            </div>


                            <div>

                                <p class="mobile-label">
                                    Tanggal
                                </p>

                                <p class="mobile-value">
                                    {{
                                        formatDate(
                                            booking.booking_date
                                        )
                                    }}
                                </p>

                            </div>


                            <div>

                                <p class="mobile-label">
                                    Peserta
                                </p>

                                <p class="mobile-value">
                                    {{
                                        booking.number_of_people
                                        ?? 0
                                    }}

                                    orang
                                </p>

                            </div>


                            <div>

                                <p class="mobile-label">
                                    Total
                                </p>

                                <p
                                    class="mobile-value text-[#b87a28]"
                                >
                                    {{
                                        formatPrice(
                                            booking.total_price
                                        )
                                    }}
                                </p>

                            </div>

                        </div>


                        <div class="mt-4">

                            <p class="mobile-label">
                                Pembayaran
                            </p>

                            <span
                                class="mt-1 inline-flex rounded-full border px-3 py-1 text-xs font-bold"
                                :class="
                                    paymentClass(
                                        booking.payment_status
                                    )
                                "
                            >
                                {{
                                    paymentStatusLabel(
                                        booking.payment_status
                                    )
                                }}
                            </span>

                        </div>


                        <Link
                            :href="`/admin/bookings/${booking.id}`"
                            class="mt-5 flex w-full items-center justify-center gap-2 rounded-lg bg-[#172331] py-3 text-sm font-bold text-white transition hover:bg-[#b87a28]"
                        >

                            <Eye :size="17" />

                            Lihat Detail

                        </Link>

                    </div>


                    <!-- MOBILE EMPTY -->

                    <div
                        v-if="bookingRows.length === 0"
                        class="px-6 py-16 text-center"
                    >

                        <ClipboardList
                            :size="35"
                            class="mx-auto text-slate-300"
                        />

                        <p
                            class="mt-4 font-bold text-slate-600"
                        >
                            Belum ada booking
                        </p>

                    </div>

                </div>


                <!-- =================================================
                     PAGINATION
                ================================================== -->

                <div
                    v-if="paginationLinks.length > 3"
                    class="flex items-center justify-between border-t border-slate-100 px-6 py-5"
                >

                    <p
                        class="hidden text-sm text-slate-400 sm:block"
                    >

                        Halaman

                        {{ bookings.current_page }}

                        dari

                        {{ bookings.last_page }}

                    </p>


                    <div class="flex items-center gap-1">

                        <template
                            v-for="(link, index) in paginationLinks"
                            :key="index"
                        >

                            <Link
                                v-if="link.url"
                                :href="link.url"
                                class="flex h-9 min-w-9 items-center justify-center rounded-lg px-3 text-sm font-semibold transition"
                                :class="
                                    link.active
                                        ? 'bg-[#172331] text-white'
                                        : 'text-slate-500 hover:bg-slate-100'
                                "
                                v-html="link.label"
                            />

                            <span
                                v-else
                                class="flex h-9 min-w-9 items-center justify-center rounded-lg px-3 text-sm text-slate-300"
                                v-html="link.label"
                            />

                        </template>

                    </div>

                </div>

            </section>

        </main>

    </div>

</template>


<style scoped>

.stat-card {
    display: flex;
    align-items: center;
    gap: 16px;
    border-radius: 18px;
    background: white;
    padding: 22px;
    box-shadow: 0 8px 30px rgba(20, 35, 49, 0.05);
}

.stat-icon {
    display: flex;
    height: 50px;
    width: 50px;
    flex-shrink: 0;
    align-items: center;
    justify-content: center;
    border-radius: 14px;
    background: #fff5e3;
    color: #b87a28;
}

.stat-label {
    font-size: 12px;
    font-weight: 600;
    color: #94a3b8;
}

.stat-value {
    margin-top: 3px;
    font-size: 26px;
    font-weight: 900;
    color: #172331;
}

.filter-label {
    display: block;
    margin-bottom: 8px;
    font-size: 12px;
    font-weight: 700;
    color: #64748b;
}

.filter-input {
    height: 48px;
    width: 100%;
    border: 1px solid #e2e8f0;
    border-radius: 9px;
    background: white;
    padding: 0 14px;
    font-size: 14px;
    color: #334155;
    outline: none;
    transition: 0.2s ease;
}

.filter-input:focus {
    border-color: #b87a28;
    box-shadow: 0 0 0 3px rgba(184, 122, 40, 0.08);
}

.table-heading {
    padding: 15px 18px;
    text-align: left;
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: #94a3b8;
}

.table-cell {
    padding: 18px;
    vertical-align: middle;
}

.mobile-label {
    font-size: 11px;
    color: #94a3b8;
}

.mobile-value {
    margin-top: 3px;
    font-size: 13px;
    font-weight: 700;
    color: #334155;
}

</style>