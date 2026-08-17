<script setup>
import { Head, Link } from '@inertiajs/vue3'
import {
    ArrowLeft,
    CalendarDays,
    CheckCircle2,
    Mail,
    MapPin,
    Phone,
    User,
    Wallet,
    XCircle,
} from 'lucide-vue-next'

const props = defineProps({
    customer: {
        type: Object,
        required: true,
    },
})

const rupiah = (value) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(Number(value ?? 0))
}

const formatDate = (value) => {
    if (!value) return '-'

    return new Intl.DateTimeFormat('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
    }).format(new Date(value))
}

const statusLabel = (status) => {
    return {
        pending: 'Pending',
        confirmed: 'Confirmed',
        completed: 'Completed',
        cancelled: 'Cancelled',
    }[status] ?? status
}

const statusClass = (status) => {
    return {
        pending: 'bg-amber-50 text-amber-700 border-amber-200',
        confirmed: 'bg-blue-50 text-blue-700 border-blue-200',
        completed: 'bg-emerald-50 text-emerald-700 border-emerald-200',
        cancelled: 'bg-red-50 text-red-700 border-red-200',
    }[status] ?? 'bg-slate-50 text-slate-600 border-slate-200'
}
</script>

<template>

    <Head :title="`Customer - ${customer.name}`" />

    <div class="min-h-screen bg-[#f6f8fb] text-[#172331]">

        <!-- HEADER -->
        <header class="border-b border-slate-200 bg-white">

            <div
                class="mx-auto flex max-w-[1500px] items-center justify-between px-6 py-5 lg:px-10"
            >

                <div>

                    <p
                        class="text-xs font-extrabold tracking-[0.25em] text-[#b87a28]"
                    >
                        CUSTOMER DETAIL
                    </p>

                    <h1 class="mt-1 text-3xl font-black">
                        {{ customer.name }}
                    </h1>

                    <p class="mt-1 text-sm text-slate-500">
                        Detail customer dan riwayat booking.
                    </p>

                </div>


                <Link
                    href="/admin/customers"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-5 py-3 text-sm font-bold transition hover:border-[#b87a28] hover:text-[#b87a28]"
                >
                    <ArrowLeft :size="18" />

                    Kembali
                </Link>

            </div>

        </header>


        <main class="mx-auto max-w-[1500px] px-6 py-8 lg:px-10">

            <!-- CUSTOMER INFO -->
            <section class="grid gap-6 lg:grid-cols-3">

                <div
                    class="rounded-[24px] bg-[#172331] p-7 text-white lg:col-span-1"
                >

                    <div
                        class="flex h-20 w-20 items-center justify-center rounded-full bg-[#fff4df] text-3xl font-black text-[#b87a28]"
                    >
                        {{ customer.name?.charAt(0)?.toUpperCase() ?? '?' }}
                    </div>

                    <h2 class="mt-6 text-2xl font-black">
                        {{ customer.name }}
                    </h2>

                    <p class="mt-1 text-sm text-white/50">
                        Customer #{{ customer.id }}
                    </p>

                    <div class="mt-7 space-y-4">

                        <div
                            v-if="customer.email"
                            class="flex gap-3"
                        >
                            <Mail class="shrink-0 text-[#e5bd73]" :size="19" />

                            <div>
                                <p class="text-xs text-white/40">
                                    Email
                                </p>

                                <p class="mt-1 break-all text-sm font-semibold">
                                    {{ customer.email }}
                                </p>
                            </div>
                        </div>


                        <div
                            v-if="customer.phone"
                            class="flex gap-3"
                        >
                            <Phone class="shrink-0 text-[#e5bd73]" :size="19" />

                            <div>
                                <p class="text-xs text-white/40">
                                    Telepon
                                </p>

                                <p class="mt-1 text-sm font-semibold">
                                    {{ customer.phone }}
                                </p>
                            </div>
                        </div>


                        <div
                            v-if="customer.whatsapp"
                            class="flex gap-3"
                        >
                            <Phone class="shrink-0 text-[#e5bd73]" :size="19" />

                            <div>
                                <p class="text-xs text-white/40">
                                    WhatsApp
                                </p>

                                <p class="mt-1 text-sm font-semibold">
                                    {{ customer.whatsapp }}
                                </p>
                            </div>
                        </div>


                        <div
                            v-if="customer.address"
                            class="flex gap-3"
                        >
                            <MapPin class="shrink-0 text-[#e5bd73]" :size="19" />

                            <div>
                                <p class="text-xs text-white/40">
                                    Alamat
                                </p>

                                <p class="mt-1 text-sm font-semibold leading-6">
                                    {{ customer.address }}
                                </p>
                            </div>
                        </div>

                    </div>

                </div>


                <!-- SUMMARY -->
                <div class="grid gap-5 sm:grid-cols-2 lg:col-span-2">

                    <div class="summary-card">

                        <div class="summary-icon bg-blue-50 text-blue-600">
                            <CalendarDays :size="23" />
                        </div>

                        <div>
                            <p class="label">
                                TOTAL BOOKING
                            </p>

                            <p class="number">
                                {{ customer.bookings?.length ?? 0 }}
                            </p>
                        </div>

                    </div>


                    <div class="summary-card">

                        <div class="summary-icon bg-emerald-50 text-emerald-600">
                            <Wallet :size="23" />
                        </div>

                        <div>

                            <p class="label">
                                TOTAL TRANSAKSI
                            </p>

                            <p class="mt-1 text-2xl font-black text-[#b87a28]">
                                {{
                                    rupiah(
                                        customer.bookings?.reduce(
                                            (sum, booking) =>
                                                sum + Number(booking.total_price ?? 0),
                                            0
                                        )
                                    )
                                }}
                            </p>

                        </div>

                    </div>


                    <div class="summary-card">

                        <div class="summary-icon bg-amber-50 text-amber-600">
                            <CheckCircle2 :size="23" />
                        </div>

                        <div>

                            <p class="label">
                                BOOKING SELESAI
                            </p>

                            <p class="number">
                                {{
                                    customer.bookings?.filter(
                                        booking => booking.status === 'completed'
                                    ).length ?? 0
                                }}
                            </p>

                        </div>

                    </div>


                    <div class="summary-card">

                        <div class="summary-icon bg-red-50 text-red-600">
                            <XCircle :size="23" />
                        </div>

                        <div>

                            <p class="label">
                                BOOKING DIBATALKAN
                            </p>

                            <p class="number">
                                {{
                                    customer.bookings?.filter(
                                        booking => booking.status === 'cancelled'
                                    ).length ?? 0
                                }}
                            </p>

                        </div>

                    </div>

                </div>

            </section>


            <!-- BOOKING HISTORY -->
            <section
                class="mt-7 overflow-hidden rounded-[24px] bg-white shadow-sm"
            >

                <div
                    class="border-b border-slate-100 px-6 py-5"
                >

                    <p class="section-label">
                        BOOKING HISTORY
                    </p>

                    <h2 class="mt-1 text-xl font-black">
                        Riwayat Booking
                    </h2>

                </div>


                <div
                    v-if="customer.bookings?.length"
                    class="overflow-x-auto"
                >

                    <table class="w-full min-w-[1000px]">

                        <thead class="bg-slate-50">

                            <tr>

                                <th class="th">
                                    BOOKING
                                </th>

                                <th class="th">
                                    PAKET
                                </th>

                                <th class="th">
                                    TANGGAL
                                </th>

                                <th class="th">
                                    PESERTA
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
                                v-for="booking in customer.bookings"
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
                                        {{ booking.tour_package?.name ?? '-' }}
                                    </b>

                                    <small>
                                        {{ booking.service?.name ?? '' }}
                                    </small>

                                </td>


                                <td class="td">
                                    {{ formatDate(booking.booking_date) }}
                                </td>


                                <td class="td">
                                    {{ booking.number_of_people ?? 0 }} orang
                                </td>


                                <td class="td">

                                    <b class="text-[#b87a28]">
                                        {{ rupiah(booking.total_price) }}
                                    </b>

                                </td>


                                <td class="td">

                                    <span
                                        class="inline-flex rounded-full border px-3 py-1 text-xs font-bold"
                                        :class="statusClass(booking.status)"
                                    >
                                        {{ statusLabel(booking.status) }}
                                    </span>

                                </td>


                                <td class="td">

                                    <span
                                        class="inline-flex rounded-full border px-3 py-1 text-xs font-bold"
                                        :class="
                                            booking.payment_status === 'paid'
                                                ? 'border-emerald-200 bg-emerald-50 text-emerald-700'
                                                : 'border-amber-200 bg-amber-50 text-amber-700'
                                        "
                                    >
                                        {{
                                            booking.payment_status === 'paid'
                                                ? 'Sudah Bayar'
                                                : 'Belum Bayar'
                                        }}
                                    </span>

                                </td>


                                <td class="td text-right">

                                    <Link
                                        :href="`/admin/bookings/${booking.id}`"
                                        class="inline-flex items-center gap-2 rounded-lg bg-[#172331] px-4 py-2.5 text-xs font-bold text-white transition hover:bg-[#b87a28]"
                                    >
                                        Lihat Detail
                                    </Link>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>


                <div
                    v-else
                    class="px-6 py-16 text-center"
                >

                    <User
                        :size="42"
                        class="mx-auto text-slate-300"
                    />

                    <h3 class="mt-4 font-black">
                        Belum ada booking
                    </h3>

                    <p class="mt-1 text-sm text-slate-400">
                        Customer ini belum melakukan booking.
                    </p>

                </div>

            </section>

        </main>

    </div>

</template>

<style scoped>
.summary-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    border-radius: 22px;
    background: #fff;
    padding: 1.5rem;
    box-shadow: 0 10px 35px rgba(20, 35, 49, .06);
}

.summary-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    width: 3.5rem;
    height: 3.5rem;
    border-radius: 1rem;
}

.label {
    font-size: .72rem;
    font-weight: 800;
    color: #94a3b8;
}

.number {
    margin-top: .25rem;
    font-size: 1.875rem;
    font-weight: 900;
}

.section-label {
    font-size: .7rem;
    font-weight: 900;
    letter-spacing: .2em;
    color: #b87a28;
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
</style>