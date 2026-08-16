<script setup>
import { computed } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import {
    ArrowRight,
    CalendarDays,
    CheckCircle2,
    Clock3,
    CarFront,
    DollarSign,
    Eye,
    MapPin,
    Package,
    Star,
    Users,
} from 'lucide-vue-next'

const props = defineProps({
    bookingStatistics: { type: Object, default: () => ({}) },
    masterStatistics: { type: Object, default: () => ({}) },
    recentBookings: { type: Array, default: () => [] },
    recentTourPackages: { type: Array, default: () => [] },
    bookingTrend: { type: Array, default: () => [] },
})

const rupiah = (value) => new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0,
}).format(Number(value ?? 0))

const date = (value) => {
    if (!value) return '-'
    return new Intl.DateTimeFormat('id-ID', {
        day: '2-digit', month: 'short', year: 'numeric',
    }).format(new Date(value))
}

const statusLabel = (value) => ({
    pending: 'Pending',
    confirmed: 'Confirmed',
    completed: 'Completed',
    cancelled: 'Cancelled',
}[value] ?? value)

const statusClass = (value) => ({
    pending: 'bg-amber-50 text-amber-700 border-amber-200',
    confirmed: 'bg-blue-50 text-blue-700 border-blue-200',
    completed: 'bg-emerald-50 text-emerald-700 border-emerald-200',
    cancelled: 'bg-red-50 text-red-700 border-red-200',
}[value] ?? 'bg-slate-50 text-slate-600 border-slate-200')

const maxTrend = computed(() => Math.max(
    ...props.bookingTrend.map(x => Number(x.total ?? 0)),
    1
))

const barHeight = (value) => Math.max(
    8,
    Math.round((Number(value ?? 0) / maxTrend.value) * 100)
)
</script>

<template>
    <Head title="Admin Dashboard" />

    <div class="min-h-screen bg-[#f6f8fb] text-[#172331]">
        <header class="border-b border-slate-200 bg-white">
            <div class="mx-auto flex max-w-[1500px] items-center justify-between px-6 py-5 lg:px-10">
                <div>
                    <p class="text-xs font-extrabold tracking-[0.25em] text-[#b87a28]">ADMINISTRATION</p>
                    <h1 class="mt-1 text-3xl font-black">Dashboard</h1>
                    <p class="mt-1 text-sm text-slate-500">Ringkasan aktivitas Dude Tour & Trans Lombok.</p>
                </div>

                <div class="flex gap-3">
                    <Link href="/" target="_blank" class="hidden rounded-xl border border-slate-200 px-5 py-3 text-sm font-bold sm:inline-flex">
                        Lihat Website
                    </Link>
                    <Link href="/admin/bookings" class="inline-flex items-center gap-2 rounded-xl bg-[#172331] px-5 py-3 text-sm font-bold text-white hover:bg-[#b87a28]">
                        <CalendarDays :size="18" /> Booking
                    </Link>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-[1500px] px-6 py-8 lg:px-10">

            <section class="mb-7 rounded-[26px] bg-[#172331] p-7 text-white shadow-xl lg:p-9">
                <p class="text-xs font-extrabold tracking-[0.25em] text-[#e5bd73]">DUDE TOUR & TRANS LOMBOK</p>
                <h2 class="mt-3 text-3xl font-black lg:text-4xl">Selamat Datang di Dashboard Admin</h2>
                <p class="mt-3 max-w-2xl leading-7 text-white/65">
                    Pantau booking, pembayaran, paket tour, kendaraan, dan destinasi dari satu tempat.
                </p>
            </section>

            <section class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
                <div class="stat-card"><div class="icon bg-[#fff4df] text-[#b87a28]"><CalendarDays :size="23"/></div><div><p class="label">Total Booking</p><p class="value">{{ bookingStatistics.total ?? 0 }}</p></div></div>
                <div class="stat-card"><div class="icon bg-amber-50 text-amber-600"><Clock3 :size="23"/></div><div><p class="label">Pending</p><p class="value">{{ bookingStatistics.pending ?? 0 }}</p></div></div>
                <div class="stat-card"><div class="icon bg-blue-50 text-blue-600"><CheckCircle2 :size="23"/></div><div><p class="label">Confirmed</p><p class="value">{{ bookingStatistics.confirmed ?? 0 }}</p></div></div>
                <div class="stat-card"><div class="icon bg-emerald-50 text-emerald-600"><CheckCircle2 :size="23"/></div><div><p class="label">Completed</p><p class="value">{{ bookingStatistics.completed ?? 0 }}</p></div></div>
            </section>

            <section class="mt-5 grid gap-5 lg:grid-cols-3">
                <div class="rounded-[22px] bg-white p-6 shadow-sm lg:col-span-2">
                    <p class="label">TOTAL PENDAPATAN</p>
                    <p class="mt-1 text-3xl font-black text-[#b87a28]">{{ rupiah(bookingStatistics.revenue) }}</p>
                    <p class="mt-1 text-sm text-slate-400">Booking dengan pembayaran sudah lunas.</p>
                </div>

                <div class="rounded-[22px] bg-white p-6 shadow-sm">
                    <p class="label">PEMBAYARAN</p>
                    <div class="mt-4 grid grid-cols-2 gap-3">
                        <div class="rounded-2xl bg-emerald-50 p-4"><p class="text-xs font-bold text-emerald-600">Sudah Bayar</p><p class="mt-1 text-2xl font-black text-emerald-700">{{ bookingStatistics.paid ?? 0 }}</p></div>
                        <div class="rounded-2xl bg-amber-50 p-4"><p class="text-xs font-bold text-amber-600">Belum Bayar</p><p class="mt-1 text-2xl font-black text-amber-700">{{ bookingStatistics.unpaid ?? 0 }}</p></div>
                    </div>
                </div>
            </section>

            <section class="mt-7">
                <p class="section-label">MASTER DATA</p>
                <h2 class="mt-1 text-2xl font-black">Data Sistem</h2>

                <div class="mt-4 grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
                    <Link href="/admin/bookings" class="master-card"><div class="master-icon bg-blue-50 text-blue-600"><Users :size="23"/></div><div><p class="label">Customer</p><p class="number">{{ masterStatistics.customers ?? 0 }}</p><p class="caption">Customer terdaftar</p></div><ArrowRight class="ml-auto text-slate-300"/></Link>
                    <Link href="/admin/tour-packages" class="master-card"><div class="master-icon bg-[#fff4df] text-[#b87a28]"><Package :size="23"/></div><div><p class="label">Tour Package</p><p class="number">{{ masterStatistics.tour_packages ?? 0 }}</p><p class="caption">{{ masterStatistics.active_tour_packages ?? 0 }} aktif · {{ masterStatistics.featured_tour_packages ?? 0 }} featured</p></div><ArrowRight class="ml-auto text-slate-300"/></Link>
                    <Link href="/admin/bookings" class="master-card"><div class="master-icon bg-purple-50 text-purple-600"><CarFront :size="23"/></div><div><p class="label">Kendaraan</p><p class="number">{{ masterStatistics.vehicles ?? 0 }}</p><p class="caption">{{ masterStatistics.available_vehicles ?? 0 }} tersedia</p></div><ArrowRight class="ml-auto text-slate-300"/></Link>
                    <Link href="/search" class="master-card"><div class="master-icon bg-emerald-50 text-emerald-600"><MapPin :size="23"/></div><div><p class="label">Destinasi</p><p class="number">{{ masterStatistics.destinations ?? 0 }}</p><p class="caption">{{ masterStatistics.active_destinations ?? 0 }} aktif</p></div><ArrowRight class="ml-auto text-slate-300"/></Link>
                </div>
            </section>

            <section class="mt-7 grid gap-5 lg:grid-cols-3">
                <div class="rounded-[22px] bg-white p-6 shadow-sm lg:col-span-2">
                    <div class="flex items-center justify-between">
                        <div><p class="section-label">ACTIVITY</p><h2 class="mt-1 text-xl font-black">Booking 7 Hari Terakhir</h2></div>
                        <Link href="/admin/bookings" class="text-sm font-bold text-[#b87a28]">Lihat semua</Link>
                    </div>

                    <div class="mt-8 flex h-52 items-end gap-3 sm:gap-5">
                        <div v-for="item in bookingTrend" :key="item.date" class="flex min-w-0 flex-1 flex-col items-center justify-end gap-2">
                            <span class="text-xs font-bold text-slate-500">{{ item.total }}</span>
                            <div class="flex h-36 w-full items-end justify-center">
                                <div class="w-full max-w-10 rounded-t-xl bg-gradient-to-t from-[#b87a28] to-[#e5bd73]" :style="{height: `${barHeight(item.total)}%`}"></div>
                            </div>
                            <span class="text-[11px] font-semibold text-slate-400">{{ item.label }}</span>
                        </div>
                    </div>
                </div>

                <div class="rounded-[22px] bg-white p-6 shadow-sm">
                    <p class="section-label">BOOKING STATUS</p>
                    <h2 class="mt-1 text-xl font-black">Ringkasan Status</h2>

                    <div class="mt-5 space-y-3">
                        <div class="status-row"><i class="bg-amber-400"></i><span>Pending</span><b>{{ bookingStatistics.pending ?? 0 }}</b></div>
                        <div class="status-row"><i class="bg-blue-500"></i><span>Confirmed</span><b>{{ bookingStatistics.confirmed ?? 0 }}</b></div>
                        <div class="status-row"><i class="bg-emerald-500"></i><span>Completed</span><b>{{ bookingStatistics.completed ?? 0 }}</b></div>
                        <div class="status-row"><i class="bg-red-500"></i><span>Cancelled</span><b>{{ bookingStatistics.cancelled ?? 0 }}</b></div>
                    </div>
                </div>
            </section>

            <section class="mt-7 overflow-hidden rounded-[22px] bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">
                    <div><p class="section-label">RESERVATIONS</p><h2 class="mt-1 text-xl font-black">Booking Terbaru</h2></div>
                    <Link href="/admin/bookings" class="inline-flex items-center gap-2 text-sm font-bold text-[#b87a28]">Lihat semua <ArrowRight :size="17"/></Link>
                </div>

                <div v-if="recentBookings.length" class="overflow-x-auto">
                    <table class="w-full min-w-[1000px]">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="th">BOOKING</th><th class="th">CUSTOMER</th><th class="th">PAKET</th><th class="th">TANGGAL</th><th class="th">TOTAL</th><th class="th">STATUS</th><th class="th">PEMBAYARAN</th><th class="th text-right">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="booking in recentBookings" :key="booking.id" class="border-t border-slate-100 hover:bg-slate-50">
                                <td class="td"><b>{{ booking.booking_code }}</b><small>ID #{{ booking.id }}</small></td>
                                <td class="td"><b>{{ booking.customer?.name ?? '-' }}</b><small>{{ booking.customer?.phone ?? booking.customer?.email ?? '-' }}</small></td>
                                <td class="td"><b>{{ booking.tour_package?.name ?? '-' }}</b></td>
                                <td class="td">{{ date(booking.booking_date) }}</td>
                                <td class="td"><b class="text-[#b87a28]">{{ rupiah(booking.total_price) }}</b></td>
                                <td class="td"><span class="badge" :class="statusClass(booking.status)">{{ statusLabel(booking.status) }}</span></td>
                                <td class="td"><span class="badge" :class="booking.payment_status === 'paid' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-amber-50 text-amber-700 border-amber-200'">{{ booking.payment_status === 'paid' ? 'Sudah Bayar' : 'Belum Bayar' }}</span></td>
                                <td class="td text-right"><Link :href="`/admin/bookings/${booking.id}`" class="inline-flex items-center gap-2 rounded-lg bg-[#172331] px-4 py-2.5 text-xs font-bold text-white hover:bg-[#b87a28]"><Eye :size="16"/>Detail</Link></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-else class="px-6 py-14 text-center text-slate-400">Belum ada booking.</div>
            </section>

            <section class="mt-7 rounded-[22px] bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div><p class="section-label">TOUR PACKAGES</p><h2 class="mt-1 text-xl font-black">Paket Tour Terbaru</h2></div>
                    <Link href="/admin/tour-packages" class="text-sm font-bold text-[#b87a28]">Kelola</Link>
                </div>

                <div v-if="recentTourPackages.length" class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-5">
                    <Link v-for="tour in recentTourPackages" :key="tour.id" href="/admin/tour-packages" class="rounded-2xl border border-slate-100 bg-slate-50 p-4 hover:border-[#e5bd73] hover:bg-white">
                        <div class="flex items-start justify-between"><div class="master-icon bg-[#fff4df] text-[#b87a28]"><Package :size="19"/></div><Star v-if="tour.is_featured" :size="17" class="text-[#b87a28]" fill="currentColor"/></div>
                        <h3 class="mt-4 line-clamp-2 font-black">{{ tour.name }}</h3>
                        <p class="mt-2 line-clamp-2 text-xs text-slate-400">{{ tour.short_description }}</p>
                        <div class="mt-4 flex justify-between"><b class="text-sm text-[#b87a28]">{{ rupiah(tour.price) }}</b><span class="text-xs text-slate-400">{{ tour.duration_days }} Hari</span></div>
                    </Link>
                </div>
            </section>
        </main>
    </div>
</template>

<style scoped>
.stat-card{display:flex;align-items:center;gap:1rem;border-radius:22px;background:#fff;padding:1.5rem;box-shadow:0 10px 35px rgba(20,35,49,.06)}
.icon,.master-icon{display:flex;align-items:center;justify-content:center;flex-shrink:0;border-radius:1rem;height:3.5rem;width:3.5rem}
.label{font-size:.75rem;font-weight:800;color:#94a3b8}
.value{margin-top:.25rem;font-size:1.875rem;font-weight:900}
.master-card{display:flex;align-items:center;gap:1rem;border-radius:20px;background:#fff;padding:1.25rem;box-shadow:0 10px 35px rgba(20,35,49,.06);transition:.2s}
.master-card:hover{transform:translateY(-2px)}
.number{margin-top:.15rem;font-size:1.7rem;font-weight:900}
.caption{margin-top:.3rem;font-size:.7rem;color:#94a3b8}
.section-label{font-size:.7rem;font-weight:900;letter-spacing:.2em;color:#b87a28}
.status-row{display:flex;align-items:center;gap:.75rem;border-radius:.9rem;background:#f8fafc;padding:.85rem 1rem;font-size:.85rem}
.status-row i{height:.6rem;width:.6rem;border-radius:9999px}.status-row b{margin-left:auto}
.th{padding:.9rem 1.25rem;text-align:left;font-size:.68rem;font-weight:900;color:#64748b}
.td{padding:1rem 1.25rem;font-size:.82rem;color:#475569}.td small{display:block;margin-top:.25rem;color:#94a3b8;font-size:.7rem}
.badge{display:inline-flex;border:1px solid;border-radius:9999px;padding:.35rem .7rem;font-size:.7rem;font-weight:800}
</style>
