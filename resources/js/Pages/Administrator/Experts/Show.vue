<script setup>
import { Head, Link } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import {
    ArrowLeft,
    Edit,
    ShieldOff,
    GraduationCap,
    Stethoscope,
    Award,
    CheckCircle2,
    XCircle,
    Mail,
    Phone,
    Clock,
    Calendar,
} from "lucide-vue-next";

defineOptions({ layout: DashboardLayout });

const props = defineProps({
    expert: Object,
});

// Get rating stars array
const getRatingStars = (rating) => {
    return Array(5)
        .fill(0)
        .map((_, i) => i < rating);
};
</script>

<template>
    <Head :title="`${expert.name} - Expert Detail`" />

    <!-- Header with Back Button -->
    <div class="mb-6 flex items-center justify-between">
        <Link
            :href="route('dashboard.experts.index')"
            class="flex items-center gap-2 text-slate-400 hover:text-slate-200 transition-colors"
        >
            <ArrowLeft class="w-4 h-4" />
            <span class="text-sm font-medium">Back to Experts List</span>
        </Link>

        <div class="flex items-center gap-3">
            <button
                class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 font-medium rounded-lg border border-slate-700 transition-colors flex items-center gap-2"
            >
                <ShieldOff class="w-4 h-4" />
                Suspend
            </button>
            <button
                class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg transition-colors flex items-center gap-2"
            >
                <Edit class="w-4 h-4" />
                Edit Expert
            </button>
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
                        v-if="expert.avatar"
                        :src="expert.avatar"
                        :alt="expert.name"
                        class="w-full h-full rounded-2xl object-cover"
                    />
                    <span v-else>{{
                        expert.name
                            .split(" ")
                            .map((n) => n[0])
                            .join("")
                            .substring(0, 2)
                    }}</span>
                </div>
                <div
                    v-if="expert.is_online"
                    class="absolute -bottom-1 -right-1 px-3 py-1 bg-green-500 text-white text-xs font-semibold rounded-full flex items-center gap-1"
                >
                    <div class="w-2 h-2 bg-white rounded-full"></div>
                    ACTIVE
                </div>
            </div>

            <!-- Profile Info -->
            <div class="flex-1">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-100 mb-1">
                            {{ expert.name }}
                        </h1>
                        <div
                            class="flex items-center gap-3 text-sm text-slate-400 mb-2"
                        >
                            <span class="flex items-center gap-1.5">
                                <Award class="w-4 h-4" />
                                {{ expert.specialization }}
                            </span>
                            <span>•</span>
                            <span>{{ expert.experience }}</span>
                            <span>•</span>
                            <span>{{ expert.location }}</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <div
                            class="text-xs font-semibold text-slate-500 uppercase mb-1"
                        >
                            Expert ID
                        </div>
                        <div class="text-sm font-mono text-slate-300">
                            {{ expert.expert_id }}
                        </div>
                    </div>
                </div>

                <p class="text-slate-300 leading-relaxed mb-4">
                    {{ expert.bio }}
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
                        {{ expert.stats.total_appointments }}
                    </div>
                    <div class="text-xs font-semibold text-green-400">
                        {{ expert.stats.appointments_trend }}
                    </div>
                </div>
            </div>
            <div>
                <div class="text-xs text-slate-500 uppercase mb-1">
                    Client Rating
                </div>
                <div class="flex items-center gap-2">
                    <div class="text-2xl font-bold text-slate-100">
                        {{ expert.stats.rating }}
                    </div>
                    <div class="flex gap-0.5">
                        <template
                            v-for="(filled, index) in getRatingStars(
                                expert.stats.rating_stars
                            )"
                            :key="index"
                        >
                            <svg
                                v-if="filled"
                                class="w-4 h-4 text-yellow-400 fill-current"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"
                                />
                            </svg>
                            <svg
                                v-else
                                class="w-4 h-4 text-slate-600 fill-current"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"
                                />
                            </svg>
                        </template>
                    </div>
                </div>
            </div>
            <div>
                <div class="text-xs text-slate-500 uppercase mb-1">
                    Avg Duration
                </div>
                <div class="text-2xl font-bold text-slate-100">
                    {{ expert.stats.avg_duration }}
                </div>
            </div>
            <div>
                <div class="text-xs text-slate-500 uppercase mb-1">
                    Response Rate
                </div>
                <div class="text-2xl font-bold text-slate-100">
                    {{ expert.stats.response_rate }}
                </div>
            </div>
        </div>
    </div>

    <!-- Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Professional Qualifications -->
            <div
                class="bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700/50 p-6"
            >
                <div class="flex items-center gap-2 mb-6">
                    <GraduationCap class="w-5 h-5 text-blue-400" />
                    <h2 class="text-lg font-bold text-slate-100">
                        Professional Qualifications
                    </h2>
                </div>

                <!-- Education -->
                <div class="mb-6">
                    <h3 class="text-xs font-bold text-slate-400 uppercase mb-3">
                        Education
                    </h3>
                    <div class="space-y-3">
                        <div
                            v-for="(edu, index) in expert.qualifications
                                .education"
                            :key="index"
                            class="flex justify-between"
                        >
                            <div>
                                <div
                                    class="text-sm font-semibold text-slate-200"
                                >
                                    {{ edu.degree }}
                                </div>
                                <div class="text-sm text-blue-400">
                                    {{ edu.institution }}
                                </div>
                            </div>
                            <div class="text-xs text-slate-500">
                                {{ edu.years }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Residency -->
                <div class="mb-6">
                    <h3 class="text-xs font-bold text-slate-400 uppercase mb-3">
                        Residency
                    </h3>
                    <div class="space-y-3">
                        <div
                            v-for="(res, index) in expert.qualifications
                                .residency"
                            :key="index"
                            class="flex justify-between"
                        >
                            <div>
                                <div
                                    class="text-sm font-semibold text-slate-200"
                                >
                                    {{ res.program }}
                                </div>
                                <div class="text-sm text-blue-400">
                                    {{ res.hospital }}
                                </div>
                            </div>
                            <div class="text-xs text-slate-500">
                                {{ res.years }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Certifications & Licenses -->
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase mb-3">
                        Certifications & Licenses
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        <div
                            v-for="(cert, index) in expert.qualifications
                                .certifications"
                            :key="index"
                            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-medium"
                            :class="
                                cert.verified
                                    ? 'bg-green-500/20 text-green-400'
                                    : 'bg-slate-700 text-slate-300'
                            "
                        >
                            <CheckCircle2
                                v-if="cert.verified"
                                class="w-3 h-3"
                            />
                            <span>{{ cert.name }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Expertise & Skills -->
            <div
                class="bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700/50 p-6"
            >
                <div class="flex items-center gap-2 mb-6">
                    <Stethoscope class="w-5 h-5 text-violet-400" />
                    <h2 class="text-lg font-bold text-slate-100">
                        Expertise & Skills
                    </h2>
                </div>

                <!-- Core Specializations -->
                <div class="mb-6">
                    <h3 class="text-xs font-bold text-slate-400 uppercase mb-3">
                        Core Specializations
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        <span
                            v-for="(spec, index) in expert.expertise
                                .core_specializations"
                            :key="index"
                            class="px-3 py-1.5 bg-violet-500/20 text-violet-300 text-xs font-medium rounded-lg"
                        >
                            {{ spec }}
                        </span>
                    </div>
                </div>

                <!-- Languages Spoken -->
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase mb-3">
                        Languages Spoken
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        <span
                            v-for="(lang, index) in expert.expertise.languages"
                            :key="index"
                            class="px-3 py-1.5 bg-slate-700 text-slate-300 text-xs font-medium rounded-lg"
                        >
                            {{ lang }}
                        </span>
                    </div>
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
                            {{ expert.contact.email }}
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
                            {{ expert.contact.phone }}
                        </div>
                    </div>

                    <div>
                        <div
                            class="flex items-center gap-2 text-xs text-slate-500 uppercase mb-1"
                        >
                            <Clock class="w-3 h-3" />
                            <span>Timezone</span>
                        </div>
                        <div class="text-sm text-slate-200">
                            {{ expert.contact.timezone }}
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
                            {{ expert.metadata.joined_date }}
                        </div>
                    </div>

                    <div>
                        <div class="text-xs text-slate-500 uppercase mb-1">
                            Last Login
                        </div>
                        <div class="text-sm text-slate-200">
                            {{ expert.metadata.last_login }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
