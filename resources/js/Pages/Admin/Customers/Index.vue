<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import {
    ArrowLeft,
    Eye,
    Mail,
    Phone,
    Search,
    Users,
    RefreshCw,
    CalendarDays,
    Wallet,
} from 'lucide-vue-next'

const props = defineProps({
    customers: {
        type: Object,
        default: () => ({
            data: [],
            current_page: 1,
            last_page: 1,
            from: 0,
            to: 0,
            total: 0,
            links: [],
        }),
    },

    statistics: {
        type: Object,
        default: () => ({
            total: 0,
            with_booking: 0,
            without_booking: 0,
            total_booking: 0,
        }),
    },

    filters: {
        type: Object,
        default: () => ({
            search: '',
        }),
    },
})

const search = ref(props.filters.search ?? '')

const submitSearch = () => {
    router.get(
        '/admin/customers',
        {
            search: search.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    )
}

const resetSearch = () => {
    search.value = ''

    router.get(
        '/admin/customers',
        {},
        {
            preserveState: true,
            replace: true,
        }
    )
}

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
        month: 'short',
        year: 'numeric',
    }).format(new Date(value))
}
</script>

<template>
    <Head title="Customer Management" />

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
                        ADMINISTRATION
                    </p>

                    <h1 class="mt-1 text-3xl font-black">
                        Customer Management
                    </h1>

                    <p class="mt-1 text-sm text-slate-500">
                        Kelola data customer dan riwayat booking.
                    </p>
                </div>

                <div class="flex gap-3">

                    <Link
                        href="/admin/dashboard"
                        class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-5 py-3 text-sm font-bold transition hover:border-[#b87a28] hover:text-[#b87a28]"
                    >
                        <ArrowLeft :size="18" />

                        Dashboard
                    </Link>

                    <Link
                        href="/admin/bookings"
                        class="hidden rounded-xl bg-[#172331] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#b87a28] sm:inline-flex"
                    >
                        Booking
                    </Link>

                </div>

            </div>
        </header>


        <main class="mx-auto max-w-[1500px] px-6 py-8 lg:px-10">

            <!-- STATISTICS -->
            <section class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">

                <div class="stat-card">

                    <div class="icon bg-[#fff4df] text-[#b87a28]">
                        <Users :size="23" />
                    </div>

                    <div>
                        <p class="label">
                            TOTAL CUSTOMER
                        </p>

                        <p class="value">
                            {{ statistics.total ?? 0 }}
                        </p>
                    </div>

                </div>


                <div class="stat-card">

                    <div class="icon bg-blue-50 text-blue-600">
                        <CalendarDays :size="23" />
                    </div>

                    <div>
                        <p class="label">
                            SUDAH BOOKING
                        </p>

                        <p class="value">
                            {{ statistics.with_booking ?? 0 }}
                        </p>
                    </div>

                </div>


                <div class="stat-card">

                    <div class="icon bg-emerald-50 text-emerald-600">
                        <Wallet :size="23" />
                    </div>

                    <div>
                        <p class="label">
                            TOTAL BOOKING
                        </p>

                        <p class="value">
                            {{ statistics.total_booking ?? 0 }}
                        </p>
                    </div>

                </div>


                <div class="stat-card">

                    <div class="icon bg-slate-100 text-slate-600">
                        <Users :size="23" />
                    </div>

                    <div>
                        <p class="label">
                            BELUM BOOKING
                        </p>

                        <p class="value">
                            {{ statistics.without_booking ?? 0 }}
                        </p>
                    </div>

                </div>

            </section>


            <!-- SEARCH -->
            <section
                class="mt-7 rounded-[22px] bg-white p-5 shadow-sm"
            >

                <form
                    class="flex flex-col gap-3 lg:flex-row"
                    @submit.prevent="submitSearch"
                >

                    <div class="relative flex-1">

                        <Search
                            :size="19"
                            class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"
                        />

                        <input
                            v-model="search"
                            type="text"
                            placeholder="Cari nama, email, nomor telepon, WhatsApp..."
                            class="w-full rounded-xl border border-slate-200 py-3.5 pl-11 pr-4 text-sm outline-none transition focus:border-[#b87a28] focus:ring-2 focus:ring-[#b87a28]/10"
                        />

                    </div>


                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#172331] px-7 py-3.5 text-sm font-bold text-white transition hover:bg-[#b87a28]"
                    >
                        <Search :size="18" />

                        Cari
                    </button>


                    <button
                        type="button"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 px-5 py-3.5 text-sm font-bold text-slate-600 transition hover:border-[#b87a28] hover:text-[#b87a28]"
                        @click="resetSearch"
                    >
                        <RefreshCw :size="18" />
                    </button>

                </form>

            </section>


            <!-- TABLE -->
            <section
                class="mt-7 overflow-hidden rounded-[22px] bg-white shadow-sm"
            >

                <div
                    class="flex flex-col justify-between gap-3 border-b border-slate-100 px-6 py-5 sm:flex-row sm:items-center"
                >

                    <div>

                        <p class="section-label">
                            CUSTOMER DATABASE
                        </p>

                        <h2 class="mt-1 text-xl font-black">
                            Daftar Customer
                        </h2>

                        <p class="mt-1 text-sm text-slate-400">
                            Semua customer yang tersimpan di sistem.
                        </p>

                    </div>

                    <p class="text-sm text-slate-400">
                        Menampilkan
                        <b class="text-[#172331]">
                            {{ customers.from ?? 0 }}
                        </b>
                        -
                        <b class="text-[#172331]">
                            {{ customers.to ?? 0 }}
                        </b>
                        dari
                        <b class="text-[#172331]">
                            {{ customers.total ?? 0 }}
                        </b>
                    </p>

                </div>


                <div
                    v-if="customers.data?.length"
                    class="overflow-x-auto"
                >

                    <table class="w-full min-w-[1000px]">

                        <thead class="bg-slate-50">

                            <tr>

                                <th class="th">
                                    CUSTOMER
                                </th>

                                <th class="th">
                                    KONTAK
                                </th>

                                <th class="th">
                                    WHATSAPP
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
                                v-for="customer in customers.data"
                                :key="customer.id"
                                class="border-t border-slate-100 transition hover:bg-slate-50"
                            >

                                <!-- CUSTOMER -->
                                <td class="td">

                                    <div class="flex items-center gap-3">

                                        <div
                                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#fff4df] font-black text-[#b87a28]"
                                        >
                                            {{ customer.name?.charAt(0)?.toUpperCase() ?? '?' }}
                                        </div>

                                        <div>

                                            <b class="block text-sm text-[#172331]">
                                                {{ customer.name }}
                                            </b>

                                            <small>
                                                ID #{{ customer.id }}
                                            </small>

                                        </div>

                                    </div>

                                </td>


                                <!-- CONTACT -->
                                <td class="td">

                                    <div
                                        v-if="customer.email"
                                        class="flex items-center gap-2"
                                    >
                                        <Mail :size="15" />

                                        {{ customer.email }}
                                    </div>

                                    <div
                                        v-if="customer.phone"
                                        class="mt-1 flex items-center gap-2"
                                    >
                                        <Phone :size="15" />

                                        {{ customer.phone }}
                                    </div>

                                    <span
                                        v-if="!customer.email && !customer.phone"
                                        class="text-slate-400"
                                    >
                                        -
                                    </span>

                                </td>


                                <!-- WHATSAPP -->
                                <td class="td">
                                    {{ customer.whatsapp || '-' }}
                                </td>


                                <!-- BOOKING -->
                                <td class="td">

                                    <span
                                        class="inline-flex rounded-full border border-blue-200 bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700"
                                    >
                                        {{ customer.bookings_count ?? 0 }}
                                        Booking
                                    </span>

                                </td>


                                <!-- TRANSAKSI -->
                                <td class="td">

                                    <b class="text-[#b87a28]">
                                        {{ rupiah(customer.bookings_sum_total_price) }}
                                    </b>

                                </td>


                                <!-- DATE -->
                                <td class="td">
                                    {{ formatDate(customer.created_at) }}
                                </td>


                                <!-- ACTION -->
                                <td class="td text-right">

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
                    class="px-6 py-16 text-center"
                >

                    <Users
                        :size="42"
                        class="mx-auto text-slate-300"
                    />

                    <h3 class="mt-4 font-black">
                        Customer tidak ditemukan
                    </h3>

                    <p class="mt-1 text-sm text-slate-400">
                        Belum ada customer yang sesuai dengan pencarian.
                    </p>

                </div>


                <!-- PAGINATION -->
                <div
                    v-if="customers.last_page > 1"
                    class="flex flex-wrap gap-2 border-t border-slate-100 px-6 py-5"
                >

                    <Link
                        v-for="link in customers.links"
                        :key="link.label"
                        :href="link.url || '#'"
                        class="rounded-lg border px-3 py-2 text-xs font-bold transition"
                        :class="[
                            link.active
                                ? 'border-[#172331] bg-[#172331] text-white'
                                : 'border-slate-200 text-slate-600 hover:border-[#b87a28] hover:text-[#b87a28]',
                            !link.url
                                ? 'pointer-events-none opacity-40'
                                : ''
                        ]"
                        v-html="link.label"
                    />

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
    box-shadow: 0 10px 35px rgba(20, 35, 49, .06);
}

.icon {
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

.value {
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