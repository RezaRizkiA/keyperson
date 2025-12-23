<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import {
    ArrowLeft,
    User,
    Mail,
    Phone,
    MapPin,
    Upload,
    Save,
    ShieldCheck,
    CheckCircle2,
    XCircle,
} from "lucide-vue-next";
import { ref } from "vue";

defineOptions({ layout: DashboardLayout });

const props = defineProps({
    user: Object,
});

// Form setup
const form = useForm({
    name: props.user.name || "",
    email: props.user.email || "",
    phone: props.user.phone || "",
    address: props.user.address || "",
    roles: props.user.roles || [],
    picture: null,
});

// Submit update
const submit = () => {
    form.put(route("dashboard.users.update", props.user.id), {
        preserveScroll: true,
        forceFormData: true,
    });
};

// File upload handler
const picturePreview = ref(null);

const handlePictureUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.picture = file;
        picturePreview.value = URL.createObjectURL(file);
    }
};

// Available roles
const availableRoles = [
    { value: "administrator", label: "Administrator", color: "purple" },
    { value: "expert", label: "Expert", color: "blue" },
    { value: "client", label: "Client", color: "green" },
    { value: "user", label: "User", color: "slate" },
];
</script>

<template>
    <Head :title="`Edit User - ${user.name}`" />

    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <Link
                    :href="route('dashboard.users.show', user.id)"
                    class="p-2 hover:bg-slate-800 rounded-lg transition-colors"
                >
                    <ArrowLeft class="w-5 h-5 text-slate-400" />
                </Link>
                <div>
                    <h1 class="text-3xl font-bold text-slate-100">Edit User</h1>
                    <p class="text-sm text-slate-400 mt-1">
                        Update user information and roles
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <Link
                    :href="route('dashboard.users.index')"
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
            <!-- Left Column - Personal Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Personal Information Card -->
                <div
                    class="bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700/50 p-6"
                >
                    <div
                        class="flex items-center gap-2 mb-6 pb-4 border-b border-slate-700/50"
                    >
                        <User class="w-5 h-5 text-blue-400" />
                        <h2 class="text-lg font-bold text-slate-100">
                            Personal Information
                        </h2>
                    </div>

                    <div class="space-y-4">
                        <!-- Name -->
                        <div>
                            <label
                                class="block text-xs font-semibold text-slate-400 uppercase mb-2"
                            >
                                Full Name *
                            </label>
                            <input
                                v-model="form.name"
                                type="text"
                                class="w-full px-4 py-2.5 bg-slate-900/50 border border-slate-700 rounded-lg text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500"
                                placeholder="John Doe"
                            />
                            <div
                                v-if="form.errors.name"
                                class="text-xs text-red-400 mt-1"
                            >
                                {{ form.errors.name }}
                            </div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label
                                class="block text-xs font-semibold text-slate-400 uppercase mb-2"
                            >
                                Email Address *
                            </label>
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
                                    placeholder="john.doe@example.com"
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
                            >
                                Phone Number
                            </label>
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

                        <!-- Address -->
                        <div>
                            <label
                                class="block text-xs font-semibold text-slate-400 uppercase mb-2"
                            >
                                Address / Location
                            </label>
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
                </div>

                <!-- Profile Picture Upload -->
                <div
                    class="bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700/50 p-6"
                >
                    <h3 class="text-lg font-bold text-slate-100 mb-4">
                        Profile Picture
                    </h3>
                    <div
                        class="border-2 border-dashed border-slate-700 rounded-lg p-6 text-center hover:border-blue-500 transition-colors"
                    >
                        <input
                            type="file"
                            @change="handlePictureUpload"
                            accept="image/*"
                            class="hidden"
                            id="picture-upload"
                        />
                        <label
                            for="picture-upload"
                            class="cursor-pointer flex flex-col items-center"
                        >
                            <div
                                v-if="picturePreview || user.picture"
                                class="mb-4"
                            >
                                <img
                                    :src="picturePreview || user.picture"
                                    class="w-32 h-32 object-cover rounded-full border-4 border-slate-700"
                                />
                            </div>
                            <Upload class="w-10 h-10 text-slate-400 mb-3" />
                            <p class="text-sm text-slate-300 font-medium mb-1">
                                Click to upload profile picture
                            </p>
                            <p class="text-xs text-slate-500">
                                PNG, JPG up to 5MB
                            </p>
                        </label>
                    </div>
                    <div
                        v-if="form.errors.picture"
                        class="text-xs text-red-400 mt-2"
                    >
                        {{ form.errors.picture }}
                    </div>
                </div>
            </div>

            <!-- Right Column - Roles & Status -->
            <div class="space-y-6">
                <!-- Roles Selection -->
                <div
                    class="bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700/50 p-6"
                >
                    <div class="mb-4">
                        <h3 class="text-lg font-bold text-slate-100 mb-1">
                            User Roles
                        </h3>
                        <p class="text-xs text-slate-400">
                            Select one or more roles for this user
                        </p>
                    </div>

                    <div class="space-y-3">
                        <div
                            v-for="role in availableRoles"
                            :key="role.value"
                            class="flex items-center p-3 bg-slate-900/50 rounded-lg hover:bg-slate-900/70 transition-colors"
                        >
                            <input
                                :id="`role-${role.value}`"
                                type="checkbox"
                                :value="role.value"
                                v-model="form.roles"
                                class="w-4 h-4 text-blue-500 bg-slate-900 border-slate-600 rounded focus:ring-blue-500 focus:ring-2"
                            />
                            <label
                                :for="`role-${role.value}`"
                                class="ml-3 flex-1 cursor-pointer"
                            >
                                <span
                                    class="text-sm font-medium text-slate-200"
                                >
                                    {{ role.label }}
                                </span>
                            </label>
                            <ShieldCheck
                                class="w-4 h-4"
                                :class="`text-${role.color}-400`"
                            />
                        </div>
                    </div>

                    <div
                        v-if="form.errors.roles"
                        class="text-xs text-red-400 mt-3"
                    >
                        {{ form.errors.roles }}
                    </div>

                    <div
                        v-if="form.roles.length > 0"
                        class="mt-4 pt-4 border-t border-slate-700"
                    >
                        <div class="text-xs text-slate-400 mb-2">
                            {{ form.roles.length }} role(s) selected
                        </div>
                    </div>
                </div>

                <!-- Account Status (Display Only) -->
                <div
                    class="bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700/50 p-6"
                >
                    <h3 class="text-lg font-bold text-slate-100 mb-4">
                        Account Status
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-400"
                                >Email Verified</span
                            >
                            <div class="flex items-center gap-2">
                                <CheckCircle2
                                    v-if="user.email_verified"
                                    class="w-5 h-5 text-green-400"
                                />
                                <XCircle v-else class="w-5 h-5 text-red-400" />
                                <span
                                    class="text-sm font-semibold"
                                    :class="
                                        user.email_verified
                                            ? 'text-green-400'
                                            : 'text-red-400'
                                    "
                                >
                                    {{ user.email_verified ? "Yes" : "No" }}
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-400"
                                >Calendar Connected</span
                            >
                            <div class="flex items-center gap-2">
                                <CheckCircle2
                                    v-if="user.calendar_connected"
                                    class="w-5 h-5 text-green-400"
                                />
                                <XCircle
                                    v-else
                                    class="w-5 h-5 text-slate-400"
                                />
                                <span
                                    class="text-sm font-semibold"
                                    :class="
                                        user.calendar_connected
                                            ? 'text-green-400'
                                            : 'text-slate-400'
                                    "
                                >
                                    {{ user.calendar_connected ? "Yes" : "No" }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</template>
