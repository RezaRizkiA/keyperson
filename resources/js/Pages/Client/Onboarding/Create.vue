<script setup>
import { useForm, Head } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import { route } from "ziggy-js";
import ToastNotification from "@/Components/ToastNotification.vue";

import {
    Building2,
    Users,
    Globe,
    MapPin,
    ImagePlus,
    UploadCloud,
    X,
    Loader2,
    Briefcase,
    Stamp,
    Grid3x3,
    Info,
    ChevronDown,
    ChevronUp,
    Search,
    Check,
    Sparkles,
    Code,
    Palette,
    Scale,
    DollarSign,
} from "lucide-vue-next";

// Props dari Controller
const props = defineProps({
    options: Object,
    user_defaults: Object,
    categories: Array,
});

// Form Setup
const form = useForm({
  company_name: "",
    type: "",
    industry: "",
    employee_count: "",
    website: "",
    address: "",
    logo: null,
    cover_image: null,
    about: "",
    skills: [],
});

// State
const logoPreview = ref(null);
const coverPreview = ref(null);
const skillSearch = ref("");
const openCategories = ref([]);

// Category Icons Map
const categoryIcons = {
    "Technology & IT": Code,
    "Design & Creative": Palette,
    "Legal & Compliance": Scale,
    "Finance & Accounting": DollarSign,
    "Business Management": Building2,
};

// Computed: Filter categories based on search
const filteredCategories = computed(() => {
    if (!skillSearch.value.trim()) return props.categories || [];

    const search = skillSearch.value.toLowerCase();
    return (props.categories || [])
        .map((cat) => ({
            ...cat,
            sub_categories: (cat.sub_categories || [])
                .map((sub) => ({
                    ...sub,
                    skills: (sub.skills || []).filter((skill) =>
                        skill.name.toLowerCase().includes(search)
                    ),
                }))
                .filter((sub) => sub.skills.length > 0),
        }))
        .filter((cat) => cat.sub_categories.length > 0);
});

// Selected skills count
const selectedSkillsCount = computed(() => form.skills.length);

// Toggle category
const toggleCategory = (catId) => {
    if (openCategories.value.includes(catId)) {
        openCategories.value = openCategories.value.filter(
            (id) => id !== catId
        );
    } else {
        openCategories.value.push(catId);
    }
};

// Get category icon
const getCategoryIcon = (name) => categoryIcons[name] || Briefcase;

// Count selected skills in category
const countSelectedInCategory = (category) => {
    return (category.sub_categories || []).reduce(
        (acc, sub) =>
            acc +
            (sub.skills || []).filter((s) => form.skills.includes(Number(s.id)))
                .length,
        0
    );
};

// Clear all skills
const clearAllSkills = () => {
    form.skills = [];
};

// Handle File Upload
const handleFileUpload = (event, fieldName, previewRef) => {
    const file = event.target.files[0];
    if (!file) return;

    if (!file.type.match("image.*")) {
        alert("Mohon pilih file gambar (JPG, PNG, WEBP)");
        return;
    }

    form[fieldName] = file;

    const reader = new FileReader();
    reader.onload = (e) => {
        // Check if previewRef is still valid (component might be unmounted)
        if (previewRef && e.target) {
            previewRef.value = e.target.result;
        }
    };
    reader.readAsDataURL(file);
};

// Remove File
const removeFile = (fieldName, previewRef) => {
    form[fieldName] = null;
    previewRef.value = null;
    const inputId = fieldName === "logo" ? "logo-upload" : "cover-upload";
    if (document.getElementById(inputId))
        document.getElementById(inputId).value = "";
};

// Submit Form
const submit = () => {
    form.post(route("client_onboarding.store"), {
        preserveScroll: true,
        forceFormData: true,
    });
};
</script>

<template>
    <Head title="Client Onboarding" />
    <ToastNotification />

    <div class="min-h-screen bg-slate-950">
        <!-- Header -->
        <header
            class="bg-slate-900/80 backdrop-blur-md border-b border-slate-800 px-6 py-3 sticky top-0 z-50"
        >
            <div class="w-full px-4 xl:px-8 flex justify-between items-center">
                <!-- Logo -->
                <div class="flex items-center gap-2">
                    <Grid3x3 class="w-6 h-6 text-blue-400" />
                    <span class="text-xl font-bold text-white">Keyperson</span>
                </div>

                <!-- Right: Support & Avatar -->
                <div class="flex items-center gap-4">
                    <button
                        class="px-4 py-2 text-sm text-slate-300 hover:text-white flex items-center gap-2 border border-slate-700 hover:border-slate-600 rounded-full transition-all"
                    >
                        <Info class="w-4 h-4" />
                        Support
                    </button>
                    <div
                        class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-sm ring-2 ring-slate-700"
                    >
                        {{ user_defaults?.name?.charAt(0) || "U" }}
                    </div>
                </div>
            </div>
        </header>

        <!-- Title Section -->
        <div class="max-w-screen-2xl mx-auto px-6 xl:px-10 pt-8 pb-6">
            <div
                class="flex flex-col md:flex-row md:items-center md:justify-between gap-4"
            >
                <div>
                    <h1 class="text-2xl font-bold text-white">
                        Client Registration
                    </h1>
                    <p
                        class="text-sm text-slate-400 mt-1 flex items-center gap-2"
                    >
                        <span
                            class="px-2.5 py-1 bg-blue-500/20 text-blue-400 text-xs font-bold rounded-full"
                            >Step 1</span
                        >
                        Company Profile Setup
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <button
                        type="button"
                        class="px-5 py-2.5 text-sm font-medium text-slate-300 hover:text-white bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-full transition-all"
                    >
                        Save Draft
                    </button>
                    <button
                        type="button"
                        @click="submit"
                        :disabled="form.processing"
                        class="px-6 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-bold text-sm rounded-full hover:from-blue-600 hover:to-blue-700 transition-all shadow-lg shadow-blue-500/25 flex items-center gap-2 disabled:opacity-50"
                    >
                        <Loader2
                            v-if="form.processing"
                            class="animate-spin h-4 w-4"
                        />
                        Complete Registration
                    </button>
                </div>
            </div>
        </div>

        <form
            @submit.prevent="submit"
            class="max-w-screen-2xl mx-auto px-6 xl:px-10 pb-8"
        >
            <div class="grid lg:grid-cols-3 gap-8 items-start">
                <!-- Left Column - Main Form (2/3) -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Company Identity -->
                    <div
                        class="bg-slate-900/70 backdrop-blur-sm p-6 rounded-2xl border border-slate-800"
                    >
                        <h3 class="text-lg font-bold text-white mb-6">
                            Company Identity
                        </h3>

                        <div class="space-y-5">
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="block text-xs font-medium text-slate-400 mb-2"
                                    >
                                        Company Name
                                        <span class="text-red-400">*</span>
                                    </label>
                                    <div class="relative">
                                        <Building2
                                            class="absolute left-3 top-2.5 w-5 h-5 text-slate-500"
                                        />
                                        <input
                                            v-model="form.company_name"
                                            type="text"
                                            class="w-full bg-slate-800/50 border border-slate-700 rounded-lg text-white placeholder-slate-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-sm py-2.5 pl-10 pr-4"
                                            :class="{
                                                'border-red-500':
                                                    form.errors.company_name,
                                            }"
                                            placeholder="e.g. PT Teknologi Maju Bersama"
                                        />
                                    </div>
                                    <p
                                        v-if="form.errors.company_name"
                                        class="text-red-400 text-xs mt-1"
                                    >
                                        {{ form.errors.company_name }}
                                    </p>
                                </div>

                                <div>
                                    <label
                                        class="block text-xs font-medium text-slate-400 mb-2"
                                    >
                                        Entity Type
                                        <span class="text-red-400">*</span>
                                    </label>
                                    <div class="relative">
                                        <Stamp
                                            class="absolute left-3 top-2.5 w-5 h-5 text-slate-500 z-10"
                                        />
                                        <select
                                            v-model="form.type"
                                            class="w-full bg-slate-800/50 border border-slate-700 rounded-lg text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-sm py-2.5 pl-10 pr-4 appearance-none cursor-pointer"
                                            :class="{
                                                'border-red-500':
                                                    form.errors.type,
                                                'text-slate-500': !form.type,
                                            }"
                                        >
                                            <option value="" disabled>
                                                Select Entity Type
                                            </option>
                                            <option
                                                v-for="type in options.types"
                                                :key="type.value"
                                                :value="type.value"
                                            >
                                                {{ type.label }}
                                            </option>
                                        </select>
                                        <ChevronDown
                                            class="absolute right-3 top-2.5 w-5 h-5 text-slate-500 pointer-events-none"
                                        />
                                    </div>
                                    <p
                                        v-if="form.errors.type"
                                        class="text-red-400 text-xs mt-1"
                                    >
                                        {{ form.errors.type }}
                                    </p>
                                </div>
                            </div>

                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="block text-xs font-medium text-slate-400 mb-2"
                                    >
                                        Industry
                                        <span class="text-red-400">*</span>
                                    </label>
                                    <div class="relative">
                                        <Briefcase
                                            class="absolute left-3 top-2.5 w-5 h-5 text-slate-500 z-10"
                                        />
                                        <select
                                            v-model="form.industry"
                                            class="w-full bg-slate-800/50 border border-slate-700 rounded-lg text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-sm py-2.5 pl-10 pr-4 appearance-none cursor-pointer"
                                            :class="{
                                                'border-red-500':
                                                    form.errors.industry,
                                                'text-slate-500':
                                                    !form.industry,
                                            }"
                                        >
                                            <option value="" disabled>
                                                Select Industry
                                            </option>
                                            <option
                                                v-for="industry in options.industries"
                                                :key="industry"
                                                :value="industry"
                                            >
                                                {{ industry }}
                                            </option>
                                        </select>
                                        <ChevronDown
                                            class="absolute right-3 top-2.5 w-5 h-5 text-slate-500 pointer-events-none"
                                        />
                                    </div>
                                    <p
                                        v-if="form.errors.industry"
                                        class="text-red-400 text-xs mt-1"
                                    >
                                        {{ form.errors.industry }}
                                    </p>
                                </div>

                                <div>
                                    <label
                                        class="block text-xs font-medium text-slate-400 mb-2"
                                    >
                                        Company Size
                                        <span class="text-red-400">*</span>
                                    </label>
                                    <div class="relative">
                                        <Users
                                            class="absolute left-3 top-2.5 w-5 h-5 text-slate-500 z-10"
                                        />
                                        <select
                                            v-model="form.employee_count"
                                            class="w-full bg-slate-800/50 border border-slate-700 rounded-lg text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-sm py-2.5 pl-10 pr-4 appearance-none cursor-pointer"
                                            :class="{
                                                'border-red-500':
                                                    form.errors.employee_count,
                                                'text-slate-500':
                                                    !form.employee_count,
                                            }"
                                        >
                                            <option value="" disabled>
                                                Select Company Size
                                            </option>
                                            <option
                                                v-for="count in options.employee_counts"
                                                :key="count"
                                                :value="count"
                                            >
                                                {{ count }}
                                            </option>
                                        </select>
                                        <ChevronDown
                                            class="absolute right-3 top-2.5 w-5 h-5 text-slate-500 pointer-events-none"
                                        />
                                    </div>
                                    <p
                                        v-if="form.errors.employee_count"
                                        class="text-red-400 text-xs mt-1"
                                    >
                                        {{ form.errors.employee_count }}
                                    </p>
                                </div>
                            </div>

                            <div>
                                <label
                                    class="block text-xs font-medium text-slate-400 mb-2"
                                >
                                    Website
                                    <span class="text-slate-600 text-[10px]"
                                        >(Optional)</span
                                    >
                                </label>
                                <div class="relative">
                                    <Globe
                                        class="absolute left-3 top-2.5 w-5 h-5 text-slate-500"
                                    />
                                    <input
                                        v-model="form.website"
                                        type="url"
                                        class="w-full bg-slate-800/50 border border-slate-700 rounded-lg text-white placeholder-slate-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-sm py-2.5 pl-10 pr-4"
                                        placeholder="https://www.company.com"
                                    />
                                </div>
                            </div>

                            <div>
                                <label
                                    class="block text-xs font-medium text-slate-400 mb-2"
                                >
                                    Office Address
                                    <span class="text-red-400">*</span>
                                </label>
                                <div class="relative">
                                    <MapPin
                                        class="absolute left-3 top-3 w-5 h-5 text-slate-500"
                                    />
                                    <textarea
                                        v-model="form.address"
                                        rows="2"
                                        class="w-full bg-slate-800/50 border border-slate-700 rounded-lg text-white placeholder-slate-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-sm py-2.5 pl-10 pr-4 resize-none"
                                        :class="{
                                            'border-red-500':
                                                form.errors.address,
                                        }"
                                        placeholder="Enter your office address..."
                                    ></textarea>
                                </div>
                                <p
                                    v-if="form.errors.address"
                                    class="text-red-400 text-xs mt-1"
                                >
                                    {{ form.errors.address }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Branding & Visual -->
                    <div
                        class="bg-slate-900/70 backdrop-blur-sm p-6 rounded-2xl border border-slate-800"
                    >
                        <h3 class="text-lg font-bold text-white mb-6">
                            Branding & Visual
                        </h3>

                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- Logo Upload -->
                            <div>
                                <label
                                    class="block text-xs font-medium text-slate-400 mb-2"
                                >
                                    Company Logo
                                    <span class="text-slate-600 text-[10px]"
                                        >(Optional)</span
                                    >
                                </label>
                                <div class="flex items-start gap-4">
                                    <div
                                        v-if="logoPreview"
                                        class="relative group shrink-0"
                                    >
                                        <img
                                            :src="logoPreview"
                                            class="h-20 w-20 rounded-xl object-cover border border-slate-700"
                                            alt="Logo Preview"
                                        />
                                        <button
                                            type="button"
                                            @click="
                                                removeFile('logo', logoPreview)
                                            "
                                            class="absolute -top-2 -right-2 bg-slate-800 text-red-400 border border-slate-700 p-1 rounded-full hover:bg-red-500/20 transition-all"
                                        >
                                            <X class="w-3 h-3" />
                                        </button>
                                    </div>
                                    <label
                                        v-if="!logoPreview"
                                        for="logo-upload"
                                        class="flex-1 flex flex-col items-center justify-center p-6 border-2 border-dashed border-slate-700 rounded-xl cursor-pointer hover:border-blue-500/50 hover:bg-slate-800/30 transition-all"
                                    >
                                        <ImagePlus
                                            class="w-8 h-8 text-slate-500 mb-2"
                                        />
                                        <span class="text-sm text-slate-400"
                                            >Upload logo</span
                                        >
                                        <span
                                            class="text-xs text-slate-600 mt-1"
                                            >PNG, JPG (Max 5MB)</span
                                        >
                                        <input
                                            id="logo-upload"
                                            type="file"
                                            class="hidden"
                                            accept="image/png, image/jpeg, image/jpg, image/webp"
                                            @change="
                                                (e) =>
                                                    handleFileUpload(
                                                        e,
                                                        'logo',
                                                        logoPreview
                                                    )
                                            "
                                        />
                                    </label>
                                </div>
                            </div>

                            <!-- Cover Upload -->
                            <div>
                                <label
                                    class="block text-xs font-medium text-slate-400 mb-2"
                                >
                                    Profile Banner
                                    <span class="text-slate-600 text-[10px]"
                                        >(Optional)</span
                                    >
                                </label>
                                <div v-if="coverPreview" class="relative group">
                                    <img
                                        :src="coverPreview"
                                        class="w-full h-24 rounded-xl object-cover border border-slate-700"
                                        alt="Cover Preview"
                                    />
                                    <button
                                        type="button"
                                        @click="
                                            removeFile(
                                                'cover_image',
                                                coverPreview
                                            )
                                        "
                                        class="absolute -top-2 -right-2 bg-slate-800 text-red-400 border border-slate-700 p-1 rounded-full hover:bg-red-500/20 transition-all"
                                    >
                                        <X class="w-3 h-3" />
                                    </button>
                                </div>
                                <label
                                    v-if="!coverPreview"
                                    for="cover-upload"
                                    class="flex flex-col items-center justify-center p-6 border-2 border-dashed border-slate-700 rounded-xl cursor-pointer hover:border-blue-500/50 hover:bg-slate-800/30 transition-all"
                                >
                                    <UploadCloud
                                        class="w-8 h-8 text-slate-500 mb-2"
                                    />
                                    <span class="text-sm text-slate-400"
                                        >Upload banner</span
                                    >
                                    <span class="text-xs text-slate-600 mt-1"
                                        >1200x300px recommended</span
                                    >
                                    <input
                                        id="cover-upload"
                                        type="file"
                                        class="hidden"
                                        accept="image/png, image/jpeg, image/jpg, image/webp"
                                        @change="
                                            (e) =>
                                                handleFileUpload(
                                                    e,
                                                    'cover_image',
                                                    coverPreview
                                                )
                                        "
                                    />
                                </label>
                            </div>
                        </div>

                        <!-- About -->
                        <div class="mt-6">
                            <label
                                class="block text-xs font-medium text-slate-400 mb-2"
                            >
                                About Company
                                <span class="text-red-400">*</span>
                            </label>
                            <textarea
                                v-model="form.about"
                                rows="4"
                                class="w-full bg-slate-800/50 border border-slate-700 rounded-lg text-white placeholder-slate-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-sm py-2.5 px-4 resize-none overflow-y-auto"
                                style="
                                    word-break: break-word;
                                    overflow-wrap: break-word;
                                "
                                :class="{ 'border-red-500': form.errors.about }"
                                placeholder="Tell us about your company, vision, mission, and work culture..."
                            ></textarea>
                            <div class="flex justify-between mt-1">
                                <p
                                    v-if="form.errors.about"
                                    class="text-red-400 text-xs"
                                >
                                    {{ form.errors.about }}
                                </p>
                                <p
                                    class="text-xs text-slate-500 ml-auto"
                                    :class="{
                                        'text-blue-400':
                                            form.about.length >= 50,
                                    }"
                                >
                                    {{ form.about.length }} / 50 min characters
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Skills Sidebar (1/3) -->
                <div class="lg:col-span-1 space-y-4 lg:sticky lg:top-24">
                    <div
                        class="bg-slate-900/70 backdrop-blur-sm rounded-2xl border border-slate-800 overflow-hidden"
                    >
                        <div class="p-5 border-b border-slate-800">
                            <h3 class="text-lg font-bold text-white">
                                Select Your Skills
                            </h3>
                            <p class="text-xs text-slate-500 mt-1">
                                Choose categories that match your expertise
                                needs.
                            </p>

                            <!-- Search -->
                            <div class="relative mt-4">
                                <Search
                                    class="absolute left-3 top-2.5 w-4 h-4 text-slate-500"
                                />
                                <input
                                    v-model="skillSearch"
                                    type="text"
                                    class="w-full bg-slate-800/50 border border-slate-700 rounded-lg text-white placeholder-slate-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 text-sm py-2 pl-9 pr-4"
                                    placeholder="Search skills..."
                                />
                            </div>
                        </div>

                        <div
                            class="max-h-[55vh] overflow-y-auto custom-scrollbar"
                        >
                            <div
                                v-if="form.errors.skills"
                                class="mx-4 mt-4 bg-red-500/10 text-red-400 p-3 rounded-lg text-sm border border-red-500/20"
                            >
                                {{ form.errors.skills }}
                            </div>

                            <div class="p-4 space-y-1">
                                <div
                                    v-for="category in filteredCategories"
                                    :key="category.id"
                                    class="rounded-xl overflow-hidden"
                                >
                                    <!-- Category Header -->
                                    <button
                                        type="button"
                                        @click="toggleCategory(category.id)"
                                        class="w-full flex items-center justify-between p-3.5 bg-slate-800/60 hover:bg-slate-800/80 rounded-xl transition-colors text-left"
                                        :class="
                                            openCategories.includes(category.id)
                                                ? 'rounded-b-none'
                                                : ''
                                        "
                                    >
                                        <span class="flex items-center gap-3">
                                            <component
                                                :is="
                                                    getCategoryIcon(
                                                        category.name
                                                    )
                                                "
                                                class="w-5 h-5 text-blue-400"
                                            />
                                            <span
                                                class="font-medium text-white text-sm"
                                                >{{ category.name }}</span
                                            >
                                            <span
                                                v-if="
                                                    countSelectedInCategory(
                                                        category
                                                    ) > 0
                                                "
                                                class="bg-blue-500/20 text-blue-400 text-[10px] px-2 py-0.5 rounded-full font-bold"
                                            >
                                                {{
                                                    countSelectedInCategory(
                                                        category
                                                    )
                                                }}
                                            </span>
                                        </span>
                                        <ChevronUp
                                            v-if="
                                                openCategories.includes(
                                                    category.id
                                                )
                                            "
                                            class="w-4 h-4 text-slate-400"
                                        />
                                        <ChevronDown
                                            v-else
                                            class="w-4 h-4 text-slate-400"
                                        />
                                    </button>

                                    <!-- Category Content (Expanded) -->
                                    <div
                                        v-if="
                                            openCategories.includes(category.id)
                                        "
                                        class="bg-slate-800/30 px-4 py-3 rounded-b-xl border-t border-slate-700/50"
                                    >
                                        <div
                                            v-for="sub in category.sub_categories"
                                            :key="sub.id"
                                            class="mb-4 last:mb-0"
                                        >
                                            <!-- Subcategory Header -->
                                            <h5
                                                class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2 pl-1 border-l-2 border-slate-600 ml-1"
                                            >
                                                {{ sub.name }}
                                            </h5>
                                            <!-- Skills List -->
                                            <div class="space-y-0.5">
                                                <label
                                                    v-for="skill in sub.skills"
                                                    :key="skill.id"
                                                    class="flex items-center gap-3 py-2 px-2 rounded-lg hover:bg-slate-700/30 cursor-pointer transition-colors group"
                                                >
                                                    <div
                                                        class="relative flex items-center justify-center w-5 h-5"
                                                    >
                                                        <input
                                                            type="checkbox"
                                                            :value="
                                                                Number(skill.id)
                                                            "
                                                            v-model="
                                                                form.skills
                                                            "
                                                            class="peer appearance-none w-5 h-5 rounded border-2 border-slate-600 bg-slate-800 checked:bg-blue-500 checked:border-blue-500 transition-all cursor-pointer"
                                                        />
                                                        <Check
                                                            class="absolute w-3 h-3 text-white pointer-events-none opacity-0 peer-checked:opacity-100 transition-opacity"
                                                        />
                                                    </div>
                                                    <span
                                                        class="text-sm transition-colors"
                                                        :class="
                                                            form.skills.includes(
                                                                Number(skill.id)
                                                            )
                                                                ? 'text-white font-medium'
                                                                : 'text-slate-400 group-hover:text-slate-300'
                                                        "
                                                    >
                                                        {{ skill.name }}
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                        <div
                                            v-if="
                                                !category.sub_categories ||
                                                category.sub_categories
                                                    .length === 0
                                            "
                                            class="py-3 text-xs text-slate-500 italic text-center"
                                        >
                                            No skills available in this
                                            category.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div
                            class="p-4 border-t border-slate-800 flex items-center justify-between bg-slate-900/50"
                        >
                            <span class="text-sm text-slate-400"
                                >{{ selectedSkillsCount }} skills selected</span
                            >
                            <button
                                v-if="selectedSkillsCount > 0"
                                type="button"
                                @click="clearAllSkills"
                                class="text-xs text-blue-400 hover:text-blue-300 font-medium"
                            >
                                Clear all
                            </button>
                        </div>
                    </div>

                    <!-- Tip Box -->
                    <div
                        class="bg-blue-500/10 border border-blue-500/20 rounded-xl p-4 flex gap-3"
                    >
                        <Sparkles
                            class="w-5 h-5 text-blue-400 shrink-0 mt-0.5"
                        />
                        <div>
                            <h4 class="text-sm font-bold text-blue-400">
                                Tip for Success
                            </h4>
                            <p class="text-xs text-slate-400 mt-1">
                                Select the skills you need to help us match you
                                with the right experts for your projects.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>
