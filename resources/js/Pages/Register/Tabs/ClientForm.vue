<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { route } from "ziggy-js";
import ExpertiseSelector from "../../../Components/ExpertiseSelector.vue";

// ICONS
import {
    LayoutTemplate,
    Type,
    FileText,
    User,
    Image as ImageIcon,
    Palette,
    Link2,
    Loader2,
    Check,
    Save,
} from "lucide-vue-next";

const props = defineProps({
    user: Object,
    existingData: Object,
    expertises: Array,
});

// Helper: Parse JSON data
const parseData = (data, defaultValue = []) => {
    if (!data) return defaultValue;
    try {
        return Array.isArray(data) ? data : JSON.parse(data);
    } catch {
        return defaultValue;
    }
};

const form = useForm({
    section_hero: props.existingData?.section_hero || "",
    banner_title: props.existingData?.banner_title || "",
    banner_desc: props.existingData?.banner_desc || "",
    author_name: props.existingData?.author_name || "",
    banner_footer: props.existingData?.banner_footer || "",
    banner_footer_desc: props.existingData?.banner_footer_desc || "",
    color: props.existingData?.color || "#7c3aed",
    slug: props.existingData?.slug || "",
    expertise_id: parseData(props.existingData?.expertise_id, []),

    // Files
    file_author_photo: null,
    file_banner_background: null,
    file_logo: null,
});

const previews = ref({
    author: props.existingData?.author_photo
        ? `/storage/${props.existingData.author_photo}`
        : null,
    banner: props.existingData?.banner_background
        ? `/storage/${props.existingData.banner_background}`
        : null,
    logo: props.existingData?.logo
        ? `/storage/${props.existingData.logo}`
        : null,
});

const handleFileChange = (event, key, previewKey) => {
    const file = event.target.files[0];
    if (file) {
        form[key] = file;
        previews.value[previewKey] = URL.createObjectURL(file);
    }
};

const submit = () => {
    form.post(route("register_client_post"), {
        forceFormData: true,
        preserveScroll: true,
    });
};
</script>

<template>
    <form @submit.prevent="submit" class="p-6 md:p-10">
        <div class="grid lg:grid-cols-3 gap-8 items-start">
            <div class="lg:col-span-2 space-y-8">
                <div
                    class="bg-white p-8 rounded-3xl border border-slate-200 shadow-xl shadow-slate-200/50"
                >
                    <div class="mb-6 pb-4 border-b border-slate-100">
                        <h3
                            class="font-display text-lg font-bold text-slate-900 flex items-center gap-2"
                        >
                            <LayoutTemplate class="w-5 h-5 text-violet-500" />
                            Hero Section
                        </h3>
                        <p class="text-sm text-slate-500">
                            Configure the main headline of your public page.
                        </p>
                    </div>

                    <div>
                        <label
                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2"
                            >Hero Tagline</label
                        >
                        <div class="relative group">
                            <div
                                class="absolute top-3 left-3 flex items-center pointer-events-none"
                            >
                                <Type
                                    class="h-5 w-5 text-slate-400 group-focus-within:text-violet-500 transition-colors"
                                />
                            </div>
                            <textarea
                                v-model="form.section_hero"
                                rows="2"
                                class="block w-full pl-10 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:bg-white focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 transition-all duration-200 sm:text-sm"
                                placeholder="Enter your hero tagline..."
                            ></textarea>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white p-8 rounded-3xl border border-slate-200 shadow-xl shadow-slate-200/50"
                >
                    <div class="mb-6 pb-4 border-b border-slate-100">
                        <h3
                            class="font-display text-lg font-bold text-slate-900 flex items-center gap-2"
                        >
                            <ImageIcon class="w-5 h-5 text-violet-500" />
                            Banner Configuration
                        </h3>
                        <p class="text-sm text-slate-500">
                            Customize the main banner appearance.
                        </p>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label
                                class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2"
                                >Banner Title</label
                            >
                            <input
                                v-model="form.banner_title"
                                type="text"
                                class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:bg-white focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 transition-all sm:text-sm"
                                placeholder="Banner Title"
                            />
                        </div>

                        <div>
                            <label
                                class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2"
                                >Banner Description</label
                            >
                            <div class="relative group">
                                <div
                                    class="absolute top-3 left-3 flex items-center pointer-events-none"
                                >
                                    <FileText
                                        class="h-5 w-5 text-slate-400 group-focus-within:text-violet-500 transition-colors"
                                    />
                                </div>
                                <textarea
                                    v-model="form.banner_desc"
                                    rows="3"
                                    class="block w-full pl-10 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:bg-white focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 transition-all sm:text-sm"
                                    placeholder="Describe your banner content..."
                                ></textarea>
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label
                                    class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2"
                                    >Author Name</label
                                >
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                                    >
                                        <User
                                            class="h-5 w-5 text-slate-400 group-focus-within:text-violet-500 transition-colors"
                                        />
                                    </div>
                                    <input
                                        v-model="form.author_name"
                                        type="text"
                                        class="block w-full pl-10 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:bg-white focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 transition-all sm:text-sm"
                                        placeholder="e.g. John Doe"
                                    />
                                </div>
                            </div>

                            <div>
                                <label
                                    class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2"
                                    >Author Photo</label
                                >
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-12 h-12 rounded-full overflow-hidden bg-slate-100 border border-slate-200  shrink-0"
                                    >
                                        <img
                                            v-if="previews.author"
                                            :src="previews.author"
                                            class="w-full h-full object-cover"
                                        />
                                        <div
                                            v-else
                                            class="w-full h-full flex items-center justify-center text-slate-400"
                                        >
                                            <User class="w-6 h-6" />
                                        </div>
                                    </div>
                                    <label
                                        class="cursor-pointer px-4 py-2 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-xl text-xs font-bold text-slate-600 transition-colors"
                                    >
                                        Change Photo
                                        <input
                                            type="file"
                                            class="hidden"
                                            @change="
                                                (e) =>
                                                    handleFileChange(
                                                        e,
                                                        'file_author_photo',
                                                        'author'
                                                    )
                                            "
                                            accept="image/*"
                                        />
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2"
                                >Banner Background</label
                            >
                            <div
                                class="relative w-full h-40 bg-slate-50 border-2 border-dashed border-slate-300 rounded-2xl overflow-hidden hover:border-violet-400 transition-colors group cursor-pointer"
                            >
                                <img
                                    v-if="previews.banner"
                                    :src="previews.banner"
                                    class="w-full h-full object-cover group-hover:opacity-50 transition-opacity"
                                />
                                <div
                                    class="absolute inset-0 flex flex-col items-center justify-center text-slate-400 pointer-events-none"
                                >
                                    <ImageIcon
                                        class="w-8 h-8 mb-2 group-hover:text-violet-500 transition-colors"
                                    />
                                    <span class="text-xs font-medium"
                                        >Click to upload banner</span
                                    >
                                </div>
                                <input
                                    type="file"
                                    @change="
                                        (e) =>
                                            handleFileChange(
                                                e,
                                                'file_banner_background',
                                                'banner'
                                            )
                                    "
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                    accept="image/*"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white p-8 rounded-3xl border border-slate-200 shadow-xl shadow-slate-200/50"
                >
                    <div class="mb-6 pb-4 border-b border-slate-100">
                        <h3
                            class="font-display text-lg font-bold text-slate-900 flex items-center gap-2"
                        >
                            <Palette class="w-5 h-5 text-violet-500" />
                            Footer & Branding
                        </h3>
                        <p class="text-sm text-slate-500">
                            Set up footer content and color theme.
                        </p>
                    </div>

                    <div class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label
                                    class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2"
                                    >Footer Title</label
                                >
                                <input
                                    v-model="form.banner_footer"
                                    type="text"
                                    class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:bg-white focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 transition-all sm:text-sm"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2"
                                    >Theme Color</label
                                >
                                <div class="flex items-center gap-3">
                                    <input
                                        v-model="form.color"
                                        type="color"
                                        class="w-12 h-12 rounded-xl cursor-pointer border border-slate-200 p-1 bg-white"
                                    />
                                    <span
                                        class="text-sm text-slate-600 font-mono bg-slate-100 px-2 py-1 rounded"
                                        >{{ form.color }}</span
                                    >
                                </div>
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2"
                                >Footer Description</label
                            >
                            <textarea
                                v-model="form.banner_footer_desc"
                                rows="2"
                                class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:bg-white focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 transition-all sm:text-sm"
                            ></textarea>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2"
                                >Company Logo</label
                            >
                            <div
                                class="flex items-center gap-4 p-4 border border-slate-200 rounded-xl bg-slate-50"
                            >
                                <div
                                    v-if="previews.logo"
                                    class="h-12 w-auto max-w-[150px] border border-slate-200 bg-white rounded-lg p-2"
                                >
                                    <img
                                        :src="previews.logo"
                                        class="h-full w-full object-contain"
                                    />
                                </div>
                                <div
                                    v-else
                                    class="h-12 w-12 bg-white rounded-lg border border-slate-200 flex items-center justify-center text-slate-400"
                                >
                                    <ImageIcon class="w-6 h-6" />
                                </div>

                                <label
                                    class="cursor-pointer px-4 py-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 rounded-lg text-xs font-bold transition shadow-sm"
                                >
                                    Upload Logo
                                    <input
                                        type="file"
                                        class="hidden"
                                        @change="
                                            (e) =>
                                                handleFileChange(
                                                    e,
                                                    'file_logo',
                                                    'logo'
                                                )
                                        "
                                        accept="image/*"
                                    />
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white p-8 rounded-3xl border border-slate-200 shadow-xl shadow-slate-200/50"
                >
                    <div>
                        <label
                            class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3 flex items-center gap-2"
                        >
                            Public Page URL
                            <span
                                class="bg-violet-100 text-violet-700 text-[10px] px-2 py-0.5 rounded-full normal-case"
                                >Required</span
                            >
                        </label>
                        <div
                            class="flex items-center bg-slate-50 border border-slate-200 rounded-xl focus-within:bg-white focus-within:border-violet-500 focus-within:ring-4 focus-within:ring-violet-500/10 transition-all duration-200 overflow-hidden"
                        >
                            <div
                                class="pl-4 pr-1 py-3 bg-transparent flex items-center gap-2 text-slate-500 border-r border-slate-200/50"
                            >
                                <Link2 class="h-4 w-4" />
                                <span class="text-sm font-medium"
                                    >keyperson.id/</span
                                >
                            </div>
                            <input
                                v-model="form.slug"
                                type="text"
                                class="flex-1 border-none bg-transparent focus:ring-0 text-slate-900 placeholder-slate-400 text-sm py-3 pl-2"
                                placeholder="company-name"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1 sticky top-6 z-20">
                <ExpertiseSelector
                    :expertises="expertises"
                    v-model="form.expertise_id"
                    label="Show Specialists"
                />

                <div
                    class="mt-4 p-4 bg-blue-50 text-blue-700 text-xs rounded-xl border border-blue-100 leading-relaxed shadow-sm"
                >
                    <div class="flex gap-2">
                        <i class="ti ti-bulb text-lg"></i>
                        <span>
                            <strong>Pro Tip:</strong> Selected expertise
                            categories will be displayed on your public page for
                            visitors to filter.
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="mt-10 pt-6 border-t border-slate-100 flex items-center justify-end gap-4"
        >
            <a
                :href="route('profile')"
                class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-slate-50 hover:text-slate-800 transition-all flex items-center justify-center"
            >
                Cancel
            </a>
            <button
                type="submit"
                :disabled="form.processing"
                class="px-8 py-3 rounded-xl bg-slate-900 text-white font-bold text-sm hover:bg-slate-800 shadow-lg disabled:opacity-70 flex items-center gap-2 transition-all transform active:scale-95"
            >
                <template v-if="form.processing">
                    <Loader2 class="animate-spin h-4 w-4" />
                    Saving...
                </template>
                <template v-else>
                    <Save class="h-4 w-4" />
                    Save Configuration
                </template>
            </button>
        </div>
    </form>
</template>
