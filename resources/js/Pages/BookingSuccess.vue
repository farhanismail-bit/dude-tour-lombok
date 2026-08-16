<script setup>
import { Head, Link } from '@inertiajs/vue3'
import {
    CheckCircle2,
    CalendarDays,
    Users,
    MapPin,
    CarFront,
    Phone,
    Mail,
    ArrowRight,
    Home,
} from 'lucide-vue-next'

const props = defineProps({
    booking: {
        type: Object,
        required: true,
    },
})

const formatRupiah = (value) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(Number(value ?? 0))
}

const formatDate = (value) => {
    if (!value) return '-'

    const date = new Date(value)

    return new Intl.DateTimeFormat('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
    }).format(date)
}
</script>

<template>
    <Head>
        <title>Booking Berhasil - Dude Tour & Trans Lombok</title>

        <meta
            name="description"
            content="Booking perjalanan Anda berhasil dikirim ke Dude Tour & Trans Lombok."
        />
    </Head>

    <div class="min-h-screen bg-[#f7f9fb] text-[#172331]">

        <!-- NAVBAR -->
        <header
            class="border-b border-white/10 bg-[#172331]"
        >
            <div
                class="mx-auto flex min-h-[82px] max-w-[1200px] items-center justify-between px-6 lg:px-10"
            >

                <!-- LOGO -->
                <Link
                    href="/"
                    class="flex items-center gap-3"
                >
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-tr-[24px] rounded-bl-[24px] bg-gradient-to-br from-[#f3cf83] to-[#b87a28] text-2xl font-black text-[#172331]"
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
                            class="text-[9px] tracking-[0.28em] text-white"
                        >
                            TOUR & TRANS
                        </div>

                        <div
                            class="text-[8px] tracking-[0.4em] text-white/50"
                        >
                            LOMBOK
                        </div>
                    </div>
                </Link>

                <Link
                    href="/"
                    class="flex items-center gap-2 rounded-lg border border-white/10 px-4 py-2.5 text-sm font-semibold text-white transition hover:border-[#e5bd73]/50 hover:text-[#e5bd73]"
                >
                    <Home :size="17" />
                    Home
                </Link>

            </div>
        </header>


        <!-- SUCCESS HERO -->
        <section
            class="bg-[#172331] px-6 py-16 lg:px-10"
        >
            <div
                class="mx-auto max-w-[1000px] text-center"
            >

                <!-- ICON -->
                <div
                    class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-[#e5bd73]/15"
                >
                    <CheckCircle2
                        :size="52"
                        class="text-[#e5bd73]"
                    />
                </div>

                <p
                    class="mt-7 text-xs font-bold tracking-[0.3em] text-[#e5bd73]"
                >
                    BOOKING BERHASIL
                </p>

                <h1
                    class="mt-3 text-4xl font-black text-white md:text-5xl"
                >
                    Booking Anda Berhasil!
                </h1>

                <p
                    class="mx-auto mt-5 max-w-2xl text-base leading-7 text-white/65"
                >
                    Terima kasih telah mempercayakan perjalanan Anda kepada
                    Dude Tour & Trans Lombok. Data booking Anda telah berhasil
                    kami terima.
                </p>

            </div>
        </section>


        <!-- CONTENT -->
        <main class="px-6 py-12 lg:px-10">

            <div
                class="mx-auto grid max-w-[1000px] gap-8 lg:grid-cols-[1fr_330px]"
            >

                <!-- LEFT -->
                <div class="space-y-6">

                    <!-- BOOKING CODE -->
                    <section
                        class="rounded-[24px] border border-[#ead29c] bg-[#fffaf0] p-7"
                    >

                        <p
                            class="text-xs font-bold tracking-[0.2em] text-[#b87a28]"
                        >
                            KODE BOOKING
                        </p>

                        <div
                            class="mt-3 flex flex-wrap items-center justify-between gap-4"
                        >

                            <h2
                                class="text-2xl font-black tracking-wide text-[#172331]"
                            >
                                {{ booking.booking_code }}
                            </h2>

                            <span
                                class="rounded-full bg-[#e5bd73]/20 px-4 py-2 text-xs font-bold text-[#a66a18]"
                            >
                                MENUNGGU KONFIRMASI
                            </span>

                        </div>

                        <p
                            class="mt-3 text-sm leading-6 text-slate-500"
                        >
                            Simpan kode booking ini untuk memudahkan proses
                            konfirmasi dengan tim Dude Tour & Trans Lombok.
                        </p>

                    </section>


                    <!-- TOUR -->
                    <section
                        class="rounded-[24px] bg-white p-7 shadow-[0_12px_35px_rgba(20,35,49,0.06)]"
                    >

                        <p
                            class="text-xs font-bold tracking-[0.2em] text-[#b87a28]"
                        >
                            DETAIL PERJALANAN
                        </p>

                        <h2
                            class="mt-2 text-2xl font-black text-[#172331]"
                        >
                            {{ booking.tour_package?.name ?? '-' }}
                        </h2>

                        <div
                            class="mt-7 grid gap-4 sm:grid-cols-2"
                        >

                            <!-- DATE -->
                            <div
                                class="flex items-start gap-3 rounded-xl bg-slate-50 p-4"
                            >
                                <CalendarDays
                                    :size="21"
                                    class="mt-0.5 shrink-0 text-[#b87a28]"
                                />

                                <div>
                                    <p class="text-xs text-slate-400">
                                        Tanggal Perjalanan
                                    </p>

                                    <p class="mt-1 font-bold text-[#172331]">
                                        {{ formatDate(booking.booking_date) }}
                                    </p>
                                </div>
                            </div>


                            <!-- PEOPLE -->
                            <div
                                class="flex items-start gap-3 rounded-xl bg-slate-50 p-4"
                            >
                                <Users
                                    :size="21"
                                    class="mt-0.5 shrink-0 text-[#b87a28]"
                                />

                                <div>
                                    <p class="text-xs text-slate-400">
                                        Jumlah Orang
                                    </p>

                                    <p class="mt-1 font-bold text-[#172331]">
                                        {{ booking.number_of_people }} Orang
                                    </p>
                                </div>
                            </div>


                            <!-- DESTINATION -->
                            <div
                                class="flex items-start gap-3 rounded-xl bg-slate-50 p-4"
                            >
                                <MapPin
                                    :size="21"
                                    class="mt-0.5 shrink-0 text-[#b87a28]"
                                />

                                <div>
                                    <p class="text-xs text-slate-400">
                                        Destinasi
                                    </p>

                                    <p class="mt-1 font-bold text-[#172331]">
                                        {{ booking.destination?.name ?? '-' }}
                                    </p>
                                </div>
                            </div>


                            <!-- SERVICE -->
                            <div
                                class="flex items-start gap-3 rounded-xl bg-slate-50 p-4"
                            >
                                <CheckCircle2
                                    :size="21"
                                    class="mt-0.5 shrink-0 text-[#b87a28]"
                                />

                                <div>
                                    <p class="text-xs text-slate-400">
                                        Layanan
                                    </p>

                                    <p class="mt-1 font-bold text-[#172331]">
                                        {{ booking.service?.name ?? '-' }}
                                    </p>
                                </div>
                            </div>

                        </div>

                    </section>


                    <!-- CUSTOMER -->
                    <section
                        class="rounded-[24px] bg-white p-7 shadow-[0_12px_35px_rgba(20,35,49,0.06)]"
                    >

                        <p
                            class="text-xs font-bold tracking-[0.2em] text-[#b87a28]"
                        >
                            DATA PEMESAN
                        </p>

                        <h2
                            class="mt-2 text-2xl font-black text-[#172331]"
                        >
                            Informasi Pemesan
                        </h2>

                        <div class="mt-7 space-y-4">

                            <div class="flex items-center gap-3">
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#fff5e3] text-[#b87a28]"
                                >
                                    <Users :size="19" />
                                </div>

                                <div>
                                    <p class="text-xs text-slate-400">
                                        Nama
                                    </p>

                                    <p class="font-bold text-[#172331]">
                                        {{ booking.customer?.name ?? '-' }}
                                    </p>
                                </div>
                            </div>


                            <div class="flex items-center gap-3">
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#fff5e3] text-[#b87a28]"
                                >
                                    <Mail :size="19" />
                                </div>

                                <div>
                                    <p class="text-xs text-slate-400">
                                        Email
                                    </p>

                                    <p class="font-bold text-[#172331]">
                                        {{ booking.customer?.email ?? '-' }}
                                    </p>
                                </div>
                            </div>


                            <div class="flex items-center gap-3">
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#fff5e3] text-[#b87a28]"
                                >
                                    <Phone :size="19" />
                                </div>

                                <div>
                                    <p class="text-xs text-slate-400">
                                        Telepon
                                    </p>

                                    <p class="font-bold text-[#172331]">
                                        {{ booking.customer?.phone ?? '-' }}
                                    </p>
                                </div>
                            </div>

                        </div>

                    </section>

                </div>


                <!-- RIGHT SUMMARY -->
                <aside>

                    <div
                        class="sticky top-6 overflow-hidden rounded-[24px] bg-[#172331] text-white shadow-[0_20px_60px_rgba(15,30,45,0.15)]"
                    >

                        <div class="p-7">

                            <p
                                class="text-xs font-bold tracking-[0.2em] text-[#e5bd73]"
                            >
                                BOOKING SUMMARY
                            </p>

                            <h2
                                class="mt-3 text-2xl font-black"
                            >
                                Ringkasan Booking
                            </h2>

                        </div>


                        <div
                            class="border-t border-white/10 px-7 py-6"
                        >

                            <div class="space-y-5">

                                <div>
                                    <p class="text-xs text-white/45">
                                        Paket Tour
                                    </p>

                                    <p class="mt-1 font-bold">
                                        {{ booking.tour_package?.name ?? '-' }}
                                    </p>
                                </div>


                                <div>
                                    <p class="text-xs text-white/45">
                                        Kendaraan
                                    </p>

                                    <div
                                        class="mt-1 flex items-center gap-2"
                                    >
                                        <CarFront
                                            :size="17"
                                            class="text-[#e5bd73]"
                                        />

                                        <span class="font-bold">
                                            {{ booking.vehicle?.name ?? 'Tanpa kendaraan' }}
                                        </span>
                                    </div>
                                </div>


                                <div>
                                    <p class="text-xs text-white/45">
                                        Harga Paket
                                    </p>

                                    <p class="mt-1 font-bold">
                                        {{ formatRupiah(booking.base_price) }}
                                    </p>
                                </div>


                                <div>
                                    <p class="text-xs text-white/45">
                                        Harga Kendaraan
                                    </p>

                                    <p class="mt-1 font-bold">
                                        {{ formatRupiah(booking.vehicle_price) }}
                                    </p>
                                </div>

                            </div>

                        </div>


                        <!-- TOTAL -->
                        <div
                            class="border-t border-white/10 bg-white/5 p-7"
                        >

                            <p class="text-sm text-white/50">
                                Total Perkiraan
                            </p>

                            <p
                                class="mt-2 text-3xl font-black text-[#e5bd73]"
                            >
                                {{ formatRupiah(booking.total_price) }}
                            </p>

                            <p
                                class="mt-3 text-xs leading-5 text-white/45"
                            >
                                Pembayaran belum dilakukan. Tim kami akan
                                menghubungi Anda untuk konfirmasi booking.
                            </p>

                        </div>

                    </div>

                </aside>

            </div>


            <!-- ACTION -->
            <div
                class="mx-auto mt-8 flex max-w-[1000px] flex-col justify-center gap-3 sm:flex-row"
            >

                <Link
                    href="/"
                    class="flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-6 py-3.5 text-sm font-bold text-[#172331] transition hover:border-[#e5bd73] hover:text-[#b87a28]"
                >
                    <Home :size="18" />
                    Kembali ke Home
                </Link>

                <Link
                    href="/search"
                    class="flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#b87a28] to-[#e5bd73] px-6 py-3.5 text-sm font-bold text-white shadow-lg transition hover:-translate-y-0.5"
                >
                    Cari Paket Tour Lain
                    <ArrowRight :size="18" />
                </Link>

            </div>

        </main>

    </div>
</template>