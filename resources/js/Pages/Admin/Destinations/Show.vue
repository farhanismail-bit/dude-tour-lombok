<script setup>

import {
    Head,
    Link,
} from '@inertiajs/vue3'

import {
    ArrowLeft,
    CheckCircle2,
    Edit,
    MapPin,
    Package,
    XCircle,
} from 'lucide-vue-next'


const props = defineProps({

    destination: {
        type: Object,
        required: true,
    },

})


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


const rupiah = (value) => {

    return new Intl.NumberFormat(
        'id-ID',
        {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
        }
    ).format(
        Number(value ?? 0)
    )

}

</script>


<template>

    <Head
        :title="destination.name"
    />


    <div class="min-h-screen bg-[#f6f8fb]">


        <!-- HEADER -->

        <header class="border-b border-slate-200 bg-white">

            <div
                class="mx-auto max-w-[1200px] px-6 py-6 lg:px-10"
            >

                <div
                    class="flex flex-wrap items-center justify-between gap-4"
                >

                    <div>

                        <Link
                            href="/admin/destinations"
                            class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-[#b87a28]"
                        >

                            <ArrowLeft :size="17" />

                            Kembali

                        </Link>


                        <p
                            class="mt-5 text-xs font-black tracking-[.25em] text-[#b87a28]"
                        >
                            DESTINATION DETAIL
                        </p>

                        <h1
                            class="mt-1 text-3xl font-black text-[#172331]"
                        >
                            {{ destination.name }}
                        </h1>

                    </div>


                    <Link
                        :href="`/admin/destinations/${destination.id}/edit`"
                        class="inline-flex items-center gap-2 rounded-xl bg-[#172331] px-5 py-3 text-sm font-bold text-white hover:bg-[#b87a28]"
                    >

                        <Edit :size="17" />

                        Edit Destination

                    </Link>

                </div>

            </div>

        </header>


        <main
            class="mx-auto max-w-[1200px] px-6 py-8 lg:px-10"
        >


            <!-- HERO -->

            <section
                class="grid overflow-hidden rounded-[24px] bg-white shadow-sm lg:grid-cols-[1fr_1fr]"
            >

                <div
                    class="min-h-[340px] bg-slate-100"
                >

                    <img
                        v-if="imageUrl(destination.image)"
                        :src="imageUrl(destination.image)"
                        :alt="destination.name"
                        class="h-full min-h-[340px] w-full object-cover"
                    />

                    <div
                        v-else
                        class="flex h-full min-h-[340px] items-center justify-center text-slate-300"
                    >

                        <MapPin :size="70" />

                    </div>

                </div>


                <div class="p-7 lg:p-9">

                    <div
                        class="flex flex-wrap gap-2"
                    >

                        <span
                            class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-bold"
                        >
                            {{ destination.category ?? 'Destination' }}
                        </span>


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

                    </div>


                    <div
                        class="mt-6 flex items-center gap-2 text-sm font-bold text-slate-500"
                    >

                        <MapPin
                            :size="17"
                            class="text-[#b87a28]"
                        />

                        {{ destination.location ?? '-' }}

                    </div>


                    <p
                        class="mt-6 text-lg font-bold leading-8 text-[#172331]"
                    >
                        {{
                            destination.short_description
                            ?? '-'
                        }}
                    </p>


                    <p
                        class="mt-4 whitespace-pre-line text-sm leading-7 text-slate-500"
                    >
                        {{
                            destination.description
                            ?? '-'
                        }}
                    </p>

                </div>

            </section>


            <!-- TOUR PACKAGES -->

            <section
                class="mt-7 overflow-hidden rounded-[22px] bg-white shadow-sm"
            >

                <div
                    class="border-b border-slate-100 px-6 py-5"
                >

                    <p class="section-label">
                        RELATION
                    </p>

                    <h2
                        class="mt-1 text-xl font-black"
                    >
                        Paket Tour
                    </h2>

                    <p
                        class="mt-1 text-sm text-slate-400"
                    >
                        Paket tour yang menggunakan destination ini.
                    </p>

                </div>


                <div
                    v-if="
                        destination.tour_packages
                        && destination.tour_packages.length
                    "
                    class="grid gap-4 p-6 md:grid-cols-2 xl:grid-cols-3"
                >

                    <Link
                        v-for="tour in destination.tour_packages"
                        :key="tour.id"
                        href="/admin/tour-packages"
                        class="rounded-2xl border border-slate-100 p-5 transition hover:border-[#e5bd73] hover:shadow-sm"
                    >

                        <div
                            class="flex items-center justify-between"
                        >

                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#fff4df] text-[#b87a28]"
                            >

                                <Package :size="20" />

                            </div>


                            <span
                                class="text-xs font-bold text-[#b87a28]"
                            >
                                {{ rupiah(tour.price) }}
                            </span>

                        </div>


                        <h3
                            class="mt-4 font-black text-[#172331]"
                        >
                            {{ tour.name }}
                        </h3>


                        <div
                            class="mt-3 flex items-center justify-between text-xs text-slate-400"
                        >

                            <span>
                                {{ tour.is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>

                            <span>
                                {{ tour.slug }}
                            </span>

                        </div>

                    </Link>

                </div>


                <div
                    v-else
                    class="px-6 py-14 text-center text-slate-400"
                >

                    Belum digunakan oleh paket tour.

                </div>

            </section>

        </main>

    </div>

</template>


<style scoped>

.section-label {
    font-size: .7rem;
    font-weight: 900;
    letter-spacing: .2em;
    color: #b87a28;
}

</style>