<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import ExpertiseSelector from '../../../Components/ExpertiseSelector.vue';

const props = defineProps({
    user: Object,
    existingData: Object, // Data client jika edit mode
    expertises: Array     // Data list expertise untuk checkbox
});

// Setup Form sesuai Blade register_client
const form = useForm({
    section_hero: props.existingData?.section_hero || '',

    banner_title: props.existingData?.banner_title || '',
    banner_desc: props.existingData?.banner_desc || '',
    author_name: props.existingData?.author_name || '',

    banner_footer: props.existingData?.banner_footer || '',
    banner_footer_desc: props.existingData?.banner_footer_desc || '',
    color: props.existingData?.color || '#7c3aed', // Default violet

    slug_page: props.existingData?.slug_page || '',

    // Checkbox array (Expertise ID)
    expertise_id: props.existingData?.expertise_id || [],

    // File Inputs
    file_author_photo: null,
    file_banner_background: null,
    file_logo: null,
});

// State untuk Preview
const previews = ref({
    author: props.existingData?.author_photo ? `/storage/${props.existingData.author_photo}` : null,
    banner: props.existingData?.banner_background ? `/storage/${props.existingData.banner_background}` : null,
    logo: props.existingData?.logo ? `/storage/${props.existingData.logo}` : null
});

const handleFileChange = (event, key, previewKey) => {
    const file = event.target.files[0];
    if (file) {
        form[key] = file;
        const reader = new FileReader();
        reader.onload = (e) => previews.value[previewKey] = e.target.result;
        reader.readAsDataURL(file);
    }
};

const submit = () => {
    form.post(route('register_client_post'), {
        forceFormData: true,
        preserveScroll: true,
    });
};
</script>

<template>
    <form @submit.prevent="submit" class="p-8 md:p-12">

        <div class="grid lg:grid-cols-3 gap-10">

            <div class="lg:col-span-2 space-y-8">

                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Section Hero / Tagline</label>
                    <textarea v-model="form.section_hero" rows="2"
                        class="w-full rounded-xl border-slate-300 focus:border-violet-500 focus:ring-violet-500"
                        placeholder="Enter your hero tagline..."></textarea>
                    <p class="text-xs text-slate-500 mt-1">Set a section hero / tagline</p>
                </div>

                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 space-y-4">
                    <h3 class="font-bold text-slate-800 border-b border-slate-200 pb-2">Banner Configuration</h3>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Banner Title</label>
                        <input v-model="form.banner_title" type="text" class="w-full rounded-xl border-slate-300"
                            placeholder="Banner Title">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Banner Description</label>
                        <textarea v-model="form.banner_desc" rows="4" class="w-full rounded-xl border-slate-300"
                            placeholder="Banner Description"></textarea>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Author Name</label>
                            <input v-model="form.author_name" type="text" class="w-full rounded-xl border-slate-300"
                                placeholder="e.g. John Doe">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Author Photo</label>
                            <input type="file" @change="e => handleFileChange(e, 'file_author_photo', 'author')"
                                class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100" />
                            <div v-if="previews.author" class="mt-2">
                                <img :src="previews.author"
                                    class="h-10 w-10 rounded-full object-cover border border-slate-300">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Banner Background Image</label>
                        <input type="file" @change="e => handleFileChange(e, 'file_banner_background', 'banner')"
                            class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100" />
                        <div v-if="previews.banner" class="mt-2">
                            <img :src="previews.banner"
                                class="h-24 w-full object-cover rounded-lg border border-slate-300">
                        </div>
                    </div>
                </div>

                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 space-y-4">
                    <h3 class="font-bold text-slate-800 border-b border-slate-200 pb-2">Footer Configuration</h3>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Footer Title</label>
                        <input v-model="form.banner_footer" type="text" class="w-full rounded-xl border-slate-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Footer Description</label>
                        <textarea v-model="form.banner_footer_desc" rows="3"
                            class="w-full rounded-xl border-slate-300"></textarea>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Theme Color</label>
                            <input v-model="form.color" type="color"
                                class="w-full h-10 rounded-lg cursor-pointer border-slate-300 p-1">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Logo Upload</label>
                            <input type="file" @change="e => handleFileChange(e, 'file_logo', 'logo')"
                                class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100" />
                            <div v-if="previews.logo" class="mt-2">
                                <img :src="previews.logo" class="h-10 object-contain">
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Section Page URL (Slug)</label>
                    <div class="flex rounded-xl shadow-sm">
                        <span
                            class="inline-flex items-center px-4 rounded-l-xl border border-r-0 border-slate-300 bg-slate-50 text-slate-500 text-sm">
                            keyperson.id/
                        </span>
                        <input v-model="form.slug_page" type="text"
                            class="flex-1 min-w-0 block w-full rounded-none rounded-r-xl border-slate-300 focus:border-violet-500 focus:ring-violet-500 sm:text-sm">
                    </div>
                </div>

            </div>

            <div class="lg:col-span-1">
                <ExpertiseSelector :expertises="expertises" v-model="form.expertise_id" label="Show Specialists" />
            </div>

        </div>

        <div class="mt-10 pt-6 border-t border-slate-100 flex justify-end gap-4">
            <a :href="route('profile')"
                class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50 transition-all flex items-center justify-center">
                Cancel
            </a>
            <button type="submit" :disabled="form.processing"
                class="px-8 py-3 rounded-xl bg-violet-600 text-white font-bold hover:bg-violet-700 shadow-lg transition-all disabled:opacity-70 flex items-center gap-2">
                <span v-if="form.processing" class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></span>
                {{ form.processing ? 'Saving...' : 'Save Configuration' }}
            </button>
        </div>

    </form>
</template>

<style scoped>
/* Custom Scrollbar untuk kolom kanan */
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f5f9;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>