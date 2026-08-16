<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { Link } from '@inertiajs/vue3'

import {
    Menu,
    X,
    Phone,
    ArrowRight,
    MapPin,
    CalendarDays,
    Users,
    CarFront,
    Search,
    ChevronDown,
    Compass,
    ShieldCheck,
    Headphones,
    Star,
    CheckCircle2,
    Facebook,
    Instagram,
    MessageCircle,
    Mail,
    Clock3,
} from 'lucide-vue-next'

const isScrolled = ref(false)
const mobileMenuOpen = ref(false)

const destination = ref('')
const date = ref('')
const people = ref('2 Orang')
const service = ref('Semua Layanan')

/*
|--------------------------------------------------------------------------
| DATA DARI BACKEND
|--------------------------------------------------------------------------
| Tour package dikirim dari route Home melalui Inertia.
*/
const props = defineProps({
    tourPackages: {
        type: Array,
        default: () => [],
    },
})

/*
|--------------------------------------------------------------------------
| FORMAT HARGA
|--------------------------------------------------------------------------
*/
const formatPrice = (price) => {
    const numericPrice = Number(price ?? 0)

    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(numericPrice)
}

/*
|--------------------------------------------------------------------------
| IMAGE URL
|--------------------------------------------------------------------------
| Data image dari database disimpan seperti:
| tour-packages/xxxxx.jpg
|
| Storage public diakses melalui:
| /storage/tour-packages/xxxxx.jpg
|--------------------------------------------------------------------------
*/
const imageUrl = (image) => {
    if (!image) return null

    const value = String(image).trim()
    if (!value) return null

    if (value.startsWith('http://') || value.startsWith('https://')) {
        return value
    }

    if (value.startsWith('/storage/')) {
        return value
    }

    const cleanValue = value
        .replace(/^\/+/, '')
        .replace(/^public\//, '')
        .replace(/^storage\//, '')

    return `/storage/${cleanValue}`
}

/*
|--------------------------------------------------------------------------
| SCROLL NAVIGATION
|--------------------------------------------------------------------------
*/

const handleScroll = () => {
    isScrolled.value = window.scrollY > 50
}

const scrollToSection = (sectionId) => {
    mobileMenuOpen.value = false

    const element = document.getElementById(sectionId)

    if (element) {
        element.scrollIntoView({
            behavior: 'smooth',
            block: 'start',
        })
    }
}

/*
|--------------------------------------------------------------------------
| BOOKING NAVIGATION
|--------------------------------------------------------------------------
|
| Contoh:
| /booking/create?tour_package=lombok-highlight-tour
|
*/

const goToBooking = (slug) => {
    mobileMenuOpen.value = false

    window.location.href = `/booking/create?tour_package=${encodeURIComponent(slug)}`
}

/*
|--------------------------------------------------------------------------
| SEARCH
|--------------------------------------------------------------------------
*/

const handleSearch = () => {
    scrollToSection('tours')
}

/*
|--------------------------------------------------------------------------
| LIFECYCLE
|--------------------------------------------------------------------------
*/

onMounted(() => {
    handleScroll()

    window.addEventListener('scroll', handleScroll)
})

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll)
})
</script>

<template>
    <div class="min-h-screen overflow-x-hidden bg-[#f8fafc] text-slate-800">

        <!-- =========================================================
             NAVBAR
        ========================================================== -->
        <header
            :class="[
                'fixed left-0 top-0 z-[100] w-full transition-all duration-300',
                isScrolled
                    ? 'border-b border-white/10 bg-[#172331]/95 shadow-lg backdrop-blur-xl'
                    : 'bg-transparent',
            ]"
        >
            <div
                class="mx-auto flex h-[88px] max-w-[1600px] items-center justify-between px-6 lg:px-10"
            >

                <!-- LOGO -->
                <a
                    href="#home"
                    class="flex items-center gap-3"
                    @click.prevent="scrollToSection('home')"
                >
                    <div
                        class="flex h-14 w-14 items-center justify-center rounded-tr-[28px] rounded-bl-[28px] bg-gradient-to-br from-[#f3cf83] to-[#b87a28] text-3xl font-bold text-[#172331] shadow-xl"
                    >
                        D
                    </div>

                    <div class="leading-tight">
                        <div
                            class="text-xl font-bold tracking-[0.2em] text-[#e5bd73]"
                        >
                            DUDE
                        </div>

                        <div
                            class="text-[10px] tracking-[0.38em] text-white"
                        >
                            TOUR & TRANS
                        </div>

                        <div
                            class="text-[9px] tracking-[0.45em] text-white/70"
                        >
                            LOMBOK
                        </div>
                    </div>
                </a>


                <!-- DESKTOP MENU -->
                <nav class="hidden items-center gap-7 lg:flex">

                    <button
                        class="nav-link active"
                        @click="scrollToSection('home')"
                    >
                        Home
                    </button>

                    <button
                        class="nav-link"
                        @click="scrollToSection('tours')"
                    >
                        Tours
                    </button>

                    <button
                        class="nav-link"
                        @click="scrollToSection('transport')"
                    >
                        Transport
                    </button>

                    <button
                        class="nav-link"
                        @click="scrollToSection('destinations')"
                    >
                        Destinations
                    </button>

                    <button
                        class="nav-link"
                        @click="scrollToSection('about')"
                    >
                        About Us
                    </button>

                    <button
                        class="nav-link"
                        @click="scrollToSection('gallery')"
                    >
                        Gallery
                    </button>

                    <button
                        class="nav-link"
                        @click="scrollToSection('contact')"
                    >
                        Contact
                    </button>

                </nav>


                <!-- RIGHT DESKTOP -->
                <div class="hidden items-center gap-6 xl:flex">

                    <a
                        href="tel:+6281234567890"
                        class="flex items-center gap-2 text-sm font-medium text-white transition hover:text-[#e5bd73]"
                    >
                        <Phone :size="18" />
                        <span>+62 812 3456 7890</span>
                    </a>

                    <!-- BOOK NOW -->
                    <button
                        type="button"
                        class="rounded-lg bg-gradient-to-r from-[#b87a28] to-[#e5bd73] px-6 py-3 text-sm font-bold text-white shadow-lg transition hover:-translate-y-0.5"
                        @click="goToBooking(props.tourPackages[0]?.slug || 'lombok-highlight-tour')"
                    >
                        BOOK NOW
                    </button>

                </div>


                <!-- MOBILE BUTTON -->
                <button
                    type="button"
                    class="text-white lg:hidden"
                    aria-label="Toggle menu"
                    @click="mobileMenuOpen = !mobileMenuOpen"
                >
                    <X
                        v-if="mobileMenuOpen"
                        :size="30"
                    />

                    <Menu
                        v-else
                        :size="30"
                    />
                </button>

            </div>


            <!-- MOBILE MENU -->
            <div
                v-if="mobileMenuOpen"
                class="border-t border-white/10 bg-[#172331]/98 px-6 py-6 backdrop-blur-xl lg:hidden"
            >
                <div class="flex flex-col gap-5">

                    <button
                        type="button"
                        class="text-left text-white transition hover:text-[#e5bd73]"
                        @click="scrollToSection('home')"
                    >
                        Home
                    </button>

                    <button
                        type="button"
                        class="text-left text-white transition hover:text-[#e5bd73]"
                        @click="scrollToSection('tours')"
                    >
                        Tours
                    </button>

                    <button
                        type="button"
                        class="text-left text-white transition hover:text-[#e5bd73]"
                        @click="scrollToSection('transport')"
                    >
                        Transport
                    </button>

                    <button
                        type="button"
                        class="text-left text-white transition hover:text-[#e5bd73]"
                        @click="scrollToSection('destinations')"
                    >
                        Destinations
                    </button>

                    <button
                        type="button"
                        class="text-left text-white transition hover:text-[#e5bd73]"
                        @click="scrollToSection('about')"
                    >
                        About Us
                    </button>

                    <button
                        type="button"
                        class="text-left text-white transition hover:text-[#e5bd73]"
                        @click="scrollToSection('gallery')"
                    >
                        Gallery
                    </button>

                    <button
                        type="button"
                        class="text-left text-white transition hover:text-[#e5bd73]"
                        @click="scrollToSection('contact')"
                    >
                        Contact
                    </button>

                    <!-- MOBILE BOOK NOW -->
                    <button
                        type="button"
                        class="mt-2 rounded-lg bg-gradient-to-r from-[#b87a28] to-[#e5bd73] py-4 font-bold text-white shadow-lg transition hover:-translate-y-0.5"
                        @click="goToBooking(props.tourPackages[0]?.slug || 'lombok-highlight-tour')"
                    >
                        BOOK NOW
                    </button>

                </div>
            </div>

        </header>


        <!-- =========================================================
             HERO
        ========================================================== -->
        <section
            id="home"
            class="hero-section relative min-h-[760px] overflow-hidden lg:min-h-[820px]"
        >

            <!-- BACKGROUND -->
            <div
                class="absolute inset-0 bg-cover bg-center"
                style="
                    background-image:
                        linear-gradient(
                            90deg,
                            rgba(16, 32, 45, 0.94) 0%,
                            rgba(16, 32, 45, 0.76) 42%,
                            rgba(16, 32, 45, 0.30) 100%
                        ),
                        url('https://images.unsplash.com/photo-1559628233-6ce7c49c8a1e?auto=format&fit=crop&w=1920&q=90');
                "
            ></div>


            <!-- DECORATION -->
            <div
                class="absolute inset-0 bg-[radial-gradient(circle_at_80%_30%,rgba(229,189,115,0.14),transparent_35%)]"
            ></div>


            <!-- HERO CONTENT -->
            <div
                class="relative z-10 mx-auto flex min-h-[760px] max-w-[1500px] items-center px-6 pb-[120px] pt-[130px] lg:min-h-[820px] lg:px-10"
            >
                <div class="max-w-[720px]">

                    <p
                        class="mb-4 text-sm font-bold tracking-[0.25em] text-[#e5bd73]"
                    >
                        EXPLORE THE BEAUTY OF
                    </p>


                    <h1
                        class="font-black uppercase leading-[0.9] tracking-tight text-white"
                    >
                        <span
                            class="block text-5xl sm:text-6xl lg:text-7xl xl:text-[84px]"
                        >
                            LOMBOK
                        </span>

                        <span
                            class="mt-3 block text-3xl text-white/95 sm:text-4xl lg:text-5xl"
                        >
                            WITH DUDE TOUR
                        </span>
                    </h1>


                    <div
                        class="mt-7 h-1 w-32 bg-gradient-to-r from-[#bd852e] to-[#e5c273]"
                    ></div>


                    <p
                        class="mt-7 text-lg font-medium text-white/90 lg:text-2xl"
                    >
                        Private Tour · Transport Service · Local Experience
                    </p>


                    <p
                        class="mt-5 max-w-[650px] text-base leading-8 text-white/75 lg:text-lg"
                    >
                        Nikmati perjalanan terbaik bersama Dude Tour & Trans
                        Lombok. Temukan keindahan alam, budaya lokal, dan
                        petualangan tak terlupakan bersama kami.
                    </p>


                    <!-- HERO BUTTON -->
                    <div class="mt-9 flex flex-col gap-4 sm:flex-row">

                        <button
                            type="button"
                            class="flex items-center justify-center gap-3 rounded-lg bg-gradient-to-r from-[#b87a28] to-[#e5bd73] px-7 py-4 font-bold text-white shadow-xl transition hover:-translate-y-1"
                            @click="scrollToSection('tours')"
                        >
                            EXPLORE TOURS

                            <ArrowRight :size="20" />
                        </button>


                        <button
                            type="button"
                            class="flex items-center justify-center gap-3 rounded-lg border border-white/40 bg-white/5 px-7 py-4 font-bold text-white backdrop-blur-sm transition hover:bg-white hover:text-[#172331]"
                            @click="scrollToSection('contact')"
                        >
                            CONTACT US
                        </button>

                    </div>

                </div>
            </div>

        </section>


        <!-- =========================================================
             SEARCH BAR
        ========================================================== -->
        <section
            class="relative z-40 -mt-[72px] px-4 sm:px-6 lg:px-10"
        >
            <div class="mx-auto max-w-[1400px]">

                <div
                    class="grid grid-cols-1 overflow-hidden rounded-[22px] bg-white p-3 shadow-[0_25px_70px_rgba(15,30,45,0.22)] lg:grid-cols-[1.15fr_1fr_1fr_1.15fr_auto] lg:p-4"
                >

                    <!-- DESTINATION -->
                    <div class="search-field">

                        <MapPin
                            :size="27"
                            stroke-width="1.8"
                            class="search-icon"
                        />

                        <div class="min-w-0 flex-1">

                            <label class="search-label">
                                Destinasi
                            </label>

                            <select
                                v-model="destination"
                                class="search-select"
                            >
                                <option value="">
                                    Pilih Destinasi
                                </option>

                                <option value="Gili Trawangan">
                                    Gili Trawangan
                                </option>

                                <option value="Kuta Mandalika">
                                    Kuta Mandalika
                                </option>

                                <option value="Pink Beach">
                                    Pink Beach
                                </option>

                                <option value="Tanjung Aan">
                                    Tanjung Aan
                                </option>

                                <option value="Sendang Gile">
                                    Air Terjun Sendang Gile
                                </option>
                            </select>

                        </div>

                        <ChevronDown
                            :size="18"
                            class="text-slate-400"
                        />

                    </div>


                    <!-- DATE -->
                    <div class="search-field">

                        <CalendarDays
                            :size="26"
                            stroke-width="1.8"
                            class="search-icon"
                        />

                        <div class="min-w-0 flex-1">

                            <label class="search-label">
                                Tanggal
                            </label>

                            <input
                                v-model="date"
                                type="date"
                                class="search-input"
                            />

                        </div>

                    </div>


                    <!-- PEOPLE -->
                    <div class="search-field">

                        <Users
                            :size="27"
                            stroke-width="1.8"
                            class="search-icon"
                        />

                        <div class="min-w-0 flex-1">

                            <label class="search-label">
                                Jumlah Orang
                            </label>

                            <select
                                v-model="people"
                                class="search-select"
                            >
                                <option>1 Orang</option>
                                <option>2 Orang</option>
                                <option>3 Orang</option>
                                <option>4 Orang</option>
                                <option>5 Orang</option>
                                <option>6+ Orang</option>
                            </select>

                        </div>

                        <ChevronDown
                            :size="18"
                            class="text-slate-400"
                        />

                    </div>


                    <!-- SERVICE -->
                    <div class="search-field search-field-last">

                        <CarFront
                            :size="27"
                            stroke-width="1.8"
                            class="search-icon"
                        />

                        <div class="min-w-0 flex-1">

                            <label class="search-label">
                                Jenis Layanan
                            </label>

                            <select
                                v-model="service"
                                class="search-select"
                            >
                                <option>Semua Layanan</option>
                                <option>Private Tour</option>
                                <option>Transport</option>
                                <option>Airport Transfer</option>
                                <option>Custom Trip</option>
                            </select>

                        </div>

                        <ChevronDown
                            :size="18"
                            class="text-slate-400"
                        />

                    </div>


                    <!-- SEARCH BUTTON -->
                    <button
                        type="button"
                        class="search-button mt-3 lg:mt-0"
                        @click="handleSearch"
                    >
                        <Search :size="20" />

                        <span>
                            CARI SEKARANG
                        </span>
                    </button>

                </div>

            </div>
        </section>


        <!-- =========================================================
             FEATURE
        ========================================================== -->
        <section
            class="bg-white px-6 pb-16 pt-[155px] lg:px-10"
        >
            <div
                class="mx-auto grid max-w-[1400px] gap-10 md:grid-cols-3"
            >

                <div class="feature-card">

                    <div class="feature-icon">
                        <Compass :size="26" />
                    </div>

                    <div>

                        <h3>
                            Local Experience
                        </h3>

                        <p>
                            Nikmati pengalaman perjalanan autentik bersama
                            pemandu lokal Lombok.
                        </p>

                    </div>
                </div>


                <div class="feature-card">

                    <div class="feature-icon">
                        <ShieldCheck :size="26" />
                    </div>

                    <div>

                        <h3>
                            Trusted Service
                        </h3>

                        <p>
                            Perjalanan nyaman, aman, dan terpercaya untuk
                            setiap kebutuhan Anda.
                        </p>

                    </div>
                </div>


                <div class="feature-card">

                    <div class="feature-icon">
                        <Headphones :size="26" />
                    </div>

                    <div>

                        <h3>
                            24/7 Support
                        </h3>

                        <p>
                            Tim kami siap membantu perjalanan Anda kapan pun
                            dibutuhkan.
                        </p>

                    </div>
                </div>

            </div>
        </section>


        <!-- =========================================================
             TOURS
        ========================================================== -->
        <section
            id="tours"
            class="scroll-mt-24 bg-[#f7f9fb] px-6 py-24 lg:px-10"
        >
            <div class="mx-auto max-w-[1400px]">

                <div class="mb-14 max-w-2xl">
                    <p class="section-label">OUR BEST PACKAGES</p>
                    <h2 class="section-title">Explore Lombok With Us</h2>
                    <p class="section-description">
                        Pilih paket perjalanan terbaik untuk menikmati
                        keindahan Lombok sesuai gaya perjalanan Anda.
                    </p>
                </div>

                <div
                    v-if="props.tourPackages.length > 0"
                    class="grid gap-8 md:grid-cols-2 xl:grid-cols-3"
                >
                    <article
                        v-for="tour in props.tourPackages"
                        :key="tour.id"
                        class="tour-card"
                    >
                        <div class="relative">
                            <div
                                v-if="imageUrl(tour.image)"
                                class="tour-image"
                                :style="{ backgroundImage: `url('${imageUrl(tour.image)}')` }"
                            ></div>

                            <div
                                v-else
                                class="tour-image flex items-center justify-center bg-gradient-to-br from-[#39514f] to-[#c5d6bd]"
                            >
                                <Compass :size="48" class="text-white/80" />
                            </div>

                            <div
                                class="absolute right-4 top-4 rounded-full bg-white/95 px-3 py-1.5 text-xs font-bold text-[#b87a28] shadow-lg"
                            >
                                <span class="flex items-center gap-1">
                                    <Star :size="13" fill="currentColor" />
                                    Featured
                                </span>
                            </div>
                        </div>

                        <div class="p-7">
                            <div class="mb-4 min-h-[28px]">
                                <span v-if="tour.tag" class="tour-tag">
                                    {{ tour.tag }}
                                </span>
                            </div>

                            <h3 class="text-2xl font-bold text-[#172331]">
                                {{ tour.name }}
                            </h3>

                            <p class="mt-4 min-h-[84px] leading-7 text-slate-500">
                                {{ tour.short_description }}
                            </p>

                            <div
                                v-if="tour.destinations && tour.destinations.length > 0"
                                class="mt-5 flex flex-wrap gap-2"
                            >
                                <span
                                    v-for="destination in tour.destinations.slice(0, 3)"
                                    :key="destination.id"
                                    class="rounded-full bg-[#f5f7fa] px-3 py-1.5 text-xs font-semibold text-slate-600"
                                >
                                    {{ destination.name }}
                                </span>
                            </div>

                            <div class="mt-7 flex items-end justify-between gap-4">
                                <div>
                                    <p class="text-xs font-semibold text-slate-400">
                                        Mulai dari
                                    </p>
                                    <span class="text-xl font-bold text-[#b87a28]">
                                        {{ formatPrice(tour.price) }}
                                    </span>
                                    <p class="mt-1 text-xs text-slate-400">
                                        {{ tour.duration_days }} Hari
                                        <span v-if="Number(tour.duration_nights) > 0">
                                            · {{ tour.duration_nights }} Malam
                                        </span>
                                    </p>
                                </div>

                                <button
                                    type="button"
                                    class="tour-arrow"
                                    :aria-label="`Book ${tour.name}`"
                                    :title="`Book ${tour.name}`"
                                    @click="goToBooking(tour.slug)"
                                >
                                    <ArrowRight :size="20" />
                                </button>
                            </div>
                        </div>
                    </article>
                </div>

                <div
                    v-else
                    class="rounded-[22px] bg-white px-6 py-16 text-center shadow-[0_14px_40px_rgba(20,35,49,0.08)]"
                >
                    <div
                        class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-[#f8edd8] text-[#b87a28]"
                    >
                        <Compass :size="30" />
                    </div>

                    <h3 class="mt-5 text-xl font-bold text-[#172331]">
                        Belum Ada Paket Tour
                    </h3>

                    <p class="mx-auto mt-2 max-w-md text-sm leading-7 text-slate-500">
                        Belum ada paket tour yang aktif dan ditampilkan sebagai
                        featured. Silakan aktifkan paket dari halaman admin.
                    </p>
                </div>

            </div>
        </section>


        <!-- =========================================================
             TRANSPORT
        ========================================================== -->
        <section
            id="transport"
            class="scroll-mt-24 bg-white px-6 py-24 lg:px-10"
        >
            <div
                class="mx-auto grid max-w-[1400px] items-center gap-14 lg:grid-cols-2"
            >

                <div>

                    <p class="section-label">
                        COMFORTABLE TRANSPORT
                    </p>

                    <h2 class="section-title">
                        Travel Around Lombok Comfortably
                    </h2>

                    <p class="mt-6 leading-8 text-slate-500">
                        Kami menyediakan berbagai pilihan transportasi untuk
                        airport transfer, private trip, perjalanan bisnis,
                        keluarga, hingga custom trip.
                    </p>


                    <div class="mt-8 grid gap-4 sm:grid-cols-2">

                        <div class="transport-point">
                            <CheckCircle2 :size="20" />
                            Airport Transfer
                        </div>

                        <div class="transport-point">
                            <CheckCircle2 :size="20" />
                            Private Car
                        </div>

                        <div class="transport-point">
                            <CheckCircle2 :size="20" />
                            Driver Experience
                        </div>

                        <div class="transport-point">
                            <CheckCircle2 :size="20" />
                            Custom Trip
                        </div>

                    </div>


                    <button
                        type="button"
                        class="mt-9 rounded-lg bg-[#172331] px-7 py-4 font-bold text-white transition hover:bg-[#b87a28]"
                        @click="scrollToSection('contact')"
                    >
                        BOOK TRANSPORT
                    </button>

                </div>


                <div
                    class="transport-image"
                    style="background-image: url('https://images.unsplash.com/photo-1511919884226-fd3cad34687c?auto=format&fit=crop&w=1200&q=80')"
                ></div>

            </div>
        </section>


        <!-- =========================================================
             DESTINATIONS
        ========================================================== -->
        <section
            id="destinations"
            class="scroll-mt-24 bg-[#172331] px-6 py-24 lg:px-10"
        >
            <div class="mx-auto max-w-[1400px]">

                <div class="mb-14">

                    <p
                        class="text-sm font-bold tracking-[0.25em] text-[#e5bd73]"
                    >
                        POPULAR DESTINATIONS
                    </p>

                    <h2
                        class="mt-3 text-4xl font-black text-white lg:text-5xl"
                    >
                        Discover The Beauty of Lombok
                    </h2>

                </div>


                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">

                    <div class="destination-card">

                        <div
                            class="destination-image"
                            style="background-image: url('https://images.unsplash.com/photo-1510414842594-a61c69b5ae57?auto=format&fit=crop&w=800&q=80')"
                        ></div>

                        <div class="destination-overlay">
                            <h3>Gili Trawangan</h3>
                            <p>Island Paradise</p>
                        </div>

                    </div>


                    <div class="destination-card">

                        <div
                            class="destination-image"
                            style="background-image: url('https://images.unsplash.com/photo-1544550285-f813152fb2fd?auto=format&fit=crop&w=800&q=80')"
                        ></div>

                        <div class="destination-overlay">
                            <h3>Kuta Mandalika</h3>
                            <p>Beautiful Beach</p>
                        </div>

                    </div>


                    <div class="destination-card">

                        <div
                            class="destination-image"
                            style="background-image: url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80')"
                        ></div>

                        <div class="destination-overlay">
                            <h3>Pink Beach</h3>
                            <p>Unique Destination</p>
                        </div>

                    </div>


                    <div class="destination-card">

                        <div
                            class="destination-image"
                            style="background-image: url('https://images.unsplash.com/photo-1433086966358-54859d0ed716?auto=format&fit=crop&w=800&q=80')"
                        ></div>

                        <div class="destination-overlay">
                            <h3>Sendang Gile</h3>
                            <p>Waterfall Adventure</p>
                        </div>

                    </div>

                </div>

            </div>
        </section>


        <!-- =========================================================
             ABOUT
        ========================================================== -->
        <section
            id="about"
            class="scroll-mt-24 bg-white px-6 py-24 lg:px-10"
        >
            <div
                class="mx-auto grid max-w-[1400px] items-center gap-16 lg:grid-cols-2"
            >

                <div
                    class="about-image"
                    style="background-image: url('https://images.unsplash.com/photo-1486911278844-a81c5267e227?auto=format&fit=crop&w=1200&q=80')"
                ></div>


                <div>

                    <p class="section-label">
                        ABOUT DUDE TOUR
                    </p>

                    <h2 class="section-title">
                        Your Local Travel Partner in Lombok
                    </h2>

                    <p class="mt-6 leading-8 text-slate-500">
                        Dude Tour & Trans Lombok hadir untuk memberikan
                        pengalaman perjalanan yang nyaman, aman, fleksibel,
                        dan berkesan.
                    </p>

                    <p class="mt-4 leading-8 text-slate-500">
                        Kami memahami Lombok bukan hanya sebagai destinasi,
                        tetapi sebagai rumah dengan budaya, alam, dan cerita
                        yang siap untuk Anda jelajahi.
                    </p>


                    <div class="mt-8 grid grid-cols-2 gap-8">

                        <div>

                            <div
                                class="text-4xl font-black text-[#b87a28]"
                            >
                                5+
                            </div>

                            <p class="mt-2 text-sm text-slate-500">
                                Years Experience
                            </p>

                        </div>


                        <div>

                            <div
                                class="text-4xl font-black text-[#b87a28]"
                            >
                                1K+
                            </div>

                            <p class="mt-2 text-sm text-slate-500">
                                Happy Travelers
                            </p>

                        </div>

                    </div>

                </div>

            </div>
        </section>


        <!-- =========================================================
             GALLERY
        ========================================================== -->
        <section
            id="gallery"
            class="scroll-mt-24 bg-[#f7f9fb] px-6 py-24 lg:px-10"
        >
            <div class="mx-auto max-w-[1400px]">

                <div class="mb-14 text-center">

                    <p class="section-label">
                        GALLERY
                    </p>

                    <h2 class="section-title">
                        Moments From Our Journey
                    </h2>

                </div>


                <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-4">

                    <div
                        class="gallery-item"
                        style="background-image: url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80')"
                    ></div>

                    <div
                        class="gallery-item"
                        style="background-image: url('https://images.unsplash.com/photo-1510414842594-a61c69b5ae57?auto=format&fit=crop&w=800&q=80')"
                    ></div>

                    <div
                        class="gallery-item"
                        style="background-image: url('https://images.unsplash.com/photo-1544550285-f813152fb2fd?auto=format&fit=crop&w=800&q=80')"
                    ></div>

                    <div
                        class="gallery-item"
                        style="background-image: url('https://images.unsplash.com/photo-1433086966358-54859d0ed716?auto=format&fit=crop&w=800&q=80')"
                    ></div>

                </div>

            </div>
        </section>


        <!-- =========================================================
             CONTACT
        ========================================================== -->
        <section
            id="contact"
            class="scroll-mt-24 bg-[#172331] px-6 py-24 lg:px-10"
        >
            <div
                class="mx-auto grid max-w-[1400px] gap-14 lg:grid-cols-2"
            >

                <div>

                    <p
                        class="text-sm font-bold tracking-[0.25em] text-[#e5bd73]"
                    >
                        PLAN YOUR JOURNEY
                    </p>

                    <h2
                        class="mt-4 text-4xl font-black text-white lg:text-5xl"
                    >
                        Ready To Explore Lombok?
                    </h2>

                    <p
                        class="mt-6 max-w-xl leading-8 text-white/70"
                    >
                        Hubungi kami sekarang dan rencanakan perjalanan impian
                        Anda bersama Dude Tour & Trans Lombok.
                    </p>


                    <div class="mt-8 space-y-4 text-white/85">

                        <a
                            href="tel:+6281234567890"
                            class="flex items-center gap-3"
                        >
                            <Phone
                                :size="20"
                                class="text-[#e5bd73]"
                            />

                            +62 812 3456 7890
                        </a>


                        <a
                            href="mailto:info@dudetourlombok.com"
                            class="flex items-center gap-3"
                        >
                            <Mail
                                :size="20"
                                class="text-[#e5bd73]"
                            />

                            info@dudetourlombok.com
                        </a>


                        <div class="flex items-center gap-3">

                            <Clock3
                                :size="20"
                                class="text-[#e5bd73]"
                            />

                            Open Every Day

                        </div>

                    </div>

                </div>


                <div
                    class="rounded-[28px] bg-white p-7 shadow-2xl lg:p-10"
                >

                    <div class="grid gap-5">

                        <input
                            type="text"
                            placeholder="Nama Anda"
                            class="contact-input"
                        />

                        <input
                            type="tel"
                            placeholder="Nomor WhatsApp"
                            class="contact-input"
                        />

                        <select class="contact-input">
                            <option>
                                Pilih Layanan
                            </option>

                            <option>
                                Private Tour
                            </option>

                            <option>
                                Transport
                            </option>

                            <option>
                                Airport Transfer
                            </option>

                            <option>
                                Custom Trip
                            </option>
                        </select>

                        <textarea
                            rows="5"
                            placeholder="Ceritakan rencana perjalanan Anda..."
                            class="contact-input resize-none"
                        ></textarea>

                        <button
                            type="button"
                            class="rounded-lg bg-gradient-to-r from-[#b87a28] to-[#e5bd73] py-4 font-bold text-white shadow-lg transition hover:-translate-y-0.5"
                        >
                            KIRIM PESAN
                        </button>

                    </div>

                </div>

            </div>
        </section>


        <!-- =========================================================
             FOOTER
        ========================================================== -->
        <footer
            class="bg-[#101c27] px-6 pb-8 pt-16 text-white lg:px-10"
        >

            <div
                class="mx-auto grid max-w-[1400px] gap-12 border-b border-white/10 pb-12 md:grid-cols-2 lg:grid-cols-4"
            >

                <!-- BRAND -->
                <div>

                    <div class="flex items-center gap-3">

                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-tr-[24px] rounded-bl-[24px] bg-gradient-to-br from-[#f3cf83] to-[#b87a28] text-2xl font-bold text-[#172331]"
                        >
                            D
                        </div>

                        <div>

                            <div
                                class="text-lg font-bold tracking-[0.2em] text-[#e5bd73]"
                            >
                                DUDE
                            </div>

                            <div
                                class="text-[9px] tracking-[0.28em] text-white"
                            >
                                TOUR & TRANS
                            </div>

                        </div>

                    </div>


                    <p class="mt-6 leading-7 text-white/60">
                        Travel partner terpercaya untuk menjelajahi keindahan
                        Lombok dengan nyaman dan berkesan.
                    </p>


                    <div class="mt-6 flex gap-3">

                        <a
                            href="#"
                            class="social-icon"
                            aria-label="Facebook"
                        >
                            <Facebook :size="19" />
                        </a>

                        <a
                            href="#"
                            class="social-icon"
                            aria-label="Instagram"
                        >
                            <Instagram :size="19" />
                        </a>

                        <a
                            href="#"
                            class="social-icon"
                            aria-label="WhatsApp"
                        >
                            <MessageCircle :size="19" />
                        </a>

                    </div>

                </div>


                <!-- QUICK LINKS -->
                <div>

                    <h3 class="footer-title">
                        Quick Links
                    </h3>

                    <div class="footer-links">

                        <button
                            type="button"
                            @click="scrollToSection('home')"
                        >
                            Home
                        </button>

                        <button
                            type="button"
                            @click="scrollToSection('tours')"
                        >
                            Tours
                        </button>

                        <button
                            type="button"
                            @click="scrollToSection('transport')"
                        >
                            Transport
                        </button>

                        <button
                            type="button"
                            @click="scrollToSection('destinations')"
                        >
                            Destinations
                        </button>

                    </div>

                </div>


                <!-- SERVICES -->
                <div>

                    <h3 class="footer-title">
                        Our Services
                    </h3>

                    <div class="footer-links">

                        <span>
                            Private Tour
                        </span>

                        <span>
                            Transport Service
                        </span>

                        <span>
                            Airport Transfer
                        </span>

                        <span>
                            Custom Trip
                        </span>

                    </div>

                </div>


                <!-- CONTACT -->
                <div>

                    <h3 class="footer-title">
                        Contact
                    </h3>

                    <div
                        class="space-y-4 text-sm text-white/60"
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
                class="mx-auto flex max-w-[1400px] flex-col justify-between gap-4 pt-8 text-sm text-white/40 md:flex-row"
            >

                <p>
                    © 2026 Dude Tour & Trans Lombok. All rights reserved.
                </p>

                <p>
                    Explore Lombok With Local Experience.
                </p>

            </div>

        </footer>

    </div>
</template>


<style scoped>

html {
    scroll-behavior: smooth;
}


/* =========================================================
   NAVIGATION
========================================================= */

.nav-link {
    position: relative;
    color: rgba(255, 255, 255, 0.88);
    font-size: 14px;
    font-weight: 500;
    transition: all 0.25s ease;
}

.nav-link:hover {
    color: #e5bd73;
}

.nav-link::after {
    position: absolute;
    bottom: -9px;
    left: 50%;
    height: 2px;
    width: 0;
    content: '';
    background: #e5bd73;
    transform: translateX(-50%);
    transition: width 0.25s ease;
}

.nav-link:hover::after,
.nav-link.active::after {
    width: 100%;
}


/* =========================================================
   SEARCH
========================================================= */

.search-field {
    display: flex;
    min-height: 72px;
    align-items: center;
    gap: 14px;
    padding: 8px 20px;
    border-right: 1px solid #e5e7eb;
}

.search-field-last {
    border-right: none;
}

.search-icon {
    flex-shrink: 0;
    color: #b87a28;
}

.search-label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: #64748b;
}

.search-select,
.search-input {
    margin-top: 5px;
    width: 100%;
    border: none;
    outline: none;
    background: transparent;
    color: #172331;
    font-size: 14px;
    font-weight: 600;
}

.search-button {
    display: flex;
    min-height: 60px;
    align-items: center;
    justify-content: center;
    gap: 10px;
    border-radius: 12px;
    padding: 0 28px;
    background: linear-gradient(
        135deg,
        #b87a28,
        #e5bd73
    );
    color: white;
    font-size: 13px;
    font-weight: 800;
    white-space: nowrap;
    transition: all 0.25s ease;
}

.search-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 14px 30px rgba(184, 122, 40, 0.3);
}


/* =========================================================
   FEATURE
========================================================= */

.feature-card {
    display: flex;
    align-items: flex-start;
    gap: 18px;
}

.feature-icon {
    display: flex;
    height: 58px;
    width: 58px;
    flex-shrink: 0;
    align-items: center;
    justify-content: center;
    border: 1px solid #ead29c;
    border-radius: 50%;
    background: linear-gradient(
        145deg,
        #ffffff,
        #f9f4e9
    );
    color: #b87a28;
    box-shadow: 0 10px 30px rgba(20, 35, 49, 0.08);
}

.feature-card h3 {
    font-size: 18px;
    font-weight: 800;
    color: #172331;
}

.feature-card p {
    margin-top: 8px;
    line-height: 1.7;
    color: #64748b;
}


/* =========================================================
   SECTION
========================================================= */

.section-label {
    font-size: 12px;
    font-weight: 800;
    letter-spacing: 0.25em;
    color: #b87a28;
}

.section-title {
    margin-top: 12px;
    font-size: 38px;
    font-weight: 900;
    line-height: 1.1;
    color: #172331;
}

.section-description {
    margin-top: 20px;
    line-height: 1.8;
    color: #64748b;
}


/* =========================================================
   TOUR CARD
========================================================= */

.tour-card {
    overflow: hidden;
    border-radius: 22px;
    background: white;
    box-shadow: 0 14px 40px rgba(20, 35, 49, 0.08);
    transition: all 0.3s ease;
}

.tour-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 24px 55px rgba(20, 35, 49, 0.15);
}

.tour-image {
    height: 260px;
    background-size: cover;
    background-position: center;
}

.tour-tag {
    border-radius: 999px;
    background: #f8edd8;
    padding: 6px 12px;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 0.08em;
    color: #a66a18;
}

.tour-arrow {
    display: flex;
    height: 44px;
    width: 44px;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: #172331;
    color: white;
    transition: 0.25s;
}

.tour-arrow:hover {
    background: #b87a28;
}

.tour-arrow:active {
    transform: scale(0.94);
}


/* =========================================================
   TRANSPORT
========================================================= */

.transport-point {
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 600;
    color: #475569;
}

.transport-point svg {
    color: #b87a28;
}

.transport-image {
    min-height: 430px;
    border-radius: 32px;
    background-size: cover;
    background-position: center;
    box-shadow: 0 25px 60px rgba(20, 35, 49, 0.2);
}


/* =========================================================
   DESTINATION
========================================================= */

.destination-card {
    position: relative;
    min-height: 380px;
    overflow: hidden;
    border-radius: 22px;
}

.destination-image {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-position: center;
    transition: transform 0.5s ease;
}

.destination-card:hover .destination-image {
    transform: scale(1.08);
}

.destination-overlay {
    position: absolute;
    right: 0;
    bottom: 0;
    left: 0;
    padding: 90px 25px 25px;
    background: linear-gradient(
        transparent,
        rgba(10, 20, 28, 0.92)
    );
}

.destination-overlay h3 {
    font-size: 22px;
    font-weight: 800;
    color: white;
}

.destination-overlay p {
    margin-top: 5px;
    color: rgba(255, 255, 255, 0.7);
}


/* =========================================================
   ABOUT
========================================================= */

.about-image {
    min-height: 500px;
    border-radius: 32px;
    background-size: cover;
    background-position: center;
    box-shadow: 0 25px 60px rgba(20, 35, 49, 0.15);
}


/* =========================================================
   GALLERY
========================================================= */

.gallery-item {
    min-height: 300px;
    border-radius: 20px;
    background-size: cover;
    background-position: center;
    transition: transform 0.3s ease;
    box-shadow: 0 12px 35px rgba(20, 35, 49, 0.08);
}

.gallery-item:hover {
    transform: translateY(-6px);
}


/* =========================================================
   CONTACT
========================================================= */

.contact-input {
    width: 100%;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 15px 16px;
    outline: none;
    color: #172331;
    transition: 0.2s ease;
}

.contact-input:focus {
    border-color: #b87a28;
    box-shadow: 0 0 0 3px rgba(184, 122, 40, 0.1);
}


/* =========================================================
   FOOTER
========================================================= */

.footer-title {
    margin-bottom: 20px;
    font-size: 16px;
    font-weight: 800;
    color: white;
}

.footer-links {
    display: flex;
    flex-direction: column;
    gap: 13px;
    font-size: 14px;
    color: rgba(255, 255, 255, 0.55);
}

.footer-links button,
.footer-links span {
    text-align: left;
    transition: 0.2s ease;
}

.footer-links button:hover {
    color: #e5bd73;
}

.social-icon {
    display: flex;
    height: 42px;
    width: 42px;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.08);
    color: white;
    transition: 0.25s ease;
}

.social-icon:hover {
    background: #b87a28;
    transform: translateY(-3px);
}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 1023px) {

    .search-field {
        border-right: none;
        border-bottom: 1px solid #e5e7eb;
    }

    .search-field-last {
        border-bottom: none;
    }

    .search-button {
        width: 100%;
    }

    .section-title {
        font-size: 34px;
    }
}


@media (max-width: 640px) {

    .hero-section {
        min-height: 720px;
    }

    .section-title {
        font-size: 30px;
    }

    .search-field {
        min-height: 65px;
        padding: 8px 10px;
    }

    .transport-image,
    .about-image {
        min-height: 330px;
    }

}

</style>