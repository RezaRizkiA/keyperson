<script setup>
import { Head, Link } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import {
    Users,
    TrendingUp,
    Calendar,
    Plus,
    History,
    ArrowUpRight,
    ArrowDownLeft,
    ArrowDownRight,
    AlertTriangle,
    CheckCircle,
} from "lucide-vue-next";
import { computed } from "vue";

defineOptions({ layout: DashboardLayout });

const props = defineProps({
    data: Object,
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

// Quota status styling based on backend status
const getQuotaBorderColor = () => {
    const status = props.data?.quota_status;
    if (status === "healthy") return "border-emerald-500";
    if (status === "warning") return "border-amber-500";
    return "border-red-500";
};

const getQuotaBarColor = () => {
    const status = props.data?.quota_status;
    if (status === "healthy") return "bg-emerald-500";
    if (status === "warning") return "bg-amber-500";
    return "bg-red-500";
};

const getQuotaIconBgColor = () => {
    const status = props.data?.quota_status;
    if (status === "healthy") return "bg-emerald-50 dark:bg-emerald-500/10";
    if (status === "warning") return "bg-amber-50 dark:bg-amber-500/10";
    return "bg-red-50 dark:bg-red-500/10";
};

const getQuotaIconColor = () => {
    const status = props.data?.quota_status;
    if (status === "healthy") return "text-emerald-500";
    if (status === "warning") return "text-amber-500";
    return "text-red-500";
};

const getQuotaActionColor = () => {
    const status = props.data?.quota_status;
    if (status === "healthy") return "text-emerald-600 dark:text-emerald-400";
    if (status === "warning") return "text-amber-600 dark:text-amber-400";
    return "text-red-600 dark:text-red-400";
};

// =========================================================================
// ENGAGEMENT DONUT CHART
// =========================================================================

// SVG circle circumference: 2 * PI * radius = 2 * 3.14159 * 15.5 ≈ 97.4
const CIRCUMFERENCE = 97.4;

// Computed: stroke-dasharray for donut chart
const engagementDashArray = computed(() => {
    const percent = props.data?.engagement_percentage ?? 0;
    const filled = (percent / 100) * CIRCUMFERENCE;
    return `${filled}, ${CIRCUMFERENCE}`;
});

// Computed: engagement change text and styling
const engagementChangeText = computed(() => {
    const change = props.data?.engagement_change ?? 0;
    if (change > 0) return `+${change}% vs last month`;
    if (change < 0) return `${change}% vs last month`;
    return "No change vs last month";
});

const engagementChangeIsPositive = computed(() => {
    return (props.data?.engagement_change ?? 0) >= 0;
});
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
                    {{ data.company_name }}
                </h1>
                <p class="text-slate-500 dark:text-slate-400 mt-1">
                    Kelola kuota dan pantau aktivitas booking karyawan Anda.
                </p>
            </div>

            <!-- action button -->
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

        <!-- data Cards -->
        <div>
            <p class="text-xl font-bold text-slate-900 dark:text-white mb-4">
                Strategic Analytics
            </p>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                <!-- Quota Alert Card -->
                <Link
                    :href="route('topup.create')"
                    :class="[
                        'block relative overflow-hidden py-6 px-5 rounded-2xl bg-white dark:bg-slate-800/50 border-l-4 shadow-sm hover:shadow-lg hover:scale-[1.02] transition-all duration-300 cursor-pointer group',
                        getQuotaBorderColor(),
                    ]"
                >
                    <!-- Header: Title & Icon -->
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <h4
                                class="text-lg font-bold text-slate-900 dark:text-white"
                            >
                                Status Kuota
                            </h4>
                            <p
                                class="text-sm text-slate-500 dark:text-slate-400 mt-0.5"
                            >
                                {{ data.quota_message }}
                            </p>
                        </div>
                        <div :class="['p-2 rounded-xl', getQuotaIconBgColor()]">
                            <CheckCircle
                                v-if="data.quota_status === 'healthy'"
                                :class="['w-5 h-5', getQuotaIconColor()]"
                            />
                            <AlertTriangle
                                v-else
                                :class="['w-5 h-5', getQuotaIconColor()]"
                            />
                        </div>
                    </div>

                    <!-- Main Content: Percentage -->
                    <div class="mb-4">
                        <div class="flex items-baseline gap-2">
                            <span
                                class="text-4xl font-extrabold text-slate-900 dark:text-white"
                                >{{ data.quota_percentage }}%</span
                            >
                            <span
                                class="text-lg text-slate-500 dark:text-slate-400"
                                >remaining</span
                            >
                        </div>
                        <!-- Progress Bar -->
                        <div
                            class="mt-3 h-2 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden"
                        >
                            <div
                                :class="[
                                    'h-full rounded-full transition-all duration-500',
                                    getQuotaBarColor(),
                                ]"
                                :style="{ width: data.quota_percentage + '%' }"
                            ></div>
                        </div>
                    </div>

                    <!-- Action Text (only show if action exists) -->
                    <p
                        v-if="data.quota_action"
                        :class="[
                            'text-sm font-medium group-hover:underline',
                            getQuotaActionColor(),
                        ]"
                    >
                        {{ data.quota_action }}
                    </p>
                    <p
                        v-else
                        class="text-sm font-medium text-slate-400 dark:text-slate-500"
                    >
                        {{ formatCurrency(data.quota_balance) }} tersedia
                    </p>
                </Link>

                <!-- Employee Engagement Card -->
                <Link
                    href="#"
                    class="block py-6 px-5 rounded-2xl shadow-sm transition-all duration-300 hover:shadow-lg hover:scale-[1.02] cursor-pointer group relative overflow-hidden bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50"
                >
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-4">
                        <h4
                            class="text-base font-bold text-slate-900 dark:text-white"
                        >
                            Total Employee Engagement
                        </h4>
                        <div
                            class="p-2 bg-blue-50 dark:bg-blue-500/10 rounded-xl"
                        >
                            <Users class="w-5 h-5 text-blue-500" />
                        </div>
                    </div>

                    <!-- Donut Chart -->
                    <div class="flex justify-center mb-4">
                        <div class="relative w-32 h-32">
                            <!-- Background circle -->
                            <svg
                                class="w-full h-full transform -rotate-90"
                                viewBox="0 0 36 36"
                            >
                                <circle
                                    cx="18"
                                    cy="18"
                                    r="15.5"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="3"
                                    class="text-slate-100 dark:text-slate-700"
                                />
                                <!-- Progress circle (dynamic) -->
                                <circle
                                    cx="18"
                                    cy="18"
                                    r="15.5"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="3"
                                    :stroke-dasharray="engagementDashArray"
                                    stroke-linecap="round"
                                    class="text-blue-600 dark:text-blue-500 transition-all duration-500"
                                />
                            </svg>
                            <!-- Center text -->
                            <div
                                class="absolute inset-0 flex flex-col items-center justify-center"
                            >
                                <span
                                    class="text-2xl font-extrabold text-slate-900 dark:text-white"
                                    >{{ data.engagement_percentage }}%</span
                                >
                                <span
                                    class="text-xs text-slate-500 dark:text-slate-400"
                                    >Active</span
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Comparison -->
                    <div class="flex items-center justify-center gap-1 text-sm">
                        <component
                            :is="
                                engagementChangeIsPositive
                                    ? ArrowUpRight
                                    : ArrowDownRight
                            "
                            :class="[
                                'w-4 h-4',
                                engagementChangeIsPositive
                                    ? 'text-emerald-500'
                                    : 'text-red-500',
                            ]"
                        />
                        <span
                            :class="[
                                'font-medium group-hover:underline',
                                engagementChangeIsPositive
                                    ? 'text-emerald-600 dark:text-emerald-400'
                                    : 'text-red-600 dark:text-red-400',
                            ]"
                            >{{ engagementChangeText }}</span
                        >
                    </div>
                </Link>

                <!-- Total Spent -->
                <div
                    class="py-8 px-6 rounded-2xl shadow-sm transition-all duration-300 hover:shadow-lg group relative overflow-hidden bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50"
                >
                    <div class="relative z-10 text-center">
                        <div
                            class="inline-flex p-3 bg-violet-50 dark:bg-violet-500/10 text-violet-600 dark:text-violet-400 rounded-2xl mb-4"
                        >
                            <TrendingUp class="w-6 h-6" />
                        </div>
                        <p
                            class="text-sm font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider"
                        >
                            Total Terpakai
                        </p>
                        <h3
                            class="text-4xl font-extrabold text-slate-900 dark:text-white mt-3 mb-2 truncate"
                            :title="formatCurrency(data.total_spent)"
                        >
                            {{ formatCurrency(data.total_spent) }}
                        </h3>
                        <p class="text-sm text-slate-400 dark:text-slate-500">
                            {{ data.total_bookings }} booking
                        </p>
                    </div>
                </div>

                <!-- Upcoming Bookings -->
                <div
                    class="py-8 px-6 rounded-2xl shadow-sm transition-all duration-300 hover:shadow-lg group relative overflow-hidden bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50"
                >
                    <div class="relative z-10 text-center">
                        <div
                            class="inline-flex p-3 bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 rounded-2xl mb-4"
                        >
                            <Calendar class="w-6 h-6" />
                        </div>
                        <p
                            class="text-sm font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider"
                        >
                            Mendatang
                        </p>
                        <h3
                            class="text-4xl font-extrabold text-slate-900 dark:text-white mt-3 mb-2"
                        >
                            {{ data.upcoming_bookings }}
                        </h3>
                        <p class="text-sm text-slate-400 dark:text-slate-500">
                            Sesi terjadwal
                        </p>
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
                        v-for="booking in data.recent_bookings"
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
                            !data.recent_bookings ||
                            data.recent_bookings.length === 0
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
                        v-for="ledger in data.recent_ledger"
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
                            !data.recent_ledger ||
                            data.recent_ledger.length === 0
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
