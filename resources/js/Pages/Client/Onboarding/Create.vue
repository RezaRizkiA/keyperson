<script setup>
import { useForm, Head, Link } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import { route } from "ziggy-js";
import ToastNotification from "@/Components/ToastNotification.vue";
import RegistrationStepHeader from "@/Components/Registration/RegistrationStepHeader.vue";

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
    ChevronDown,
    ChevronUp,
    Search,
    Check,
    Sparkles,
    Code,
    Palette,
    Scale,
    DollarSign,
    ArrowLeft,
    Save,
    ArrowRight,
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
const handleFileUpload = (event, fieldName) => {
    const file = event.target.files[0];
    if (!file) return;

    if (!file.type.match("image.*")) {
        alert("Mohon pilih file gambar (JPG, PNG, WEBP)");
        return;
    }

    form[fieldName] = file;

    const reader = new FileReader();
    reader.onload = (e) => {
        if (fieldName === "logo") {
            logoPreview.value = e.target.result;
        } else if (fieldName === "cover_image") {
            coverPreview.value = e.target.result;
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
    <Head title="Client Onboarding - Step 2" />
    <ToastNotification />

    <div class="min-h-screen bg-slate-50">
        <!-- Step Header -->
        <RegistrationStepHeader
            :current-step="2"
            :total-steps="2"
            step-title="Company Details"
            step-subtitle="Tell us about your company to customize your workspace"
        />

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-6 py-8">
            <form @submit.prevent="submit">
                <div class="grid lg:grid-cols-3 gap-8 items-start">
                    <!-- Left Column - Main Form (2/3) -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Form Card -->
                        <div
                            class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden"
                        >
                            <div class="p-8">
                                <h2
                                    class="text-xl font-bold text-slate-900 mb-6"
                                >
                                    Tell us about your company
                                </h2>
                                <p class="text-slate-500 mb-8 -mt-4">
                                    Select your industry expertise to help us
                                    customize your workspace. Accurate
                                    categorization improves your visibility to
                                    potential partners.
                                </p>

                                <div class="space-y-6">
                                    <div class="grid md:grid-cols-2 gap-4">
                                        <div>
                                            <label
                                                class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2"
                                            >
                                                Company Name
                                                <span class="text-red-400"
                                                    >*</span
                                                >
                                            </label>
                                            <div class="relative">
                                                <Building2
                                                    class="absolute left-3 top-3 w-5 h-5 text-slate-400"
                                                />
                                                <input
                                                    v-model="form.company_name"
                                                    type="text"
                                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all text-sm py-3 pl-10 pr-4"
                                                    :class="{
                                                        'border-red-300':
                                                            form.errors
                                                                .company_name,
                                                    }"
                                                    placeholder="e.g. PT Teknologi Maju Bersama"
                                                />
                                            </div>
                                            <p
                                                v-if="form.errors.company_name"
                                                class="text-red-500 text-xs mt-1"
                                            >
                                                {{ form.errors.company_name }}
                                            </p>
                                        </div>

                                        <div>
                                            <label
                                                class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2"
                                            >
                                                Entity Type
                                                <span class="text-red-400"
                                                    >*</span
                                                >
                                            </label>
                                            <div class="relative">
                                                <Stamp
                                                    class="absolute left-3 top-3 w-5 h-5 text-slate-400 z-10"
                                                />
                                                <select
                                                    v-model="form.type"
                                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all text-sm py-3 pl-10 pr-10 appearance-none cursor-pointer"
                                                    :class="{
                                                        'border-red-300':
                                                            form.errors.type,
                                                        'text-slate-400':
                                                            !form.type,
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
                                                    class="absolute right-3 top-3 w-5 h-5 text-slate-400 pointer-events-none"
                                                />
                                            </div>
                                            <p
                                                v-if="form.errors.type"
                                                class="text-red-500 text-xs mt-1"
                                            >
                                                {{ form.errors.type }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="grid md:grid-cols-2 gap-4">
                                        <div>
                                            <label
                                                class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2"
                                            >
                                                Industry Category
                                                <span class="text-red-400"
                                                    >*</span
                                                >
                                            </label>
                                            <div class="relative">
                                                <Briefcase
                                                    class="absolute left-3 top-3 w-5 h-5 text-slate-400 z-10"
                                                />
                                                <select
                                                    v-model="form.industry"
                                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all text-sm py-3 pl-10 pr-10 appearance-none cursor-pointer"
                                                    :class="{
                                                        'border-red-300':
                                                            form.errors
                                                                .industry,
                                                        'text-slate-400':
                                                            !form.industry,
                                                    }"
                                                >
                                                    <option value="" disabled>
                                                        Select your industry
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
                                                    class="absolute right-3 top-3 w-5 h-5 text-slate-400 pointer-events-none"
                                                />
                                            </div>
                                            <p
                                                v-if="form.errors.industry"
                                                class="text-red-500 text-xs mt-1"
                                            >
                                                {{ form.errors.industry }}
                                            </p>
                                        </div>

                                        <div>
                                            <label
                                                class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2"
                                            >
                                                Company Size
                                                <span class="text-red-400"
                                                    >*</span
                                                >
                                            </label>
                                            <div class="relative">
                                                <Users
                                                    class="absolute left-3 top-3 w-5 h-5 text-slate-400 z-10"
                                                />
                                                <select
                                                    v-model="
                                                        form.employee_count
                                                    "
                                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all text-sm py-3 pl-10 pr-10 appearance-none cursor-pointer"
                                                    :class="{
                                                        'border-red-300':
                                                            form.errors
                                                                .employee_count,
                                                        'text-slate-400':
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
                                                    class="absolute right-3 top-3 w-5 h-5 text-slate-400 pointer-events-none"
                                                />
                                            </div>
                                            <p
                                                v-if="
                                                    form.errors.employee_count
                                                "
                                                class="text-red-500 text-xs mt-1"
                                            >
                                                {{ form.errors.employee_count }}
                                            </p>
                                        </div>
                                    </div>

                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2"
                                        >
                                            Website
                                            <span
                                                class="text-slate-400 text-[10px] normal-case"
                                                >(Optional)</span
                                            >
                                        </label>
                                        <div class="relative">
                                            <Globe
                                                class="absolute left-3 top-3 w-5 h-5 text-slate-400"
                                            />
                                            <input
                                                v-model="form.website"
                                                type="text"
                                                class="w-full bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all text-sm py-3 pl-10 pr-4"
                                                :class="{
                                                    'border-red-300':
                                                        form.errors.website,
                                                }"
                                                placeholder="https://www.company.com"
                                            />
                                        </div>
                                        <p
                                            v-if="form.errors.website"
                                            class="text-red-500 text-xs mt-1"
                                        >
                                            {{ form.errors.website }}
                                        </p>
                                    </div>

                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2"
                                        >
                                            Office Address
                                            <span class="text-red-400">*</span>
                                        </label>
                                        <div class="relative">
                                            <MapPin
                                                class="absolute left-3 top-3 w-5 h-5 text-slate-400"
                                            />
                                            <textarea
                                                v-model="form.address"
                                                rows="2"
                                                class="w-full bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all text-sm py-3 pl-10 pr-4 resize-none"
                                                :class="{
                                                    'border-red-300':
                                                        form.errors.address,
                                                }"
                                                placeholder="Enter your office address..."
                                            ></textarea>
                                        </div>
                                        <p
                                            v-if="form.errors.address"
                                            class="text-red-500 text-xs mt-1"
                                        >
                                            {{ form.errors.address }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Branding & Visual Card -->
                        <div
                            class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden"
                        >
                            <div class="p-8">
                                <h3
                                    class="text-lg font-bold text-slate-900 mb-6"
                                >
                                    Branding & Visual
                                </h3>

                                <div class="grid md:grid-cols-2 gap-6">
                                    <!-- Logo Upload -->
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2"
                                        >
                                            Company Logo
                                            <span
                                                class="text-slate-400 text-[10px] normal-case"
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
                                                    class="h-20 w-20 rounded-xl object-cover border border-slate-200"
                                                    alt="Logo Preview"
                                                />
                                                <button
                                                    type="button"
                                                    @click="
                                                        removeFile(
                                                            'logo',
                                                            logoPreview
                                                        )
                                                    "
                                                    class="absolute -top-2 -right-2 bg-white text-red-500 border border-slate-200 p-1 rounded-full hover:bg-red-50 transition-all shadow-sm"
                                                >
                                                    <X class="w-3 h-3" />
                                                </button>
                                            </div>
                                            <label
                                                v-if="!logoPreview"
                                                for="logo-upload"
                                                class="flex-1 flex flex-col items-center justify-center p-6 border-2 border-dashed border-slate-200 rounded-xl cursor-pointer hover:border-blue-400 hover:bg-blue-50/50 transition-all"
                                            >
                                                <ImagePlus
                                                    class="w-8 h-8 text-slate-400 mb-2"
                                                />
                                                <span
                                                    class="text-sm text-slate-600"
                                                    >Upload logo</span
                                                >
                                                <span
                                                    class="text-xs text-slate-400 mt-1"
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
                                                                'logo'
                                                            )
                                                    "
                                                />
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Cover Upload -->
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2"
                                        >
                                            Profile Banner
                                            <span
                                                class="text-slate-400 text-[10px] normal-case"
                                                >(Optional)</span
                                            >
                                        </label>
                                        <div
                                            v-if="coverPreview"
                                            class="relative group"
                                        >
                                            <img
                                                :src="coverPreview"
                                                class="w-full h-24 rounded-xl object-cover border border-slate-200"
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
                                                class="absolute -top-2 -right-2 bg-white text-red-500 border border-slate-200 p-1 rounded-full hover:bg-red-50 transition-all shadow-sm"
                                            >
                                                <X class="w-3 h-3" />
                                            </button>
                                        </div>
                                        <label
                                            v-if="!coverPreview"
                                            for="cover-upload"
                                            class="flex flex-col items-center justify-center p-6 border-2 border-dashed border-slate-200 rounded-xl cursor-pointer hover:border-blue-400 hover:bg-blue-50/50 transition-all"
                                        >
                                            <UploadCloud
                                                class="w-8 h-8 text-slate-400 mb-2"
                                            />
                                            <span class="text-sm text-slate-600"
                                                >Upload banner</span
                                            >
                                            <span
                                                class="text-xs text-slate-400 mt-1"
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
                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2"
                                    >
                                        About Company
                                        <span class="text-red-400">*</span>
                                    </label>
                                    <textarea
                                        v-model="form.about"
                                        rows="4"
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all text-sm py-3 px-4 resize-none"
                                        :class="{
                                            'border-red-300': form.errors.about,
                                        }"
                                        placeholder="Tell us about your company, vision, mission, and work culture..."
                                    ></textarea>
                                    <div class="flex justify-between mt-1">
                                        <p
                                            v-if="form.errors.about"
                                            class="text-red-500 text-xs"
                                        >
                                            {{ form.errors.about }}
                                        </p>
                                        <p
                                            class="text-xs text-slate-400 ml-auto"
                                            :class="{
                                                'text-blue-600':
                                                    form.about.length >= 50,
                                            }"
                                        >
                                            {{ form.about.length }} / 50 min
                                            characters
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Info Box -->
                        <div
                            class="p-4 bg-blue-50 border border-blue-100 rounded-xl"
                        >
                            <div class="flex gap-3">
                                <div
                                    class="w-5 h-5 rounded-full bg-blue-100 flex items-center justify-center shrink-0 mt-0.5"
                                >
                                    <svg
                                        class="w-3 h-3 text-blue-600"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </div>
                                <p class="text-sm text-blue-700">
                                    Your skills and category will be used to
                                    calculate your initial trust score. You can
                                    update these details later in your profile
                                    settings.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Skills Sidebar (1/3) -->
                    <div class="lg:col-span-1 space-y-4 lg:sticky lg:top-40">
                        <div
                            class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden"
                        >
                            <div class="p-5 border-b border-slate-100">
                                <div class="flex items-center justify-between">
                                    <h3
                                        class="text-lg font-bold text-slate-900"
                                    >
                                        Key Skills
                                    </h3>
                                    <button
                                        v-if="selectedSkillsCount > 0"
                                        type="button"
                                        @click="clearAllSkills"
                                        class="text-xs text-blue-600 hover:text-blue-700 font-medium"
                                    >
                                        Clear all
                                    </button>
                                </div>
                                <p class="text-xs text-slate-500 mt-1">
                                    Choose categories that match your expertise
                                    needs.
                                </p>

                                <!-- Search -->
                                <div class="relative mt-4">
                                    <Search
                                        class="absolute left-3 top-2.5 w-4 h-4 text-slate-400"
                                    />
                                    <input
                                        v-model="skillSearch"
                                        type="text"
                                        class="w-full bg-slate-50 border border-slate-200 rounded-lg text-slate-900 placeholder-slate-400 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 text-sm py-2 pl-9 pr-4"
                                        placeholder="Type to add skills..."
                                    />
                                </div>

                                <!-- Selected Skills Tags -->
                                <div
                                    v-if="selectedSkillsCount > 0"
                                    class="flex flex-wrap gap-2 mt-3"
                                >
                                    <template
                                        v-for="category in categories"
                                        :key="category.id"
                                    >
                                        <template
                                            v-for="sub in category.sub_categories"
                                            :key="sub.id"
                                        >
                                            <template
                                                v-for="skill in sub.skills"
                                                :key="skill.id"
                                            >
                                                <span
                                                    v-if="
                                                        form.skills.includes(
                                                            Number(skill.id)
                                                        )
                                                    "
                                                    class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-100 text-slate-700 text-sm rounded-full"
                                                >
                                                    {{ skill.name }}
                                                    <button
                                                        type="button"
                                                        @click="
                                                            form.skills =
                                                                form.skills.filter(
                                                                    (id) =>
                                                                        id !==
                                                                        Number(
                                                                            skill.id
                                                                        )
                                                                )
                                                        "
                                                        class="text-slate-400 hover:text-slate-600"
                                                    >
                                                        <X
                                                            class="w-3.5 h-3.5"
                                                        />
                                                    </button>
                                                </span>
                                            </template>
                                        </template>
                                    </template>
                                </div>
                            </div>

                            <div class="max-h-[40vh] overflow-y-auto">
                                <div
                                    v-if="form.errors.skills"
                                    class="mx-4 mt-4 bg-red-50 text-red-600 p-3 rounded-lg text-sm border border-red-100"
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
                                            class="w-full flex items-center justify-between p-3.5 bg-slate-50 hover:bg-slate-100 rounded-xl transition-colors text-left"
                                            :class="
                                                openCategories.includes(
                                                    category.id
                                                )
                                                    ? 'rounded-b-none'
                                                    : ''
                                            "
                                        >
                                            <span
                                                class="flex items-center gap-3"
                                            >
                                                <component
                                                    :is="
                                                        getCategoryIcon(
                                                            category.name
                                                        )
                                                    "
                                                    class="w-5 h-5 text-blue-500"
                                                />
                                                <span
                                                    class="font-medium text-slate-900 text-sm"
                                                    >{{ category.name }}</span
                                                >
                                                <span
                                                    v-if="
                                                        countSelectedInCategory(
                                                            category
                                                        ) > 0
                                                    "
                                                    class="bg-blue-100 text-blue-600 text-[10px] px-2 py-0.5 rounded-full font-bold"
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
                                                openCategories.includes(
                                                    category.id
                                                )
                                            "
                                            class="bg-slate-50 px-4 py-3 rounded-b-xl border-t border-slate-100"
                                        >
                                            <div
                                                v-for="sub in category.sub_categories"
                                                :key="sub.id"
                                                class="mb-4 last:mb-0"
                                            >
                                                <!-- Subcategory Header -->
                                                <h5
                                                    class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2 pl-1 border-l-2 border-slate-300 ml-1"
                                                >
                                                    {{ sub.name }}
                                                </h5>
                                                <!-- Skills List -->
                                                <div class="space-y-0.5">
                                                    <label
                                                        v-for="skill in sub.skills"
                                                        :key="skill.id"
                                                        class="flex items-center gap-3 py-2 px-2 rounded-lg hover:bg-white cursor-pointer transition-colors group"
                                                    >
                                                        <div
                                                            class="relative flex items-center justify-center w-5 h-5"
                                                        >
                                                            <input
                                                                type="checkbox"
                                                                :value="
                                                                    Number(
                                                                        skill.id
                                                                    )
                                                                "
                                                                v-model="
                                                                    form.skills
                                                                "
                                                                class="peer appearance-none w-5 h-5 rounded border-2 border-slate-300 bg-white checked:bg-blue-500 checked:border-blue-500 transition-all cursor-pointer"
                                                            />
                                                            <Check
                                                                class="absolute w-3 h-3 text-white pointer-events-none opacity-0 peer-checked:opacity-100 transition-opacity"
                                                            />
                                                        </div>
                                                        <span
                                                            class="text-sm transition-colors"
                                                            :class="
                                                                form.skills.includes(
                                                                    Number(
                                                                        skill.id
                                                                    )
                                                                )
                                                                    ? 'text-slate-900 font-medium'
                                                                    : 'text-slate-600 group-hover:text-slate-800'
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
                                class="p-4 border-t border-slate-100 flex items-center justify-between bg-slate-50"
                            >
                                <span class="text-sm text-slate-500"
                                    >{{ selectedSkillsCount }} skills
                                    selected</span
                                >
                            </div>
                        </div>

                        <!-- Tip Box -->
                        <div
                            class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex gap-3"
                        >
                            <Sparkles
                                class="w-5 h-5 text-blue-500 shrink-0 mt-0.5"
                            />
                            <div>
                                <h4 class="text-sm font-bold text-blue-700">
                                    Tip for Success
                                </h4>
                                <p class="text-xs text-blue-600 mt-1">
                                    Select the skills you need to help us match
                                    you with the right experts for your
                                    projects.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div
                    class="mt-8 pt-6 border-t border-slate-200 flex items-center justify-between"
                >
                    <Link
                        :href="route('client_register.step_one')"
                        class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors"
                    >
                        <ArrowLeft class="w-4 h-4" />
                        Back
                    </Link>

                    <div class="flex items-center gap-3">
                        <button
                            type="button"
                            class="px-5 py-2.5 text-sm font-medium text-slate-600 hover:text-slate-800 bg-white hover:bg-slate-50 border border-slate-200 rounded-xl transition-all"
                        >
                            Save Draft
                        </button>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 text-white font-semibold text-sm rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/20 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg shadow-blue-500/20"
                        >
                            <Loader2
                                v-if="form.processing"
                                class="animate-spin h-4 w-4"
                            />
                            <span>{{
                                form.processing
                                    ? "Processing..."
                                    : "Complete Registration"
                            }}</span>
                            <ArrowRight
                                v-if="!form.processing"
                                class="w-4 h-4"
                            />
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
