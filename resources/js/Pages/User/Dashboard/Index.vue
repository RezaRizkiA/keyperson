<script setup>
import { Head, Link } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import {
    Calendar,
    Clock,
    CreditCard,
    Wallet,
    Search,
    ShoppingBag,
    Building2,
    BadgeCheck,
} from "lucide-vue-next";

defineOptions({ layout: DashboardLayout });

const props = defineProps({
    stats: Object,
});

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
    <Head title="My Dashboard" />

    <div class="space-y-8">
        <!-- Header -->
        <div
            class="flex flex-col sm:flex-row sm:items-center justify-between gap-4"
        >
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">
                    Welcome Back!
                </h1>
                <p class="text-slate-500 dark:text-slate-400 mt-1">
                    Track your learning journey and upcoming sessions.
                </p>
            </div>

            <Link
                :href="route('home')"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-900 dark:bg-blue-600 hover:bg-slate-800 dark:hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-slate-200 dark:shadow-blue-500/20"
            >
                <Search class="w-4 h-4" />
                Find New Expert
            </Link>
        </div>

        <!-- B2B Corporate Badge (if user is corporate member) -->
        <div
            v-if="stats.is_corporate"
            class="p-4 rounded-2xl flex items-center gap-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-500/10 dark:to-indigo-500/10 border border-blue-100 dark:border-blue-500/20"
        >
            <div class="p-3 bg-blue-100 dark:bg-blue-500/20 rounded-xl">
                <Building2 class="w-6 h-6 text-blue-600 dark:text-blue-400" />
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-2">
                    <p class="font-semibold text-slate-900 dark:text-white">
                        {{ stats.company_name }}
                    </p>
                    <span
                        class="px-2 py-0.5 text-xs font-medium bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400 rounded-full uppercase"
                    >
                        {{ stats.company_role || "Employee" }}
                    </span>
                </div>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-0.5">
                    Booking Anda dibayar menggunakan kuota perusahaan
                </p>
            </div>
            <BadgeCheck class="w-8 h-8 text-blue-500 dark:text-blue-400" />
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Unpaid Bookings -->
            <div
                class="relative overflow-hidden p-6 rounded-3xl border shadow-sm transition-all duration-300 hover:shadow-md group"
                :class="
                    stats.pending_payments > 0
                        ? 'bg-yellow-50 dark:bg-yellow-500/10 border-yellow-200 dark:border-yellow-500/30 ring-1 ring-yellow-100 dark:ring-yellow-500/20'
                        : 'bg-white dark:bg-slate-800/50 border-slate-200 dark:border-slate-700/50'
                "
            >
                <div class="flex justify-between items-start relative z-10">
                    <div>
                        <p
                            class="text-sm font-bold uppercase tracking-wider"
                            :class="
                                stats.pending_payments > 0
                                    ? 'text-yellow-700 dark:text-yellow-400'
                                    : 'text-slate-500 dark:text-slate-400'
                            "
                        >
                            {{
                                stats.is_corporate
                                    ? "Pending Approval"
                                    : "Unpaid Booking"
                            }}
                        </p>
                        <h3
                            class="text-3xl font-extrabold text-slate-900 dark:text-white mt-2"
                        >
                            {{ stats.pending_payments }}
                        </h3>
                        <p
                            class="text-xs mt-1"
                            :class="
                                stats.pending_payments > 0
                                    ? 'text-yellow-600 dark:text-yellow-500'
                                    : 'text-slate-400 dark:text-slate-500'
                            "
                        >
                            {{
                                stats.is_corporate
                                    ? "Menunggu konfirmasi"
                                    : "Waiting for payment"
                            }}
                        </p>
                    </div>
                    <div
                        class="p-3 rounded-2xl"
                        :class="
                            stats.pending_payments > 0
                                ? 'bg-yellow-100 dark:bg-yellow-500/20 text-yellow-600 dark:text-yellow-400'
                                : 'bg-slate-50 dark:bg-slate-700 text-slate-400 dark:text-slate-500'
                        "
                    >
                        <CreditCard class="w-6 h-6" />
                    </div>
                </div>

                <Link
                    v-if="stats.pending_payments > 0"
                    :href="route('dashboard.appointments.index')"
                    class="absolute inset-0 z-20"
                ></Link>
            </div>

            <!-- Upcoming Sessions -->
            <div
                class="p-6 rounded-3xl shadow-sm transition-all duration-300 hover:shadow-md group relative overflow-hidden bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50"
            >
                <div
                    class="absolute top-0 right-0 w-24 h-24 bg-blue-50 dark:bg-blue-500/10 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"
                ></div>

                <div class="flex justify-between items-start relative z-10">
                    <div>
                        <p
                            class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider"
                        >
                            Upcoming
                        </p>
                        <h3
                            class="text-3xl font-extrabold text-slate-900 dark:text-white mt-2"
                        >
                            {{ stats.upcoming_sessions }}
                        </h3>
                        <p
                            class="text-xs text-slate-400 dark:text-slate-500 mt-1"
                        >
                            Confirmed Sessions
                        </p>
                    </div>
                    <div
                        class="p-3 bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 rounded-2xl"
                    >
                        <Clock class="w-6 h-6" />
                    </div>
                </div>
            </div>

            <!-- Total Spent -->
            <div
                class="p-6 rounded-3xl shadow-sm transition-all duration-300 hover:shadow-md group relative overflow-hidden bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50"
            >
                <div
                    class="absolute top-0 right-0 w-24 h-24 bg-emerald-50 dark:bg-emerald-500/10 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"
                ></div>

                <div class="flex justify-between items-start relative z-10">
                    <div>
                        <p
                            class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider"
                        >
                            Total Spent
                        </p>
                        <h3
                            class="text-3xl font-extrabold text-slate-900 dark:text-white mt-2 truncate max-w-[180px]"
                            :title="formatCurrency(stats.total_spent)"
                        >
                            {{ formatCurrency(stats.total_spent) }}
                        </h3>
                        <p
                            class="text-xs text-slate-400 dark:text-slate-500 mt-1"
                        >
                            {{
                                stats.is_corporate
                                    ? "Via kuota perusahaan"
                                    : "Investment in yourself"
                            }}
                        </p>
                    </div>
                    <div
                        class="p-3 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 rounded-2xl"
                    >
                        <Wallet class="w-6 h-6" />
                    </div>
                </div>
            </div>

            <!-- Total Bookings -->
            <div
                class="p-6 rounded-3xl shadow-sm transition-all duration-300 hover:shadow-md group relative overflow-hidden bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50"
            >
                <div
                    class="absolute top-0 right-0 w-24 h-24 bg-violet-50 dark:bg-violet-500/10 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"
                ></div>

                <div class="flex justify-between items-start relative z-10">
                    <div>
                        <p
                            class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider"
                        >
                            Total Orders
                        </p>
                        <h3
                            class="text-3xl font-extrabold text-slate-900 dark:text-white mt-2"
                        >
                            {{ stats.total_bookings }}
                        </h3>
                        <p
                            class="text-xs text-slate-400 dark:text-slate-500 mt-1"
                        >
                            All time history
                        </p>
                    </div>
                    <div
                        class="p-3 bg-violet-50 dark:bg-violet-500/10 text-violet-600 dark:text-violet-400 rounded-2xl"
                    >
                        <ShoppingBag class="w-6 h-6" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div
            v-if="stats.total_bookings === 0"
            class="bg-gradient-to-r from-violet-600 to-indigo-600 rounded-3xl p-8 text-center text-white shadow-xl shadow-violet-500/20"
        >
            <div class="max-w-2xl mx-auto">
                <h3 class="text-2xl font-bold mb-2">
                    Ready to start learning?
                </h3>
                <p class="text-violet-100 mb-6">
                    Connect with top industry experts and accelerate your career
                    growth today.
                </p>
                <Link
                    :href="route('home')"
                    class="inline-block px-8 py-3 bg-white text-violet-700 font-bold rounded-xl hover:bg-violet-50 transition-colors shadow-sm"
                >
                    Browse Experts
                </Link>
            </div>
        </div>
    </div>
</template>
