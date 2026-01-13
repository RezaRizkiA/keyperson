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
    Star,
    Upload,
    FileText,
    CreditCard,
    ChevronRight,
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

                <!-- Top Interest Categories Card -->
                <Link
                    href="#"
                    class="block py-6 px-5 rounded-2xl shadow-sm transition-all duration-300 hover:shadow-lg hover:scale-[1.02] cursor-pointer group relative overflow-hidden bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50"
                >
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-5">
                        <h4
                            class="text-base font-bold text-slate-900 dark:text-white"
                        >
                            Top Interest Categories
                        </h4>
                        <div
                            class="p-2 bg-violet-50 dark:bg-violet-500/10 rounded-xl"
                        >
                            <TrendingUp class="w-5 h-5 text-violet-500" />
                        </div>
                    </div>

                    <!-- Categories List -->
                    <div class="space-y-4">
                        <!-- Category 1: Mental Health -->
                        <div>
                            <div
                                class="flex items-center justify-between mb-1.5"
                            >
                                <span
                                    class="text-sm font-medium text-slate-700 dark:text-slate-300"
                                    >Mental Health</span
                                >
                                <span
                                    class="text-sm font-semibold text-slate-900 dark:text-white"
                                    >40%</span
                                >
                            </div>
                            <div
                                class="h-2 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden"
                            >
                                <div
                                    class="h-full bg-blue-600 rounded-full"
                                    style="width: 40%"
                                ></div>
                            </div>
                        </div>

                        <!-- Category 2: Career Development -->
                        <div>
                            <div
                                class="flex items-center justify-between mb-1.5"
                            >
                                <span
                                    class="text-sm font-medium text-slate-700 dark:text-slate-300"
                                    >Career Development</span
                                >
                                <span
                                    class="text-sm font-semibold text-slate-900 dark:text-white"
                                    >30%</span
                                >
                            </div>
                            <div
                                class="h-2 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden"
                            >
                                <div
                                    class="h-full bg-blue-600 rounded-full"
                                    style="width: 30%"
                                ></div>
                            </div>
                        </div>

                        <!-- Category 3: Financial Wellness -->
                        <div>
                            <div
                                class="flex items-center justify-between mb-1.5"
                            >
                                <span
                                    class="text-sm font-medium text-slate-700 dark:text-slate-300"
                                    >Financial Wellness</span
                                >
                                <span
                                    class="text-sm font-semibold text-slate-900 dark:text-white"
                                    >25%</span
                                >
                            </div>
                            <div
                                class="h-2 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden"
                            >
                                <div
                                    class="h-full bg-slate-400 rounded-full"
                                    style="width: 25%"
                                ></div>
                            </div>
                        </div>
                    </div>
                </Link>

                <!-- Avg. Expert Rating Card -->
                <Link
                    href="#"
                    class="block py-6 px-5 rounded-2xl shadow-sm transition-all duration-300 hover:shadow-lg hover:scale-[1.02] cursor-pointer group relative overflow-hidden bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50"
                >
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-4">
                        <h4
                            class="text-base font-bold text-slate-900 dark:text-white"
                        >
                            Avg. Expert Rating
                        </h4>
                        <div
                            class="p-2 bg-amber-50 dark:bg-amber-500/10 rounded-xl"
                        >
                            <Star class="w-5 h-5 text-amber-500" />
                        </div>
                    </div>

                    <!-- Rating Display -->
                    <div class="text-center py-2">
                        <h3
                            class="text-5xl font-extrabold text-slate-900 dark:text-white mb-3"
                        >
                            4.8
                        </h3>
                        <!-- Stars -->
                        <div
                            class="flex items-center justify-center gap-1 mb-3"
                        >
                            <Star
                                class="w-6 h-6 text-amber-400 fill-amber-400"
                            />
                            <Star
                                class="w-6 h-6 text-amber-400 fill-amber-400"
                            />
                            <Star
                                class="w-6 h-6 text-amber-400 fill-amber-400"
                            />
                            <Star
                                class="w-6 h-6 text-amber-400 fill-amber-400"
                            />
                            <Star
                                class="w-6 h-6 text-slate-200 dark:text-slate-600"
                            />
                        </div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            Based on 118 sessions
                        </p>
                    </div>
                </Link>
            </div>
        </div>

        <!-- Budget Overview & Quick Actions Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Budget Overview Card -->
            <div
                class="rounded-2xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 shadow-sm p-6"
            >
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <h3
                        class="text-lg font-bold text-slate-900 dark:text-white"
                    >
                        Budget Overview
                    </h3>
                    <div class="p-2 bg-blue-50 dark:bg-blue-500/10 rounded-xl">
                        <CreditCard class="w-5 h-5 text-blue-500" />
                    </div>
                </div>

                <!-- Total Wallet Balance -->
                <div class="mb-6">
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-1">
                        Total Wallet Balance
                    </p>
                    <h2
                        class="text-3xl font-extrabold text-slate-900 dark:text-white mb-2"
                    >
                        IDR 500.000.000
                    </h2>
                    <div class="flex items-center gap-2">
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400"
                        >
                            + IDR 50M Topup
                        </span>
                        <span class="text-sm text-slate-400 dark:text-slate-500"
                            >last week</span
                        >
                    </div>
                </div>

                <!-- Divider -->
                <div
                    class="border-t border-slate-100 dark:border-slate-700/50 my-5"
                ></div>

                <!-- Total Expenditure -->
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <p
                            class="text-sm font-medium text-slate-700 dark:text-slate-300"
                        >
                            Total Expenditure
                        </p>
                        <p
                            class="text-sm font-bold text-slate-900 dark:text-white"
                        >
                            IDR 150.000.000
                        </p>
                    </div>
                    <!-- Progress Bar -->
                    <div
                        class="h-2.5 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden mb-2"
                    >
                        <div
                            class="h-full bg-blue-600 rounded-full"
                            style="width: 30%"
                        ></div>
                    </div>
                    <p
                        class="text-xs text-slate-400 dark:text-slate-500 text-right"
                    >
                        30% of budget used
                    </p>
                </div>

                <!-- Divider -->
                <div
                    class="border-t border-slate-100 dark:border-slate-700/50 my-5"
                ></div>

                <!-- Credit Conversion -->
                <div
                    class="flex items-center gap-4 p-4 bg-slate-50 dark:bg-slate-800 rounded-xl"
                >
                    <div
                        class="p-3 bg-blue-100 dark:bg-blue-500/20 rounded-full"
                    >
                        <svg
                            class="w-6 h-6 text-blue-600 dark:text-blue-400"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <circle cx="12" cy="12" r="10" />
                            <path d="M12 6v6l4 2" />
                        </svg>
                    </div>
                    <div>
                        <h4
                            class="text-sm font-bold text-slate-900 dark:text-white"
                        >
                            Credit Conversion
                        </h4>
                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            1 Credit =
                            <span
                                class="text-blue-600 dark:text-blue-400 font-semibold"
                                >IDR 250.000</span
                            >
                        </p>
                        <p class="text-xs text-slate-400 dark:text-slate-500">
                            Cap:
                            <span
                                class="font-semibold text-slate-700 dark:text-slate-300"
                                >Max 4 Credits</span
                            >
                            / employee
                        </p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div
                class="rounded-2xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 shadow-sm p-6 flex flex-col"
            >
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <h3
                        class="text-lg font-bold text-slate-900 dark:text-white"
                    >
                        Quick Actions
                    </h3>
                    <div
                        class="p-2 bg-violet-50 dark:bg-violet-500/10 rounded-xl"
                    >
                        <TrendingUp class="w-5 h-5 text-violet-500" />
                    </div>
                </div>

                <!-- Actions List -->
                <div class="space-y-3 flex-1">
                    <!-- Bulk Invitation -->
                    <Link
                        href="#"
                        class="flex items-center gap-4 p-4 rounded-xl bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700/50 transition-all duration-200 group"
                    >
                        <div
                            class="p-2.5 bg-blue-100 dark:bg-blue-500/20 rounded-lg"
                        >
                            <Upload
                                class="w-4 h-4 text-blue-600 dark:text-blue-400"
                            />
                        </div>
                        <div class="flex-1">
                            <h4
                                class="text-sm font-semibold text-slate-900 dark:text-white"
                            >
                                Bulk Invitation
                            </h4>
                            <p
                                class="text-xs text-slate-500 dark:text-slate-400"
                            >
                                Invite multiple employees via CSV
                            </p>
                        </div>
                        <ChevronRight
                            class="w-4 h-4 text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300 transition-colors"
                        />
                    </Link>

                    <!-- Generate SPJ Report -->
                    <Link
                        href="#"
                        class="flex items-center gap-4 p-4 rounded-xl bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700/50 transition-all duration-200 group"
                    >
                        <div
                            class="p-2.5 bg-emerald-100 dark:bg-emerald-500/20 rounded-lg"
                        >
                            <FileText
                                class="w-4 h-4 text-emerald-600 dark:text-emerald-400"
                            />
                        </div>
                        <div class="flex-1">
                            <h4
                                class="text-sm font-semibold text-slate-900 dark:text-white"
                            >
                                Generate SPJ Report
                            </h4>
                            <p
                                class="text-xs text-slate-500 dark:text-slate-400"
                            >
                                Download monthly analytics PDF
                            </p>
                        </div>
                        <ChevronRight
                            class="w-4 h-4 text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300 transition-colors"
                        />
                    </Link>

                    <!-- Top Up Credits -->
                    <Link
                        href="#"
                        class="flex items-center gap-4 p-4 rounded-xl bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700/50 transition-all duration-200 group"
                    >
                        <div
                            class="p-2.5 bg-amber-100 dark:bg-amber-500/20 rounded-lg"
                        >
                            <CreditCard
                                class="w-4 h-4 text-amber-600 dark:text-amber-400"
                            />
                        </div>
                        <div class="flex-1">
                            <h4
                                class="text-sm font-semibold text-slate-900 dark:text-white"
                            >
                                Top Up Credits
                            </h4>
                            <p
                                class="text-xs text-slate-500 dark:text-slate-400"
                            >
                                Purchase additional consultation hours
                            </p>
                        </div>
                        <ChevronRight
                            class="w-4 h-4 text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300 transition-colors"
                        />
                    </Link>

                    <!-- Employee Directory -->
                    <Link
                        href="#"
                        class="flex items-center gap-4 p-4 rounded-xl bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700/50 transition-all duration-200 group"
                    >
                        <div
                            class="p-2.5 bg-violet-100 dark:bg-violet-500/20 rounded-lg"
                        >
                            <Users
                                class="w-4 h-4 text-violet-600 dark:text-violet-400"
                            />
                        </div>
                        <div class="flex-1">
                            <h4
                                class="text-sm font-semibold text-slate-900 dark:text-white"
                            >
                                Employee Directory
                            </h4>
                            <p
                                class="text-xs text-slate-500 dark:text-slate-400"
                            >
                                Manage and view all employees
                            </p>
                        </div>
                        <ChevronRight
                            class="w-4 h-4 text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300 transition-colors"
                        />
                    </Link>
                </div>
            </div>
        </div>

        <!-- Recent Consultations Card (Full Width) -->
        <div
            class="rounded-2xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 shadow-sm overflow-hidden"
        >
            <!-- Header -->
            <div
                class="flex items-center justify-between p-6 border-b border-slate-100 dark:border-slate-700/50"
            >
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-50 dark:bg-blue-500/10 rounded-xl">
                        <Calendar class="w-5 h-5 text-blue-500" />
                    </div>
                    <h3
                        class="text-lg font-bold text-slate-900 dark:text-white"
                    >
                        Recent Consultations
                    </h3>
                </div>
                <Link
                    href="#"
                    class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-500/10 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-500/20 transition-colors"
                >
                    View All
                    <ChevronRight class="w-4 h-4" />
                </Link>
            </div>

            <!-- Table Header -->
            <div
                class="grid grid-cols-5 gap-4 px-6 py-3 bg-slate-50 dark:bg-slate-800 border-b border-slate-100 dark:border-slate-700"
            >
                <span
                    class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider"
                    >Employee</span
                >
                <span
                    class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider"
                    >Topic</span
                >
                <span
                    class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider"
                    >Expert</span
                >
                <span
                    class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider"
                    >Date</span
                >
                <span
                    class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider"
                    >Status</span
                >
            </div>

            <!-- Table Body -->
            <div class="divide-y divide-slate-100 dark:divide-slate-700/50">
                <!-- Row 1 -->
                <div
                    class="grid grid-cols-5 gap-4 px-6 py-4 items-center hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors"
                >
                    <div class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-xs font-semibold"
                        >
                            JD
                        </div>
                        <span
                            class="text-sm font-medium text-slate-900 dark:text-white"
                            >John Doe</span
                        >
                    </div>
                    <span class="text-sm text-slate-600 dark:text-slate-300"
                        >Financial Planning</span
                    >
                    <span class="text-sm text-slate-600 dark:text-slate-300"
                        >Dr. Emily Chen</span
                    >
                    <span class="text-sm text-slate-500 dark:text-slate-400"
                        >Sep 28, 2023</span
                    >
                    <span
                        class="inline-flex items-center gap-1.5 text-sm font-medium text-emerald-600 dark:text-emerald-400"
                    >
                        <span
                            class="w-1.5 h-1.5 rounded-full bg-emerald-500"
                        ></span>
                        Completed
                    </span>
                </div>
                <!-- Row 2 -->
                <div
                    class="grid grid-cols-5 gap-4 px-6 py-4 items-center hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors"
                >
                    <div class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-full bg-gradient-to-br from-pink-400 to-pink-600 flex items-center justify-center text-white text-xs font-semibold"
                        >
                            AS
                        </div>
                        <span
                            class="text-sm font-medium text-slate-900 dark:text-white"
                            >Alice Smith</span
                        >
                    </div>
                    <span class="text-sm text-slate-600 dark:text-slate-300"
                        >Stress Management</span
                    >
                    <span class="text-sm text-slate-600 dark:text-slate-300"
                        >Dr. Mark Wilson</span
                    >
                    <span class="text-sm text-slate-500 dark:text-slate-400"
                        >Sep 22, 2023</span
                    >
                    <span
                        class="inline-flex items-center gap-1.5 text-sm font-medium text-emerald-600 dark:text-emerald-400"
                    >
                        <span
                            class="w-1.5 h-1.5 rounded-full bg-emerald-500"
                        ></span>
                        Completed
                    </span>
                </div>
                <!-- Row 3 -->
                <div
                    class="grid grid-cols-5 gap-4 px-6 py-4 items-center hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors"
                >
                    <div class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-full bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-white text-xs font-semibold"
                        >
                            MB
                        </div>
                        <span
                            class="text-sm font-medium text-slate-900 dark:text-white"
                            >Michael Brown</span
                        >
                    </div>
                    <span class="text-sm text-slate-600 dark:text-slate-300"
                        >Career Growth</span
                    >
                    <span class="text-sm text-slate-600 dark:text-slate-300"
                        >Sarah Johnson</span
                    >
                    <span class="text-sm text-slate-500 dark:text-slate-400"
                        >Sep 20, 2023</span
                    >
                    <span
                        class="inline-flex items-center gap-1.5 text-sm font-medium text-emerald-600 dark:text-emerald-400"
                    >
                        <span
                            class="w-1.5 h-1.5 rounded-full bg-emerald-500"
                        ></span>
                        Completed
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>
