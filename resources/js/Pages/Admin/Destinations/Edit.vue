<script setup>

import { ref } from 'vue'

import {
    Head,
    Link,
    useForm,
} from '@inertiajs/vue3'

import {
    ArrowLeft,
    ImagePlus,
    Save,
} from 'lucide-vue-next'


const props = defineProps({

    destination: {
        type: Object,
        required: true,
    },

})


const form = useForm({

    name: props.destination.name ?? '',

    slug: props.destination.slug ?? '',

    short_description:
        props.destination.short_description ?? '',

    description:
        props.destination.description ?? '',

    location:
        props.destination.location ?? '',

    category:
        props.destination.category ?? '',

    image: null,

    is_active:
        Boolean(props.destination.is_active),

})


const preview = ref(
    props.destination.image
        ? `/storage/${props.destination.image}`
        : null
)


const handleImage = (event) => {

    const file = event.target.files[0]

    if (!file) {
        return
    }

    form.image = file

    preview.value = URL.createObjectURL(file)

}


const submit = () => {

    form.transform((data) => ({
        ...data,
        _method: 'PUT',
    })).post(
        `/admin/destinations/${props.destination.id}`,
        {
            forceFormData: true,

            preserveScroll: true,
        }
    )

}

</script>


<template>

    <Head
        :title="`Edit ${destination.name}`"
    />


    <div class="min-h-screen bg-[#f6f8fb]">


        <!-- HEADER -->

        <header class="border-b border-slate-200 bg-white">

            <div
                class="mx-auto max-w-[1100px] px-6 py-6 lg:px-10"
            >

                <Link
                    href="/admin/destinations"
                    class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-[#b87a28]"
                >

                    <ArrowLeft :size="17" />

                    Kembali ke Destinations

                </Link>


                <p
                    class="mt-6 text-xs font-black tracking-[.25em] text-[#b87a28]"
                >
                    MASTER DATA
                </p>

                <h1
                    class="mt-1 text-3xl font-black text-[#172331]"
                >
                    Edit Destination
                </h1>

                <p
                    class="mt-1 text-sm text-slate-500"
                >
                    Perbarui informasi destination.
                </p>

            </div>

        </header>


        <main
            class="mx-auto max-w-[1100px] px-6 py-8 lg:px-10"
        >

            <form
                @submit.prevent="submit"
                class="grid gap-6 lg:grid-cols-[1fr_360px]"
            >


                <!-- FORM -->

                <section
                    class="rounded-[22px] bg-white p-6 shadow-sm"
                >

                    <div class="grid gap-5">


                        <div>

                            <label class="field-label">
                                Nama Destination *
                            </label>

                            <input
                                v-model="form.name"
                                type="text"
                                class="input"
                            />

                            <p
                                v-if="form.errors.name"
                                class="error"
                            >
                                {{ form.errors.name }}
                            </p>

                        </div>


                        <div>

                            <label class="field-label">
                                Slug
                            </label>

                            <input
                                v-model="form.slug"
                                type="text"
                                class="input"
                            />

                            <p
                                v-if="form.errors.slug"
                                class="error"
                            >
                                {{ form.errors.slug }}
                            </p>

                        </div>


                        <div
                            class="grid gap-5 md:grid-cols-2"
                        >

                            <div>

                                <label class="field-label">
                                    Lokasi
                                </label>

                                <input
                                    v-model="form.location"
                                    type="text"
                                    class="input"
                                />

                            </div>


                            <div>

                                <label class="field-label">
                                    Kategori
                                </label>

                                <input
                                    v-model="form.category"
                                    type="text"
                                    class="input"
                                />

                            </div>

                        </div>


                        <div>

                            <label class="field-label">
                                Deskripsi Singkat
                            </label>

                            <textarea
                                v-model="form.short_description"
                                rows="3"
                                class="input resize-none"
                            ></textarea>

                        </div>


                        <div>

                            <label class="field-label">
                                Deskripsi Lengkap
                            </label>

                            <textarea
                                v-model="form.description"
                                rows="7"
                                class="input resize-none"
                            ></textarea>

                        </div>


                        <label
                            class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 p-4"
                        >

                            <input
                                v-model="form.is_active"
                                type="checkbox"
                                class="h-4 w-4 accent-[#b87a28]"
                            />

                            <div>

                                <p class="font-bold">
                                    Destination Aktif
                                </p>

                                <p
                                    class="text-xs text-slate-400"
                                >
                                    Destination dapat digunakan
                                    dalam paket tour.
                                </p>

                            </div>

                        </label>

                    </div>

                </section>


                <!-- IMAGE -->

                <section
                    class="h-fit rounded-[22px] bg-white p-6 shadow-sm"
                >

                    <label class="field-label">
                        Foto Destination
                    </label>


                    <label
                        class="mt-3 flex aspect-[4/3] cursor-pointer items-center justify-center overflow-hidden rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50"
                    >

                        <img
                            v-if="preview"
                            :src="preview"
                            :alt="destination.name"
                            class="h-full w-full object-cover"
                        />

                        <div
                            v-else
                            class="flex flex-col items-center"
                        >

                            <ImagePlus
                                :size="38"
                                class="text-slate-300"
                            />

                            <p
                                class="mt-3 font-bold text-slate-600"
                            >
                                Upload Foto
                            </p>

                        </div>


                        <input
                            type="file"
                            accept="image/jpeg,image/png,image/webp"
                            class="hidden"
                            @change="handleImage"
                        />

                    </label>


                    <p
                        v-if="form.errors.image"
                        class="error"
                    >
                        {{ form.errors.image }}
                    </p>


                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#172331] px-5 py-3.5 text-sm font-bold text-white transition hover:bg-[#b87a28] disabled:opacity-50"
                    >

                        <Save :size="18" />

                        {{
                            form.processing
                                ? 'Menyimpan...'
                                : 'Simpan Perubahan'
                        }}

                    </button>

                </section>

            </form>

        </main>

    </div>

</template>


<style scoped>

.field-label {
    display: block;
    margin-bottom: .55rem;
    font-size: .78rem;
    font-weight: 800;
    color: #334155;
}

.input {
    width: 100%;
    border-radius: .8rem;
    border: 1px solid #e2e8f0;
    padding: .8rem 1rem;
    font-size: .875rem;
    outline: none;
}

.input:focus {
    border-color: #b87a28;
    box-shadow: 0 0 0 3px rgba(184, 122, 40, .1);
}

.error {
    margin-top: .35rem;
    font-size: .75rem;
    font-weight: 600;
    color: #dc2626;
}

</style>