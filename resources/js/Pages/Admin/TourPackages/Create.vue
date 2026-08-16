<script setup>
import { computed, ref } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import {
    ArrowLeft,
    Check,
    Image as ImageIcon,
    MapPin,
    Package,
    Save,
    Upload,
    X,
} from 'lucide-vue-next'

const props = defineProps({
    destinations: {
        type: Array,
        default: () => [],
    },
})

const form = useForm({
    name: '',
    tag: '',
    short_description: '',
    description: '',
    price: '',
    duration_days: 1,
    duration_nights: 0,
    image: null,
    is_featured: false,
    is_active: true,
    destination_ids: [],
})

const imagePreview = ref(null)

const selectedDestinations = computed(() => {
    return props.destinations.filter((destination) =>
        form.destination_ids.includes(destination.id),
    )
})

const handleImage = (event) => {
    const file = event.target.files?.[0]

    if (!file) {
        return
    }

    form.image = file

    if (imagePreview.value) {
        URL.revokeObjectURL(imagePreview.value)
    }

    imagePreview.value = URL.createObjectURL(file)
}

const removeImage = () => {
    form.image = null

    if (imagePreview.value) {
        URL.revokeObjectURL(imagePreview.value)
        imagePreview.value = null
    }
}

const toggleDestination = (id) => {
    const index = form.destination_ids.indexOf(id)

    if (index === -1) {
        form.destination_ids.push(id)
    } else {
        form.destination_ids.splice(index, 1)
    }
}

const isSelected = (id) => {
    return form.destination_ids.includes(id)
}

const formatPricePreview = computed(() => {
    if (!form.price) {
        return 'Rp 0'
    }

    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(Number(form.price))
})

const submit = () => {
    form.post('/admin/tour-packages', {
        forceFormData: true,
        preserveScroll: true,
    })
}
</script>

<template>
    <Head title="Tambah Tour Package" />

    <div class="min-h-screen bg-[#f7f9fb] text-slate-800">

        <!-- HEADER -->
        <header class="border-b border-slate-200 bg-white">
            <div
                class="mx-auto flex max-w-[1500px] items-center gap-4 px-6 py-5 lg:px-10"
            >

                <Link
                    href="/admin/tour-packages"
                    class="flex h-11 w-11 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition hover:border-[#b87a28] hover:bg-[#fffaf1] hover:text-[#b87a28]"
                >
                    <ArrowLeft :size="19" />
                </Link>

                <div>
                    <div class="flex items-center gap-2">
                        <Package
                            :size="21"
                            class="text-[#b87a28]"
                        />

                        <h1
                            class="text-2xl font-black tracking-tight text-[#172331]"
                        >
                            Tambah Tour Package
                        </h1>
                    </div>

                    <p class="mt-1 text-sm text-slate-500">
                        Tambahkan paket perjalanan baru ke sistem.
                    </p>
                </div>

            </div>
        </header>


        <!-- CONTENT -->
        <main
            class="mx-auto max-w-[1200px] px-6 py-8 lg:px-10"
        >

            <form
                @submit.prevent="submit"
                class="grid gap-6 lg:grid-cols-[1fr_360px]"
            >

                <!-- ================================================= -->
                <!-- LEFT -->
                <!-- ================================================= -->

                <div class="space-y-6">

                    <!-- INFORMASI DASAR -->
                    <section class="form-card">

                        <div class="form-card-header">
                            <div>
                                <h2 class="form-title">
                                    Informasi Paket
                                </h2>

                                <p class="form-description">
                                    Informasi utama mengenai paket perjalanan.
                                </p>
                            </div>
                        </div>


                        <div class="grid gap-5 md:grid-cols-2">

                            <!-- NAME -->
                            <div class="md:col-span-2">

                                <label class="form-label">
                                    Nama Paket
                                    <span class="required">*</span>
                                </label>

                                <input
                                    v-model="form.name"
                                    type="text"
                                    class="form-input"
                                    placeholder="Contoh: Lombok Highlight Tour"
                                />

                                <p
                                    v-if="form.errors.name"
                                    class="form-error"
                                >
                                    {{ form.errors.name }}
                                </p>

                            </div>


                            <!-- TAG -->
                            <div>

                                <label class="form-label">
                                    Tag
                                </label>

                                <input
                                    v-model="form.tag"
                                    type="text"
                                    class="form-input"
                                    placeholder="PRIVATE TOUR"
                                />

                                <p class="form-hint">
                                    Contoh: PRIVATE TOUR, BEACH TOUR.
                                </p>

                                <p
                                    v-if="form.errors.tag"
                                    class="form-error"
                                >
                                    {{ form.errors.tag }}
                                </p>

                            </div>


                            <!-- PRICE -->
                            <div>

                                <label class="form-label">
                                    Harga
                                    <span class="required">*</span>
                                </label>

                                <div class="relative">

                                    <span
                                        class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-400"
                                    >
                                        Rp
                                    </span>

                                    <input
                                        v-model="form.price"
                                        type="number"
                                        min="0"
                                        class="form-input pl-11"
                                        placeholder="750000"
                                    />

                                </div>

                                <p
                                    v-if="form.errors.price"
                                    class="form-error"
                                >
                                    {{ form.errors.price }}
                                </p>

                            </div>


                            <!-- SHORT DESCRIPTION -->
                            <div class="md:col-span-2">

                                <label class="form-label">
                                    Deskripsi Singkat
                                    <span class="required">*</span>
                                </label>

                                <textarea
                                    v-model="form.short_description"
                                    rows="3"
                                    maxlength="500"
                                    class="form-textarea"
                                    placeholder="Deskripsi singkat yang akan ditampilkan pada kartu paket."
                                />

                                <p class="form-hint">
                                    Maksimal 500 karakter.
                                </p>

                                <p
                                    v-if="form.errors.short_description"
                                    class="form-error"
                                >
                                    {{ form.errors.short_description }}
                                </p>

                            </div>


                            <!-- DESCRIPTION -->
                            <div class="md:col-span-2">

                                <label class="form-label">
                                    Deskripsi Lengkap
                                    <span class="required">*</span>
                                </label>

                                <textarea
                                    v-model="form.description"
                                    rows="7"
                                    class="form-textarea"
                                    placeholder="Jelaskan paket perjalanan secara lengkap..."
                                />

                                <p
                                    v-if="form.errors.description"
                                    class="form-error"
                                >
                                    {{ form.errors.description }}
                                </p>

                            </div>

                        </div>

                    </section>


                    <!-- DURASI -->
                    <section class="form-card">

                        <div class="form-card-header">
                            <div>
                                <h2 class="form-title">
                                    Durasi Perjalanan
                                </h2>

                                <p class="form-description">
                                    Tentukan lama perjalanan paket.
                                </p>
                            </div>
                        </div>


                        <div class="grid gap-5 sm:grid-cols-2">

                            <div>

                                <label class="form-label">
                                    Jumlah Hari
                                    <span class="required">*</span>
                                </label>

                                <input
                                    v-model.number="form.duration_days"
                                    type="number"
                                    min="1"
                                    class="form-input"
                                />

                                <p
                                    v-if="form.errors.duration_days"
                                    class="form-error"
                                >
                                    {{ form.errors.duration_days }}
                                </p>

                            </div>


                            <div>

                                <label class="form-label">
                                    Jumlah Malam
                                    <span class="required">*</span>
                                </label>

                                <input
                                    v-model.number="form.duration_nights"
                                    type="number"
                                    min="0"
                                    class="form-input"
                                />

                                <p
                                    v-if="form.errors.duration_nights"
                                    class="form-error"
                                >
                                    {{ form.errors.duration_nights }}
                                </p>

                            </div>

                        </div>


                        <div
                            class="mt-5 rounded-xl bg-[#fffaf1] p-4"
                        >

                            <p class="text-xs font-semibold text-slate-500">
                                Preview durasi
                            </p>

                            <p
                                class="mt-1 text-lg font-black text-[#b87a28]"
                            >
                                {{ form.duration_days }} Hari
                                <span
                                    v-if="form.duration_nights > 0"
                                >
                                    / {{ form.duration_nights }} Malam
                                </span>
                            </p>

                        </div>

                    </section>


                    <!-- DESTINATIONS -->
                    <section class="form-card">

                        <div class="form-card-header">

                            <div>
                                <h2 class="form-title">
                                    Destinasi
                                </h2>

                                <p class="form-description">
                                    Pilih destinasi yang termasuk dalam paket.
                                </p>
                            </div>

                            <span class="selected-count">
                                {{ form.destination_ids.length }}
                                dipilih
                            </span>

                        </div>


                        <div
                            v-if="destinations.length"
                            class="grid gap-3 sm:grid-cols-2"
                        >

                            <button
                                v-for="destination in destinations"
                                :key="destination.id"
                                type="button"
                                class="destination-option"
                                :class="{
                                    'destination-selected':
                                        isSelected(destination.id),
                                }"
                                @click="toggleDestination(destination.id)"
                            >

                                <div
                                    class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl"
                                    :class="
                                        isSelected(destination.id)
                                            ? 'bg-[#b87a28] text-white'
                                            : 'bg-slate-100 text-slate-400'
                                    "
                                >
                                    <Check
                                        v-if="isSelected(destination.id)"
                                        :size="18"
                                    />

                                    <MapPin
                                        v-else
                                        :size="18"
                                    />
                                </div>


                                <div class="min-w-0 flex-1 text-left">

                                    <p
                                        class="truncate text-sm font-bold"
                                        :class="
                                            isSelected(destination.id)
                                                ? 'text-[#172331]'
                                                : 'text-slate-700'
                                        "
                                    >
                                        {{ destination.name }}
                                    </p>

                                    <p
                                        class="mt-0.5 truncate text-xs text-slate-400"
                                    >
                                        {{ destination.location }}
                                        <span
                                            v-if="destination.category"
                                        >
                                            · {{ destination.category }}
                                        </span>
                                    </p>

                                </div>

                            </button>

                        </div>


                        <div
                            v-else
                            class="rounded-xl border border-dashed border-slate-300 p-8 text-center"
                        >
                            <MapPin
                                :size="28"
                                class="mx-auto text-slate-300"
                            />

                            <p
                                class="mt-3 text-sm font-bold text-slate-600"
                            >
                                Belum ada destination aktif.
                            </p>

                            <p
                                class="mt-1 text-xs text-slate-400"
                            >
                                Tambahkan destination terlebih dahulu.
                            </p>
                        </div>


                        <p
                            v-if="form.errors.destination_ids"
                            class="form-error mt-3"
                        >
                            {{ form.errors.destination_ids }}
                        </p>

                    </section>

                </div>


                <!-- ================================================= -->
                <!-- RIGHT -->
                <!-- ================================================= -->

                <div class="space-y-6">

                    <!-- IMAGE -->
                    <section class="form-card">

                        <div class="form-card-header">
                            <div>
                                <h2 class="form-title">
                                    Gambar Paket
                                </h2>

                                <p class="form-description">
                                    JPG, PNG atau WebP. Maksimal 5 MB.
                                </p>
                            </div>
                        </div>


                        <div
                            v-if="imagePreview"
                            class="relative overflow-hidden rounded-2xl"
                        >

                            <img
                                :src="imagePreview"
                                alt="Preview"
                                class="aspect-[4/3] w-full object-cover"
                            />

                            <button
                                type="button"
                                class="absolute right-3 top-3 flex h-9 w-9 items-center justify-center rounded-full bg-black/60 text-white backdrop-blur transition hover:bg-red-500"
                                @click="removeImage"
                            >
                                <X :size="17" />
                            </button>

                        </div>


                        <label
                            v-else
                            class="group flex aspect-[4/3] cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 transition hover:border-[#b87a28] hover:bg-[#fffaf1]"
                        >

                            <div
                                class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-slate-400 shadow-sm transition group-hover:text-[#b87a28]"
                            >
                                <Upload :size="24" />
                            </div>

                            <p
                                class="mt-4 text-sm font-bold text-slate-600"
                            >
                                Upload Gambar
                            </p>

                            <p
                                class="mt-1 text-xs text-slate-400"
                            >
                                Klik untuk memilih gambar
                            </p>

                            <input
                                type="file"
                                class="hidden"
                                accept="image/jpeg,image/png,image/webp"
                                @change="handleImage"
                            />

                        </label>


                        <p
                            v-if="form.errors.image"
                            class="form-error mt-3"
                        >
                            {{ form.errors.image }}
                        </p>

                    </section>


                    <!-- STATUS -->
                    <section class="form-card">

                        <div class="form-card-header">
                            <div>
                                <h2 class="form-title">
                                    Pengaturan
                                </h2>

                                <p class="form-description">
                                    Atur status paket.
                                </p>
                            </div>
                        </div>


                        <!-- ACTIVE -->
                        <label
                            class="toggle-row"
                        >

                            <div>
                                <p class="text-sm font-bold text-slate-700">
                                    Paket Aktif
                                </p>

                                <p class="mt-1 text-xs text-slate-400">
                                    Paket dapat ditampilkan dan dipesan.
                                </p>
                            </div>

                            <input
                                v-model="form.is_active"
                                type="checkbox"
                                class="toggle-input"
                            />

                        </label>


                        <!-- FEATURED -->
                        <label
                            class="toggle-row mt-4"
                        >

                            <div>
                                <p class="text-sm font-bold text-slate-700">
                                    Featured
                                </p>

                                <p class="mt-1 text-xs text-slate-400">
                                    Tampilkan sebagai paket unggulan.
                                </p>
                            </div>

                            <input
                                v-model="form.is_featured"
                                type="checkbox"
                                class="toggle-input"
                            />

                        </label>

                    </section>


                    <!-- PRICE PREVIEW -->
                    <section
                        class="overflow-hidden rounded-2xl bg-[#172331] p-6 text-white shadow-lg"
                    >

                        <p
                            class="text-xs font-bold uppercase tracking-wider text-white/50"
                        >
                            Harga Paket
                        </p>

                        <p
                            class="mt-2 text-3xl font-black"
                        >
                            {{ formatPricePreview }}
                        </p>

                        <p
                            class="mt-2 text-xs text-white/50"
                        >
                            Harga yang akan ditampilkan kepada pelanggan.
                        </p>

                    </section>

                </div>


                <!-- ================================================= -->
                <!-- ACTION -->
                <!-- ================================================= -->

                <div
                    class="flex flex-col-reverse gap-3 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:flex-row sm:justify-end lg:col-span-2"
                >

                    <Link
                        href="/admin/tour-packages"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 px-6 py-3 text-sm font-bold text-slate-600 transition hover:bg-slate-50"
                    >
                        <X :size="17" />
                        Batal
                    </Link>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#b87a28] to-[#e5bd73] px-7 py-3 text-sm font-bold text-white shadow-lg shadow-[#b87a28]/20 transition hover:-translate-y-0.5 disabled:cursor-not-allowed disabled:opacity-60"
                    >
                        <Save :size="17" />

                        {{
                            form.processing
                                ? 'Menyimpan...'
                                : 'Simpan Paket'
                        }}
                    </button>

                </div>

            </form>

        </main>

    </div>
</template>

<style scoped>
.form-card {
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    background: white;
    padding: 24px;
    box-shadow: 0 5px 20px rgba(15, 30, 45, 0.04);
}

.form-card-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 22px;
}

.form-title {
    font-size: 16px;
    font-weight: 900;
    color: #172331;
}

.form-description {
    margin-top: 4px;
    font-size: 12px;
    line-height: 1.6;
    color: #94a3b8;
}

.form-label {
    display: block;
    margin-bottom: 8px;
    font-size: 12px;
    font-weight: 800;
    color: #475569;
}

.required {
    color: #ef4444;
}

.form-input {
    height: 48px;
    width: 100%;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    background: white;
    padding: 0 15px;
    outline: none;
    font-size: 14px;
    color: #172331;
    transition: 0.2s ease;
}

.form-input:focus,
.form-textarea:focus {
    border-color: #b87a28;
    box-shadow: 0 0 0 3px rgba(184, 122, 40, 0.1);
}

.form-textarea {
    width: 100%;
    resize: vertical;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    background: white;
    padding: 13px 15px;
    outline: none;
    font-size: 14px;
    line-height: 1.6;
    color: #172331;
    transition: 0.2s ease;
}

.form-hint {
    margin-top: 6px;
    font-size: 11px;
    color: #94a3b8;
}

.form-error {
    margin-top: 6px;
    font-size: 11px;
    font-weight: 600;
    color: #ef4444;
}

.selected-count {
    border-radius: 999px;
    background: #fff6e5;
    padding: 6px 10px;
    font-size: 10px;
    font-weight: 800;
    color: #b87a28;
}

.destination-option {
    display: flex;
    align-items: center;
    gap: 12px;
    width: 100%;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 12px;
    background: white;
    transition: 0.2s ease;
}

.destination-option:hover {
    border-color: #d8b273;
    background: #fffdf8;
}

.destination-selected {
    border-color: #b87a28;
    background: #fffaf1;
}

.toggle-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    cursor: pointer;
    border-radius: 14px;
    background: #f8fafc;
    padding: 14px;
}

.toggle-input {
    position: relative;
    height: 22px;
    width: 40px;
    flex-shrink: 0;
    cursor: pointer;
    appearance: none;
    border-radius: 999px;
    background: #cbd5e1;
    transition: 0.2s ease;
}

.toggle-input::after {
    position: absolute;
    top: 3px;
    left: 3px;
    height: 16px;
    width: 16px;
    border-radius: 999px;
    background: white;
    content: '';
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
    transition: 0.2s ease;
}

.toggle-input:checked {
    background: #b87a28;
}

.toggle-input:checked::after {
    transform: translateX(18px);
}
</style>