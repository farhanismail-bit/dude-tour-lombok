<script setup>
import { computed, ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import {
    ArrowLeft,
    CheckCircle2,
    Edit,
    Image as ImageIcon,
    MoreVertical,
    Package,
    Plus,
    Search,
    Star,
    Trash2,
    XCircle,
} from 'lucide-vue-next'

/*
|--------------------------------------------------------------------------
| PROPS
|--------------------------------------------------------------------------
*/

const props = defineProps({
    packages: {
        type: Object,
        required: true,
        default: () => ({
            data: [],
            current_page: 1,
            from: 0,
            last_page: 1,
            per_page: 10,
            to: 0,
            total: 0,
            links: [],
        }),
    },

    statistics: {
        type: Object,
        default: () => ({
            total: 0,
            active: 0,
            inactive: 0,
            featured: 0,
        }),
    },

    filters: {
        type: Object,
        default: () => ({
            search: '',
            status: 'all',
            featured: 'all',
        }),
    },
})

/*
|--------------------------------------------------------------------------
| STATE
|--------------------------------------------------------------------------
*/

const search = ref(props.filters?.search ?? '')
const status = ref(props.filters?.status ?? 'all')
const featured = ref(props.filters?.featured ?? 'all')

const showDeleteModal = ref(false)
const selectedPackage = ref(null)

const showActionMenu = ref(null)

/*
|--------------------------------------------------------------------------
| IMAGE ERROR STATE
|--------------------------------------------------------------------------
|
| Menyimpan ID package yang gambarnya gagal dimuat.
| Kalau gambar 404/rusak, otomatis tampil placeholder.
|--------------------------------------------------------------------------
*/

const failedImages = ref(new Set())

/*
|--------------------------------------------------------------------------
| FORMAT PRICE
|--------------------------------------------------------------------------
*/

const formatPrice = (price) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(Number(price ?? 0))
}

/*
|--------------------------------------------------------------------------
| FORMAT DURATION
|--------------------------------------------------------------------------
*/

const formatDuration = (days, nights) => {
    const dayValue = Number(days ?? 0)
    const nightValue = Number(nights ?? 0)

    if (nightValue > 0) {
        return `${dayValue} Hari / ${nightValue} Malam`
    }

    return `${dayValue} Hari`
}

/*
|--------------------------------------------------------------------------
| IMAGE URL
|--------------------------------------------------------------------------
|
| Mendukung:
|
| 1. https://...
| 2. http://...
| 3. /storage/...
| 4. storage/...
| 5. public/...
| 6. tour-packages/nama.jpg
| 7. nama.jpg
|--------------------------------------------------------------------------
*/

const imageUrl = (image) => {
    if (!image) {
        return null
    }

    let value = String(image).trim()

    if (!value) {
        return null
    }

    /*
    |----------------------------------------------------------------------
    | External URL
    |----------------------------------------------------------------------
    */

    if (
        value.startsWith('http://') ||
        value.startsWith('https://')
    ) {
        return value
    }

    /*
    |----------------------------------------------------------------------
    | Bersihkan prefix
    |----------------------------------------------------------------------
    */

    value = value.replace(/^\/+/, '')

    value = value.replace(/^public\//, '')
    value = value.replace(/^storage\//, '')

    /*
    |----------------------------------------------------------------------
    | Laravel storage
    |----------------------------------------------------------------------
    */

    return `/storage/${value}`
}

/*
|--------------------------------------------------------------------------
| CHECK IMAGE FAILED
|--------------------------------------------------------------------------
*/

const isImageFailed = (id) => {
    return failedImages.value.has(id)
}

/*
|--------------------------------------------------------------------------
| IMAGE ERROR
|--------------------------------------------------------------------------
*/

const handleImageError = (id) => {
    const newSet = new Set(failedImages.value)

    newSet.add(id)

    failedImages.value = newSet
}

/*
|--------------------------------------------------------------------------
| FILTER
|--------------------------------------------------------------------------
*/

const applyFilters = () => {
    router.get(
        '/admin/tour-packages',
        {
            search: search.value || undefined,

            status:
                status.value !== 'all'
                    ? status.value
                    : undefined,

            featured:
                featured.value !== 'all'
                    ? featured.value
                    : undefined,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    )
}

/*
|--------------------------------------------------------------------------
| CLEAR FILTER
|--------------------------------------------------------------------------
*/

const clearFilters = () => {
    search.value = ''
    status.value = 'all'
    featured.value = 'all'

    router.get(
        '/admin/tour-packages',
        {},
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    )
}

/*
|--------------------------------------------------------------------------
| TOGGLE ACTIVE
|--------------------------------------------------------------------------
*/

const toggleActive = (item) => {
    router.patch(
        `/admin/tour-packages/${item.id}/toggle-active`,
        {},
        {
            preserveScroll: true,
        },
    )
}

/*
|--------------------------------------------------------------------------
| TOGGLE FEATURED
|--------------------------------------------------------------------------
*/

const toggleFeatured = (item) => {
    router.patch(
        `/admin/tour-packages/${item.id}/toggle-featured`,
        {},
        {
            preserveScroll: true,
        },
    )
}

/*
|--------------------------------------------------------------------------
| DELETE MODAL
|--------------------------------------------------------------------------
*/

const openDeleteModal = (item) => {
    selectedPackage.value = item
    showDeleteModal.value = true
    showActionMenu.value = null
}

const closeDeleteModal = () => {
    showDeleteModal.value = false
    selectedPackage.value = null
}

/*
|--------------------------------------------------------------------------
| DELETE PACKAGE
|--------------------------------------------------------------------------
*/

const deletePackage = () => {
    if (!selectedPackage.value) {
        return
    }

    router.delete(
        `/admin/tour-packages/${selectedPackage.value.id}`,
        {
            preserveScroll: true,

            onSuccess: () => {
                closeDeleteModal()
            },
        },
    )
}

/*
|--------------------------------------------------------------------------
| ACTION MENU
|--------------------------------------------------------------------------
*/

const toggleMenu = (id) => {
    showActionMenu.value =
        showActionMenu.value === id
            ? null
            : id
}

const closeMenu = () => {
    showActionMenu.value = null
}

/*
|--------------------------------------------------------------------------
| HAS FILTER
|--------------------------------------------------------------------------
*/

const hasFilters = computed(() => {
    return (
        search.value !== '' ||
        status.value !== 'all' ||
        featured.value !== 'all'
    )
})
</script>

<template>
    <Head title="Tour Packages" />

    <div
        class="min-h-screen bg-[#f7f9fb] text-slate-800"
        @click="closeMenu"
    >

        <!-- ========================================================= -->
        <!-- HEADER -->
        <!-- ========================================================= -->

        <header class="border-b border-slate-200 bg-white">
            <div
                class="mx-auto flex max-w-[1500px] items-center justify-between gap-6 px-6 py-5 lg:px-10"
            >

                <!-- LEFT -->
                <div class="flex items-center gap-4">

                    <Link
                        href="/admin/dashboard"
                        class="flex h-11 w-11 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 transition hover:border-[#b87a28] hover:bg-[#fffaf1] hover:text-[#b87a28]"
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
                                Tour Packages
                            </h1>
                        </div>

                        <p class="mt-1 text-sm text-slate-500">
                            Kelola paket perjalanan Dude Tour & Trans Lombok.
                        </p>
                    </div>
                </div>

                <!-- RIGHT -->
                <Link
                    href="/admin/tour-packages/create"
                    class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-[#b87a28] to-[#e5bd73] px-5 py-3 text-sm font-bold text-white shadow-lg shadow-[#b87a28]/20 transition hover:-translate-y-0.5"
                >
                    <Plus :size="19" />
                    Tambah Paket
                </Link>

            </div>
        </header>


        <!-- ========================================================= -->
        <!-- CONTENT -->
        <!-- ========================================================= -->

        <main
            class="mx-auto max-w-[1500px] px-6 py-8 lg:px-10"
        >

            <!-- ===================================================== -->
            <!-- STATISTICS -->
            <!-- ===================================================== -->

            <div
                class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4"
            >

                <!-- TOTAL -->
                <div class="stat-card">
                    <div class="stat-icon bg-[#fff6e5] text-[#b87a28]">
                        <Package :size="22" />
                    </div>

                    <div>
                        <p class="stat-label">
                            Total Paket
                        </p>

                        <p class="stat-value">
                            {{ statistics.total }}
                        </p>
                    </div>
                </div>


                <!-- ACTIVE -->
                <div class="stat-card">
                    <div class="stat-icon bg-emerald-50 text-emerald-600">
                        <CheckCircle2 :size="22" />
                    </div>

                    <div>
                        <p class="stat-label">
                            Paket Aktif
                        </p>

                        <p class="stat-value">
                            {{ statistics.active }}
                        </p>
                    </div>
                </div>


                <!-- INACTIVE -->
                <div class="stat-card">
                    <div class="stat-icon bg-red-50 text-red-500">
                        <XCircle :size="22" />
                    </div>

                    <div>
                        <p class="stat-label">
                            Paket Nonaktif
                        </p>

                        <p class="stat-value">
                            {{ statistics.inactive }}
                        </p>
                    </div>
                </div>


                <!-- FEATURED -->
                <div class="stat-card">
                    <div class="stat-icon bg-amber-50 text-amber-500">
                        <Star
                            :size="22"
                            fill="currentColor"
                        />
                    </div>

                    <div>
                        <p class="stat-label">
                            Featured
                        </p>

                        <p class="stat-value">
                            {{ statistics.featured }}
                        </p>
                    </div>
                </div>

            </div>


            <!-- ===================================================== -->
            <!-- FILTER -->
            <!-- ===================================================== -->

            <div
                class="mt-8 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"
            >

                <div
                    class="flex flex-col gap-4 xl:flex-row xl:items-end"
                >

                    <!-- SEARCH -->
                    <div class="flex-1">

                        <label class="filter-label">
                            Cari Paket
                        </label>

                        <div class="relative">

                            <Search
                                :size="18"
                                class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"
                            />

                            <input
                                v-model="search"
                                type="text"
                                placeholder="Cari nama paket, tag, destinasi..."
                                class="filter-input pl-11"
                                @keyup.enter="applyFilters"
                            />

                        </div>
                    </div>


                    <!-- STATUS -->
                    <div class="w-full xl:w-52">

                        <label class="filter-label">
                            Status
                        </label>

                        <select
                            v-model="status"
                            class="filter-input"
                            @change="applyFilters"
                        >
                            <option value="all">
                                Semua Status
                            </option>

                            <option value="active">
                                Aktif
                            </option>

                            <option value="inactive">
                                Nonaktif
                            </option>
                        </select>

                    </div>


                    <!-- FEATURED -->
                    <div class="w-full xl:w-52">

                        <label class="filter-label">
                            Featured
                        </label>

                        <select
                            v-model="featured"
                            class="filter-input"
                            @change="applyFilters"
                        >
                            <option value="all">
                                Semua
                            </option>

                            <option value="featured">
                                Featured
                            </option>

                            <option value="not_featured">
                                Tidak Featured
                            </option>
                        </select>

                    </div>


                    <!-- SEARCH BUTTON -->
                    <button
                        type="button"
                        class="inline-flex h-[48px] items-center justify-center gap-2 rounded-xl bg-[#172331] px-6 text-sm font-bold text-white transition hover:bg-[#b87a28]"
                        @click="applyFilters"
                    >
                        <Search :size="17" />
                        Cari
                    </button>


                    <!-- RESET -->
                    <button
                        v-if="hasFilters"
                        type="button"
                        class="inline-flex h-[48px] items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 text-sm font-bold text-slate-600 transition hover:border-red-200 hover:bg-red-50 hover:text-red-600"
                        @click="clearFilters"
                    >
                        <XCircle :size="17" />
                        Reset
                    </button>

                </div>
            </div>


            <!-- ===================================================== -->
            <!-- TABLE -->
            <!-- ===================================================== -->

            <div
                class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
            >

                <!-- TABLE HEADER -->
                <div
                    class="flex flex-col justify-between gap-3 border-b border-slate-200 px-6 py-5 sm:flex-row sm:items-center"
                >

                    <div>

                        <h2
                            class="text-lg font-black text-[#172331]"
                        >
                            Daftar Tour Package
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Menampilkan
                            {{ packages.from ?? 0 }}
                            -
                            {{ packages.to ?? 0 }}
                            dari
                            {{ packages.total ?? 0 }}
                            paket.
                        </p>

                    </div>

                </div>


                <!-- ================================================= -->
                <!-- DESKTOP TABLE -->
                <!-- ================================================= -->

                <div class="hidden overflow-x-auto lg:block">

                    <table class="w-full min-w-[1100px]">

                        <thead>
                            <tr
                                class="border-b border-slate-200 bg-slate-50"
                            >

                                <th class="table-head w-[110px]">
                                    Gambar
                                </th>

                                <th class="table-head">
                                    Paket Tour
                                </th>

                                <th class="table-head">
                                    Destinasi
                                </th>

                                <th class="table-head">
                                    Harga
                                </th>

                                <th class="table-head">
                                    Durasi
                                </th>

                                <th class="table-head">
                                    Status
                                </th>

                                <th class="table-head">
                                    Featured
                                </th>

                                <th class="table-head text-right">
                                    Aksi
                                </th>

                            </tr>
                        </thead>


                        <tbody>

                            <!-- EMPTY -->
                            <tr
                                v-if="!packages.data || packages.data.length === 0"
                            >

                                <td
                                    colspan="8"
                                    class="px-6 py-16 text-center"
                                >

                                    <div
                                        class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-slate-400"
                                    >
                                        <Package :size="28" />
                                    </div>

                                    <h3
                                        class="mt-4 text-base font-bold text-slate-700"
                                    >
                                        Belum ada tour package
                                    </h3>

                                    <p
                                        class="mt-1 text-sm text-slate-500"
                                    >
                                        Silakan tambahkan paket tour baru.
                                    </p>

                                </td>

                            </tr>


                            <!-- DATA -->
                            <tr
                                v-for="item in packages.data"
                                :key="item.id"
                                class="border-b border-slate-100 transition hover:bg-slate-50/70"
                            >

                                <!-- IMAGE -->
                                <td class="table-cell">

                                    <div
                                        class="relative flex h-16 w-20 items-center justify-center overflow-hidden rounded-xl bg-slate-100"
                                    >

                                        <!-- IMAGE -->
                                        <img
                                            v-if="
                                                imageUrl(item.image) &&
                                                !isImageFailed(item.id)
                                            "
                                            :src="imageUrl(item.image)"
                                            :alt="item.name"
                                            class="h-full w-full object-cover"
                                            loading="lazy"
                                            @error="handleImageError(item.id)"
                                        />

                                        <!-- PLACEHOLDER -->
                                        <div
                                            v-else
                                            class="flex h-full w-full items-center justify-center bg-slate-100 text-slate-300"
                                        >
                                            <ImageIcon :size="25" />
                                        </div>

                                    </div>

                                </td>


                                <!-- PACKAGE -->
                                <td class="table-cell">

                                    <div class="max-w-[280px]">

                                        <div
                                            class="flex flex-wrap items-center gap-2"
                                        >

                                            <span
                                                class="font-bold text-[#172331]"
                                            >
                                                {{ item.name }}
                                            </span>

                                            <span
                                                v-if="item.tag"
                                                class="rounded-full bg-[#f8edd8] px-2.5 py-1 text-[9px] font-black tracking-wider text-[#a66a18]"
                                            >
                                                {{ item.tag }}
                                            </span>

                                        </div>

                                        <p
                                            class="mt-1 line-clamp-2 text-xs leading-5 text-slate-500"
                                        >
                                            {{ item.short_description }}
                                        </p>

                                    </div>

                                </td>


                                <!-- DESTINATIONS -->
                                <td class="table-cell">

                                    <div
                                        v-if="item.destinations?.length"
                                        class="flex max-w-[230px] flex-wrap gap-1.5"
                                    >

                                        <span
                                            v-for="destination in item.destinations.slice(0, 3)"
                                            :key="destination.id"
                                            class="rounded-lg bg-slate-100 px-2.5 py-1.5 text-[11px] font-semibold text-slate-600"
                                        >
                                            {{ destination.name }}
                                        </span>

                                        <span
                                            v-if="item.destinations.length > 3"
                                            class="rounded-lg bg-[#fff6e5] px-2.5 py-1.5 text-[11px] font-bold text-[#b87a28]"
                                        >
                                            +{{ item.destinations.length - 3 }}
                                        </span>

                                    </div>

                                    <span
                                        v-else
                                        class="text-xs text-slate-400"
                                    >
                                        Belum ada destinasi
                                    </span>

                                </td>


                                <!-- PRICE -->
                                <td class="table-cell">

                                    <span
                                        class="whitespace-nowrap font-black text-[#b87a28]"
                                    >
                                        {{ formatPrice(item.price) }}
                                    </span>

                                </td>


                                <!-- DURATION -->
                                <td class="table-cell">

                                    <span
                                        class="whitespace-nowrap text-sm font-semibold text-slate-600"
                                    >
                                        {{
                                            formatDuration(
                                                item.duration_days,
                                                item.duration_nights,
                                            )
                                        }}
                                    </span>

                                </td>


                                <!-- STATUS -->
                                <td class="table-cell">

                                    <button
                                        type="button"
                                        class="status-badge"
                                        :class="
                                            item.is_active
                                                ? 'status-active'
                                                : 'status-inactive'
                                        "
                                        @click.stop="toggleActive(item)"
                                    >

                                        <CheckCircle2
                                            v-if="item.is_active"
                                            :size="14"
                                        />

                                        <XCircle
                                            v-else
                                            :size="14"
                                        />

                                        {{
                                            item.is_active
                                                ? 'Aktif'
                                                : 'Nonaktif'
                                        }}

                                    </button>

                                </td>


                                <!-- FEATURED -->
                                <td class="table-cell">

                                    <button
                                        type="button"
                                        class="inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-bold transition"
                                        :class="
                                            item.is_featured
                                                ? 'bg-amber-50 text-amber-600 hover:bg-amber-100'
                                                : 'bg-slate-100 text-slate-500 hover:bg-slate-200'
                                        "
                                        @click.stop="toggleFeatured(item)"
                                    >

                                        <Star
                                            :size="14"
                                            :fill="
                                                item.is_featured
                                                    ? 'currentColor'
                                                    : 'none'
                                            "
                                        />

                                        {{
                                            item.is_featured
                                                ? 'Featured'
                                                : 'Tidak'
                                        }}

                                    </button>

                                </td>


                                <!-- ACTION -->
                                <td
                                    class="table-cell text-right"
                                    @click.stop
                                >

                                    <div
                                        class="relative inline-block"
                                    >

                                        <button
                                            type="button"
                                            class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-500 transition hover:border-[#b87a28] hover:bg-[#fffaf1] hover:text-[#b87a28]"
                                            @click="toggleMenu(item.id)"
                                        >
                                            <MoreVertical :size="18" />
                                        </button>


                                        <!-- ACTION MENU -->
                                        <div
                                            v-if="showActionMenu === item.id"
                                            class="absolute right-0 top-12 z-50 w-44 overflow-hidden rounded-xl border border-slate-200 bg-white py-1 text-left shadow-xl"
                                        >

                                            <Link
                                                :href="`/admin/tour-packages/${item.id}/edit`"
                                                class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 hover:text-[#b87a28]"
                                                @click="closeMenu"
                                            >
                                                <Edit :size="16" />
                                                Edit Paket
                                            </Link>


                                            <button
                                                type="button"
                                                class="flex w-full items-center gap-3 px-4 py-3 text-sm font-semibold text-red-500 transition hover:bg-red-50"
                                                @click="openDeleteModal(item)"
                                            >
                                                <Trash2 :size="16" />
                                                Hapus Paket
                                            </button>

                                        </div>

                                    </div>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>


                <!-- ================================================= -->
                <!-- MOBILE -->
                <!-- ================================================= -->

                <div
                    class="divide-y divide-slate-100 lg:hidden"
                >

                    <!-- EMPTY -->
                    <div
                        v-if="!packages.data || packages.data.length === 0"
                        class="px-6 py-14 text-center"
                    >

                        <div
                            class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-slate-400"
                        >
                            <Package :size="28" />
                        </div>

                        <h3
                            class="mt-4 font-bold text-slate-700"
                        >
                            Belum ada tour package
                        </h3>

                    </div>


                    <!-- DATA -->
                    <div
                        v-for="item in packages.data"
                        :key="item.id"
                        class="p-5"
                    >

                        <div class="flex gap-4">

                            <!-- IMAGE -->
                            <div
                                class="relative flex h-24 w-28 flex-shrink-0 items-center justify-center overflow-hidden rounded-xl bg-slate-100"
                            >

                                <img
                                    v-if="
                                        imageUrl(item.image) &&
                                        !isImageFailed(item.id)
                                    "
                                    :src="imageUrl(item.image)"
                                    :alt="item.name"
                                    class="h-full w-full object-cover"
                                    loading="lazy"
                                    @error="handleImageError(item.id)"
                                />

                                <div
                                    v-else
                                    class="flex h-full w-full items-center justify-center bg-slate-100 text-slate-300"
                                >
                                    <ImageIcon :size="28" />
                                </div>

                            </div>


                            <div class="min-w-0 flex-1">

                                <div
                                    class="flex items-start justify-between gap-3"
                                >

                                    <div>

                                        <span
                                            class="text-base font-black text-[#172331]"
                                        >
                                            {{ item.name }}
                                        </span>

                                        <p
                                            v-if="item.tag"
                                            class="mt-1 text-[10px] font-black tracking-wider text-[#b87a28]"
                                        >
                                            {{ item.tag }}
                                        </p>

                                    </div>


                                    <button
                                        type="button"
                                        class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg border border-slate-200 text-slate-500"
                                        @click.stop="toggleMenu(item.id)"
                                    >
                                        <MoreVertical :size="17" />
                                    </button>

                                </div>

                                <p
                                    class="mt-2 line-clamp-2 text-xs leading-5 text-slate-500"
                                >
                                    {{ item.short_description }}
                                </p>

                            </div>

                        </div>


                        <!-- PRICE + DURATION -->
                        <div
                            class="mt-4 grid grid-cols-2 gap-3"
                        >

                            <div
                                class="rounded-xl bg-slate-50 p-3"
                            >

                                <p
                                    class="text-[10px] font-semibold uppercase tracking-wider text-slate-400"
                                >
                                    Harga
                                </p>

                                <p
                                    class="mt-1 font-black text-[#b87a28]"
                                >
                                    {{ formatPrice(item.price) }}
                                </p>

                            </div>


                            <div
                                class="rounded-xl bg-slate-50 p-3"
                            >

                                <p
                                    class="text-[10px] font-semibold uppercase tracking-wider text-slate-400"
                                >
                                    Durasi
                                </p>

                                <p
                                    class="mt-1 text-sm font-bold text-slate-700"
                                >
                                    {{
                                        formatDuration(
                                            item.duration_days,
                                            item.duration_nights,
                                        )
                                    }}
                                </p>

                            </div>

                        </div>


                        <!-- STATUS -->
                        <div
                            class="mt-4 flex flex-wrap gap-2"
                        >

                            <button
                                type="button"
                                class="status-badge"
                                :class="
                                    item.is_active
                                        ? 'status-active'
                                        : 'status-inactive'
                                "
                                @click="toggleActive(item)"
                            >

                                <CheckCircle2
                                    v-if="item.is_active"
                                    :size="14"
                                />

                                <XCircle
                                    v-else
                                    :size="14"
                                />

                                {{
                                    item.is_active
                                        ? 'Aktif'
                                        : 'Nonaktif'
                                }}

                            </button>


                            <button
                                type="button"
                                class="inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-bold"
                                :class="
                                    item.is_featured
                                        ? 'bg-amber-50 text-amber-600'
                                        : 'bg-slate-100 text-slate-500'
                                "
                                @click="toggleFeatured(item)"
                            >

                                <Star
                                    :size="14"
                                    :fill="
                                        item.is_featured
                                            ? 'currentColor'
                                            : 'none'
                                    "
                                />

                                {{
                                    item.is_featured
                                        ? 'Featured'
                                        : 'Tidak Featured'
                                }}

                            </button>

                        </div>


                        <!-- MOBILE ACTION MENU -->
                        <div
                            v-if="showActionMenu === item.id"
                            class="mt-3 grid grid-cols-2 gap-2"
                        >

                            <Link
                                :href="`/admin/tour-packages/${item.id}/edit`"
                                class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 px-4 py-3 text-sm font-bold text-slate-600 transition hover:border-[#b87a28] hover:text-[#b87a28]"
                            >
                                <Edit :size="16" />
                                Edit
                            </Link>


                            <button
                                type="button"
                                class="inline-flex items-center justify-center gap-2 rounded-xl border border-red-100 bg-red-50 px-4 py-3 text-sm font-bold text-red-500 transition hover:bg-red-100"
                                @click="openDeleteModal(item)"
                            >
                                <Trash2 :size="16" />
                                Hapus
                            </button>

                        </div>

                    </div>

                </div>


                <!-- ================================================= -->
                <!-- PAGINATION -->
                <!-- ================================================= -->

                <div
                    v-if="packages.last_page > 1"
                    class="flex flex-col gap-4 border-t border-slate-200 px-6 py-5 sm:flex-row sm:items-center sm:justify-between"
                >

                    <p class="text-sm text-slate-500">
                        Halaman
                        <span class="font-bold text-slate-700">
                            {{ packages.current_page }}
                        </span>
                        dari
                        <span class="font-bold text-slate-700">
                            {{ packages.last_page }}
                        </span>
                    </p>


                    <div class="flex flex-wrap gap-2">

                        <template
                            v-for="(link, index) in packages.links"
                            :key="`${index}-${link.label}`"
                        >

                            <button
                                v-if="link.url"
                                type="button"
                                class="min-w-10 rounded-lg border px-3 py-2 text-sm font-semibold transition"
                                :class="
                                    link.active
                                        ? 'border-[#b87a28] bg-[#b87a28] text-white'
                                        : 'border-slate-200 bg-white text-slate-600 hover:border-[#b87a28] hover:text-[#b87a28]'
                                "
                                @click="
                                    router.get(
                                        link.url,
                                        {},
                                        {
                                            preserveState: true,
                                            preserveScroll: true,
                                        },
                                    )
                                "
                                v-html="link.label"
                            />

                            <span
                                v-else
                                class="min-w-10 rounded-lg border border-slate-100 px-3 py-2 text-center text-sm text-slate-300"
                                v-html="link.label"
                            />

                        </template>

                    </div>

                </div>

            </div>

        </main>


        <!-- ========================================================= -->
        <!-- DELETE MODAL -->
        <!-- ========================================================= -->

        <Teleport to="body">

            <div
                v-if="showDeleteModal"
                class="fixed inset-0 z-[200] flex items-center justify-center bg-[#172331]/60 px-5 backdrop-blur-sm"
                @click.self="closeDeleteModal"
            >

                <div
                    class="w-full max-w-md rounded-2xl bg-white p-7 shadow-2xl"
                >

                    <div
                        class="flex h-14 w-14 items-center justify-center rounded-full bg-red-50 text-red-500"
                    >
                        <Trash2 :size="25" />
                    </div>


                    <h2
                        class="mt-5 text-xl font-black text-[#172331]"
                    >
                        Hapus Tour Package?
                    </h2>


                    <p
                        class="mt-3 text-sm leading-6 text-slate-500"
                    >
                        Apakah kamu yakin ingin menghapus paket

                        <span
                            class="font-bold text-slate-700"
                        >
                            {{ selectedPackage?.name }}
                        </span>

                        ?

                        Data paket dan relasinya dengan destinasi akan
                        dihapus.
                    </p>


                    <div
                        class="mt-7 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end"
                    >

                        <button
                            type="button"
                            class="rounded-xl border border-slate-200 px-5 py-3 text-sm font-bold text-slate-600 transition hover:bg-slate-50"
                            @click="closeDeleteModal"
                        >
                            Batal
                        </button>


                        <button
                            type="button"
                            class="rounded-xl bg-red-500 px-5 py-3 text-sm font-bold text-white transition hover:bg-red-600"
                            @click="deletePackage"
                        >
                            Ya, Hapus
                        </button>

                    </div>

                </div>

            </div>

        </Teleport>

    </div>
</template>

<style scoped>
.stat-card {
    display: flex;
    align-items: center;
    gap: 16px;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    background: white;
    padding: 20px;
    box-shadow: 0 5px 20px rgba(15, 30, 45, 0.04);
}

.stat-icon {
    display: flex;
    height: 48px;
    width: 48px;
    flex-shrink: 0;
    align-items: center;
    justify-content: center;
    border-radius: 14px;
}

.stat-label {
    font-size: 12px;
    font-weight: 600;
    color: #64748b;
}

.stat-value {
    margin-top: 3px;
    font-size: 26px;
    font-weight: 900;
    line-height: 1;
    color: #172331;
}

.filter-label {
    display: block;
    margin-bottom: 8px;
    font-size: 12px;
    font-weight: 700;
    color: #475569;
}

.filter-input {
    height: 48px;
    width: 100%;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    background: white;
    padding: 0 15px;
    outline: none;
    font-size: 14px;
    font-weight: 500;
    color: #172331;
    transition: 0.2s ease;
}

.filter-input:focus {
    border-color: #b87a28;
    box-shadow: 0 0 0 3px rgba(184, 122, 40, 0.1);
}

.table-head {
    padding: 15px 18px;
    text-align: left;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #64748b;
}

.table-cell {
    padding: 17px 18px;
    vertical-align: middle;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border-radius: 999px;
    padding: 6px 11px;
    font-size: 11px;
    font-weight: 800;
    transition: 0.2s ease;
}

.status-active {
    background: #ecfdf5;
    color: #059669;
}

.status-active:hover {
    background: #d1fae5;
}

.status-inactive {
    background: #fef2f2;
    color: #ef4444;
}

.status-inactive:hover {
    background: #fee2e2;
}
</style>