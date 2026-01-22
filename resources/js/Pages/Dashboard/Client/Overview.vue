<script setup>
import { Head, Link } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import {
    Users,
    Wallet,
    TrendingUp,
    Calendar,
    Plus,
    History,
    ArrowUpRight,
    ArrowDownLeft,
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

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString("id-ID", {
        day: "numeric",
        month: "short",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
};

const getStatusColor = (status) => {
    const colors = {
        completed:
            "bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400",
        confirmed:
            "bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400",
        pending:
            "bg-yellow-100 dark:bg-yellow-500/20 text-yellow-700 dark:text-yellow-400",
        cancelled:
            "bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400",
        declined:
            "bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400",
    };
    return (
        colors[status] ||
        "bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300"
    );
};

const getLedgerTypeColor = (type) => {
    return type === "credit"
        ? "text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10"
        : "text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-500/10";
};
</script>

<template>
    <Head title="HRD Dashboard" />

    <div class="space-y-8">
        <!-- Header -->
        <div
            class="flex flex-col sm:flex-row sm:items-center justify-between gap-4"
        >
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">
                    {{ stats.company_name }}
                </h1>
                <p class="text-slate-500 dark:text-slate-400 mt-1">
                    Kelola kuota dan pantau aktivitas booking karyawan Anda.
                </p>
            </div>

            <div class="flex gap-3">
                <Link
                    :href="route('topup.history')"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-sm font-semibold rounded-xl transition-all"
                >
                    <History class="w-4 h-4" />
                    Riwayat
                </Link>
                <Link
                    :href="route('topup.create')"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-emerald-500/20"
                >
                    <Plus class="w-4 h-4" />
                    Top Up Kuota
                </Link>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Quota Balance -->
            <div
                class="relative overflow-hidden p-6 rounded-3xl bg-linear-to-br from-emerald-500 to-emerald-600 text-white shadow-lg shadow-emerald-500/20"
            >
                <div
                    class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-bl-full -mr-8 -mt-8"
                ></div>
                <div class="relative z-10">
                    <p
                        class="text-sm font-medium text-emerald-100 uppercase tracking-wider"
                    >
                        Saldo Kuota
                    </p>
                    <h3
                        class="text-3xl font-extrabold mt-2 truncate"
                        :title="formatCurrency(stats.quota_balance)"
                    >
                        {{ formatCurrency(stats.quota_balance) }}
                    </h3>
                    <p class="text-xs text-emerald-100 mt-1">
                        Tersedia untuk booking
                    </p>
                </div>
                <Wallet
                    class="absolute bottom-4 right-4 w-12 h-12 text-white/20"
                />
            </div>

            <!-- Total Employees -->
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
                            Karyawan
                        </p>
                        <h3
                            class="text-3xl font-extrabold text-slate-900 dark:text-white mt-2"
                        >
                            {{ stats.registered_employees }}
                        </h3>
                        <p
                            class="text-xs text-slate-400 dark:text-slate-500 mt-1"
                        >
                            Terdaftar ({{ stats.employee_count_category }})
                        </p>
                    </div>
                    <div
                        class="p-3 bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 rounded-2xl"
                    >
                        <Users class="w-6 h-6" />
                    </div>
                </div>
            </div>

            <!-- Total Spent -->
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
                            Total Terpakai
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
                            {{ stats.total_bookings }} booking
                        </p>
                    </div>
                    <div
                        class="p-3 bg-violet-50 dark:bg-violet-500/10 text-violet-600 dark:text-violet-400 rounded-2xl"
                    >
                        <TrendingUp class="w-6 h-6" />
                    </div>
                </div>
            </div>

            <!-- Upcoming Bookings -->
            <div
                class="p-6 rounded-3xl shadow-sm transition-all duration-300 hover:shadow-md group relative overflow-hidden bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50"
            >
                <div
                    class="absolute top-0 right-0 w-24 h-24 bg-amber-50 dark:bg-amber-500/10 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"
                ></div>
                <div class="flex justify-between items-start relative z-10">
                    <div>
                        <p
                            class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider"
                        >
                            Mendatang
                        </p>
                        <h3
                            class="text-3xl font-extrabold text-slate-900 dark:text-white mt-2"
                        >
                            {{ stats.upcoming_bookings }}
                        </h3>
                        <p
                            class="text-xs text-slate-400 dark:text-slate-500 mt-1"
                        >
                            Sesi terjadwal
                        </p>
                    </div>
                    <div
                        class="p-3 bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 rounded-2xl"
                    >
                        <Calendar class="w-6 h-6" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Employee Bookings -->
            <div
                class="rounded-3xl shadow-sm overflow-hidden bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50"
            >
                <div
                    class="p-6 border-b border-slate-100 dark:border-slate-700/50"
                >
                    <h2
                        class="text-lg font-bold text-slate-900 dark:text-white"
                    >
                        Booking Karyawan Terbaru
                    </h2>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-slate-700/50">
                    <div
                        v-for="booking in stats.recent_bookings"
                        :key="booking.id"
                        class="p-4 hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors"
                    >
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p
                                    class="font-semibold text-slate-900 dark:text-white"
                                >
                                    {{ booking.employee_name }}
                                </p>
                                <p
                                    class="text-sm text-slate-500 dark:text-slate-400"
                                >
                                    dengan {{ booking.expert_name }}
                                </p>
                            </div>
                            <div class="text-right">
                                <span
                                    :class="getStatusColor(booking.status)"
                                    class="inline-block px-2 py-1 text-xs font-semibold rounded-full"
                                >
                                    {{ booking.status }}
                                </span>
                                <p
                                    class="text-xs text-slate-400 dark:text-slate-500 mt-1"
                                >
                                    {{ formatCurrency(booking.price) }}
                                </p>
                            </div>
                        </div>
                        <p
                            class="text-xs text-slate-400 dark:text-slate-500 mt-2"
                        >
                            {{ formatDate(booking.date_time) }}
                        </p>
                    </div>

                    <div
                        v-if="
                            !stats.recent_bookings ||
                            stats.recent_bookings.length === 0
                        "
                        class="p-8 text-center text-slate-500 dark:text-slate-400"
                    >
                        Belum ada booking dari karyawan.
                    </div>
                </div>
            </div>

            <!-- Recent Ledger -->
            <div
                class="rounded-3xl shadow-sm overflow-hidden bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50"
            >
                <div
                    class="p-6 border-b border-slate-100 dark:border-slate-700/50 flex items-center justify-between"
                >
                    <h2
                        class="text-lg font-bold text-slate-900 dark:text-white"
                    >
                        Riwayat Transaksi Kuota
                    </h2>
                    <Link
                        :href="route('topup.history')"
                        class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium"
                    >
                        Lihat Semua
                    </Link>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-slate-700/50">
                    <div
                        v-for="ledger in stats.recent_ledger"
                        :key="ledger.id"
                        class="p-4 hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors"
                    >
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div
                                    :class="getLedgerTypeColor(ledger.type)"
                                    class="p-2 rounded-xl"
                                >
                                    <ArrowUpRight
                                        v-if="ledger.type === 'credit'"
                                        class="w-4 h-4"
                                    />
                                    <ArrowDownLeft v-else class="w-4 h-4" />
                                </div>
                                <div>
                                    <p
                                        class="font-medium text-slate-900 dark:text-white text-sm"
                                    >
                                        {{
                                            ledger.reference_type === "booking"
                                                ? "Booking"
                                                : ledger.reference_type ===
                                                  "topup"
                                                ? "Top Up"
                                                : "Refund"
                                        }}
                                    </p>
                                    <p
                                        class="text-xs text-slate-500 dark:text-slate-400"
                                    >
                                        {{ ledger.user_name }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p
                                    :class="
                                        ledger.type === 'credit'
                                            ? 'text-emerald-600 dark:text-emerald-400'
                                            : 'text-red-600 dark:text-red-400'
                                    "
                                    class="font-bold"
                                >
                                    {{ ledger.type === "credit" ? "+" : "-"
                                    }}{{ formatCurrency(ledger.amount) }}
                                </p>
                                <p
                                    class="text-xs text-slate-400 dark:text-slate-500"
                                >
                                    Saldo:
                                    {{ formatCurrency(ledger.balance_after) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        v-if="
                            !stats.recent_ledger ||
                            stats.recent_ledger.length === 0
                        "
                        class="p-8 text-center text-slate-500 dark:text-slate-400"
                    >
                        Belum ada transaksi kuota.
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
