<script setup>
import { computed, ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'

import {
    ArrowLeft,
    CalendarDays,
    CarFront,
    Check,
    CheckCircle2,
    Clock3,
    CreditCard,
    Mail,
    MapPin,
    Phone,
    User,
    Users,
    X,
    XCircle,
} from 'lucide-vue-next'

/*
|--------------------------------------------------------------------------
| PROPS
|--------------------------------------------------------------------------
*/

const props = defineProps({
    booking: {
        type: Object,
        required: true,
    },
})

/*
|--------------------------------------------------------------------------
| STATE
|--------------------------------------------------------------------------
*/

const processingStatus = ref(false)
const processingPayment = ref(false)

const successMessage = ref('')
const errorMessage = ref('')

/*
|--------------------------------------------------------------------------
| BOOKING
|--------------------------------------------------------------------------
*/

const booking = computed(() => props.booking)

/*
|--------------------------------------------------------------------------
| NORMALIZE STATUS
|--------------------------------------------------------------------------
*/

const normalizedStatus = computed(() => {
    return String(
        booking.value?.status ?? 'pending'
    ).toLowerCase()
})

const normalizedPaymentStatus = computed(() => {
    return String(
        booking.value?.payment_status ?? 'unpaid'
    ).toLowerCase()
})

/*
|--------------------------------------------------------------------------
| STATUS LABEL
|--------------------------------------------------------------------------
*/

const statusLabel = computed(() => {
    const labels = {
        pending: 'Pending',
        confirmed: 'Confirmed',
        completed: 'Completed',
        cancelled: 'Cancelled',
    }

    return labels[normalizedStatus.value]
        ?? normalizedStatus.value
})

const paymentStatusLabel = computed(() => {
    const labels = {
        unpaid: 'Belum Bayar',
        paid: 'Sudah Bayar',
    }

    return labels[normalizedPaymentStatus.value]
        ?? normalizedPaymentStatus.value
})

/*
|--------------------------------------------------------------------------
| STATUS CLASS
|--------------------------------------------------------------------------
*/

const statusClass = computed(() => {
    switch (normalizedStatus.value) {
        case 'confirmed':
            return 'status-confirmed'

        case 'completed':
            return 'status-completed'

        case 'cancelled':
            return 'status-cancelled'

        default:
            return 'status-pending'
    }
})

const paymentClass = computed(() => {
    return normalizedPaymentStatus.value === 'paid'
        ? 'payment-paid'
        : 'payment-unpaid'
})

/*
|--------------------------------------------------------------------------
| FORMAT RUPIAH
|--------------------------------------------------------------------------
*/

const formatRupiah = (value) => {
    const number = Number(value ?? 0)

    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(number)
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
        return value
    }

    return new Intl.DateTimeFormat('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
    }).format(date)
}

/*
|--------------------------------------------------------------------------
| SAFE VALUE
|--------------------------------------------------------------------------
*/

const safeValue = (value, fallback = '-') => {
    if (
        value === null ||
        value === undefined ||
        value === ''
    ) {
        return fallback
    }

    return value
}

/*
|--------------------------------------------------------------------------
| UPDATE BOOKING STATUS
|--------------------------------------------------------------------------
*/

const updateStatus = (newStatus) => {
    if (processingStatus.value) {
        return
    }

    const messages = {
        confirmed:
            'Apakah kamu yakin ingin mengonfirmasi booking ini?',

        completed:
            'Apakah booking ini sudah selesai?',

        cancelled:
            'Apakah kamu yakin ingin menolak / membatalkan booking ini?',
    }

    const message =
        messages[newStatus]
        ?? 'Apakah kamu yakin ingin mengubah status booking ini?'

    if (!window.confirm(message)) {
        return
    }

    processingStatus.value = true
    successMessage.value = ''
    errorMessage.value = ''

    router.patch(
        `/admin/bookings/${booking.value.id}/status`,
        {
            status: newStatus,
        },
        {
            preserveScroll: true,

            onSuccess: () => {
                successMessage.value =
                    'Status booking berhasil diperbarui.'

                setTimeout(() => {
                    successMessage.value = ''
                }, 4000)
            },

            onError: () => {
                errorMessage.value =
                    'Status booking gagal diperbarui. Silakan coba lagi.'

                setTimeout(() => {
                    errorMessage.value = ''
                }, 5000)
            },

            onFinish: () => {
                processingStatus.value = false
            },
        }
    )
}

/*
|--------------------------------------------------------------------------
| UPDATE PAYMENT STATUS
|--------------------------------------------------------------------------
*/

const updatePayment = (newStatus) => {
    if (processingPayment.value) {
        return
    }

    const message =
        newStatus === 'paid'
            ? 'Apakah pembayaran booking ini sudah diterima?'
            : 'Apakah kamu yakin ingin mengubah status pembayaran?'

    if (!window.confirm(message)) {
        return
    }

    processingPayment.value = true
    successMessage.value = ''
    errorMessage.value = ''

    router.patch(
        `/admin/bookings/${booking.value.id}/payment-status`,
        {
            payment_status: newStatus,
        },
        {
            preserveScroll: true,

            onSuccess: () => {
                successMessage.value =
                    'Status pembayaran berhasil diperbarui.'

                setTimeout(() => {
                    successMessage.value = ''
                }, 4000)
            },

            onError: () => {
                errorMessage.value =
                    'Status pembayaran gagal diperbarui. Silakan coba lagi.'

                setTimeout(() => {
                    errorMessage.value = ''
                }, 5000)
            },

            onFinish: () => {
                processingPayment.value = false
            },
        }
    )
}
</script>


<template>
    <Head
        :title="`Booking ${safeValue(booking.booking_code)}`"
    />

    <div class="min-h-screen bg-[#f7f9fb] text-[#172331]">

        <!-- ===================================================== -->
        <!-- HEADER -->
        <!-- ===================================================== -->

        <header class="border-b border-slate-200 bg-[#172331]">

            <div
                class="mx-auto flex max-w-[1500px] items-center justify-between gap-5 px-6 py-5 lg:px-10"
            >

                <!-- LOGO -->

                <Link
                    href="/"
                    class="flex items-center gap-3"
                >
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-tr-[22px] rounded-bl-[22px] bg-gradient-to-br from-[#f3cf83] to-[#b87a28] text-2xl font-black text-[#172331]"
                    >
                        D
                    </div>

                    <div class="leading-tight">

                        <div
                            class="text-lg font-black tracking-[0.2em] text-[#e5bd73]"
                        >
                            DUDE
                        </div>

                        <div
                            class="text-[9px] font-medium tracking-[0.32em] text-white"
                        >
                            TOUR & TRANS
                        </div>

                    </div>
                </Link>


                <!-- BACK -->

                <Link
                    href="/admin/bookings"
                    class="inline-flex items-center gap-2 rounded-xl border border-white/15 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-white/10"
                >
                    <ArrowLeft :size="18" />

                    <span class="hidden sm:inline">
                        Kembali ke Booking
                    </span>

                    <span class="sm:hidden">
                        Kembali
                    </span>
                </Link>

            </div>

        </header>


        <!-- ===================================================== -->
        <!-- MAIN -->
        <!-- ===================================================== -->

        <main
            class="mx-auto max-w-[1500px] px-6 py-8 lg:px-10 lg:py-10"
        >

            <!-- ================================================= -->
            <!-- SUCCESS -->
            <!-- ================================================= -->

            <div
                v-if="successMessage"
                class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-emerald-700"
            >

                <CheckCircle2
                    :size="21"
                />

                <span class="font-semibold">
                    {{ successMessage }}
                </span>

            </div>


            <!-- ================================================= -->
            <!-- ERROR -->
            <!-- ================================================= -->

            <div
                v-if="errorMessage"
                class="mb-6 flex items-center gap-3 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-red-700"
            >

                <XCircle
                    :size="21"
                />

                <span class="font-semibold">
                    {{ errorMessage }}
                </span>

            </div>


            <!-- ================================================= -->
            <!-- TITLE -->
            <!-- ================================================= -->

            <section class="mb-8">

                <div
                    class="flex flex-col justify-between gap-5 md:flex-row md:items-end"
                >

                    <div>

                        <p
                            class="text-xs font-black tracking-[0.28em] text-[#b87a28]"
                        >
                            BOOKING DETAIL
                        </p>

                        <h1
                            class="mt-2 text-3xl font-black tracking-tight text-[#172331] sm:text-4xl"
                        >
                            Detail Booking
                        </h1>

                        <p class="mt-2 text-slate-500">
                            Informasi lengkap reservasi pelanggan.
                        </p>

                    </div>


                    <!-- BOOKING CODE -->

                    <div
                        class="rounded-2xl border border-slate-200 bg-white px-5 py-4 shadow-sm"
                    >

                        <p
                            class="text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400"
                        >
                            Booking Code
                        </p>

                        <p
                            class="mt-1 font-mono text-base font-black text-[#172331]"
                        >
                            {{ safeValue(booking.booking_code) }}
                        </p>

                    </div>

                </div>

            </section>


            <!-- ================================================= -->
            <!-- GRID -->
            <!-- ================================================= -->

            <div
                class="grid items-start gap-7 xl:grid-cols-[minmax(0,1fr)_380px]"
            >

                <!-- ================================================= -->
                <!-- LEFT -->
                <!-- ================================================= -->

                <div class="space-y-7">


                    <!-- ================================================= -->
                    <!-- CUSTOMER -->
                    <!-- ================================================= -->

                    <section class="detail-card">

                        <div class="detail-card-header">

                            <div class="section-icon">
                                <User :size="20" />
                            </div>

                            <div>

                                <p class="section-eyebrow">
                                    CUSTOMER
                                </p>

                                <h2 class="section-heading">
                                    Data Pelanggan
                                </h2>

                            </div>

                        </div>


                        <div
                            class="grid gap-5 border-t border-slate-100 p-6 sm:grid-cols-2 lg:p-7"
                        >

                            <div class="info-box">

                                <div class="info-icon">
                                    <User :size="17" />
                                </div>

                                <div>

                                    <p class="info-label">
                                        Nama
                                    </p>

                                    <p class="info-value">
                                        {{
                                            safeValue(
                                                booking.customer?.name
                                            )
                                        }}
                                    </p>

                                </div>

                            </div>


                            <div class="info-box">

                                <div class="info-icon">
                                    <Mail :size="17" />
                                </div>

                                <div>

                                    <p class="info-label">
                                        Email
                                    </p>

                                    <p class="info-value break-all">
                                        {{
                                            safeValue(
                                                booking.customer?.email
                                            )
                                        }}
                                    </p>

                                </div>

                            </div>


                            <div class="info-box">

                                <div class="info-icon">
                                    <Phone :size="17" />
                                </div>

                                <div>

                                    <p class="info-label">
                                        Telepon
                                    </p>

                                    <p class="info-value">
                                        {{
                                            safeValue(
                                                booking.customer?.phone
                                            )
                                        }}
                                    </p>

                                </div>

                            </div>


                            <div class="info-box">

                                <div class="info-icon">
                                    <Phone :size="17" />
                                </div>

                                <div>

                                    <p class="info-label">
                                        WhatsApp
                                    </p>

                                    <p class="info-value">
                                        {{
                                            safeValue(
                                                booking.customer?.whatsapp,
                                                safeValue(
                                                    booking.customer?.phone
                                                )
                                            )
                                        }}
                                    </p>

                                </div>

                            </div>


                            <div
                                class="info-box sm:col-span-2"
                            >

                                <div class="info-icon">
                                    <MapPin :size="17" />
                                </div>

                                <div>

                                    <p class="info-label">
                                        Alamat
                                    </p>

                                    <p class="info-value">
                                        {{
                                            safeValue(
                                                booking.customer?.address
                                            )
                                        }}
                                    </p>

                                </div>

                            </div>

                        </div>

                    </section>


                    <!-- ================================================= -->
                    <!-- TRIP -->
                    <!-- ================================================= -->

                    <section class="detail-card">

                        <div class="detail-card-header">

                            <div class="section-icon">
                                <CalendarDays :size="20" />
                            </div>

                            <div>

                                <p class="section-eyebrow">
                                    PERJALANAN
                                </p>

                                <h2 class="section-heading">
                                    Detail Perjalanan
                                </h2>

                            </div>

                        </div>


                        <div
                            class="grid gap-5 border-t border-slate-100 p-6 sm:grid-cols-2 lg:p-7"
                        >

                            <!-- DATE -->

                            <div class="trip-item">

                                <div class="trip-icon">
                                    <CalendarDays :size="19" />
                                </div>

                                <div>

                                    <p class="info-label">
                                        Tanggal Perjalanan
                                    </p>

                                    <p class="info-value">
                                        {{
                                            formatDate(
                                                booking.booking_date
                                            )
                                        }}
                                    </p>

                                </div>

                            </div>


                            <!-- PEOPLE -->

                            <div class="trip-item">

                                <div class="trip-icon">
                                    <Users :size="19" />
                                </div>

                                <div>

                                    <p class="info-label">
                                        Jumlah Peserta
                                    </p>

                                    <p class="info-value">
                                        {{
                                            safeValue(
                                                booking.number_of_people,
                                                0
                                            )
                                        }}
                                        Orang
                                    </p>

                                </div>

                            </div>


                            <!-- DESTINATION -->

                            <div class="trip-item">

                                <div class="trip-icon">
                                    <MapPin :size="19" />
                                </div>

                                <div>

                                    <p class="info-label">
                                        Destinasi
                                    </p>

                                    <p class="info-value">
                                        {{
                                            safeValue(
                                                booking.destination?.name
                                            )
                                        }}
                                    </p>

                                    <p
                                        v-if="booking.destination?.location"
                                        class="mt-1 text-xs text-slate-400"
                                    >
                                        {{
                                            booking.destination.location
                                        }}
                                    </p>

                                </div>

                            </div>


                            <!-- SERVICE -->

                            <div class="trip-item">

                                <div class="trip-icon">
                                    <CheckCircle2 :size="19" />
                                </div>

                                <div>

                                    <p class="info-label">
                                        Layanan
                                    </p>

                                    <p class="info-value">
                                        {{
                                            safeValue(
                                                booking.service?.name
                                            )
                                        }}
                                    </p>

                                </div>

                            </div>

                        </div>

                    </section>


                    <!-- ================================================= -->
                    <!-- TOUR PACKAGE -->
                    <!-- ================================================= -->

                    <section
                        v-if="booking.tour_package"
                        class="detail-card"
                    >

                        <div class="detail-card-header">

                            <div class="section-icon">
                                <MapPin :size="20" />
                            </div>

                            <div>

                                <p class="section-eyebrow">
                                    TOUR PACKAGE
                                </p>

                                <h2 class="section-heading">
                                    Paket Tour
                                </h2>

                            </div>

                        </div>


                        <div
                            class="border-t border-slate-100 p-6 lg:p-7"
                        >

                            <div
                                class="rounded-2xl border border-[#ead29c] bg-[#fffaf0] p-5"
                            >

                                <div
                                    class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start"
                                >

                                    <div>

                                        <p
                                            class="text-xs font-black tracking-[0.18em] text-[#b87a28]"
                                        >
                                            {{
                                                safeValue(
                                                    booking.tour_package.tag,
                                                    'TOUR PACKAGE'
                                                )
                                            }}
                                        </p>

                                        <h3
                                            class="mt-2 text-2xl font-black text-[#172331]"
                                        >
                                            {{
                                                safeValue(
                                                    booking.tour_package.name
                                                )
                                            }}
                                        </h3>

                                        <p
                                            v-if="
                                                booking.tour_package.short_description
                                            "
                                            class="mt-2 max-w-2xl leading-7 text-slate-500"
                                        >
                                            {{
                                                booking.tour_package.short_description
                                            }}
                                        </p>

                                    </div>


                                    <div
                                        class="shrink-0 text-left sm:text-right"
                                    >

                                        <p
                                            class="text-xs font-bold text-slate-400"
                                        >
                                            Harga Paket
                                        </p>

                                        <p
                                            class="mt-1 text-xl font-black text-[#b87a28]"
                                        >
                                            {{
                                                formatRupiah(
                                                    booking.base_price
                                                )
                                            }}
                                        </p>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </section>


                    <!-- ================================================= -->
                    <!-- VEHICLE -->
                    <!-- ================================================= -->

                    <section class="detail-card">

                        <div class="detail-card-header">

                            <div class="section-icon">
                                <CarFront :size="20" />
                            </div>

                            <div>

                                <p class="section-eyebrow">
                                    TRANSPORT
                                </p>

                                <h2 class="section-heading">
                                    Kendaraan
                                </h2>

                            </div>

                        </div>


                        <div
                            class="border-t border-slate-100 p-6 lg:p-7"
                        >

                            <div
                                v-if="booking.vehicle"
                                class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-slate-50 p-5 sm:flex-row sm:items-center sm:justify-between"
                            >

                                <div class="flex items-center gap-4">

                                    <div
                                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#172331] text-[#e5bd73]"
                                    >
                                        <CarFront :size="22" />
                                    </div>

                                    <div>

                                        <p
                                            class="font-black text-[#172331]"
                                        >
                                            {{
                                                safeValue(
                                                    booking.vehicle.name
                                                )
                                            }}
                                        </p>

                                        <p class="mt-1 text-sm text-slate-500">
                                            {{
                                                safeValue(
                                                    booking.vehicle.type
                                                )
                                            }}
                                        </p>

                                    </div>

                                </div>


                                <div
                                    class="text-left sm:text-right"
                                >

                                    <p class="text-xs text-slate-400">
                                        Kapasitas
                                    </p>

                                    <p class="font-bold text-[#172331]">
                                        {{
                                            safeValue(
                                                booking.vehicle.passenger_capacity,
                                                '-'
                                            )
                                        }}
                                        penumpang
                                    </p>

                                </div>

                            </div>


                            <div
                                v-else
                                class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center text-slate-500"
                            >
                                Belum ada kendaraan yang dipilih.
                            </div>

                        </div>

                    </section>


                    <!-- ================================================= -->
                    <!-- NOTES -->
                    <!-- ================================================= -->

                    <section
                        v-if="booking.notes"
                        class="detail-card"
                    >

                        <div class="detail-card-header">

                            <div class="section-icon">
                                <Clock3 :size="20" />
                            </div>

                            <div>

                                <p class="section-eyebrow">
                                    CATATAN
                                </p>

                                <h2 class="section-heading">
                                    Catatan Pelanggan
                                </h2>

                            </div>

                        </div>


                        <div
                            class="border-t border-slate-100 p-6 lg:p-7"
                        >

                            <p
                                class="whitespace-pre-line leading-8 text-slate-600"
                            >
                                {{ booking.notes }}
                            </p>

                        </div>

                    </section>

                </div>


                <!-- ================================================= -->
                <!-- RIGHT -->
                <!-- ================================================= -->

                <aside class="space-y-7 xl:sticky xl:top-6">


                    <!-- ================================================= -->
                    <!-- STATUS -->
                    <!-- ================================================= -->

                    <section class="detail-card overflow-hidden">

                        <div
                            class="bg-[#172331] p-6 lg:p-7"
                        >

                            <p
                                class="text-xs font-black tracking-[0.25em] text-[#e5bd73]"
                            >
                                KELOLA BOOKING
                            </p>

                            <h2
                                class="mt-2 text-2xl font-black text-white"
                            >
                                Status Booking
                            </h2>

                        </div>


                        <div class="p-5 lg:p-6">

                            <!-- CURRENT STATUS -->

                            <div
                                class="flex items-center justify-between gap-4 rounded-2xl bg-slate-50 px-4 py-4"
                            >

                                <div>

                                    <p class="text-sm text-slate-500">
                                        Status saat ini
                                    </p>

                                </div>

                                <span
                                    class="status-badge"
                                    :class="statusClass"
                                >
                                    {{ statusLabel }}
                                </span>

                            </div>


                            <!-- ================================ -->
                            <!-- PENDING -->
                            <!-- ================================ -->

                            <template
                                v-if="normalizedStatus === 'pending'"
                            >

                                <button
                                    type="button"
                                    :disabled="processingStatus"
                                    @click="updateStatus('confirmed')"
                                    class="action-button mt-5"
                                    style="
                                        background-color: #059669;
                                        color: #ffffff;
                                    "
                                >

                                    <svg
                                        v-if="processingStatus"
                                        class="h-5 w-5 animate-spin"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                    >
                                        <circle
                                            cx="12"
                                            cy="12"
                                            r="9"
                                            stroke="currentColor"
                                            stroke-width="3"
                                            stroke-opacity="0.3"
                                        />

                                        <path
                                            d="M21 12a9 9 0 0 0-9-9"
                                            stroke="currentColor"
                                            stroke-width="3"
                                            stroke-linecap="round"
                                        />
                                    </svg>

                                    <CheckCircle2
                                        v-else
                                        :size="19"
                                    />

                                    {{
                                        processingStatus
                                            ? 'Memproses...'
                                            : 'Konfirmasi Booking'
                                    }}

                                </button>


                                <button
                                    type="button"
                                    :disabled="processingStatus"
                                    @click="updateStatus('cancelled')"
                                    class="action-button mt-3 border border-red-200 bg-red-50 text-red-600 hover:bg-red-100"
                                >

                                    <XCircle :size="19" />

                                    Tolak / Batalkan

                                </button>

                            </template>


                            <!-- ================================ -->
                            <!-- CONFIRMED -->
                            <!-- ================================ -->

                            <template
                                v-else-if="normalizedStatus === 'confirmed'"
                            >

                                <button
                                    type="button"
                                    :disabled="processingStatus"
                                    @click="updateStatus('completed')"
                                    class="action-button mt-5"
                                    style="
                                        background-color: #172331;
                                        color: #ffffff;
                                    "
                                >

                                    <svg
                                        v-if="processingStatus"
                                        class="h-5 w-5 animate-spin"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                    >
                                        <circle
                                            cx="12"
                                            cy="12"
                                            r="9"
                                            stroke="currentColor"
                                            stroke-width="3"
                                            stroke-opacity="0.3"
                                        />

                                        <path
                                            d="M21 12a9 9 0 0 0-9-9"
                                            stroke="currentColor"
                                            stroke-width="3"
                                            stroke-linecap="round"
                                        />
                                    </svg>

                                    <CheckCircle2
                                        v-else
                                        :size="19"
                                    />

                                    {{
                                        processingStatus
                                            ? 'Memproses...'
                                            : 'Tandai Selesai'
                                    }}

                                </button>


                                <button
                                    type="button"
                                    :disabled="processingStatus"
                                    @click="updateStatus('cancelled')"
                                    class="action-button mt-3 border border-red-200 bg-red-50 text-red-600 hover:bg-red-100"
                                >

                                    <XCircle :size="19" />

                                    Batalkan Booking

                                </button>

                            </template>


                            <!-- ================================ -->
                            <!-- COMPLETED -->
                            <!-- ================================ -->

                            <div
                                v-else-if="normalizedStatus === 'completed'"
                                class="mt-5 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-700"
                            >

                                <CheckCircle2 :size="21" />

                                <div>

                                    <p class="font-bold">
                                        Booking Selesai
                                    </p>

                                    <p class="mt-1 text-xs text-emerald-600">
                                        Perjalanan telah diselesaikan.
                                    </p>

                                </div>

                            </div>


                            <!-- ================================ -->
                            <!-- CANCELLED -->
                            <!-- ================================ -->

                            <div
                                v-else-if="normalizedStatus === 'cancelled'"
                                class="mt-5 flex items-center gap-3 rounded-2xl border border-red-200 bg-red-50 p-4 text-red-700"
                            >

                                <XCircle :size="21" />

                                <div>

                                    <p class="font-bold">
                                        Booking Dibatalkan
                                    </p>

                                    <p class="mt-1 text-xs text-red-600">
                                        Booking ini sudah tidak aktif.
                                    </p>

                                </div>

                            </div>

                        </div>

                    </section>


                    <!-- ================================================= -->
                    <!-- PAYMENT -->
                    <!-- ================================================= -->

                    <section class="detail-card overflow-hidden">

                        <div class="p-6 lg:p-7">

                            <div
                                class="flex items-center gap-3"
                            >

                                <div class="section-icon">
                                    <CreditCard :size="20" />
                                </div>

                                <div>

                                    <p class="section-eyebrow">
                                        PAYMENT
                                    </p>

                                    <h2 class="section-heading">
                                        Pembayaran
                                    </h2>

                                </div>

                            </div>

                        </div>


                        <div
                            class="border-t border-slate-100 p-5 lg:p-6"
                        >

                            <!-- PAYMENT STATUS -->

                            <div
                                class="flex items-center justify-between gap-4 rounded-2xl bg-slate-50 px-4 py-4"
                            >

                                <span class="text-sm text-slate-500">
                                    Status
                                </span>

                                <span
                                    class="payment-badge"
                                    :class="paymentClass"
                                >
                                    {{ paymentStatusLabel }}
                                </span>

                            </div>


                            <!-- PRICE -->

                            <div
                                class="mt-5 rounded-2xl bg-[#172331] p-5"
                            >

                                <div
                                    class="flex items-center justify-between gap-4"
                                >

                                    <span class="text-sm text-white/60">
                                        Total Booking
                                    </span>

                                    <span
                                        class="text-xl font-black text-[#e5bd73]"
                                    >
                                        {{
                                            formatRupiah(
                                                booking.total_price
                                            )
                                        }}
                                    </span>

                                </div>

                            </div>


                            <!-- PAY -->

                            <button
                                v-if="
                                    normalizedPaymentStatus === 'unpaid'
                                "
                                type="button"
                                :disabled="processingPayment"
                                @click="updatePayment('paid')"
                                class="action-button mt-4"
                                style="
                                    background-color: #059669;
                                    color: #ffffff;
                                "
                            >

                                <svg
                                    v-if="processingPayment"
                                    class="h-5 w-5 animate-spin"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                >
                                    <circle
                                        cx="12"
                                        cy="12"
                                        r="9"
                                        stroke="currentColor"
                                        stroke-width="3"
                                        stroke-opacity="0.3"
                                    />

                                    <path
                                        d="M21 12a9 9 0 0 0-9-9"
                                        stroke="currentColor"
                                        stroke-width="3"
                                        stroke-linecap="round"
                                    />
                                </svg>

                                <CheckCircle2
                                    v-else
                                    :size="18"
                                />

                                {{
                                    processingPayment
                                        ? 'Memproses...'
                                        : 'Tandai Sudah Bayar'
                                }}

                            </button>


                            <!-- PAID -->

                            <div
                                v-else
                                class="mt-4 flex items-center justify-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3.5 font-bold text-emerald-700"
                            >

                                <Check :size="18" />

                                Pembayaran Sudah Diterima

                            </div>

                        </div>

                    </section>


                    <!-- ================================================= -->
                    <!-- PRICE DETAIL -->
                    <!-- ================================================= -->

                    <section class="detail-card">

                        <div class="p-6 lg:p-7">

                            <p class="section-eyebrow">
                                PRICE DETAIL
                            </p>

                            <h2 class="section-heading mt-2">
                                Rincian Harga
                            </h2>

                        </div>


                        <div
                            class="border-t border-slate-100 px-6 py-5 lg:px-7"
                        >

                            <div
                                class="space-y-4 text-sm"
                            >

                                <div
                                    class="flex items-center justify-between gap-4"
                                >

                                    <span class="text-slate-500">
                                        Harga Paket
                                    </span>

                                    <span class="font-semibold text-[#172331]">
                                        {{
                                            formatRupiah(
                                                booking.base_price
                                            )
                                        }}
                                    </span>

                                </div>


                                <div
                                    class="flex items-center justify-between gap-4"
                                >

                                    <span class="text-slate-500">
                                        Kendaraan
                                    </span>

                                    <span class="font-semibold text-[#172331]">
                                        {{
                                            formatRupiah(
                                                booking.vehicle_price
                                            )
                                        }}
                                    </span>

                                </div>

                            </div>


                            <div
                                class="my-5 border-t border-dashed border-slate-200"
                            ></div>


                            <div
                                class="flex items-center justify-between gap-4"
                            >

                                <span
                                    class="font-black text-[#172331]"
                                >
                                    Total
                                </span>

                                <span
                                    class="text-2xl font-black text-[#b87a28]"
                                >
                                    {{
                                        formatRupiah(
                                            booking.total_price
                                        )
                                    }}
                                </span>

                            </div>

                        </div>

                    </section>


                </aside>

            </div>

        </main>

    </div>
</template>


<style scoped>
/* =========================================================
   CARD
========================================================= */

.detail-card {
    overflow: hidden;
    border: 1px solid #e2e8f0;
    border-radius: 24px;
    background: #ffffff;
    box-shadow:
        0 12px 35px rgba(15, 30, 45, 0.05);
}

.detail-card-header {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 24px;
}

.section-icon {
    display: flex;
    height: 44px;
    width: 44px;
    flex-shrink: 0;
    align-items: center;
    justify-content: center;
    border-radius: 13px;
    background: #fff4df;
    color: #b87a28;
}

.section-eyebrow {
    font-size: 11px;
    font-weight: 900;
    letter-spacing: 0.22em;
    color: #b87a28;
}

.section-heading {
    margin-top: 4px;
    font-size: 20px;
    font-weight: 900;
    color: #172331;
}


/* =========================================================
   INFO
========================================================= */

.info-box {
    display: flex;
    align-items: flex-start;
    gap: 13px;
    border-radius: 16px;
    background: #f8fafc;
    padding: 16px;
}

.info-icon {
    display: flex;
    height: 34px;
    width: 34px;
    flex-shrink: 0;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    background: #ffffff;
    color: #b87a28;
    box-shadow: 0 3px 10px rgba(15, 30, 45, 0.05);
}

.info-label {
    font-size: 11px;
    font-weight: 700;
    color: #94a3b8;
}

.info-value {
    margin-top: 4px;
    font-size: 14px;
    font-weight: 800;
    line-height: 1.5;
    color: #172331;
}


/* =========================================================
   TRIP
========================================================= */

.trip-item {
    display: flex;
    align-items: flex-start;
    gap: 13px;
    border-radius: 16px;
    border: 1px solid #eef2f7;
    padding: 16px;
}

.trip-icon {
    display: flex;
    height: 38px;
    width: 38px;
    flex-shrink: 0;
    align-items: center;
    justify-content: center;
    border-radius: 11px;
    background: #fff7e9;
    color: #b87a28;
}


/* =========================================================
   STATUS
========================================================= */

.status-badge,
.payment-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 999px;
    padding: 7px 13px;
    font-size: 12px;
    font-weight: 800;
    white-space: nowrap;
}

.status-pending,
.payment-unpaid {
    border: 1px solid #fcd34d;
    background: #fffbeb;
    color: #b45309;
}

.status-confirmed {
    border: 1px solid #86efac;
    background: #f0fdf4;
    color: #15803d;
}

.status-completed,
.payment-paid {
    border: 1px solid #6ee7b7;
    background: #ecfdf5;
    color: #047857;
}

.status-cancelled {
    border: 1px solid #fca5a5;
    background: #fef2f2;
    color: #dc2626;
}


/* =========================================================
   ACTION BUTTON
========================================================= */

.action-button {
    display: flex;
    width: 100%;
    min-height: 52px;
    align-items: center;
    justify-content: center;
    gap: 9px;
    border-radius: 12px;
    padding: 13px 18px;
    font-size: 14px;
    font-weight: 800;
    transition:
        transform 0.2s ease,
        box-shadow 0.2s ease,
        opacity 0.2s ease,
        background-color 0.2s ease;
}

.action-button:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow:
        0 10px 25px rgba(15, 30, 45, 0.12);
}

.action-button:disabled {
    cursor: not-allowed;
    opacity: 0.6;
}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 640px) {
    .detail-card-header {
        padding: 20px;
    }

    .section-heading {
        font-size: 18px;
    }

    .info-box,
    .trip-item {
        padding: 14px;
    }
}
</style>