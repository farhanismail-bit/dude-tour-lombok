<script setup>

import { ref, watch } from 'vue'

import {
    Head,
    Link,
    router,
} from '@inertiajs/vue3'

import {
    ArrowRight,
    CheckCircle2,
    Edit,
    Eye,
    MapPin,
    Plus,
    Search,
    Trash2,
    XCircle,
} from 'lucide-vue-next'


const props = defineProps({

    destinations: {
        type: Object,
        default: () => ({
            data: [],
            links: [],
            total: 0,
        }),
    },

    statistics: {
        type: Object,
        default: () => ({
            total: 0,
            active: 0,
            inactive: 0,
            used: 0,
            unused: 0,
        }),
    },

    filters: {
        type: Object,
        default: () => ({
            search: '',
            status: 'all',
        }),
    },

})


/*
|--------------------------------------------------------------------------
| FILTER
|--------------------------------------------------------------------------
*/

const search = ref(
    props.filters.search ?? ''
)

const status = ref(
    props.filters.status ?? 'all'
)


let searchTimeout = null

watch(
    [search, status],
    () => {

        clearTimeout(searchTimeout)

        searchTimeout = setTimeout(() => {

            router.get(
                '/admin/destinations',
                {
                    search: search.value,
                    status: status.value,
                },
                {
                    preserveState: true,
                    preserveScroll: true,
                    replace: true,
                }
            )

        }, 400)

    }
)


/*
|--------------------------------------------------------------------------
| IMAGE URL
|--------------------------------------------------------------------------
*/

const imageUrl = (image) => {

    if (!image) {
        return null
    }

    if (
        image.startsWith('http://')
        || image.startsWith('https://')
    ) {
        return image
    }

    return `/storage/${image}`
}


/*
|--------------------------------------------------------------------------
| DELETE
|--------------------------------------------------------------------------
*/

const deleteDestination = (destination) => {

    if (
        !confirm(
            `Yakin ingin menghapus destination "${destination.name}"?`
        )
    ) {
        return
    }

    router.delete(
        `/admin/destinations/${destination.id}`,
        {
            preserveScroll: true,
        }
    )
}


/*
|--------------------------------------------------------------------------
| TOGGLE
|--------------------------------------------------------------------------
*/

const toggleActive = (destination) => {

    router.patch(
        `/admin/destinations/${destination.id}/toggle-active`,
        {},
        {
            preserveScroll: true,
        }
    )
}


/*
|--------------------------------------------------------------------------
| NUMBER
|--------------------------------------------------------------------------
*/

const number = (value) => {

    return new Intl.NumberFormat(
        'id-ID'
    ).format(
        Number(value ?? 0)
    )

}


/*
|--------------------------------------------------------------------------
| DATE
|--------------------------------------------------------------------------
*/

const date = (value) => {

    if (!value) {
        return '-'
    }

    return new Intl.DateTimeFormat(
        'id-ID',
        {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
        }
    ).format(
        new Date(value)
    )

}

</script>


<template>

    <Head title="Destinations" />


    <div class="min-h-screen bg-[#f6f8fb]">

        <!-- HEADER -->

        <header class="border-b border-slate-200 bg-white">

            <div
                class="mx-auto flex max-w-[1500px] items-center justify-between px-6 py-6 lg:px-10"
            >

                <div>

                    <p
                        class="text-xs font-black tracking-[.25em] text-[#b87a28]"
                    >
                        MASTER DATA
                    </p>

                    <h1
                        class="mt-1 text-3xl font-black text-[#172331]"
                    >
                        Destinations
                    </h1>

                    <p
                        class="mt-1 text-sm text-slate-500"
                    >
                        Kelola destinasi wisata yang tersedia
                        di Dude Tour & Trans Lombok.
                    </p>

                </div>


                <Link
                    href="/admin/destinations/create"
                    class="inline-flex items-center gap-2 rounded-xl bg-[#172331] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#b87a28]"
                >

                    <Plus :size="18" />

                    Tambah Destination

                </Link>

            </div>

        </header>


        <main
            class="mx-auto max-w-[1500px] px-6 py-8 lg:px-10"
        >


            <!-- STATISTICS -->

            <section
                class="grid gap-5 sm:grid-cols-2 xl:grid-cols-5"
            >

                <div class="stat-card">

                    <div class="icon bg-blue-50 text-blue-600">
                        <MapPin :size="22" />
                    </div>

                    <div>

                        <p class="label">
                            Total Destination
                        </p>

                        <p class="value">
                            {{ statistics.total }}
                        </p>

                    </div>

                </div>


                <div class="stat-card">

                    <div class="icon bg-emerald-50 text-emerald-600">
                        <CheckCircle2 :size="22" />
                    </div>

                    <div>

                        <p class="label">
                            Aktif
                        </p>

                        <p class="value">
                            {{ statistics.active }}
                        </p>

                    </div>

                </div>


                <div class="stat-card">

                    <div class="icon bg-red-50 text-red-600">
                        <XCircle :size="22" />
                    </div>

                    <div>

                        <p class="label">
                            Tidak Aktif
                        </p>

                        <p class="value">
                            {{ statistics.inactive }}
                        </p>

                    </div>

                </div>


                <div class="stat-card">

                    <div class="icon bg-[#fff4df] text-[#b87a28]">
                        <ArrowRight :size="22" />
                    </div>

                    <div>

                        <p class="label">
                            Digunakan Paket
                        </p>

                        <p class="value">
                            {{ statistics.used }}
                        </p>

                    </div>

                </div>


                <div class="stat-card">

                    <div class="icon bg-slate-100 text-slate-600">
                        <MapPin :size="22" />
                    </div>

                    <div>

                        <p class="label">
                            Belum Digunakan
                        </p>

                        <p class="value">
                            {{ statistics.unused }}
                        </p>

                    </div>

                </div>

            </section>


            <!-- FILTER -->

            <section
                class="mt-7 rounded-[22px] bg-white p-5 shadow-sm"
            >

                <div
                    class="grid gap-4 lg:grid-cols-[1fr_220px]"
                >

                    <div class="relative">

                        <Search
                            :size="18"
                            class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"
                        />

                        <input
                            v-model="search"
                            type="text"
                            placeholder="Cari nama, lokasi, kategori..."
                            class="h-12 w-full rounded-xl border border-slate-200 pl-11 pr-4 text-sm outline-none transition focus:border-[#b87a28] focus:ring-2 focus:ring-[#b87a28]/10"
                        />

                    </div>


                    <select
                        v-model="status"
                        class="h-12 rounded-xl border border-slate-200 px-4 text-sm font-semibold outline-none focus:border-[#b87a28]"
                    >

                        <option value="all">
                            Semua Status
                        </option>

                        <option value="active">
                            Aktif
                        </option>

                        <option value="inactive">
                            Tidak Aktif
                        </option>

                    </select>

                </div>

            </section>


            <!-- TABLE -->

            <section
                class="mt-5 overflow-hidden rounded-[22px] bg-white shadow-sm"
            >

                <div class="overflow-x-auto">

                    <table class="w-full min-w-[1100px]">

                        <thead class="bg-slate-50">

                            <tr>

                                <th class="th">
                                    DESTINATION
                                </th>

                                <th class="th">
                                    LOKASI
                                </th>

                                <th class="th">
                                    KATEGORI
                                </th>

                                <th class="th">
                                    PAKET TOUR
                                </th>

                                <th class="th">
                                    STATUS
                                </th>

                                <th class="th">
                                    DIBUAT
                                </th>

                                <th class="th text-right">
                                    AKSI
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <tr
                                v-for="destination in destinations.data"
                                :key="destination.id"
                                class="border-t border-slate-100 transition hover:bg-slate-50"
                            >

                                <!-- DESTINATION -->

                                <td class="td">

                                    <div
                                        class="flex items-center gap-3"
                                    >

                                        <div
                                            class="h-14 w-20 shrink-0 overflow-hidden rounded-xl bg-slate-100"
                                        >

                                            <img
                                                v-if="imageUrl(destination.image)"
                                                :src="imageUrl(destination.image)"
                                                :alt="destination.name"
                                                class="h-full w-full object-cover"
                                            />

                                            <div
                                                v-else
                                                class="flex h-full w-full items-center justify-center text-slate-400"
                                            >

                                                <MapPin :size="22" />

                                            </div>

                                        </div>


                                        <div>

                                            <b class="text-[#172331]">
                                                {{ destination.name }}
                                            </b>

                                            <small>
                                                {{ destination.slug }}
                                            </small>

                                        </div>

                                    </div>

                                </td>


                                <!-- LOKASI -->

                                <td class="td">

                                    <b>
                                        {{ destination.location ?? '-' }}
                                    </b>

                                </td>


                                <!-- KATEGORI -->

                                <td class="td">

                                    <span
                                        class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-bold"
                                    >
                                        {{ destination.category ?? '-' }}
                                    </span>

                                </td>


                                <!-- PAKET -->

                                <td class="td">

                                    <span
                                        class="font-bold text-[#b87a28]"
                                    >
                                        {{ number(destination.tour_packages_count) }}
                                    </span>

                                    paket

                                </td>


                                <!-- STATUS -->

                                <td class="td">

                                    <span
                                        v-if="destination.is_active"
                                        class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700"
                                    >

                                        <CheckCircle2 :size="14" />

                                        Aktif

                                    </span>


                                    <span
                                        v-else
                                        class="inline-flex items-center gap-1.5 rounded-full border border-red-200 bg-red-50 px-3 py-1 text-xs font-bold text-red-700"
                                    >

                                        <XCircle :size="14" />

                                        Tidak Aktif

                                    </span>

                                </td>


                                <!-- DATE -->

                                <td class="td">

                                    {{ date(destination.created_at) }}

                                </td>


                                <!-- ACTION -->

                                <td class="td text-right">

                                    <div
                                        class="flex justify-end gap-2"
                                    >

                                        <Link
                                            :href="`/admin/destinations/${destination.id}`"
                                            class="action-button bg-slate-100 text-slate-700 hover:bg-slate-200"
                                            title="Detail"
                                        >

                                            <Eye :size="16" />

                                        </Link>


                                        <Link
                                            :href="`/admin/destinations/${destination.id}/edit`"
                                            class="action-button bg-blue-50 text-blue-700 hover:bg-blue-100"
                                            title="Edit"
                                        >

                                            <Edit :size="16" />

                                        </Link>


                                        <button
                                            type="button"
                                            @click="toggleActive(destination)"
                                            class="action-button bg-amber-50 text-amber-700 hover:bg-amber-100"
                                            :title="
                                                destination.is_active
                                                    ? 'Nonaktifkan'
                                                    : 'Aktifkan'
                                            "
                                        >

                                            <CheckCircle2
                                                v-if="destination.is_active"
                                                :size="16"
                                            />

                                            <XCircle
                                                v-else
                                                :size="16"
                                            />

                                        </button>


                                        <button
                                            v-if="
                                                Number(
                                                    destination.tour_packages_count
                                                ) === 0
                                            "
                                            type="button"
                                            @click="
                                                deleteDestination(
                                                    destination
                                                )
                                            "
                                            class="action-button bg-red-50 text-red-600 hover:bg-red-100"
                                            title="Hapus"
                                        >

                                            <Trash2 :size="16" />

                                        </button>

                                    </div>

                                </td>

                            </tr>


                            <!-- EMPTY -->

                            <tr
                                v-if="
                                    !destinations.data
                                    || destinations.data.length === 0
                                "
                            >

                                <td
                                    colspan="7"
                                    class="px-6 py-16 text-center"
                                >

                                    <MapPin
                                        :size="40"
                                        class="mx-auto text-slate-300"
                                    />

                                    <p
                                        class="mt-4 font-bold text-slate-600"
                                    >
                                        Destination belum tersedia
                                    </p>

                                    <p
                                        class="mt-1 text-sm text-slate-400"
                                    >
                                        Tambahkan destination pertama.
                                    </p>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>


                <!-- PAGINATION -->

                <div
                    v-if="
                        destinations.links
                        && destinations.links.length > 3
                    "
                    class="flex flex-wrap items-center justify-between gap-4 border-t border-slate-100 px-6 py-4"
                >

                    <p class="text-sm text-slate-400">

                        Menampilkan
                        <b class="text-slate-700">
                            {{ destinations.from ?? 0 }}
                        </b>
                        -
                        <b class="text-slate-700">
                            {{ destinations.to ?? 0 }}
                        </b>
                        dari
                        <b class="text-slate-700">
                            {{ destinations.total ?? 0 }}
                        </b>

                    </p>


                    <div class="flex flex-wrap gap-1">

                        <template
                            v-for="(link, index) in destinations.links"
                            :key="index"
                        >

                            <Link
                                v-if="link.url"
                                :href="link.url"
                                v-html="link.label"
                                class="pagination-button"
                                :class="{
                                    'pagination-active':
                                        link.active
                                }"
                            />

                            <span
                                v-else
                                v-html="link.label"
                                class="pagination-button opacity-40"
                            />

                        </template>

                    </div>

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
    background: white;
    padding: 1.35rem;
    box-shadow: 0 10px 35px rgba(20, 35, 49, .06);
}

.icon {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 3.4rem;
    width: 3.4rem;
    flex-shrink: 0;
    border-radius: 1rem;
}

.label {
    font-size: .72rem;
    font-weight: 800;
    color: #94a3b8;
}

.value {
    margin-top: .2rem;
    font-size: 1.8rem;
    font-weight: 900;
    color: #172331;
}

.th {
    padding: 1rem 1.25rem;
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
    margin-top: .2rem;
    font-size: .7rem;
    color: #94a3b8;
}

.action-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    height: 2.3rem;
    width: 2.3rem;
    border-radius: .65rem;
    transition: .2s;
}

.pagination-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 2.25rem;
    height: 2.25rem;
    border-radius: .6rem;
    padding: 0 .65rem;
    font-size: .75rem;
    font-weight: 800;
    color: #64748b;
    background: #f8fafc;
}

.pagination-active {
    background: #172331;
    color: white;
}

</style>