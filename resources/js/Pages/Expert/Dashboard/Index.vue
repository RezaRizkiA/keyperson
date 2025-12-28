<script setup>
import { Head, Link } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import {
    Calendar,
    Clock,
    AlertCircle,
    Wallet,
    ArrowRight,
    TrendingUp,
    Users,
} from "lucide-vue-next";

// Menggunakan DashboardLayout
defineOptions({ layout: DashboardLayout });

// Menerima props 'stats' dari Controller
const props = defineProps({
    stats: Object,
});

// Helper untuk format Rupiah
const formatCurrency = (amount) => {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount || 0);
};
</script>

<template>
    <Head title="Expert Dashboard" />

    <div class="space-y-8">
        <div
            class="flex flex-col sm:flex-row sm:items-center justify-between gap-4"
        >
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">
                    Expert Overview
                </h1>
                <p class="text-slate-500 dark:text-slate-400 mt-1">
                    Welcome back! Here's what's happening with your sessions
                    today.
                </p>
            </div>

            <Link
                :href="route('dashboard.appointments.index')"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-violet-600 hover:bg-violet-700 text-white text-sm font-bold rounded-xl transition-all shadow-sm shadow-violet-500/20"
            >
                <Calendar class="w-4 h-4" />
                Manage Schedule
            </Link>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Action Needed -->
            <div
                class="p-6 rounded-2xl border shadow-sm transition-all duration-300 hover:shadow-md group relative overflow-hidden"
                :class="
                    stats.need_confirmation > 0
                        ? 'bg-orange-50 dark:bg-orange-500/10 border-orange-200 dark:border-orange-500/30 ring-1 ring-orange-100 dark:ring-orange-500/20'
                        : 'bg-white dark:bg-slate-800/50 border-slate-200 dark:border-slate-700/50'
                "
            >
                <div class="flex justify-between items-start z-10 relative">
                    <div>
                        <p
                            class="text-sm font-bold uppercase tracking-wider"
                            :class="
                                stats.need_confirmation > 0
                                    ? 'text-orange-600 dark:text-orange-400'
                                    : 'text-slate-500 dark:text-slate-400'
                            "
                        >
                            Action Needed
                        </p>
                        <h3
                            class="text-3xl font-extrabold text-slate-900 dark:text-white mt-2"
                        >
                            {{ stats.need_confirmation }}
                        </h3>
                        <p
                            class="text-xs text-slate-400 dark:text-slate-500 mt-1"
                        >
                            Pending Requests
                        </p>
                    </div>
                    <div
                        class="p-3 rounded-xl"
                        :class="
                            stats.need_confirmation > 0
                                ? 'bg-orange-100 dark:bg-orange-500/20 text-orange-600 dark:text-orange-400'
                                : 'bg-slate-100 dark:bg-slate-700 text-slate-400 dark:text-slate-500'
                        "
                    >
                        <AlertCircle class="w-6 h-6" />
                    </div>
                </div>

                <Link
                    v-if="stats.need_confirmation > 0"
                    :href="
                        route('dashboard.appointments.index', {
                            status: 'need_confirmation',
                        })
                    "
                    class="absolute inset-0 z-20"
                    aria-label="View pending appointments"
                >
                </Link>
            </div>

            <!-- Upcoming -->
            <div
                class="p-6 rounded-2xl shadow-sm transition-all duration-300 hover:shadow-md group bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50"
            >
                <div class="flex justify-between items-start">
                    <div>
                        <p
                            class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider"
                        >
                            Upcoming
                        </p>
                        <h3
                            class="text-3xl font-extrabold text-slate-900 dark:text-white mt-2"
                        >
                            {{ stats.upcoming_appointments }}
                        </h3>
                        <p
                            class="text-xs text-slate-400 dark:text-slate-500 mt-1"
                        >
                            Confirmed & Progress
                        </p>
                    </div>
                    <div
                        class="p-3 bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 rounded-xl group-hover:scale-110 transition-transform"
                    >
                        <Clock class="w-6 h-6" />
                    </div>
                </div>
            </div>

            <!-- Total Revenue -->
            <div
                class="p-6 rounded-2xl shadow-sm transition-all duration-300 hover:shadow-md group bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50"
            >
                <div class="flex justify-between items-start">
                    <div>
                        <p
                            class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider"
                        >
                            Total Revenue
                        </p>
                        <h3
                            class="text-3xl font-extrabold text-slate-900 dark:text-white mt-2 truncate max-w-[180px]"
                            :title="formatCurrency(stats.total_revenue)"
                        >
                            {{ formatCurrency(stats.total_revenue) }}
                        </h3>
                        <p
                            class="text-xs text-slate-400 dark:text-slate-500 mt-1"
                        >
                            Paid Transactions
                        </p>
                    </div>
                    <div
                        class="p-3 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 rounded-xl group-hover:scale-110 transition-transform"
                    >
                        <Wallet class="w-6 h-6" />
                    </div>
                </div>
            </div>

            <!-- Total Sessions -->
            <div
                class="p-6 rounded-2xl shadow-sm transition-all duration-300 hover:shadow-md group bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50"
            >
                <div class="flex justify-between items-start">
                    <div>
                        <p
                            class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider"
                        >
                            Total Sessions
                        </p>
                        <h3
                            class="text-3xl font-extrabold text-slate-900 dark:text-white mt-2"
                        >
                            {{ stats.total_appointments }}
                        </h3>
                        <p
                            class="text-xs text-slate-400 dark:text-slate-500 mt-1"
                        >
                            All time history
                        </p>
                    </div>
                    <div
                        class="p-3 bg-violet-50 dark:bg-violet-500/10 text-violet-600 dark:text-violet-400 rounded-xl group-hover:scale-110 transition-transform"
                    >
                        <TrendingUp class="w-6 h-6" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div
            v-if="stats.total_appointments === 0"
            class="rounded-2xl p-8 text-center bg-violet-50 dark:bg-violet-500/10 border border-violet-100 dark:border-violet-500/20"
        >
            <div
                class="mx-auto w-16 h-16 rounded-full flex items-center justify-center mb-4 shadow-sm bg-white dark:bg-slate-800 text-violet-600 dark:text-violet-400"
            >
                <Calendar class="w-8 h-8" />
            </div>
            <h3 class="text-lg font-bold text-violet-900 dark:text-violet-300">
                You are ready to start!
            </h3>
            <p class="text-slate-600 dark:text-slate-400 mt-2 max-w-md mx-auto">
                Your profile is active. Once clients start booking your
                sessions, they will appear right here.
            </p>
        </div>
    </div>
</template>
