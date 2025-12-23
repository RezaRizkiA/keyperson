<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import {
    ArrowLeft,
    User,
    Mail,
    Phone,
    MapPin,
    Building2,
    Briefcase,
    Globe,
    FileText,
    Award,
    Save,
    ChevronDown,
    ChevronRight,
    Upload,
} from "lucide-vue-next";
import { ref } from "vue";

defineOptions({ layout: DashboardLayout });

const props = defineProps({
    client: Object,
    categories: Array,
});

// Form setup
const form = useForm({
    first_name: props.client.first_name || "",
    last_name: props.client.last_name || "",
    email: props.client.email || "",
    phone: props.client.phone || "",
    address: props.client.address || "",
    company_name: props.client.company_name || "",
    industry: props.client.industry || "",
    website: props.client.website || "",
    employee_count: props.client.employee_count || "",
    about: props.client.about || "",
    selected_skills: props.client.selected_skills || [],
    logo: null,
    cover_image: null,
});

// Submit update
const submit = () => {
    form.put(route("dashboard.clients.update", props.client.id), {
        preserveScroll: true,
        forceFormData: true,
    });
};

// Expand/collapse for categories
const expandedCategories = ref({});
const expandedSubCategories = ref({});

// File upload handlers
const logoPreview = ref(null);
const coverPreview = ref(null);

const handleLogoUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.logo = file;
        logoPreview.value = URL.createObjectURL(file);
    }
};

const handleCoverUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.cover_image = file;
        coverPreview.value = URL.createObjectURL(file);
    }
};
</script>

<template>
    <Head :title="`Edit Client - ${client.company_name}`" />

    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-100">
                    Client Profile
                </h1>
                <p class="text-sm text-slate-400 mt-1">
                    Update client company information and requirements
                </p>
            </div>
            <div class="flex items-center gap-3">
                <Link
                    :href="route('dashboard.clients.index')"
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
            <!-- LEFT COLUMN - Personal & Company Information -->
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
                                Contact Person Information
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
                                placeholder="John"
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
                                placeholder="Doe"
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
                                    placeholder="john.doe@company.com"
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
                                    placeholder="+62 xxx-xxxx-xxxx"
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
                                placeholder="Jakarta, Indonesia"
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

                <!-- Company Information Card -->
                <div
                    class="bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700/50 p-6"
                >
                    <div
                        class="flex items-center gap-2 mb-6 pb-4 border-b border-slate-700/50"
                    >
                        <Building2 class="w-5 h-5 text-violet-400" />
                        <h2 class="text-lg font-bold text-slate-100">
                            Company Information
                        </h2>
                    </div>

                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Company Name -->
                            <div>
                                <label
                                    class="block text-xs font-semibold text-slate-400 uppercase mb-2"
                                    >Company Name *</label
                                >
                                <input
                                    v-model="form.company_name"
                                    type="text"
                                    class="w-full px-4 py-2.5 bg-slate-900/50 border border-slate-700 rounded-lg text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500"
                                    placeholder="e.g. Tech Corp Indonesia"
                                />
                                <div
                                    v-if="form.errors.company_name"
                                    class="text-xs text-red-400 mt-1"
                                >
                                    {{ form.errors.company_name }}
                                </div>
                            </div>

                            <!-- Industry -->
                            <div>
                                <label
                                    class="block text-xs font-semibold text-slate-400 uppercase mb-2"
                                    >Industry</label
                                >
                                <input
                                    v-model="form.industry"
                                    type="text"
                                    class="w-full px-4 py-2.5 bg-slate-900/50 border border-slate-700 rounded-lg text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500"
                                    placeholder="e.g. Technology, Healthcare"
                                />
                                <div
                                    v-if="form.errors.industry"
                                    class="text-xs text-red-400 mt-1"
                                >
                                    {{ form.errors.industry }}
                                </div>
                            </div>

                            <!-- Website -->
                            <div>
                                <label
                                    class="block text-xs font-semibold text-slate-400 uppercase mb-2"
                                    >Website</label
                                >
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                                    >
                                        <Globe class="w-4 h-4 text-slate-500" />
                                    </div>
                                    <input
                                        v-model="form.website"
                                        type="url"
                                        class="w-full pl-10 pr-4 py-2.5 bg-slate-900/50 border border-slate-700 rounded-lg text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500"
                                        placeholder="https://www.company.com"
                                    />
                                </div>
                                <div
                                    v-if="form.errors.website"
                                    class="text-xs text-red-400 mt-1"
                                >
                                    {{ form.errors.website }}
                                </div>
                            </div>

                            <!-- Employee Count -->
                            <div>
                                <label
                                    class="block text-xs font-semibold text-slate-400 uppercase mb-2"
                                    >Employee Count</label
                                >
                                <input
                                    v-model="form.employee_count"
                                    type="text"
                                    class="w-full px-4 py-2.5 bg-slate-900/50 border border-slate-700 rounded-lg text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500"
                                    placeholder="e.g. 50-100, 100+"
                                />
                                <div
                                    v-if="form.errors.employee_count"
                                    class="text-xs text-red-400 mt-1"
                                >
                                    {{ form.errors.employee_count }}
                                </div>
                            </div>
                        </div>

                        <!-- About Company -->
                        <div>
                            <label
                                class="block text-xs font-semibold text-slate-400 uppercase mb-2"
                                >About Company</label
                            >
                            <textarea
                                v-model="form.about"
                                rows="5"
                                class="w-full px-4 py-3 bg-slate-900/50 border border-slate-700 rounded-lg text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500"
                                placeholder="Describe your company, mission, and what you're looking for in experts..."
                            ></textarea>
                            <div
                                v-if="form.errors.about"
                                class="text-xs text-red-400 mt-1"
                            >
                                {{ form.errors.about }}
                            </div>
                        </div>

                        <!-- Logo Upload -->
                        <div>
                            <label
                                class="block text-xs font-semibold text-slate-400 uppercase mb-2"
                                >Company Logo</label
                            >
                            <div
                                class="border-2 border-dashed border-slate-700 rounded-lg p-4 text-center hover:border-blue-500 transition-colors"
                            >
                                <input
                                    type="file"
                                    @change="handleLogoUpload"
                                    accept="image/*"
                                    class="hidden"
                                    id="logo-upload"
                                />
                                <label
                                    for="logo-upload"
                                    class="cursor-pointer flex flex-col items-center"
                                >
                                    <div
                                        v-if="logoPreview || client.logo"
                                        class="mb-3"
                                    >
                                        <img
                                            :src="logoPreview || client.logo"
                                            class="w-24 h-24 object-cover rounded-lg border border-slate-700"
                                        />
                                    </div>
                                    <Upload
                                        class="w-8 h-8 text-slate-400 mb-2"
                                    />
                                    <p class="text-xs text-slate-400">
                                        Click to upload logo (max 5MB)
                                    </p>
                                </label>
                            </div>
                            <div
                                v-if="form.errors.logo"
                                class="text-xs text-red-400 mt-1"
                            >
                                {{ form.errors.logo }}
                            </div>
                        </div>

                        <!-- Cover Image Upload -->
                        <div>
                            <label
                                class="block text-xs font-semibold text-slate-400 uppercase mb-2"
                                >Cover Image</label
                            >
                            <div
                                class="border-2 border-dashed border-slate-700 rounded-lg p-4 text-center hover:border-blue-500 transition-colors"
                            >
                                <input
                                    type="file"
                                    @change="handleCoverUpload"
                                    accept="image/*"
                                    class="hidden"
                                    id="cover-upload"
                                />
                                <label
                                    for="cover-upload"
                                    class="cursor-pointer flex flex-col items-center"
                                >
                                    <div
                                        v-if="
                                            coverPreview || client.cover_image
                                        "
                                        class="mb-3"
                                    >
                                        <img
                                            :src="
                                                coverPreview ||
                                                client.cover_image
                                            "
                                            class="w-full h-32 object-cover rounded-lg border border-slate-700"
                                        />
                                    </div>
                                    <Upload
                                        class="w-8 h-8 text-slate-400 mb-2"
                                    />
                                    <p class="text-xs text-slate-400">
                                        Click to upload cover (max 5MB)
                                    </p>
                                </label>
                            </div>
                            <div
                                v-if="form.errors.cover_image"
                                class="text-xs text-red-400 mt-1"
                            >
                                {{ form.errors.cover_image }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END LEFT COLUMN -->

            <!-- RIGHT COLUMN - Skills & Status -->
            <div class="space-y-6">
                <!-- Skills Selection Card -->
                <div
                    class="bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700/50 p-6"
                >
                    <div class="mb-6">
                        <h2 class="text-lg font-bold text-slate-100 mb-1">
                            Required Skills
                        </h2>
                        <p class="text-xs text-slate-400">
                            Select expertise areas you need
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

                <!-- Client Status & Metrics -->
                <div
                    class="bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700/50 p-6"
                >
                    <h3 class="text-sm font-bold text-slate-100 mb-4">
                        Status & Activity
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
                                    client.status === 'active',
                                'bg-blue-500/20 text-blue-400 border-blue-500/30':
                                    client.status === 'new',
                                'bg-slate-500/20 text-slate-400 border-slate-500/30':
                                    client.status === 'inactive',
                            }"
                        >
                            {{
                                client.status.charAt(0).toUpperCase() +
                                client.status.slice(1)
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
                                >{{ client.stats.total_appointments }}</span
                            >
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-400"
                                >Total Spent</span
                            >
                            <span
                                class="text-sm font-semibold text-slate-200"
                                >{{ client.stats.total_spent }}</span
                            >
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-slate-700">
                        <div class="text-xs text-slate-500">
                            Joined: {{ client.joined_date }}
                        </div>
                    </div>
                </div>
            </div>
            <!-- END RIGHT COLUMN -->
        </div>
    </form>
</template>
