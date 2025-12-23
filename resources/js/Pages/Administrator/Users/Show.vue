<script setup>
import { Head, Link } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import {
    ArrowLeft,
    User,
    Mail,
    Phone,
    MapPin,
    ShieldCheck,
    Calendar,
    Edit,
    Trash2,
    Clock,
    CheckCircle2,
    XCircle,
    Link as LinkIcon,
} from "lucide-vue-next";

defineOptions({ layout: DashboardLayout });

const props = defineProps({
    user: Object,
});

// Role badge colors
const getRoleColor = (role) => {
    const colors = {
        administrator: "bg-purple-500/20 text-purple-400 border-purple-500/30",
        expert: "bg-blue-500/20 text-blue-400 border-blue-500/30",
        client: "bg-green-500/20 text-green-400 border-green-500/30",
        user: "bg-slate-500/20 text-slate-400 border-slate-500/30",
    };
    return colors[role] || colors.user;
};
</script>

<template>
    <Head :title="`User - ${user.name}`" />

    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <Link
                    :href="route('dashboard.users.index')"
                    class="p-2 hover:bg-slate-800 rounded-lg transition-colors"
                >
                    <ArrowLeft class="w-5 h-5 text-slate-400" />
                </Link>
                <div>
                    <h1 class="text-3xl font-bold text-slate-100">
                        User Details
                    </h1>
                    <p class="text-sm text-slate-400 mt-1">
                        View and manage user information
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <Link
                    :href="route('dashboard.users.edit', user.id)"
                    class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg transition-colors flex items-center gap-2"
                >
                    <Edit class="w-4 h-4" />
                    <span>Edit</span>
                </Link>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Left Column - User Profile -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Profile Card -->
            <div
                class="bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700/50 p-6"
            >
                <div class="flex items-start gap-6">
                    <img
                        :src="user.picture"
                        :alt="user.name"
                        class="w-24 h-24 rounded-full object-cover border-4 border-slate-700"
                    />
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-slate-100 mb-2">
                            {{ user.name }}
                        </h2>
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span
                                v-for="role in user.roles"
                                :key="role"
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold border"
                                :class="getRoleColor(role)"
                            >
                                {{
                                    role.charAt(0).toUpperCase() + role.slice(1)
                                }}
                            </span>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="flex items-center gap-2 text-slate-300">
                                <Mail class="w-4 h-4 text-slate-500" />
                                <span class="text-sm">{{ user.email }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-slate-300">
                                <Phone class="w-4 h-4 text-slate-500" />
                                <span class="text-sm">{{ user.phone }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div
                class="bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700/50 p-6"
            >
                <h3
                    class="text-lg font-bold text-slate-100 mb-4 flex items-center gap-2"
                >
                    <User class="w-5 h-5 text-blue-400" />
                    Contact Information
                </h3>
                <div class="space-y-4">
                    <div>
                        <label
                            class="text-xs font-semibold text-slate-400 uppercase"
                            >Email</label
                        >
                        <p class="text-sm text-slate-200 mt-1">
                            {{ user.email }}
                        </p>
                    </div>
                    <div>
                        <label
                            class="text-xs font-semibold text-slate-400 uppercase"
                            >Phone</label
                        >
                        <p class="text-sm text-slate-200 mt-1">
                            {{ user.phone }}
                        </p>
                    </div>
                    <div>
                        <label
                            class="text-xs font-semibold text-slate-400 uppercase"
                            >Address</label
                        >
                        <p class="text-sm text-slate-200 mt-1">
                            {{ user.address }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Related Information -->
            <div
                v-if="user.client || user.expert"
                class="bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700/50 p-6"
            >
                <h3 class="text-lg font-bold text-slate-100 mb-4">
                    Related Account
                </h3>

                <!-- Client Info -->
                <div v-if="user.client" class="space-y-3">
                    <h4 class="text-sm font-semibold text-green-400">
                        Client Account
                    </h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs text-slate-400"
                                >Company</label
                            >
                            <p class="text-sm text-slate-200">
                                {{ user.client.company_name }}
                            </p>
                        </div>
                        <div>
                            <label class="text-xs text-slate-400"
                                >Industry</label
                            >
                            <p class="text-sm text-slate-200">
                                {{ user.client.industry || "-" }}
                            </p>
                        </div>
                        <div v-if="user.client.website" class="col-span-2">
                            <label class="text-xs text-slate-400"
                                >Website</label
                            >
                            <a
                                :href="user.client.website"
                                target="_blank"
                                class="text-sm text-blue-400 hover:underline flex items-center gap-1"
                            >
                                {{ user.client.website }}
                                <LinkIcon class="w-3 h-3" />
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Expert Info -->
                <div
                    v-if="user.expert"
                    class="space-y-3"
                    :class="{ 'mt-6': user.client }"
                >
                    <h4 class="text-sm font-semibold text-blue-400">
                        Expert Account
                    </h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs text-slate-400">Title</label>
                            <p class="text-sm text-slate-200">
                                {{ user.expert.title }}
                            </p>
                        </div>
                        <div>
                            <label class="text-xs text-slate-400"
                                >Hourly Rate</label
                            >
                            <p class="text-sm text-slate-200">
                                {{ user.expert.price }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Appointments -->
            <div
                v-if="
                    user.recent_appointments &&
                    user.recent_appointments.length > 0
                "
                class="bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700/50 p-6"
            >
                <h3 class="text-lg font-bold text-slate-100 mb-4">
                    Recent Appointments
                </h3>
                <div class="space-y-3">
                    <div
                        v-for="appointment in user.recent_appointments"
                        :key="appointment.id"
                        class="flex items-center justify-between p-3 bg-slate-900/50 rounded-lg"
                    >
                        <div>
                            <p class="text-sm font-medium text-slate-200">
                                {{ appointment.date }} at {{ appointment.time }}
                            </p>
                            <p class="text-xs text-slate-400 mt-1">
                                {{ appointment.price }}
                            </p>
                        </div>
                        <span
                            class="px-2 py-1 rounded text-xs font-semibold"
                            :class="{
                                'bg-green-500/20 text-green-400':
                                    appointment.status === 'completed',
                                'bg-blue-500/20 text-blue-400':
                                    appointment.status === 'confirmed',
                                'bg-yellow-500/20 text-yellow-400':
                                    appointment.status === 'pending',
                                'bg-red-500/20 text-red-400':
                                    appointment.status === 'cancelled',
                            }"
                        >
                            {{ appointment.status }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Account Status & Metadata -->
        <div class="space-y-6">
            <!-- Account Status -->
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
                            <XCircle v-else class="w-5 h-5 text-slate-400" />
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

            <!-- Activity Stats -->
            <div
                class="bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700/50 p-6"
            >
                <h3 class="text-lg font-bold text-slate-100 mb-4">Activity</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-400"
                            >Total Appointments</span
                        >
                        <span class="text-lg font-bold text-slate-200">
                            {{ user.appointments_count }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Metadata -->
            <div
                class="bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700/50 p-6"
            >
                <h3 class="text-lg font-bold text-slate-100 mb-4">Metadata</h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-xs text-slate-400">User ID</label>
                        <p class="text-sm text-slate-200">
                            #U-{{ String(user.id).padStart(3, "0") }}
                        </p>
                    </div>
                    <div>
                        <label class="text-xs text-slate-400">Created At</label>
                        <p class="text-sm text-slate-200">
                            {{ user.created_at }}
                        </p>
                    </div>
                    <div>
                        <label class="text-xs text-slate-400">Updated At</label>
                        <p class="text-sm text-slate-200">
                            {{ user.updated_at }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
