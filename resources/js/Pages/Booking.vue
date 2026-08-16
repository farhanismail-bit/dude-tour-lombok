<script setup>
import { computed, ref } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import {
    ArrowLeft,
    CalendarDays,
    CarFront,
    CheckCircle2,
    MapPin,
    Phone,
    User,
    Users,
    Mail,
    MessageCircle,
    ShieldCheck,
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

    services: {
        type: Array,
        default: () => [],
    },

    filters: {
        type: Object,
        default: () => ({
            date: '',
            people: 2,
            vehicle: null,
        }),
    },
})

const form = useForm({
    tour_package_id: props.package.id,
    service_id: props.services[0]?.id ?? '',
    destination_id: props.package.destinations?.[0]?.id ?? '',
    vehicle_id: props.filters.vehicle ?? '',
    booking_date: props.filters.date ?? '',
    number_of_people: Number(props.filters.people ?? 2),

    customer_name: '',
    customer_email: '',
    customer_phone: '',
    customer_whatsapp: '',
    customer_address: '',
    notes: '',
})

const selectedVehicle = computed(() => {
    return props.vehicles.find(
        (vehicle) => Number(vehicle.id) === Number(form.vehicle_id),
    )
})

const basePrice = computed(() => {
    return Number(props.package.price ?? 0)
})

const vehiclePrice = computed(() => {
    return Number(selectedVehicle.value?.price_per_day ?? 0)
})

const totalPrice = computed(() => {
    return basePrice.value + vehiclePrice.value
})

const formatRupiah = (value) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(Number(value ?? 0))
}

const submit = () => {
    form.post('/booking', {
        preserveScroll: true,
    })
}
</script>

<template>
    <Head>
        <title>Booking - {{ package.name }}</title>

        <meta
            name="description"
            :content="`Booking ${package.name} bersama Dude Tour & Trans Lombok.`"
        />
    </Head>

    <div class="min-h-screen bg-[#f7f9fb] text-slate-800">
        <!-- NAVBAR -->
        <header
            class="sticky top-0 z-50 border-b border-white/10 bg-[#172331]/95 shadow-lg backdrop-blur-xl"
        >
            <div
                class="mx-auto flex h-[78px] max-w-[1400px] items-center justify-between px-6 lg:px-10"
            >
                <Link
                    href="/"
                    class="flex items-center gap-3"
                >
                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-tr-[22px] rounded-bl-[22px] bg-gradient-to-br from-[#f3cf83] to-[#b87a28] text-2xl font-bold text-[#172331]"
                    >
                        D
                    </div>

                    <div class="leading-tight">
                        <div
                            class="text-lg font-bold tracking-[0.18em] text-[#e5bd73]"
                        >
                            DUDE
                        </div>

                        <div
                            class="text-[8px] tracking-[0.32em] text-white"
                        >
                            TOUR & TRANS
                        </div>
                    </div>
                </Link>

                <Link
                    href="/"
                    class="flex items-center gap-2 rounded-lg border border-white/15 px-4 py-2.5 text-sm font-semibold text-white transition hover:border-[#e5bd73] hover:text-[#e5bd73]"
                >
                    <ArrowLeft :size="17" />
                    Kembali
                </Link>
            </div>
        </header>

        <!-- HEADER -->
        <section class="bg-[#172331] px-6 py-14 lg:px-10">
            <div class="mx-auto max-w-[1400px]">
                <p
                    class="text-xs font-bold tracking-[0.25em] text-[#e5bd73]"
                >
                    PLAN YOUR JOURNEY
                </p>

                <h1
                    class="mt-3 max-w-3xl text-4xl font-black leading-tight text-white md:text-5xl"
                >
                    Booking Perjalanan Anda
                </h1>

                <p class="mt-4 max-w-2xl leading-7 text-white/65">
                    Lengkapi data perjalanan dan data diri Anda. Tim Dude Tour
                    & Trans Lombok akan menghubungi Anda untuk konfirmasi
                    booking.
                </p>
            </div>
        </section>

        <!-- CONTENT -->
        <main class="px-6 py-12 lg:px-10">
            <div
                class="mx-auto grid max-w-[1400px] gap-8 lg:grid-cols-[1fr_400px]"
            >
                <!-- FORM -->
                <form
                    class="rounded-[28px] bg-white p-6 shadow-[0_20px_60px_rgba(15,30,45,0.08)] md:p-9"
                    @submit.prevent="submit"
                >
                    <!-- PACKAGE -->
                    <div
                        class="rounded-2xl border border-[#ead29c] bg-[#fffaf0] p-5"
                    >
                        <div class="flex items-start gap-4">
                            <div
                                class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#172331] text-[#e5bd73]"
                            >
                                <MapPin :size="23" />
                            </div>

                            <div class="min-w-0">
                                <p
                                    class="text-xs font-bold tracking-[0.15em] text-[#b87a28]"
                                >
                                    TOUR PACKAGE
                                </p>

                                <h2
                                    class="mt-1 text-xl font-black text-[#172331]"
                                >
                                    {{ package.name }}
                                </h2>

                                <p
                                    class="mt-2 text-sm leading-6 text-slate-500"
                                >
                                    {{ package.short_description }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- TRIP DETAILS -->
                    <div class="mt-10">
                        <div class="mb-6">
                            <p class="text-xs font-bold tracking-[0.2em] text-[#b87a28]">
                                01
                            </p>

                            <h2 class="mt-1 text-2xl font-black text-[#172331]">
                                Detail Perjalanan
                            </h2>

                            <p class="mt-2 text-sm text-slate-500">
                                Tentukan tanggal, jumlah orang, destinasi, dan
                                layanan yang Anda inginkan.
                            </p>
                        </div>

                        <div class="grid gap-5 md:grid-cols-2">
                            <!-- DATE -->
                            <div>
                                <label class="form-label">
                                    Tanggal Perjalanan
                                </label>

                                <div class="input-wrapper">
                                    <CalendarDays
                                        :size="19"
                                        class="text-[#b87a28]"
                                    />

                                    <input
                                        v-model="form.booking_date"
                                        type="date"
                                        class="form-input"
                                        required
                                    />
                                </div>

                                <p
                                    v-if="form.errors.booking_date"
                                    class="form-error"
                                >
                                    {{ form.errors.booking_date }}
                                </p>
                            </div>

                            <!-- PEOPLE -->
                            <div>
                                <label class="form-label">
                                    Jumlah Orang
                                </label>

                                <div class="input-wrapper">
                                    <Users
                                        :size="19"
                                        class="text-[#b87a28]"
                                    />

                                    <select
                                        v-model.number="form.number_of_people"
                                        class="form-input"
                                        required
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

                            <!-- DESTINATION -->
                            <div>
                                <label class="form-label">
                                    Destinasi
                                </label>

                                <div class="input-wrapper">
                                    <MapPin
                                        :size="19"
                                        class="text-[#b87a28]"
                                    />

                                    <select
                                        v-model="form.destination_id"
                                        class="form-input"
                                        required
                                    >
                                        <option
                                            v-for="destination in package.destinations"
                                            :key="destination.id"
                                            :value="destination.id"
                                        >
                                            {{ destination.name }}
                                        </option>
                                    </select>
                                </div>

                                <p
                                    v-if="form.errors.destination_id"
                                    class="form-error"
                                >
                                    {{ form.errors.destination_id }}
                                </p>
                            </div>

                            <!-- SERVICE -->
                            <div>
                                <label class="form-label">
                                    Jenis Layanan
                                </label>

                                <div class="input-wrapper">
                                    <CheckCircle2
                                        :size="19"
                                        class="text-[#b87a28]"
                                    />

                                    <select
                                        v-model="form.service_id"
                                        class="form-input"
                                        required
                                    >
                                        <option
                                            v-for="service in services"
                                            :key="service.id"
                                            :value="service.id"
                                        >
                                            {{ service.name }}
                                        </option>
                                    </select>
                                </div>

                                <p
                                    v-if="form.errors.service_id"
                                    class="form-error"
                                >
                                    {{ form.errors.service_id }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- VEHICLE -->
                    <div class="mt-12">
                        <div class="mb-6">
                            <p class="text-xs font-bold tracking-[0.2em] text-[#b87a28]">
                                02
                            </p>

                            <h2 class="mt-1 text-2xl font-black text-[#172331]">
                                Pilih Kendaraan
                            </h2>

                            <p class="mt-2 text-sm text-slate-500">
                                Pilih kendaraan yang sesuai dengan jumlah
                                penumpang Anda.
                            </p>
                        </div>

                        <div
                            v-if="vehicles.length"
                            class="grid gap-4 md:grid-cols-2"
                        >
                            <label
                                v-for="vehicle in vehicles"
                                :key="vehicle.id"
                                class="relative cursor-pointer rounded-2xl border p-5 transition"
                                :class="
                                    Number(form.vehicle_id) === Number(vehicle.id)
                                        ? 'border-[#b87a28] bg-[#fffaf0] ring-2 ring-[#b87a28]/20'
                                        : 'border-slate-200 bg-white hover:border-[#d6b36c]'
                                "
                            >
                                <input
                                    v-model="form.vehicle_id"
                                    type="radio"
                                    :value="vehicle.id"
                                    class="sr-only"
                                />

                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex items-start gap-3">
                                        <div
                                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#172331] text-[#e5bd73]"
                                        >
                                            <CarFront :size="21" />
                                        </div>

                                        <div>
                                            <h3
                                                class="font-bold text-[#172331]"
                                            >
                                                {{ vehicle.name }}
                                            </h3>

                                            <p
                                                class="mt-1 text-xs text-slate-500"
                                            >
                                                {{ vehicle.type }}
                                            </p>
                                        </div>
                                    </div>

                                    <CheckCircle2
                                        v-if="
                                            Number(form.vehicle_id) ===
                                            Number(vehicle.id)
                                        "
                                        :size="21"
                                        class="shrink-0 text-[#b87a28]"
                                    />
                                </div>

                                <div
                                    class="mt-4 grid grid-cols-2 gap-3 text-xs"
                                >
                                    <div
                                        class="rounded-lg bg-slate-50 px-3 py-2"
                                    >
                                        <span class="text-slate-400">
                                            Kapasitas
                                        </span>

                                        <div class="mt-1 font-bold text-slate-700">
                                            {{ vehicle.passenger_capacity }}
                                            orang
                                        </div>
                                    </div>

                                    <div
                                        class="rounded-lg bg-slate-50 px-3 py-2"
                                    >
                                        <span class="text-slate-400">
                                            Harga
                                        </span>

                                        <div class="mt-1 font-bold text-[#b87a28]">
                                            {{
                                                formatRupiah(
                                                    vehicle.price_per_day,
                                                )
                                            }}
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <div
                            v-else
                            class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center"
                        >
                            <CarFront
                                :size="28"
                                class="mx-auto text-slate-400"
                            />

                            <p class="mt-3 font-semibold text-slate-600">
                                Belum ada kendaraan yang tersedia.
                            </p>

                            <p class="mt-1 text-sm text-slate-400">
                                Anda tetap dapat melanjutkan booking tanpa
                                memilih kendaraan.
                            </p>
                        </div>

                        <p
                            v-if="form.errors.vehicle_id"
                            class="mt-2 form-error"
                        >
                            {{ form.errors.vehicle_id }}
                        </p>
                    </div>

                    <!-- CUSTOMER -->
                    <div class="mt-12">
                        <div class="mb-6">
                            <p class="text-xs font-bold tracking-[0.2em] text-[#b87a28]">
                                03
                            </p>

                            <h2 class="mt-1 text-2xl font-black text-[#172331]">
                                Data Pemesan
                            </h2>

                            <p class="mt-2 text-sm text-slate-500">
                                Masukkan data yang dapat kami gunakan untuk
                                menghubungi Anda.
                            </p>
                        </div>

                        <div class="grid gap-5 md:grid-cols-2">
                            <!-- NAME -->
                            <div>
                                <label class="form-label">
                                    Nama Lengkap
                                </label>

                                <div class="input-wrapper">
                                    <User
                                        :size="19"
                                        class="text-[#b87a28]"
                                    />

                                    <input
                                        v-model="form.customer_name"
                                        type="text"
                                        placeholder="Nama lengkap"
                                        class="form-input"
                                        required
                                    />
                                </div>

                                <p
                                    v-if="form.errors.customer_name"
                                    class="form-error"
                                >
                                    {{ form.errors.customer_name }}
                                </p>
                            </div>

                            <!-- EMAIL -->
                            <div>
                                <label class="form-label">
                                    Email
                                </label>

                                <div class="input-wrapper">
                                    <Mail
                                        :size="19"
                                        class="text-[#b87a28]"
                                    />

                                    <input
                                        v-model="form.customer_email"
                                        type="email"
                                        placeholder="nama@email.com"
                                        class="form-input"
                                        required
                                    />
                                </div>

                                <p
                                    v-if="form.errors.customer_email"
                                    class="form-error"
                                >
                                    {{ form.errors.customer_email }}
                                </p>
                            </div>

                            <!-- PHONE -->
                            <div>
                                <label class="form-label">
                                    Nomor Telepon
                                </label>

                                <div class="input-wrapper">
                                    <Phone
                                        :size="19"
                                        class="text-[#b87a28]"
                                    />

                                    <input
                                        v-model="form.customer_phone"
                                        type="tel"
                                        placeholder="08xxxxxxxxxx"
                                        class="form-input"
                                        required
                                    />
                                </div>

                                <p
                                    v-if="form.errors.customer_phone"
                                    class="form-error"
                                >
                                    {{ form.errors.customer_phone }}
                                </p>
                            </div>

                            <!-- WHATSAPP -->
                            <div>
                                <label class="form-label">
                                    WhatsApp
                                </label>

                                <div class="input-wrapper">
                                    <MessageCircle
                                        :size="19"
                                        class="text-[#b87a28]"
                                    />

                                    <input
                                        v-model="form.customer_whatsapp"
                                        type="tel"
                                        placeholder="08xxxxxxxxxx"
                                        class="form-input"
                                    />
                                </div>

                                <p
                                    v-if="form.errors.customer_whatsapp"
                                    class="form-error"
                                >
                                    {{ form.errors.customer_whatsapp }}
                                </p>
                            </div>
                        </div>

                        <!-- ADDRESS -->
                        <div class="mt-5">
                            <label class="form-label">
                                Alamat
                            </label>

                            <textarea
                                v-model="form.customer_address"
                                rows="3"
                                placeholder="Alamat Anda"
                                class="form-textarea"
                            ></textarea>

                            <p
                                v-if="form.errors.customer_address"
                                class="form-error"
                            >
                                {{ form.errors.customer_address }}
                            </p>
                        </div>

                        <!-- NOTES -->
                        <div class="mt-5">
                            <label class="form-label">
                                Catatan Tambahan
                            </label>

                            <textarea
                                v-model="form.notes"
                                rows="4"
                                placeholder="Contoh: ingin dijemput di hotel..."
                                class="form-textarea"
                            ></textarea>

                            <p
                                v-if="form.errors.notes"
                                class="form-error"
                            >
                                {{ form.errors.notes }}
                            </p>
                        </div>
                    </div>

                    <!-- SUBMIT -->
                    <div
                        class="mt-10 border-t border-slate-100 pt-7"
                    >
                        <div
                            class="flex flex-col justify-between gap-5 sm:flex-row sm:items-center"
                        >
                            <div class="flex items-start gap-3">
                                <ShieldCheck
                                    :size="22"
                                    class="mt-0.5 shrink-0 text-[#b87a28]"
                                />

                                <p class="max-w-md text-xs leading-5 text-slate-500">
                                    Data booking Anda akan digunakan untuk
                                    proses konfirmasi perjalanan.
                                </p>
                            </div>

                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="flex min-h-[54px] items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#b87a28] to-[#e5bd73] px-7 font-bold text-white shadow-lg transition hover:-translate-y-0.5 disabled:cursor-not-allowed disabled:opacity-60"
                            >
                                <span v-if="!form.processing">
                                    KONFIRMASI BOOKING
                                </span>

                                <span v-else>
                                    MEMPROSES...
                                </span>
                            </button>
                        </div>
                    </div>
                </form>

                <!-- SUMMARY -->
                <aside class="lg:sticky lg:top-[100px] lg:self-start">
                    <div
                        class="overflow-hidden rounded-[28px] bg-[#172331] text-white shadow-[0_25px_70px_rgba(15,30,45,0.18)]"
                    >
                        <div class="p-7">
                            <p
                                class="text-xs font-bold tracking-[0.2em] text-[#e5bd73]"
                            >
                                BOOKING SUMMARY
                            </p>

                            <h2 class="mt-3 text-2xl font-black">
                                {{ package.name }}
                            </h2>

                            <p class="mt-3 text-sm leading-6 text-white/60">
                                {{ package.short_description }}
                            </p>
                        </div>

                        <div
                            class="border-y border-white/10 bg-white/[0.03] p-7"
                        >
                            <div class="space-y-5">
                                <div class="flex items-start justify-between gap-5">
                                    <div class="flex items-center gap-3">
                                        <CalendarDays
                                            :size="18"
                                            class="text-[#e5bd73]"
                                        />

                                        <span class="text-sm text-white/60">
                                            Tanggal
                                        </span>
                                    </div>

                                    <span
                                        class="text-right text-sm font-semibold"
                                    >
                                        {{
                                            form.booking_date
                                                ? form.booking_date
                                                : 'Belum dipilih'
                                        }}
                                    </span>
                                </div>

                                <div class="flex items-start justify-between gap-5">
                                    <div class="flex items-center gap-3">
                                        <Users
                                            :size="18"
                                            class="text-[#e5bd73]"
                                        />

                                        <span class="text-sm text-white/60">
                                            Peserta
                                        </span>
                                    </div>

                                    <span class="text-sm font-semibold">
                                        {{ form.number_of_people }} Orang
                                    </span>
                                </div>

                                <div class="flex items-start justify-between gap-5">
                                    <div class="flex items-center gap-3">
                                        <MapPin
                                            :size="18"
                                            class="text-[#e5bd73]"
                                        />

                                        <span class="text-sm text-white/60">
                                            Destinasi
                                        </span>
                                    </div>

                                    <span
                                        class="max-w-[180px] text-right text-sm font-semibold"
                                    >
                                        {{
                                            package.destinations?.find(
                                                (item) =>
                                                    Number(item.id) ===
                                                    Number(form.destination_id),
                                            )?.name ?? '-'
                                        }}
                                    </span>
                                </div>

                                <div class="flex items-start justify-between gap-5">
                                    <div class="flex items-center gap-3">
                                        <CarFront
                                            :size="18"
                                            class="text-[#e5bd73]"
                                        />

                                        <span class="text-sm text-white/60">
                                            Kendaraan
                                        </span>
                                    </div>

                                    <span
                                        class="max-w-[180px] text-right text-sm font-semibold"
                                    >
                                        {{
                                            selectedVehicle?.name ??
                                            'Tidak dipilih'
                                        }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="p-7">
                            <div
                                class="flex items-center justify-between border-b border-white/10 pb-4"
                            >
                                <span class="text-sm text-white/60">
                                    Harga Paket
                                </span>

                                <span class="font-semibold">
                                    {{ formatRupiah(basePrice) }}
                                </span>
                            </div>

                            <div
                                class="flex items-center justify-between border-b border-white/10 py-4"
                            >
                                <span class="text-sm text-white/60">
                                    Kendaraan
                                </span>

                                <span class="font-semibold">
                                    {{ formatRupiah(vehiclePrice) }}
                                </span>
                            </div>

                            <div
                                class="mt-5 flex items-end justify-between gap-4"
                            >
                                <div>
                                    <p class="text-xs text-white/50">
                                        ESTIMASI TOTAL
                                    </p>

                                    <p
                                        class="mt-1 text-2xl font-black text-[#e5bd73]"
                                    >
                                        {{ formatRupiah(totalPrice) }}
                                    </p>
                                </div>

                                <CheckCircle2
                                    :size="26"
                                    class="text-[#e5bd73]"
                                />
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </main>

        <!-- FOOTER -->
        <footer
            class="bg-[#101c27] px-6 py-8 text-center text-sm text-white/40 lg:px-10"
        >
            © 2026 Dude Tour & Trans Lombok. All rights reserved.
        </footer>
    </div>
</template>

<style scoped>
.form-label {
    display: block;
    margin-bottom: 8px;
    font-size: 13px;
    font-weight: 700;
    color: #334155;
}

.input-wrapper {
    display: flex;
    min-height: 52px;
    align-items: center;
    gap: 11px;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    background: white;
    padding: 0 14px;
    transition: all 0.2s ease;
}

.input-wrapper:focus-within {
    border-color: #b87a28;
    box-shadow: 0 0 0 3px rgba(184, 122, 40, 0.1);
}

.form-input {
    width: 100%;
    min-width: 0;
    border: none;
    outline: none;
    background: transparent;
    color: #172331;
    font-size: 14px;
    font-weight: 500;
}

.form-input::placeholder {
    color: #94a3b8;
}

.form-textarea {
    width: 100%;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    background: white;
    padding: 14px 15px;
    color: #172331;
    font-size: 14px;
    outline: none;
    resize: vertical;
    transition: all 0.2s ease;
}

.form-textarea::placeholder {
    color: #94a3b8;
}

.form-textarea:focus {
    border-color: #b87a28;
    box-shadow: 0 0 0 3px rgba(184, 122, 40, 0.1);
}

.form-error {
    margin-top: 6px;
    font-size: 12px;
    color: #dc2626;
}
</style>