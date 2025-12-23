<script setup>
import { Head, Link, useForm, router } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import {
    ArrowLeft,
    User,
    Mail,
    Phone,
    MapPin,
    Briefcase,
    DollarSign,
    FileText,
    Award,
    Trash2,
    Save,
    ChevronDown,
    ChevronRight,
} from "lucide-vue-next";
import { ref } from "vue";

defineOptions({ layout: DashboardLayout });

const props = defineProps({
    expert: Object,
    categories: Array, // Changed from skills to categories (hierarchical)
});

// Form setup
const form = useForm({
    first_name: props.expert.first_name || "",
    last_name: props.expert.last_name || "",
    email: props.expert.email || "",
    phone: props.expert.phone || "",
    address: props.expert.address || "",
    title: props.expert.title || "",
    price: props.expert.price || 0,
    about: props.expert.about || "",
    type: props.expert.type || [],
    experiences: props.expert.experiences || [],
    selected_skills: props.expert.selected_skills || [],
    licenses: props.expert.licenses || [],
    gallerys: props.expert.gallerys || [],
});

// Submit update
const submit = () => {
    form.put(route("dashboard.experts.update", props.expert.id), {
        preserveScroll: true,
        forceFormData: true, // Important for file uploads
    });
};

// Expand/collapse for categories
const expandedCategories = ref({});
const expandedSubCategories = ref({});

// Experiences helpers
const addExperience = () => {
    form.experiences.push({
        role: "",
        company: "",
        duration: "",
        description: "",
    });
};

const removeExperience = (index) => {
    form.experiences.splice(index, 1);
};

// Licenses helpers
const addLicense = () => {
    form.licenses.push({ certification: "", file: null });
};

const removeLicense = (index) => {
    form.licenses.splice(index, 1);
};

const handleLicenseFile = (index, event) => {
    const file = event.target.files[0];
    if (file) {
        form.licenses[index].file = file;
    }
};

// Gallery helpers
const handleGalleryFiles = (event) => {
    const files = Array.from(event.target.files);
    files.forEach((file) => {
        form.gallerys.push({ file });
    });
};

const removeGallery = (index) => {
    form.gallerys.splice(index, 1);
};

// Service Type toggle helper
const toggleServiceType = (type) => {
    const index = form.type.indexOf(type);
    if (index > -1) {
        form.type.splice(index, 1);
    } else {
        form.type.push(type);
    }
};
</script>

<template>
    <Head :title="`Edit Expert - ${expert.first_name} ${expert.last_name}`" />

    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-100">
                    Expert Profile
                </h1>
                <p class="text-sm text-slate-400 mt-1">
                    Update expert information and skills
                </p>
            </div>
            <div class="flex items-center gap-3">
                <Link
                    :href="route('dashboard.experts.index')"
                    class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 font-medium rounded-lg border border-slate-700 transition-colors"
                >
                    Cancel
                </Link>
                <button
                    @click="submit"
                    :disabled="form.processing"
                    class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg transition-colors flex items-center gap-2 disabled:opacity-50"
                >
                    <Save v-if="!form.processing" class="w-4 h-4" />
                    <span v-if="form.processing">Saving...</span>
                    <span v-else>Save Changes</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Main Form -->
    <form @submit.prevent="submit">
        <div class="grid lg:grid-cols-3 gap-6">
            <!-- LEFT COLUMN - Personal & Expert Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Personal Information Card -->
                <div
                    class="bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700/50 p-6"
                >
                    <div
                        class="flex items-center gap-2 mb-6 pb-4 border-b border-slate-700/50"
                    >
                        <User class="w-5 h-5 text-blue-400" />
                        <div>
                            <h2 class="text-lg font-bold text-slate-100">
                                Personal Information
                            </h2>
                            <p class="text-xs text-slate-500">
                                From user account
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- First Name -->
                        <div>
                            <label
                                class="block text-xs font-semibold text-slate-400 uppercase mb-2"
                                >First Name</label
                            >
                            <input
                                v-model="form.first_name"
                                type="text"
                                class="w-full px-4 py-2.5 bg-slate-900/50 border border-slate-700 rounded-lg text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500"
                                placeholder="Sarah"
                            />
                            <div
                                v-if="form.errors.first_name"
                                class="text-xs text-red-400 mt-1"
                            >
                                {{ form.errors.first_name }}
                            </div>
                        </div>

                        <!-- Last Name -->
                        <div>
                            <label
                                class="block text-xs font-semibold text-slate-400 uppercase mb-2"
                                >Last Name</label
                            >
                            <input
                                v-model="form.last_name"
                                type="text"
                                class="w-full px-4 py-2.5 bg-slate-900/50 border border-slate-700 rounded-lg text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500"
                                placeholder="Jensen"
                            />
                            <div
                                v-if="form.errors.last_name"
                                class="text-xs text-red-400 mt-1"
                            >
                                {{ form.errors.last_name }}
                            </div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label
                                class="block text-xs font-semibold text-slate-400 uppercase mb-2"
                                >Email Address</label
                            >
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                                >
                                    <Mail class="w-4 h-4 text-slate-500" />
                                </div>
                                <input
                                    v-model="form.email"
                                    type="email"
                                    class="w-full pl-10 pr-4 py-2.5 bg-slate-900/50 border border-slate-700 rounded-lg text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500"
                                    placeholder="sarah.jensen@example.com"
                                />
                            </div>
                            <div
                                v-if="form.errors.email"
                                class="text-xs text-red-400 mt-1"
                            >
                                {{ form.errors.email }}
                            </div>
                        </div>

                        <!-- Phone -->
                        <div>
                            <label
                                class="block text-xs font-semibold text-slate-400 uppercase mb-2"
                                >Phone Number</label
                            >
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                                >
                                    <Phone class="w-4 h-4 text-slate-500" />
                                </div>
                                <input
                                    v-model="form.phone"
                                    type="text"
                                    class="w-full pl-10 pr-4 py-2.5 bg-slate-900/50 border border-slate-700 rounded-lg text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500"
                                    placeholder="+1 (555) 123-4567"
                                />
                            </div>
                            <div
                                v-if="form.errors.phone"
                                class="text-xs text-red-400 mt-1"
                            >
                                {{ form.errors.phone }}
                            </div>
                        </div>
                    </div>

                    <!-- Address (Full Width) -->
                    <div class="mt-4">
                        <label
                            class="block text-xs font-semibold text-slate-400 uppercase mb-2"
                            >Address / Location</label
                        >
                        <div class="relative">
                            <div
                                class="absolute top-3 left-0 pl-3 flex items-center pointer-events-none"
                            >
                                <MapPin class="w-4 h-4 text-slate-500" />
                            </div>
                            <input
                                v-model="form.address"
                                type="text"
                                class="w-full pl-10 pr-4 py-2.5 bg-slate-900/50 border border-slate-700 rounded-lg text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500"
                                placeholder="New York, NY, United States"
                            />
                        </div>
                        <div
                            v-if="form.errors.address"
                            class="text-xs text-red-400 mt-1"
                        >
                            {{ form.errors.address }}
                        </div>
                    </div>
                </div>

                <!-- Expert Information Card -->
                <div
                    class="bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700/50 p-6"
                >
                    <div
                        class="flex items-center gap-2 mb-6 pb-4 border-b border-slate-700/50"
                    >
                        <Briefcase class="w-5 h-5 text-violet-400" />
                        <h2 class="text-lg font-bold text-slate-100">
                            Expert Information
                        </h2>
                    </div>

                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Professional Title -->
                            <div>
                                <label
                                    class="block text-xs font-semibold text-slate-400 uppercase mb-2"
                                    >Professional Title</label
                                >
                                <input
                                    v-model="form.title"
                                    type="text"
                                    class="w-full px-4 py-2.5 bg-slate-900/50 border border-slate-700 rounded-lg text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500"
                                    placeholder="e.g. Senior Solutions Architect"
                                />
                                <div
                                    v-if="form.errors.title"
                                    class="text-xs text-red-400 mt-1"
                                >
                                    {{ form.errors.title }}
                                </div>
                            </div>

                            <!-- Hourly Rate -->
                            <div>
                                <label
                                    class="block text-xs font-semibold text-slate-400 uppercase mb-2"
                                    >Hourly Rate (IDR)</label
                                >
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                                    >
                                        <span class="text-slate-500 text-sm"
                                            >Rp</span
                                        >
                                    </div>
                                    <input
                                        v-model="form.price"
                                        type="number"
                                        min="0"
                                        class="w-full pl-12 pr-4 py-2.5 bg-slate-900/50 border border-slate-700 rounded-lg text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500"
                                        placeholder="0.00"
                                    />
                                </div>
                                <div
                                    v-if="form.errors.price"
                                    class="text-xs text-red-400 mt-1"
                                >
                                    {{ form.errors.price }}
                                </div>
                            </div>
                        </div>

                        <!-- About / Expert Bio -->
                        <div>
                            <label
                                class="block text-xs font-semibold text-slate-400 uppercase mb-2"
                                >About</label
                            >
                            <textarea
                                v-model="form.about"
                                rows="5"
                                class="w-full px-4 py-3 bg-slate-900/50 border border-slate-700 rounded-lg text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500"
                                placeholder="Describe your professional journey and key value proposition..."
                            ></textarea>
                            <div
                                v-if="form.errors.about"
                                class="text-xs text-red-400 mt-1"
                            >
                                {{ form.errors.about }}
                            </div>
                        </div>

                        <!-- Service Type -->
                        <div>
                            <label
                                class="block text-xs font-semibold text-slate-400 uppercase mb-3"
                            >
                                Service Type
                            </label>
                            <div class="flex flex-wrap gap-3">
                                <button
                                    v-for="serviceType in [
                                        'Consulting',
                                        'Implementation',
                                        'Training',
                                        'Development',
                                    ]"
                                    :key="serviceType"
                                    type="button"
                                    @click="toggleServiceType(serviceType)"
                                    class="px-5 py-2.5 rounded-full text-sm font-medium transition-all duration-200"
                                    :class="
                                        form.type.includes(serviceType)
                                            ? 'bg-blue-500/10 text-blue-400 border-2 border-blue-500 hover:bg-blue-500/20'
                                            : 'bg-slate-800/50 text-slate-400 border-2 border-slate-700 hover:border-slate-600 hover:text-slate-300'
                                    "
                                >
                                    {{ serviceType }}
                                </button>
                            </div>
                            <div
                                v-if="form.errors.type"
                                class="text-xs text-red-400 mt-2"
                            >
                                {{ form.errors.type }}
                            </div>
                        </div>

                        <!-- Professional Experience (Repeater) -->
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <label
                                    class="block text-xs font-semibold text-slate-400 uppercase"
                                    >Professional Experience</label
                                >
                                <button
                                    type="button"
                                    @click="addExperience"
                                    class="px-3 py-1 text-xs bg-blue-500 hover:bg-blue-600 text-white rounded transition-colors"
                                >
                                    + Add Experience
                                </button>
                            </div>

                            <div
                                v-if="form.experiences.length === 0"
                                class="text-sm text-slate-500 italic border border-dashed border-slate-700 rounded-lg p-4 text-center"
                            >
                                No experience added yet. Click "+ Add
                                Experience" to start.
                            </div>

                            <div class="space-y-4">
                                <div
                                    v-for="(exp, index) in form.experiences"
                                    :key="index"
                                    class="p-4 bg-slate-900/50 border border-slate-700 rounded-lg space-y-3"
                                >
                                    <div
                                        class="flex items-center justify-between mb-2"
                                    >
                                        <span
                                            class="text-xs font-semibold text-slate-400"
                                            >Experience #{{ index + 1 }}</span
                                        >
                                        <button
                                            type="button"
                                            @click="removeExperience(index)"
                                            class="text-xs text-red-400 hover:text-red-300"
                                        >
                                            Remove
                                        </button>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3">
                                        <input
                                            v-model="exp.role"
                                            type="text"
                                            placeholder="Role/Position"
                                            class="px-3 py-2 bg-slate-800 border border-slate-700 rounded text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50"
                                        />
                                        <input
                                            v-model="exp.company"
                                            type="text"
                                            placeholder="Company/Organization"
                                            class="px-3 py-2 bg-slate-800 border border-slate-700 rounded text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50"
                                        />
                                    </div>
                                    <input
                                        v-model="exp.duration"
                                        type="text"
                                        placeholder="Duration (e.g. 2020 - 2023)"
                                        class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50"
                                    />
                                    <textarea
                                        v-model="exp.description"
                                        rows="2"
                                        placeholder="Brief description of responsibilities..."
                                        class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50"
                                    ></textarea>
                                </div>
                            </div>
                            <div
                                v-if="form.errors.experiences"
                                class="text-xs text-red-400 mt-1"
                            >
                                {{ form.errors.experiences }}
                            </div>
                        </div>

                        <!-- Licenses & Certifications (Repeater with File Upload) -->
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <label
                                    class="block text-xs font-semibold text-slate-400 uppercase"
                                    >Licenses & Certifications</label
                                >
                                <button
                                    type="button"
                                    @click="addLicense"
                                    class="px-3 py-1 text-xs bg-blue-500 hover:bg-blue-600 text-white rounded transition-colors"
                                >
                                    + Add License
                                </button>
                            </div>

                            <div
                                v-if="form.licenses.length === 0"
                                class="text-sm text-slate-500 italic border border-dashed border-slate-700 rounded-lg p-4 text-center"
                            >
                                No licenses added yet. Click "+ Add License" to
                                start.
                            </div>

                            <div class="space-y-3">
                                <div
                                    v-for="(license, index) in form.licenses"
                                    :key="index"
                                    class="p-4 bg-slate-900/50 border border-slate-700 rounded-lg"
                                >
                                    <div
                                        class="flex items-center justify-between mb-3"
                                    >
                                        <span
                                            class="text-xs font-semibold text-slate-400"
                                            >License #{{ index + 1 }}</span
                                        >
                                        <button
                                            type="button"
                                            @click="removeLicense(index)"
                                            class="text-xs text-red-400 hover:text-red-300"
                                        >
                                            Remove
                                        </button>
                                    </div>

                                    <div class="space-y-3">
                                        <input
                                            v-model="license.certification"
                                            type="text"
                                            placeholder="Certification Name (e.g. PMP, AWS Certified Solutions Architect)"
                                            class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50"
                                        />

                                        <div>
                                            <label
                                                class="block text-xs text-slate-500 mb-1"
                                                >Upload Certificate (PDF, JPG,
                                                PNG - Max 5MB)</label
                                            >
                                            <input
                                                type="file"
                                                @change="
                                                    handleLicenseFile(
                                                        index,
                                                        $event
                                                    )
                                                "
                                                accept=".pdf,.jpg,.jpeg,.png"
                                                class="w-full text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-500 file:text-white hover:file:bg-blue-600 file:cursor-pointer"
                                            />
                                            <div
                                                v-if="license.file"
                                                class="text-xs text-green-400 mt-1"
                                            >
                                                📎 {{ license.file.name }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                v-if="form.errors.licenses"
                                class="text-xs text-red-400 mt-1"
                            >
                                {{ form.errors.licenses }}
                            </div>
                        </div>

                        <!-- Portfolio Gallery (Multiple File Upload) -->
                        <div>
                            <label
                                class="block text-xs font-semibold text-slate-400 uppercase mb-3"
                                >Portfolio Gallery</label
                            >

                            <div
                                class="border-2 border-dashed border-slate-700 rounded-lg p-6 text-center hover:border-blue-500 transition-colors"
                            >
                                <input
                                    type="file"
                                    @change="handleGalleryFiles"
                                    accept="image/*"
                                    multiple
                                    class="hidden"
                                    id="gallery-upload"
                                />
                                <label
                                    for="gallery-upload"
                                    class="cursor-pointer"
                                >
                                    <div class="text-slate-400 mb-2">
                                        <svg
                                            class="w-12 h-12 mx-auto mb-2"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                            ></path>
                                        </svg>
                                    </div>
                                    <p
                                        class="text-sm text-slate-300 font-medium"
                                    >
                                        Click to upload images
                                    </p>
                                    <p class="text-xs text-slate-500 mt-1">
                                        SVG, PNG, JPG or GIF (max. 5MB each)
                                    </p>
                                </label>
                            </div>

                            <!-- Gallery Preview -->
                            <div
                                v-if="form.gallerys.length > 0"
                                class="mt-4 grid grid-cols-3 gap-3"
                            >
                                <div
                                    v-for="(gallery, index) in form.gallerys"
                                    :key="index"
                                    class="relative group"
                                >
                                    <img
                                        v-if="gallery.file"
                                        :src="URL.createObjectURL(gallery.file)"
                                        class="w-full h-24 object-cover rounded-lg border border-slate-700"
                                    />
                                    <button
                                        type="button"
                                        @click="removeGallery(index)"
                                        class="absolute top-1 right-1 bg-red-500 hover:bg-red-600 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity"
                                    >
                                        <svg
                                            class="w-4 h-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"
                                            ></path>
                                        </svg>
                                    </button>
                                    <div
                                        class="text-xs text-slate-500 mt-1 truncate"
                                    >
                                        {{ gallery.file?.name }}
                                    </div>
                                </div>
                            </div>
                            <div
                                v-if="form.errors.gallerys"
                                class="text-xs text-red-400 mt-2"
                            >
                                {{ form.errors.gallerys }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END LEFT COLUMN -->

            <!-- RIGHT COLUMN - Skills & Performance -->
            <div class="space-y-6">
                <!-- Skills Selection Card -->
                <div
                    class="bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700/50 p-6"
                >
                    <div class="mb-6">
                        <h2 class="text-lg font-bold text-slate-100 mb-1">
                            Select Your Skills
                        </h2>
                        <p class="text-xs text-slate-400">
                            Choose categories that match your expertise
                        </p>
                    </div>

                    <!-- Hierarchical Skills Selection -->
                    <div class="space-y-2 max-h-[600px] overflow-y-auto pr-2">
                        <!-- Category Level -->
                        <div
                            v-for="category in categories"
                            :key="category.id"
                            class="border border-slate-700 rounded-lg overflow-hidden"
                        >
                            <!-- Category Header -->
                            <button
                                type="button"
                                @click="
                                    expandedCategories[category.id] =
                                        !expandedCategories[category.id]
                                "
                                class="w-full px-4 py-3 bg-slate-800/70 hover:bg-slate-700/70 flex items-center justify-between transition-colors"
                            >
                                <span
                                    class="text-sm font-semibold text-slate-200"
                                    >{{ category.name }}</span
                                >
                                <ChevronDown
                                    v-if="expandedCategories[category.id]"
                                    class="w-4 h-4 text-slate-400"
                                />
                                <ChevronRight
                                    v-else
                                    class="w-4 h-4 text-slate-400"
                                />
                            </button>

                            <!-- SubCategory Level -->
                            <div
                                v-if="expandedCategories[category.id]"
                                class="bg-slate-900/30 p-2 space-y-2"
                            >
                                <div
                                    v-for="subCategory in category.sub_categories"
                                    :key="subCategory.id"
                                    class="border border-slate-700/50 rounded-lg overflow-hidden"
                                >
                                    <!-- SubCategory Header -->
                                    <button
                                        type="button"
                                        @click="
                                            expandedSubCategories[
                                                subCategory.id
                                            ] =
                                                !expandedSubCategories[
                                                    subCategory.id
                                                ]
                                        "
                                        class="w-full px-3 py-2 bg-slate-800/50 hover:bg-slate-700/50 flex items-center justify-between transition-colors"
                                    >
                                        <span
                                            class="text-xs font-medium text-slate-300"
                                            >{{ subCategory.name }}</span
                                        >
                                        <ChevronDown
                                            v-if="
                                                expandedSubCategories[
                                                    subCategory.id
                                                ]
                                            "
                                            class="w-3 h-3 text-slate-500"
                                        />
                                        <ChevronRight
                                            v-else
                                            class="w-3 h-3 text-slate-500"
                                        />
                                    </button>

                                    <!-- Skills Level -->
                                    <div
                                        v-if="
                                            expandedSubCategories[
                                                subCategory.id
                                            ]
                                        "
                                        class="p-3 space-y-2"
                                    >
                                        <div
                                            v-for="skill in subCategory.skills"
                                            :key="skill.id"
                                            class="flex items-center"
                                        >
                                            <input
                                                :id="`skill-${skill.id}`"
                                                type="checkbox"
                                                :value="skill.id"
                                                v-model="form.selected_skills"
                                                class="w-4 h-4 text-blue-500 bg-slate-900 border-slate-600 rounded focus:ring-blue-500 focus:ring-2"
                                            />
                                            <label
                                                :for="`skill-${skill.id}`"
                                                class="ml-3 text-xs text-slate-300 cursor-pointer hover:text-slate-100"
                                            >
                                                {{ skill.name }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        v-if="form.selected_skills.length > 0"
                        class="mt-4 pt-4 border-t border-slate-700"
                    >
                        <div class="text-xs text-slate-400 mb-2">
                            {{ form.selected_skills.length }} skills selected
                        </div>
                    </div>
                    <div
                        v-if="form.errors.selected_skills"
                        class="text-xs text-red-400 mt-2"
                    >
                        {{ form.errors.selected_skills }}
                    </div>
                </div>

                <!-- Expert Status & Metrics -->
                <div
                    class="bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700/50 p-6"
                >
                    <h3 class="text-sm font-bold text-slate-100 mb-4">
                        Status & Performance
                    </h3>

                    <!-- Status Badge -->
                    <div class="mb-4">
                        <div class="text-xs text-slate-500 uppercase mb-2">
                            Current Status
                        </div>
                        <div
                            class="px-3 py-2 rounded-lg border text-sm font-medium text-center"
                            :class="{
                                'bg-green-500/20 text-green-400 border-green-500/30':
                                    expert.status === 'active',
                                'bg-yellow-500/20 text-yellow-400 border-yellow-500/30':
                                    expert.status === 'pending',
                                'bg-slate-500/20 text-slate-400 border-slate-500/30':
                                    expert.status === 'inactive',
                            }"
                        >
                            {{
                                expert.status.charAt(0).toUpperCase() +
                                expert.status.slice(1)
                            }}
                        </div>
                    </div>

                    <!-- Metrics -->
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-400"
                                >Total Appointments</span
                            >
                            <span
                                class="text-sm font-semibold text-slate-200"
                                >{{ expert.stats.total_appointments }}</span
                            >
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-400"
                                >Average Rating</span
                            >
                            <span class="text-sm font-semibold text-slate-200"
                                >{{ expert.stats.rating }} / 5.0</span
                            >
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-400"
                                >Completion Rate</span
                            >
                            <span
                                class="text-sm font-semibold text-slate-200"
                                >{{ expert.stats.completion_rate }}</span
                            >
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-slate-700">
                        <div class="text-xs text-slate-500">
                            Joined: {{ expert.joined_date }}
                        </div>
                    </div>
                </div>
            </div>
            <!-- END RIGHT COLUMN -->
        </div>
    </form>
</template>
