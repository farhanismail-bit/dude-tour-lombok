<script setup>
import { computed, ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'

import {
    ArrowLeft,
    ArrowRight,
    CalendarDays,
    CarFront,
    CheckCircle2,
    Clock3,
    MapPin,
    Star,
    Users,
    ShieldCheck,
    Phone,
} from 'lucide-vue-next'

const props = defineProps({
    package: {
        type: Object,
        required: true,
    },

    vehicles: {
        type: Array,
        default: () => [],
    },

    filters: {
        type: Object,
        default: () => ({
            date: '',
            people: 2,
        }),
    },
})

/*
|--------------------------------------------------------------------------
| FORM
|--------------------------------------------------------------------------
*/

const date = ref(props.filters?.date || '')

const people = ref(
    Number(props.filters?.people || 2)
)

const selectedVehicle = ref(
    props.vehicles?.length
        ? props.vehicles[0].id
        : null
)

const showVehicleError = ref(false)


/*
|--------------------------------------------------------------------------
| IMAGE
|--------------------------------------------------------------------------
*/

const packageImage = computed(() => {
    if (props.package?.image) {
        return props.package.image
    }

    const images = {
        'lombok-highlight-tour':
            'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=1800&q=90',

        'south-lombok-escape':
            'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1800&q=90',

        'north-lombok-adventure':
            'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?auto=format&fit=crop&w=1800&q=90',
    }

    return (
        images[props.package?.slug] ||
        'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1800&q=90'
    )
})


/*
|--------------------------------------------------------------------------
| FORMAT
|--------------------------------------------------------------------------
*/

const formatPrice = (price) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(Number(price || 0))
}


const durationText = computed(() => {
    const days = Number(props.package?.duration_days || 0)
    const nights = Number(props.package?.duration_nights || 0)

    if (days && nights) {
        return `${days} Hari ${nights} Malam`
    }

    if (days) {
        return `${days} Hari`
    }

    return 'Custom Trip'
})


/*
|--------------------------------------------------------------------------
| SELECTED VEHICLE
|--------------------------------------------------------------------------
*/

const selectedVehicleData = computed(() => {
    return props.vehicles.find(
        (vehicle) => vehicle.id === selectedVehicle.value
    )
})


/*
|--------------------------------------------------------------------------
| TOTAL
|--------------------------------------------------------------------------
*/

const tourPrice = computed(() => {
    return Number(props.package?.price || 0)
})

const vehiclePrice = computed(() => {
    return Number(
        selectedVehicleData.value?.price_per_day || 0
    )
})

const totalPrice = computed(() => {
    return tourPrice.value + vehiclePrice.value
})


/*
|--------------------------------------------------------------------------
| BOOKING
|--------------------------------------------------------------------------
*/

const handleBooking = () => {
    showVehicleError.value = false

    if (!date.value) {
        document
            .getElementById('booking-card')
            ?.scrollIntoView({
                behavior: 'smooth',
                block: 'center',
            })

        return
    }

    if (!selectedVehicle.value && props.vehicles.length > 0) {
        showVehicleError.value = true

        document
            .getElementById('vehicle-section')
            ?.scrollIntoView({
                behavior: 'smooth',
                block: 'center',
            })

        return
    }

    /*
    |--------------------------------------------------------------------------
    | SEMENTARA
    |--------------------------------------------------------------------------
    |
    | Booking page akan kita buat setelah Detail Tour selesai.
    |
    */

    router.get('/booking/create', {
        tour_package: props.package.slug,
        date: date.value,
        people: people.value,
        vehicle: selectedVehicle.value,
    })
}
</script>


<template>
    <div class="min-h-screen bg-[#f7f9fb] text-[#172331]">

        <!-- =====================================================
             NAVBAR
        ====================================================== -->

        <header
            class="sticky top-0 z-[100] border-b border-white/10 bg-[#172331]/95 shadow-lg backdrop-blur-xl"
        >
            <div
                class="mx-auto flex min-h-[82px] max-w-[1400px] items-center justify-between px-6 lg:px-10"
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


                <!-- BACK -->

                <Link
                    href="/search"
                    class="group flex items-center gap-2 rounded-lg border border-white/10 px-4 py-2.5 text-sm font-semibold text-white transition duration-300 hover:border-[#e5bd73]/40 hover:bg-white/5 hover:text-[#e5bd73]"
                >
                    <ArrowLeft
                        :size="18"
                        class="transition group-hover:-translate-x-1"
                    />

                    <span class="hidden sm:inline">
                        Kembali ke Hasil Pencarian
                    </span>

                    <span class="sm:hidden">
                        Kembali
                    </span>
                </Link>

            </div>
        </header>


        <!-- =====================================================
             HERO IMAGE
        ====================================================== -->

        <section
            class="relative h-[420px] overflow-hidden sm:h-[500px] lg:h-[560px]"
        >

            <!-- IMAGE -->

            <img
                :src="packageImage"
                :alt="package.name"
                class="absolute inset-0 h-full w-full object-cover"
            />


            <!-- OVERLAY -->

            <div
                class="absolute inset-0 bg-gradient-to-t from-[#101c27] via-[#101c27]/40 to-black/10"
            ></div>


            <!-- CONTENT -->

            <div
                class="relative mx-auto flex h-full max-w-[1400px] items-end px-6 pb-12 lg:px-10 lg:pb-16"
            >
                <div class="max-w-4xl">

                    <!-- TAG -->

                    <div
                        class="mb-5 inline-flex rounded-full bg-white/95 px-4 py-2 text-xs font-black tracking-[0.15em] text-[#a66a18] shadow-lg"
                    >
                        {{ package.tag || 'TOUR PACKAGE' }}
                    </div>


                    <!-- TITLE -->

                    <h1
                        class="text-4xl font-black leading-[1.05] tracking-tight text-white sm:text-5xl lg:text-7xl"
                    >
                        {{ package.name }}
                    </h1>


                    <!-- META -->

                    <div
                        class="mt-6 flex flex-wrap items-center gap-4 text-sm text-white/80"
                    >

                        <div class="flex items-center gap-2">
                            <Star
                                :size="18"
                                fill="currentColor"
                                class="text-[#e5bd73]"
                            />

                            <span class="font-bold">
                                4.9
                            </span>

                            <span class="text-white/50">
                                Excellent
                            </span>
                        </div>


                        <div
                            class="hidden h-5 w-px bg-white/20 sm:block"
                        ></div>


                        <div class="flex items-center gap-2">
                            <Clock3
                                :size="17"
                                class="text-[#e5bd73]"
                            />

                            {{ durationText }}
                        </div>


                        <div
                            class="hidden h-5 w-px bg-white/20 sm:block"
                        ></div>


                        <div class="flex items-center gap-2">
                            <Users
                                :size="17"
                                class="text-[#e5bd73]"
                            />

                            Private Trip
                        </div>
                    </div>

                </div>
            </div>

        </section>


        <!-- =====================================================
             MAIN
        ====================================================== -->

        <main class="px-6 py-14 lg:px-10 lg:py-20">

            <div
                class="mx-auto grid max-w-[1400px] items-start gap-10 xl:grid-cols-[minmax(0,1fr)_400px]"
            >

                <!-- =================================================
                     LEFT CONTENT
                ================================================== -->

                <div>

                    <!-- DESCRIPTION -->

                    <section
                        class="rounded-[24px] bg-white p-7 shadow-[0_12px_35px_rgba(20,35,49,0.06)] sm:p-9"
                    >

                        <p class="section-label">
                            ABOUT THIS TOUR
                        </p>

                        <h2 class="section-title">
                            Jelajahi Lombok Bersama Kami
                        </h2>


                        <p
                            class="mt-6 text-base leading-8 text-slate-500"
                        >
                            {{
                                package.description ||
                                package.short_description
                            }}
                        </p>


                        <!-- SHORT DESCRIPTION -->

                        <p
                            v-if="
                                package.short_description &&
                                package.description &&
                                package.short_description !== package.description
                            "
                            class="mt-4 text-base leading-8 text-slate-500"
                        >
                            {{ package.short_description }}
                        </p>

                    </section>


                    <!-- =================================================
                         DESTINATIONS
                    ================================================== -->

                    <section
                        class="mt-7 rounded-[24px] bg-white p-7 shadow-[0_12px_35px_rgba(20,35,49,0.06)] sm:p-9"
                    >

                        <p class="section-label">
                            DESTINATIONS
                        </p>

                        <h2 class="section-title">
                            Tempat Yang Akan Anda Kunjungi
                        </h2>


                        <div
                            v-if="package.destinations?.length"
                            class="mt-7 grid gap-4 sm:grid-cols-2"
                        >

                            <div
                                v-for="destination in package.destinations"
                                :key="destination.id"
                                class="destination-item"
                            >

                                <div
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#f8edd8] text-[#b87a28]"
                                >
                                    <MapPin :size="21" />
                                </div>

                                <div class="min-w-0">
                                    <h3
                                        class="font-bold text-[#172331]"
                                    >
                                        {{ destination.name }}
                                    </h3>

                                    <p
                                        v-if="destination.location"
                                        class="mt-1 text-xs text-slate-400"
                                    >
                                        {{ destination.location }}
                                    </p>
                                </div>

                            </div>

                        </div>


                        <div
                            v-else
                            class="mt-6 rounded-xl bg-slate-50 p-5 text-sm text-slate-500"
                        >
                            Informasi destinasi belum tersedia.
                        </div>

                    </section>


                    <!-- =================================================
                         TOUR INFORMATION
                    ================================================== -->

                    <section
                        class="mt-7 rounded-[24px] bg-white p-7 shadow-[0_12px_35px_rgba(20,35,49,0.06)] sm:p-9"
                    >

                        <p class="section-label">
                            TOUR INFORMATION
                        </p>

                        <h2 class="section-title">
                            Informasi Perjalanan
                        </h2>


                        <div
                            class="mt-7 grid gap-4 sm:grid-cols-2 lg:grid-cols-3"
                        >

                            <div class="info-box">
                                <Clock3
                                    :size="22"
                                    class="text-[#b87a28]"
                                />

                                <div>
                                    <p class="info-label">
                                        Durasi
                                    </p>

                                    <p class="info-value">
                                        {{ durationText }}
                                    </p>
                                </div>
                            </div>


                            <div class="info-box">
                                <Users
                                    :size="22"
                                    class="text-[#b87a28]"
                                />

                                <div>
                                    <p class="info-label">
                                        Tipe Perjalanan
                                    </p>

                                    <p class="info-value">
                                        Private Trip
                                    </p>
                                </div>
                            </div>


                            <div class="info-box">
                                <MapPin
                                    :size="22"
                                    class="text-[#b87a28]"
                                />

                                <div>
                                    <p class="info-label">
                                        Area
                                    </p>

                                    <p class="info-value">
                                        Lombok
                                    </p>
                                </div>
                            </div>

                        </div>

                    </section>


                    <!-- =================================================
                         WHY US
                    ================================================== -->

                    <section
                        class="mt-7 rounded-[24px] bg-[#172331] p-7 shadow-xl sm:p-9"
                    >

                        <p
                            class="text-xs font-black tracking-[0.25em] text-[#e5bd73]"
                        >
                            WHY DUDE TOUR
                        </p>

                        <h2
                            class="mt-3 text-3xl font-black text-white"
                        >
                            Perjalanan Lebih Nyaman
                        </h2>


                        <div
                            class="mt-7 grid gap-5 sm:grid-cols-2"
                        >

                            <div class="benefit-card">
                                <div class="benefit-icon">
                                    <ShieldCheck :size="22" />
                                </div>

                                <div>
                                    <h3>
                                        Trusted Service
                                    </h3>

                                    <p>
                                        Layanan perjalanan yang nyaman dan
                                        terpercaya.
                                    </p>
                                </div>
                            </div>


                            <div class="benefit-card">
                                <div class="benefit-icon">
                                    <Users :size="22" />
                                </div>

                                <div>
                                    <h3>
                                        Local Experience
                                    </h3>

                                    <p>
                                        Kenali Lombok bersama pengalaman lokal.
                                    </p>
                                </div>
                            </div>


                            <div class="benefit-card">
                                <div class="benefit-icon">
                                    <CarFront :size="22" />
                                </div>

                                <div>
                                    <h3>
                                        Comfortable Transport
                                    </h3>

                                    <p>
                                        Pilihan kendaraan sesuai kebutuhan.
                                    </p>
                                </div>
                            </div>


                            <div class="benefit-card">
                                <div class="benefit-icon">
                                    <Phone :size="22" />
                                </div>

                                <div>
                                    <h3>
                                        Support
                                    </h3>

                                    <p>
                                        Tim kami siap membantu perjalanan Anda.
                                    </p>
                                </div>
                            </div>

                        </div>

                    </section>

                </div>


                <!-- =================================================
                     BOOKING CARD
                ================================================== -->

                <aside id="booking-card">

                    <div
                        class="booking-card xl:sticky xl:top-[105px]"
                    >

                        <!-- HEADER -->

                        <div>
                            <p class="booking-label">
                                PLAN YOUR JOURNEY
                            </p>

                            <h2 class="mt-2 text-2xl font-black text-white">
                                Rencanakan Perjalanan
                            </h2>

                            <p
                                class="mt-2 text-sm leading-6 text-white/55"
                            >
                                Lengkapi pilihan perjalanan Anda sebelum
                                melakukan booking.
                            </p>
                        </div>


                        <!-- PRICE -->

                        <div
                            class="mt-7 rounded-2xl bg-white/5 p-5"
                        >
                            <p class="text-xs text-white/45">
                                Harga paket mulai dari
                            </p>

                            <p
                                class="mt-1 text-3xl font-black text-[#e5bd73]"
                            >
                                {{ formatPrice(package.price) }}
                            </p>
                        </div>


                        <!-- DATE -->

                        <div class="mt-6">

                            <label class="booking-label-small">
                                Tanggal Perjalanan
                            </label>

                            <div class="booking-input-wrapper">

                                <CalendarDays
                                    :size="19"
                                    class="shrink-0 text-[#b87a28]"
                                />

                                <input
                                    v-model="date"
                                    type="date"
                                    class="booking-input"
                                />

                            </div>

                            <p
                                v-if="!date"
                                class="mt-2 text-xs text-white/35"
                            >
                                Pilih tanggal perjalanan Anda.
                            </p>

                        </div>


                        <!-- PEOPLE -->

                        <div class="mt-5">

                            <label class="booking-label-small">
                                Jumlah Orang
                            </label>

                            <div class="booking-input-wrapper">

                                <Users
                                    :size="19"
                                    class="shrink-0 text-[#b87a28]"
                                />

                                <select
                                    v-model="people"
                                    class="booking-input"
                                >
                                    <option :value="1">
                                        1 Orang
                                    </option>

                                    <option :value="2">
                                        2 Orang
                                    </option>

                                    <option :value="3">
                                        3 Orang
                                    </option>

                                    <option :value="4">
                                        4 Orang
                                    </option>

                                    <option :value="5">
                                        5 Orang
                                    </option>

                                    <option :value="6">
                                        6 Orang
                                    </option>

                                    <option :value="7">
                                        7 Orang
                                    </option>

                                    <option :value="8">
                                        8 Orang
                                    </option>

                                    <option :value="9">
                                        9 Orang
                                    </option>

                                    <option :value="10">
                                        10 Orang
                                    </option>
                                </select>

                            </div>

                        </div>


                        <!-- VEHICLES -->

                        <div
                            id="vehicle-section"
                            class="mt-6"
                        >

                            <div
                                class="flex items-center justify-between"
                            >
                                <label class="booking-label-small">
                                    Pilih Kendaraan
                                </label>

                                <span
                                    class="text-[11px] text-white/35"
                                >
                                    Opsional
                                </span>
                            </div>


                            <!-- NO VEHICLE -->

                            <div
                                v-if="vehicles.length === 0"
                                class="mt-3 rounded-xl border border-white/10 bg-white/5 p-4"
                            >
                                <div class="flex gap-3">

                                    <CarFront
                                        :size="20"
                                        class="mt-0.5 text-[#e5bd73]"
                                    />

                                    <div>
                                        <p
                                            class="text-sm font-bold text-white"
                                        >
                                            Kendaraan belum tersedia
                                        </p>

                                        <p
                                            class="mt-1 text-xs leading-5 text-white/45"
                                        >
                                            Tim kami akan membantu menyiapkan
                                            kendaraan.
                                        </p>
                                    </div>

                                </div>
                            </div>


                            <!-- VEHICLE LIST -->

                            <div
                                v-else
                                class="mt-3 space-y-3"
                            >

                                <label
                                    v-for="vehicle in vehicles"
                                    :key="vehicle.id"
                                    class="vehicle-option"
                                    :class="{
                                        'vehicle-option-active':
                                            selectedVehicle === vehicle.id,
                                    }"
                                >

                                    <input
                                        v-model="selectedVehicle"
                                        type="radio"
                                        :value="vehicle.id"
                                        class="sr-only"
                                    />

                                    <div
                                        class="flex items-center gap-3"
                                    >

                                        <div
                                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white/10"
                                        >
                                            <CarFront
                                                :size="21"
                                                class="text-[#e5bd73]"
                                            />
                                        </div>

                                        <div class="min-w-0 flex-1">

                                            <p
                                                class="truncate text-sm font-bold text-white"
                                            >
                                                {{ vehicle.name }}
                                            </p>

                                            <p
                                                class="mt-1 text-[11px] text-white/40"
                                            >
                                                Kapasitas
                                                {{
                                                    vehicle.passenger_capacity
                                                }}
                                                orang
                                            </p>

                                        </div>

                                        <div
                                            class="text-right"
                                        >
                                            <p
                                                class="text-sm font-bold text-[#e5bd73]"
                                            >
                                                {{
                                                    formatPrice(
                                                        vehicle.price_per_day
                                                    )
                                                }}
                                            </p>

                                            <p
                                                class="mt-0.5 text-[10px] text-white/35"
                                            >
                                                / hari
                                            </p>
                                        </div>

                                    </div>

                                    <CheckCircle2
                                        v-if="
                                            selectedVehicle === vehicle.id
                                        "
                                        :size="19"
                                        class="absolute right-3 top-3 text-[#e5bd73]"
                                    />

                                </label>

                            </div>


                            <p
                                v-if="showVehicleError"
                                class="mt-2 text-xs font-semibold text-red-300"
                            >
                                Silakan pilih kendaraan terlebih dahulu.
                            </p>

                        </div>


                        <!-- TOTAL -->

                        <div
                            class="mt-7 border-t border-white/10 pt-6"
                        >

                            <div
                                class="flex items-center justify-between"
                            >
                                <span
                                    class="text-sm text-white/50"
                                >
                                    Harga paket
                                </span>

                                <span
                                    class="text-sm font-semibold text-white"
                                >
                                    {{ formatPrice(tourPrice) }}
                                </span>
                            </div>


                            <div
                                class="mt-3 flex items-center justify-between"
                            >
                                <span
                                    class="text-sm text-white/50"
                                >
                                    Kendaraan
                                </span>

                                <span
                                    class="text-sm font-semibold text-white"
                                >
                                    {{
                                        vehiclePrice
                                            ? formatPrice(vehiclePrice)
                                            : '—'
                                    }}
                                </span>
                            </div>


                            <div
                                class="mt-5 flex items-end justify-between gap-4 border-t border-white/10 pt-5"
                            >

                                <div>
                                    <p
                                        class="text-xs text-white/40"
                                    >
                                        Estimasi total
                                    </p>

                                    <p
                                        class="mt-1 text-2xl font-black text-[#e5bd73]"
                                    >
                                        {{ formatPrice(totalPrice) }}
                                    </p>
                                </div>

                                <span
                                    class="pb-1 text-[10px] text-white/35"
                                >
                                    / perjalanan
                                </span>

                            </div>

                        </div>


                        <!-- BOOK BUTTON -->

                        <button
                            type="button"
                            class="booking-button"
                            @click="handleBooking"
                        >
                            PESAN SEKARANG

                            <ArrowRight :size="19" />
                        </button>


                        <!-- TRUST -->

                        <div
                            class="mt-5 flex items-start gap-3"
                        >

                            <ShieldCheck
                                :size="19"
                                class="mt-0.5 shrink-0 text-[#e5bd73]"
                            />

                            <p
                                class="text-[11px] leading-5 text-white/40"
                            >
                                Data perjalanan Anda akan digunakan untuk
                                membantu proses pemesanan.
                            </p>

                        </div>

                    </div>

                </aside>

            </div>

        </main>


        <!-- =====================================================
             FOOTER
        ====================================================== -->

        <footer
            class="bg-[#101c27] px-6 py-8 text-white lg:px-10"
        >

            <div
                class="mx-auto flex max-w-[1400px] flex-col justify-between gap-4 text-xs text-white/35 sm:flex-row"
            >

                <p>
                    © 2026 Dude Tour & Trans Lombok.
                    All rights reserved.
                </p>

                <p>
                    Explore Lombok With Local Experience.
                </p>

            </div>

        </footer>

    </div>
</template>


<style scoped>
/* ============================================================
   SECTION
============================================================ */

.section-label {
    font-size: 11px;
    font-weight: 900;
    letter-spacing: 0.25em;
    color: #b87a28;
}

.section-title {
    margin-top: 10px;
    color: #172331;
    font-size: 30px;
    font-weight: 900;
    line-height: 1.15;
}


/* ============================================================
   DESTINATION
============================================================ */

.destination-item {
    display: flex;
    align-items: center;
    gap: 14px;
    border: 1px solid #edf0f3;
    border-radius: 16px;
    padding: 15px;
    background: #fafbfc;
    transition:
        border-color 0.25s ease,
        transform 0.25s ease,
        background 0.25s ease;
}

.destination-item:hover {
    transform: translateY(-2px);
    border-color: rgba(184, 122, 40, 0.2);
    background: #fffdf8;
}


/* ============================================================
   INFO
============================================================ */

.info-box {
    display: flex;
    align-items: center;
    gap: 13px;
    border-radius: 16px;
    background: #f8fafc;
    padding: 17px;
}

.info-label {
    color: #94a3b8;
    font-size: 11px;
    font-weight: 600;
}

.info-value {
    margin-top: 3px;
    color: #172331;
    font-size: 14px;
    font-weight: 800;
}


/* ============================================================
   BENEFIT
============================================================ */

.benefit-card {
    display: flex;
    gap: 13px;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 16px;
    background: rgba(255, 255, 255, 0.04);
    padding: 17px;
}

.benefit-icon {
    display: flex;
    height: 42px;
    width: 42px;
    flex-shrink: 0;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    background: rgba(229, 189, 115, 0.1);
    color: #e5bd73;
}

.benefit-card h3 {
    color: white;
    font-size: 14px;
    font-weight: 800;
}

.benefit-card p {
    margin-top: 5px;
    color: rgba(255, 255, 255, 0.42);
    font-size: 12px;
    line-height: 1.6;
}


/* ============================================================
   BOOKING CARD
============================================================ */

.booking-card {
    border-radius: 26px;
    background:
        radial-gradient(
            circle at 100% 0%,
            rgba(229, 189, 115, 0.13),
            transparent 35%
        ),
        #172331;
    padding: 28px;
    box-shadow: 0 24px 60px rgba(20, 35, 49, 0.18);
}

.booking-label {
    font-size: 10px;
    font-weight: 900;
    letter-spacing: 0.25em;
    color: #e5bd73;
}

.booking-label-small {
    display: block;
    color: rgba(255, 255, 255, 0.7);
    font-size: 11px;
    font-weight: 700;
}


/* ============================================================
   INPUT
============================================================ */

.booking-input-wrapper {
    display: flex;
    align-items: center;
    gap: 11px;
    margin-top: 9px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.06);
    padding: 13px 14px;
    transition:
        border-color 0.2s ease,
        background 0.2s ease;
}

.booking-input-wrapper:focus-within {
    border-color: rgba(229, 189, 115, 0.5);
    background: rgba(255, 255, 255, 0.08);
}

.booking-input {
    width: 100%;
    min-width: 0;
    border: none;
    outline: none;
    background: transparent;
    color: white;
    font-size: 13px;
    font-weight: 600;
}

.booking-input::placeholder {
    color: rgba(255, 255, 255, 0.35);
}

.booking-input option {
    background: #172331;
    color: white;
}


/* ============================================================
   VEHICLE OPTION
============================================================ */

.vehicle-option {
    position: relative;
    display: block;
    cursor: pointer;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 15px;
    background: rgba(255, 255, 255, 0.04);
    padding: 12px;
    transition:
        border-color 0.25s ease,
        background 0.25s ease,
        transform 0.25s ease;
}

.vehicle-option:hover {
    transform: translateY(-2px);
    border-color: rgba(229, 189, 115, 0.3);
    background: rgba(255, 255, 255, 0.07);
}

.vehicle-option-active {
    border-color: rgba(229, 189, 115, 0.65);
    background: rgba(229, 189, 115, 0.08);
}


/* ============================================================
   BUTTON
============================================================ */

.booking-button {
    display: flex;
    width: 100%;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin-top: 25px;
    border-radius: 12px;
    background: linear-gradient(
        135deg,
        #b87a28,
        #e5bd73
    );
    padding: 15px 20px;
    color: white;
    font-size: 13px;
    font-weight: 900;
    box-shadow: 0 12px 30px rgba(184, 122, 40, 0.2);
    transition:
        transform 0.25s ease,
        box-shadow 0.25s ease;
}

.booking-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 18px 35px rgba(184, 122, 40, 0.3);
}


/* ============================================================
   MOBILE
============================================================ */

@media (max-width: 639px) {
    .section-title {
        font-size: 26px;
    }

    .booking-card {
        padding: 22px;
    }
}
</style>