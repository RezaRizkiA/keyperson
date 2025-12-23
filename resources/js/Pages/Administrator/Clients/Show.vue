<script setup>
import { Head, Link } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import {
    ArrowLeft,
    Edit,
    ShieldOff,
    Building2,
    Briefcase,
    Award,
    CheckCircle2,
    Mail,
    Phone,
    Globe,
    MapPin,
} from "lucide-vue-next";

defineOptions({ layout: DashboardLayout });

const props = defineProps({
    client: Object,
});
</script>

<template>
    <Head :title="`${client.name} - Client Detail`" />

    <!-- Header with Back Button -->
    <div class="mb-6 flex items-center justify-between">
        <Link
            :href="route('dashboard.clients.index')"
            class="flex items-center gap-2 text-slate-400 hover:text-slate-200 transition-colors"
        >
            <ArrowLeft class="w-4 h-4" />
            <span class="text-sm font-medium">Back to Clients List</span>
        </Link>

        <div class="flex items-center gap-3">
            <button
                class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 font-medium rounded-lg border border-slate-700 transition-colors flex items-center gap-2"
            >
                <ShieldOff class="w-4 h-4" />
                Suspend
            </button>
            <Link
                :href="route('dashboard.clients.edit', client.id)"
                class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg transition-colors flex items-center gap-2"
            >
                <Edit class="w-4 h-4" />
                Edit Client
            </Link>
        </div>
    </div>

    <!-- Profile Header Card -->
    <div
        class="bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700/50 p-8 mb-6"
    >
        <div class="flex items-start gap-6">
            <!-- Avatar -->
            <div class="relative">
                <div
                    class="w-32 h-32 rounded-2xl border-4 border-blue-500 flex items-center justify-center text-3xl font-bold bg-slate-900 text-blue-400"
                >
                    <img
                        v-if="client.avatar"
                        :src="client.avatar"
                        :alt="client.name"
                        class="w-full h-full rounded-2xl object-cover"
                    />
                    <Building2 v-else class="w-16 h-16" />
                </div>
                <div
                    v-if="client.is_verified"
                    class="absolute -bottom-1 -right-1 px-3 py-1 bg-blue-500 text-white text-xs font-semibold rounded-full flex items-center gap-1"
                >
                    <CheckCircle2 class="w-3 h-3" />
                    VERIFIED
                </div>
            </div>

            <!-- Profile Info -->
            <div class="flex-1">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-100 mb-1">
                            {{ client.company_name }}
                        </h1>
                        <div
                            class="flex items-center gap-3 text-sm text-slate-400 mb-2"
                        >
                            <span class="flex items-center gap-1.5">
                                <Briefcase class="w-4 h-4" />
                                {{ client.industry }}
                            </span>
                            <span>•</span>
                            <span class="flex items-center gap-1.5">
                                <MapPin class="w-4 h-4" />
                                {{ client.location }}
                            </span>
                        </div>
                        <div class="text-sm text-slate-500">
                            Contact Person: {{ client.name }}
                        </div>
                    </div>
                    <div class="text-right">
                        <div
                            class="text-xs font-semibold text-slate-500 uppercase mb-1"
                        >
                            Client ID
                        </div>
                        <div class="text-sm font-mono text-slate-300">
                            {{ client.client_id }}
                        </div>
                    </div>
                </div>

                <p class="text-slate-300 leading-relaxed mb-4">
                    {{ client.description }}
                </p>
            </div>
        </div>

        <!-- Stats Row -->
        <div
            class="grid grid-cols-4 gap-4 mt-6 pt-6 border-t border-slate-700/50"
        >
            <div>
                <div class="text-xs text-slate-500 uppercase mb-1">
                    Total Appointments
                </div>
                <div class="flex items-baseline gap-2">
                    <div class="text-2xl font-bold text-slate-100">
                        {{ client.stats.total_appointments }}
                    </div>
                    <div class="text-xs font-semibold text-green-400">
                        {{ client.stats.appointments_trend }}
                    </div>
                </div>
            </div>
            <div>
                <div class="text-xs text-slate-500 uppercase mb-1">
                    Total Spent
                </div>
                <div class="text-2xl font-bold text-slate-100">
                    {{ client.stats.total_spent }}
                </div>
            </div>
            <div>
                <div class="text-xs text-slate-500 uppercase mb-1">
                    Average Rating
                </div>
                <div class="text-2xl font-bold text-slate-100">
                    {{ client.stats.avg_rating }}
                </div>
            </div>
            <div>
                <div class="text-xs text-slate-500 uppercase mb-1">Status</div>
                <div
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border"
                    :class="{
                        'bg-green-500/20 text-green-400 border-green-500/30':
                            client.status === 'active',
                        'bg-blue-500/20 text-blue-400 border-blue-500/30':
                            client.status === 'new',
                        'bg-slate-500/20 text-slate-400 border-slate-500/30':
                            client.status === 'inactive',
                    }"
                >
                    {{ client.status.toUpperCase() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Company Information -->
            <div
                class="bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700/50 p-6"
            >
                <div class="flex items-center gap-2 mb-6">
                    <Building2 class="w-5 h-5 text-blue-400" />
                    <h2 class="text-lg font-bold text-slate-100">
                        Company Information
                    </h2>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <div class="text-xs text-slate-500 uppercase mb-1">
                            Company Name
                        </div>
                        <div class="text-sm text-slate-200">
                            {{ client.company_name }}
                        </div>
                    </div>

                    <div>
                        <div class="text-xs text-slate-500 uppercase mb-1">
                            Industry
                        </div>
                        <div class="text-sm text-slate-200">
                            {{ client.industry }}
                        </div>
                    </div>

                    <div>
                        <div class="text-xs text-slate-500 uppercase mb-1">
                            Website
                        </div>
                        <div class="text-sm text-blue-400">
                            <a
                                v-if="client.company_info.website !== '-'"
                                :href="client.company_info.website"
                                target="_blank"
                                class="hover:underline"
                            >
                                {{ client.company_info.website }}
                            </a>
                            <span v-else class="text-slate-500">-</span>
                        </div>
                    </div>

                    <div>
                        <div class="text-xs text-slate-500 uppercase mb-1">
                            Company Size
                        </div>
                        <div class="text-sm text-slate-200">
                            {{ client.company_info.size }}
                        </div>
                    </div>

                    <div>
                        <div class="text-xs text-slate-500 uppercase mb-1">
                            Founded Year
                        </div>
                        <div class="text-sm text-slate-200">
                            {{ client.company_info.founded }}
                        </div>
                    </div>

                    <div>
                        <div class="text-xs text-slate-500 uppercase mb-1">
                            Location
                        </div>
                        <div class="text-sm text-slate-200">
                            {{ client.location }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Skills Needed -->
            <div
                class="bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700/50 p-6"
            >
                <div class="flex items-center gap-2 mb-6">
                    <Award class="w-5 h-5 text-violet-400" />
                    <h2 class="text-lg font-bold text-slate-100">
                        Skills & Expertise Needed
                    </h2>
                </div>

                <div
                    v-if="
                        client.skills_needed && client.skills_needed.length > 0
                    "
                    class="flex flex-wrap gap-2"
                >
                    <span
                        v-for="(skill, index) in client.skills_needed"
                        :key="index"
                        class="px-3 py-1.5 bg-violet-500/20 text-violet-300 text-xs font-medium rounded-lg"
                    >
                        {{ skill }}
                    </span>
                </div>
                <div
                    v-else
                    class="text-sm text-slate-500 italic text-center py-4"
                >
                    No specific skills indicated
                </div>
            </div>
        </div>

        <!-- Right Column - Sidebar -->
        <div class="space-y-6">
            <!-- Contact Details -->
            <div
                class="bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700/50 p-6"
            >
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-bold text-slate-100">
                        Contact Details
                    </h2>
                    <button
                        class="text-xs text-blue-400 hover:text-blue-300 font-medium"
                    >
                        Edit
                    </button>
                </div>

                <div class="space-y-4">
                    <div>
                        <div
                            class="flex items-center gap-2 text-xs text-slate-500 uppercase mb-1"
                        >
                            <Mail class="w-3 h-3" />
                            <span>Email</span>
                        </div>
                        <div class="text-sm text-slate-200">
                            {{ client.contact.email }}
                        </div>
                    </div>

                    <div>
                        <div
                            class="flex items-center gap-2 text-xs text-slate-500 uppercase mb-1"
                        >
                            <Phone class="w-3 h-3" />
                            <span>Phone</span>
                        </div>
                        <div class="text-sm text-slate-200">
                            {{ client.contact.phone }}
                        </div>
                    </div>

                    <div>
                        <div
                            class="flex items-center gap-2 text-xs text-slate-500 uppercase mb-1"
                        >
                            <MapPin class="w-3 h-3" />
                            <span>Address</span>
                        </div>
                        <div class="text-sm text-slate-200">
                            {{ client.contact.address }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Metadata -->
            <div
                class="bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700/50 p-6"
            >
                <h2 class="text-lg font-bold text-slate-100 mb-4">
                    System Metadata
                </h2>

                <div class="space-y-3">
                    <div>
                        <div class="text-xs text-slate-500 uppercase mb-1">
                            Joined Date
                        </div>
                        <div class="text-sm text-slate-200">
                            {{ client.metadata.joined_date }}
                        </div>
                    </div>

                    <div>
                        <div class="text-xs text-slate-500 uppercase mb-1">
                            Last Login
                        </div>
                        <div class="text-sm text-slate-200">
                            {{ client.metadata.last_login }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
