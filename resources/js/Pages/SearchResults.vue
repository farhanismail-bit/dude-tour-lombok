<script setup>
import { Link } from '@inertiajs/vue3'

import {
    ArrowLeft,
    ArrowRight,
    CalendarDays,
    CarFront,
    CheckCircle2,
    Clock3,
    MapPin,
    Users,
    Star,
} from 'lucide-vue-next'

const props = defineProps({
    filters: {
        type: Object,
        default: () => ({
            destination: '',
            date: '',
            people: 1,
            service: '',
        }),
    },

    packages: {
        type: Array,
        default: () => [],
    },

    vehicles: {
        type: Array,
        default: () => [],
    },
})

const formatPrice = (price) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(Number(price || 0))
}

const formatDate = (date) => {
    if (!date) return ''

    const parsedDate = new Date(date)

    if (Number.isNaN(parsedDate.getTime())) {
        return date
    }

    return parsedDate.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
    })
}

const getPackageImage = (packageItem) => {
    if (packageItem?.image) {
        return packageItem.image
    }

    const images = {
        'lombok-highlight-tour':
            'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=1200&q=85',

        'south-lombok-escape':
            'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=85',

        'north-lombok-adventure':
            'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?auto=format&fit=crop&w=1200&q=85',
    }

    return (
        images[packageItem?.slug] ||
        'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1200&q=85'
    )
}

const getVehicleImage = (vehicle) => {
    if (vehicle?.image) {
        return vehicle.image
    }

    const name = String(vehicle?.name || '').toLowerCase()

    if (name.includes('avanza')) {
        return 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&w=900&q=85'
    }

    if (name.includes('innova')) {
        return 'https://images.unsplash.com/photo-1551830820-330a71b99659?auto=format&fit=crop&w=900&q=85'
    }

    if (name.includes('hiace')) {
        return 'https://images.unsplash.com/photo-1563720223185-11003d516935?auto=format&fit=crop&w=900&q=85'
    }

    return 'https://images.unsplash.com/photo-1493238792000-8113da705763?auto=format&fit=crop&w=900&q=85'
}

const getDuration = (packageItem) => {
    const days = Number(packageItem?.duration_days || 0)
    const nights = Number(packageItem?.duration_nights || 0)

    if (days > 0 && nights > 0) {
        return `${days} Hari ${nights} Malam`
    }

    if (days > 0) {
        return `${days} Hari`
    }

    return 'Custom Trip'
}

const getPassengerText = (vehicle) => {
    const capacity = Number(vehicle?.passenger_capacity || 0)

    if (!capacity) {
        return 'Kapasitas tersedia'
    }

    return `${capacity} Orang`
}
</script>

<template>
    <div class="min-h-screen bg-[#f7f9fb] text-[#172331]">

        <!-- =====================================================
             HEADER
        ====================================================== -->
        <header class="relative z-20 bg-[#172331]">
            <div
                class="mx-auto flex min-h-[88px] max-w-[1400px] items-center justify-between px-6 py-4 lg:px-10"
            >
                <!-- LOGO -->
                <Link
                    href="/"
                    class="group flex items-center gap-3"
                >
                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-tr-[24px] rounded-bl-[24px] bg-gradient-to-br from-[#f3cf83] to-[#b87a28] text-2xl font-black text-[#172331] shadow-lg transition duration-300 group-hover:scale-105"
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
                            class="text-[9px] font-medium tracking-[0.28em] text-white"
                        >
                            TOUR & TRANS
                        </div>

                        <div
                            class="text-[8px] tracking-[0.38em] text-white/50"
                        >
                            LOMBOK
                        </div>
                    </div>
                </Link>

                <!-- BACK HOME -->
                <Link
                    href="/"
                    class="group inline-flex items-center gap-2 rounded-lg border border-white/10 px-4 py-2.5 text-sm font-semibold text-white transition duration-300 hover:border-[#e5bd73]/40 hover:bg-white/5 hover:text-[#e5bd73]"
                >
                    <ArrowLeft
                        :size="18"
                        class="transition-transform duration-300 group-hover:-translate-x-1"
                    />

                    <span>Kembali ke Home</span>
                </Link>
            </div>
        </header>


        <!-- =====================================================
             SEARCH RESULT HERO
        ====================================================== -->
        <section class="relative overflow-hidden bg-white">
            <!-- decorative -->
            <div
                class="absolute right-0 top-0 h-64 w-64 rounded-full bg-[#e5bd73]/10 blur-3xl"
            ></div>

            <div
                class="absolute bottom-0 left-0 h-48 w-48 rounded-full bg-[#b87a28]/5 blur-3xl"
            ></div>

            <div
                class="relative mx-auto max-w-[1400px] px-6 py-14 lg:px-10 lg:py-16"
            >
                <!-- LABEL -->
                <p
                    class="text-xs font-black tracking-[0.3em] text-[#b87a28]"
                >
                    SEARCH RESULT
                </p>

                <!-- TITLE -->
                <h1
                    class="mt-3 max-w-4xl text-4xl font-black leading-tight tracking-tight text-[#172331] sm:text-5xl lg:text-6xl"
                >
                    Temukan Perjalanan
                    <span class="text-[#b87a28]">
                        Anda
                    </span>
                </h1>

                <p
                    class="mt-5 max-w-2xl text-base leading-7 text-slate-500 lg:text-lg"
                >
                    Berikut pilihan perjalanan yang sesuai dengan pencarian
                    Anda. Pilih paket tour dan kendaraan yang paling sesuai
                    dengan kebutuhan perjalanan Anda.
                </p>

                <!-- FILTER SUMMARY -->
                <div class="mt-8 flex flex-wrap gap-3">

                    <!-- DESTINATION -->
                    <div
                        v-if="filters.destination"
                        class="filter-pill"
                    >
                        <MapPin :size="16" />

                        <span>
                            {{ filters.destination }}
                        </span>
                    </div>

                    <!-- DATE -->
                    <div
                        v-if="filters.date"
                        class="filter-pill"
                    >
                        <CalendarDays :size="16" />

                        <span>
                            {{ formatDate(filters.date) }}
                        </span>
                    </div>

                    <!-- PEOPLE -->
                    <div class="filter-pill">
                        <Users :size="16" />

                        <span>
                            {{ filters.people }} Orang
                        </span>
                    </div>

                    <!-- SERVICE -->
                    <div
                        v-if="filters.service"
                        class="filter-pill"
                    >
                        <CarFront :size="16" />

                        <span>
                            {{ filters.service }}
                        </span>
                    </div>
                </div>
            </div>
        </section>


        <!-- =====================================================
             MAIN CONTENT
        ====================================================== -->
        <main class="px-6 py-14 lg:px-10 lg:py-20">
            <div
                class="mx-auto grid max-w-[1400px] items-start gap-10 xl:grid-cols-[minmax(0,1fr)_380px]"
            >

                <!-- =================================================
                     TOUR PACKAGES
                ================================================== -->
                <section>
                    <!-- SECTION HEADER -->
                    <div
                        class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between"
                    >
                        <div>
                            <p
                                class="text-xs font-black tracking-[0.25em] text-[#b87a28]"
                            >
                                TOUR PACKAGES
                            </p>

                            <h2
                                class="mt-2 text-3xl font-black tracking-tight text-[#172331] sm:text-4xl"
                            >
                                Paket Tour
                            </h2>

                            <p class="mt-2 text-sm text-slate-500">
                                Pilihan perjalanan terbaik untuk Anda.
                            </p>
                        </div>

                        <div
                            class="inline-flex w-fit items-center rounded-full bg-white px-4 py-2 text-sm font-semibold text-slate-500 shadow-sm"
                        >
                            <span class="mr-2 h-2 w-2 rounded-full bg-[#b87a28]"></span>

                            {{ packages.length }} paket ditemukan
                        </div>
                    </div>


                    <!-- =================================================
                         EMPTY STATE
                    ================================================== -->
                    <div
                        v-if="packages.length === 0"
                        class="rounded-[24px] border border-slate-200 bg-white p-10 text-center shadow-sm sm:p-14"
                    >
                        <div
                            class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-[#f8edd8] text-[#b87a28]"
                        >
                            <MapPin :size="32" />
                        </div>

                        <h3
                            class="mt-6 text-2xl font-black text-[#172331]"
                        >
                            Paket belum ditemukan
                        </h3>

                        <p
                            class="mx-auto mt-3 max-w-md leading-7 text-slate-500"
                        >
                            Belum ada paket yang sesuai dengan pilihan
                            pencarian Anda. Silakan coba pilihan destinasi atau
                            layanan lainnya.
                        </p>

                        <Link
                            href="/"
                            class="mt-7 inline-flex items-center gap-2 rounded-xl bg-[#172331] px-6 py-3.5 font-bold text-white transition hover:bg-[#b87a28]"
                        >
                            <ArrowLeft :size="18" />

                            Kembali ke Home
                        </Link>
                    </div>


                    <!-- =================================================
                         PACKAGE LIST
                    ================================================== -->
                    <div
                        v-else
                        class="grid gap-7 md:grid-cols-2"
                    >
                        <article
                            v-for="packageItem in packages"
                            :key="packageItem.id"
                            class="package-card group"
                        >
                            <!-- IMAGE -->
                            <div class="relative h-[250px] overflow-hidden">
                                <img
                                    :src="getPackageImage(packageItem)"
                                    :alt="packageItem.name"
                                    class="h-full w-full object-cover transition duration-700 group-hover:scale-110"
                                />

                                <!-- overlay -->
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"
                                ></div>

                                <!-- tag -->
                                <div
                                    class="absolute left-5 top-5 rounded-full bg-white/95 px-3.5 py-2 text-[10px] font-black tracking-[0.12em] text-[#a66a18] shadow-lg"
                                >
                                    {{ packageItem.tag || 'TOUR PACKAGE' }}
                                </div>

                                <!-- rating -->
                                <div
                                    class="absolute right-5 top-5 flex items-center gap-1.5 rounded-full bg-black/30 px-3 py-2 text-xs font-bold text-white backdrop-blur-md"
                                >
                                    <Star
                                        :size="14"
                                        fill="currentColor"
                                        class="text-[#e5bd73]"
                                    />

                                    4.9
                                </div>

                                <!-- image bottom info -->
                                <div
                                    class="absolute bottom-5 left-5 right-5 flex items-end justify-between gap-3"
                                >
                                    <div>
                                        <p
                                            class="text-xs font-medium text-white/70"
                                        >
                                            PRIVATE EXPERIENCE
                                        </p>

                                        <p
                                            class="mt-1 text-lg font-black text-white"
                                        >
                                            Lombok
                                        </p>
                                    </div>
                                </div>
                            </div>


                            <!-- CONTENT -->
                            <div class="p-6">
                                <!-- TITLE -->
                                <h3
                                    class="text-2xl font-black leading-tight text-[#172331]"
                                >
                                    {{ packageItem.name }}
                                </h3>

                                <!-- DESCRIPTION -->
                                <p
                                    class="mt-3 line-clamp-2 text-sm leading-7 text-slate-500"
                                >
                                    {{ packageItem.short_description }}
                                </p>


                                <!-- DESTINATIONS -->
                                <div
                                    v-if="packageItem.destinations?.length"
                                    class="mt-5 flex flex-wrap gap-2"
                                >
                                    <span
                                        v-for="destination in packageItem.destinations"
                                        :key="destination.id"
                                        class="inline-flex items-center gap-1.5 rounded-full bg-[#f7f9fb] px-3 py-1.5 text-xs font-semibold text-slate-600"
                                    >
                                        <MapPin
                                            :size="12"
                                            class="text-[#b87a28]"
                                        />

                                        {{ destination.name }}
                                    </span>
                                </div>


                                <!-- META -->
                                <div
                                    class="mt-5 flex flex-wrap gap-4 border-t border-slate-100 pt-5"
                                >
                                    <div
                                        class="flex items-center gap-2 text-xs font-semibold text-slate-500"
                                    >
                                        <Clock3
                                            :size="16"
                                            class="text-[#b87a28]"
                                        />

                                        {{ getDuration(packageItem) }}
                                    </div>

                                    <div
                                        class="flex items-center gap-2 text-xs font-semibold text-slate-500"
                                    >
                                        <Users
                                            :size="16"
                                            class="text-[#b87a28]"
                                        />

                                        Private Trip
                                    </div>
                                </div>


                                <!-- PRICE + BUTTON -->
                                <div
                                    class="mt-6 flex items-end justify-between gap-4"
                                >
                                    <div>
                                        <p
                                            class="text-xs font-medium text-slate-400"
                                        >
                                            Mulai dari
                                        </p>

                                        <p
                                            class="mt-1 text-xl font-black text-[#b87a28]"
                                        >
                                            {{ formatPrice(packageItem.price) }}
                                        </p>
                                    </div>

                                    
                                   <Link
                                        :href="`/tours/${packageItem.slug}`"
                                        class="package-arrow"
                                        aria-label="Lihat detail paket"
                                    >
                                        <ArrowRight :size="19" />
                                    </Link>
                                </div>
                            </div>
                        </article>
                    </div>
                </section>


                <!-- =================================================
                     VEHICLES
                ================================================== -->
                <aside>
                    <div
                        class="vehicle-panel xl:sticky xl:top-6"
                    >
                        <!-- HEADER -->
                        <div>
                            <p
                                class="text-xs font-black tracking-[0.25em] text-[#e5bd73]"
                            >
                                TRANSPORT
                            </p>

                            <h2
                                class="mt-3 text-2xl font-black text-white sm:text-3xl"
                            >
                                Kendaraan Tersedia
                            </h2>

                            <p
                                class="mt-3 text-sm leading-7 text-white/60"
                            >
                                Kendaraan yang sesuai dengan jumlah orang yang
                                Anda pilih.
                            </p>
                        </div>


                        <!-- EMPTY -->
                        <div
                            v-if="vehicles.length === 0"
                            class="mt-7 rounded-2xl border border-white/10 bg-white/5 p-6"
                        >
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-xl bg-white/10 text-[#e5bd73]"
                            >
                                <CarFront :size="24" />
                            </div>

                            <h3
                                class="mt-4 font-bold text-white"
                            >
                                Belum ada kendaraan
                            </h3>

                            <p
                                class="mt-2 text-sm leading-6 text-white/50"
                            >
                                Belum ada kendaraan yang sesuai dengan
                                kapasitas yang Anda pilih.
                            </p>
                        </div>


                        <!-- VEHICLE LIST -->
                        <div
                            v-else
                            class="mt-7 space-y-4"
                        >
                            <div
                                v-for="vehicle in vehicles"
                                :key="vehicle.id"
                                class="vehicle-card group"
                            >
                                <!-- IMAGE -->
                                <div
                                    class="relative h-[155px] overflow-hidden rounded-xl"
                                >
                                    <img
                                        :src="getVehicleImage(vehicle)"
                                        :alt="vehicle.name"
                                        class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                    />

                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"
                                    ></div>

                                    <!-- available -->
                                    <div
                                        class="absolute left-3 top-3 inline-flex items-center gap-1.5 rounded-full bg-emerald-500/90 px-2.5 py-1.5 text-[10px] font-bold text-white"
                                    >
                                        <span
                                            class="h-1.5 w-1.5 rounded-full bg-white"
                                        ></span>

                                        Available
                                    </div>
                                </div>


                                <!-- CONTENT -->
                                <div class="mt-4">
                                    <div
                                        class="flex items-start justify-between gap-3"
                                    >
                                        <div>
                                            <h3
                                                class="text-lg font-black text-white"
                                            >
                                                {{ vehicle.name }}
                                            </h3>

                                            <p
                                                v-if="vehicle.type"
                                                class="mt-1 text-xs text-white/45"
                                            >
                                                {{ vehicle.type }}
                                            </p>
                                        </div>

                                        <CarFront
                                            :size="20"
                                            class="shrink-0 text-[#e5bd73]"
                                        />
                                    </div>


                                    <!-- SPECS -->
                                    <div
                                        class="mt-4 flex flex-wrap gap-2"
                                    >
                                        <span
                                            class="vehicle-spec"
                                        >
                                            <Users :size="14" />

                                            {{ getPassengerText(vehicle) }}
                                        </span>

                                        <span
                                            v-if="vehicle.has_ac || vehicle.ac"
                                            class="vehicle-spec"
                                        >
                                            AC
                                        </span>

                                        <span
                                            class="vehicle-spec"
                                        >
                                            <CheckCircle2 :size="14" />

                                            Ready
                                        </span>
                                    </div>


                                    <!-- PRICE -->
                                    <div
                                        class="mt-5 border-t border-white/10 pt-4"
                                    >
                                        <p
                                            class="text-[11px] text-white/40"
                                        >
                                            Mulai dari
                                        </p>

                                        <div
                                            class="mt-1 flex items-end justify-between gap-3"
                                        >
                                            <p
                                                class="text-xl font-black text-[#e5bd73]"
                                            >
                                                {{
                                                    formatPrice(
                                                        vehicle.price_per_day
                                                    )
                                                }}
                                            </p>

                                            <span
                                                class="pb-0.5 text-xs text-white/40"
                                            >
                                                / hari
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- BOTTOM INFO -->
                        <div
                            class="mt-6 rounded-2xl border border-[#e5bd73]/20 bg-[#e5bd73]/5 p-5"
                        >
                            <div class="flex gap-3">
                                <CheckCircle2
                                    :size="20"
                                    class="mt-0.5 shrink-0 text-[#e5bd73]"
                                />

                                <div>
                                    <p
                                        class="text-sm font-bold text-white"
                                    >
                                        Perjalanan lebih nyaman
                                    </p>

                                    <p
                                        class="mt-1 text-xs leading-6 text-white/50"
                                    >
                                        Pilihan kendaraan dapat disesuaikan
                                        dengan kebutuhan perjalanan Anda.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </main>


        <!-- =====================================================
             CTA
        ====================================================== -->
        <section class="px-6 pb-16 lg:px-10">
            <div
                class="mx-auto max-w-[1400px] overflow-hidden rounded-[28px] bg-[#172331] px-7 py-10 shadow-xl sm:px-10 lg:px-14 lg:py-12"
            >
                <div
                    class="flex flex-col items-start justify-between gap-8 lg:flex-row lg:items-center"
                >
                    <div>
                        <p
                            class="text-xs font-black tracking-[0.25em] text-[#e5bd73]"
                        >
                            READY FOR LOMBOK?
                        </p>

                        <h2
                            class="mt-3 text-3xl font-black text-white sm:text-4xl"
                        >
                            Buat Perjalanan Anda Sekarang
                        </h2>

                        <p
                            class="mt-3 max-w-2xl text-sm leading-7 text-white/60 sm:text-base"
                        >
                            Pilih paket tour dan kendaraan yang sesuai,
                            kemudian nikmati perjalanan Lombok dengan nyaman.
                        </p>
                    </div>

                    <Link
                        href="/"
                        class="inline-flex shrink-0 items-center gap-3 rounded-xl bg-gradient-to-r from-[#b87a28] to-[#e5bd73] px-6 py-4 text-sm font-black text-white shadow-lg transition duration-300 hover:-translate-y-1 hover:shadow-2xl"
                    >
                        Kembali Cari

                        <ArrowRight :size="18" />
                    </Link>
                </div>
            </div>
        </section>


        <!-- =====================================================
             FOOTER
        ====================================================== -->
        <footer
            class="bg-[#101c27] px-6 pb-8 pt-12 text-white lg:px-10"
        >
            <div
                class="mx-auto max-w-[1400px]"
            >
                <div
                    class="grid gap-10 border-b border-white/10 pb-10 md:grid-cols-2 lg:grid-cols-4"
                >
                    <!-- BRAND -->
                    <div>
                        <Link
                            href="/"
                            class="inline-flex items-center gap-3"
                        >
                            <div
                                class="flex h-11 w-11 items-center justify-center rounded-tr-[22px] rounded-bl-[22px] bg-gradient-to-br from-[#f3cf83] to-[#b87a28] text-xl font-black text-[#172331]"
                            >
                                D
                            </div>

                            <div>
                                <div
                                    class="text-lg font-black tracking-[0.18em] text-[#e5bd73]"
                                >
                                    DUDE
                                </div>

                                <div
                                    class="text-[8px] tracking-[0.28em] text-white"
                                >
                                    TOUR & TRANS
                                </div>
                            </div>
                        </Link>

                        <p
                            class="mt-5 max-w-xs text-sm leading-7 text-white/45"
                        >
                            Partner perjalanan terpercaya untuk menjelajahi
                            keindahan Lombok dengan nyaman dan berkesan.
                        </p>
                    </div>


                    <!-- QUICK LINKS -->
                    <div>
                        <h3 class="footer-title">
                            Quick Links
                        </h3>

                        <div class="footer-links">
                            <Link href="/">
                                Home
                            </Link>

                            <Link href="/">
                                Tours
                            </Link>

                            <Link href="/">
                                Transport
                            </Link>

                            <Link href="/">
                                Destinations
                            </Link>
                        </div>
                    </div>


                    <!-- SERVICES -->
                    <div>
                        <h3 class="footer-title">
                            Our Services
                        </h3>

                        <div class="footer-links">
                            <span>Private Tour</span>
                            <span>Transport Service</span>
                            <span>Airport Transfer</span>
                            <span>Custom Trip</span>
                        </div>
                    </div>


                    <!-- CONTACT -->
                    <div>
                        <h3 class="footer-title">
                            Contact
                        </h3>

                        <div
                            class="space-y-3 text-sm leading-6 text-white/45"
                        >
                            <p>
                                +62 812 3456 7890
                            </p>

                            <p>
                                info@dudetourlombok.com
                            </p>

                            <p>
                                Lombok, West Nusa Tenggara, Indonesia
                            </p>
                        </div>
                    </div>
                </div>


                <!-- COPYRIGHT -->
                <div
                    class="flex flex-col justify-between gap-3 pt-7 text-xs text-white/35 sm:flex-row"
                >
                    <p>
                        © 2026 Dude Tour & Trans Lombok. All rights reserved.
                    </p>

                    <p>
                        Explore Lombok With Local Experience.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>


<style scoped>
/* ============================================================
   FILTER PILL
============================================================ */

.filter-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border: 1px solid rgba(184, 122, 40, 0.12);
    border-radius: 999px;
    background: #f8edd8;
    padding: 9px 14px;
    color: #a66a18;
    font-size: 13px;
    font-weight: 700;
}


/* ============================================================
   PACKAGE CARD
============================================================ */

.package-card {
    overflow: hidden;
    border: 1px solid rgba(226, 232, 240, 0.8);
    border-radius: 24px;
    background: white;
    box-shadow: 0 12px 35px rgba(20, 35, 49, 0.07);
    transition:
        transform 0.35s ease,
        box-shadow 0.35s ease,
        border-color 0.35s ease;
}

.package-card:hover {
    transform: translateY(-7px);
    border-color: rgba(184, 122, 40, 0.2);
    box-shadow: 0 24px 55px rgba(20, 35, 49, 0.13);
}


/* ============================================================
   PACKAGE ARROW
============================================================ */

.package-arrow {
    display: flex;
    height: 45px;
    width: 45px;
    flex-shrink: 0;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: #172331;
    color: white;
    transition:
        transform 0.25s ease,
        background 0.25s ease,
        box-shadow 0.25s ease;
}

.package-arrow:hover {
    transform: translateX(3px);
    background: #b87a28;
    box-shadow: 0 8px 20px rgba(184, 122, 40, 0.25);
}


/* ============================================================
   VEHICLE PANEL
============================================================ */

.vehicle-panel {
    overflow: hidden;
    border-radius: 26px;
    background:
        radial-gradient(
            circle at 100% 0%,
            rgba(229, 189, 115, 0.12),
            transparent 35%
        ),
        #172331;
    padding: 28px;
    box-shadow: 0 20px 50px rgba(20, 35, 49, 0.15);
}


/* ============================================================
   VEHICLE CARD
============================================================ */

.vehicle-card {
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 18px;
    background: rgba(255, 255, 255, 0.07);
    padding: 12px;
    transition:
        transform 0.3s ease,
        background 0.3s ease,
        border-color 0.3s ease;
}

.vehicle-card:hover {
    transform: translateY(-3px);
    border-color: rgba(229, 189, 115, 0.25);
    background: rgba(255, 255, 255, 0.1);
}


/* ============================================================
   VEHICLE SPEC
============================================================ */

.vehicle-spec {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.07);
    padding: 6px 9px;
    color: rgba(255, 255, 255, 0.62);
    font-size: 11px;
    font-weight: 600;
}


/* ============================================================
   FOOTER
============================================================ */

.footer-title {
    margin-bottom: 18px;
    color: white;
    font-size: 15px;
    font-weight: 800;
}

.footer-links {
    display: flex;
    flex-direction: column;
    gap: 12px;
    color: rgba(255, 255, 255, 0.45);
    font-size: 13px;
}

.footer-links a {
    transition:
        color 0.2s ease,
        transform 0.2s ease;
}

.footer-links a:hover {
    color: #e5bd73;
    transform: translateX(3px);
}


/* ============================================================
   MOBILE
============================================================ */

@media (max-width: 639px) {
    .vehicle-panel {
        padding: 22px;
    }

    .package-card {
        border-radius: 20px;
    }

    .filter-pill {
        font-size: 12px;
    }
}
</style>