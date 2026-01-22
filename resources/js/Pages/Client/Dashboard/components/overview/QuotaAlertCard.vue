<script setup>
import { Link } from "@inertiajs/vue3";
import { CheckCircle, AlertTriangle, ArrowUpRight } from "lucide-vue-next";
import { useFormatters } from "@/composables/useFormatters";
import { computed } from "vue";

const props = defineProps({
    status: {
        type: String,
        default: "healthy", // healthy, warning, critical
    },
    message: {
        type: String,
        default: "",
    },
    percentage: {
        type: Number,
        default: 0,
    },
    balance: {
        type: Number,
        default: 0,
    },
    maxQuota: {
        type: Number,
        default: 5000000,
    },
    action: {
        type: String,
        default: null,
    },
    href: {
        type: String,
        default: "#",
    },
});

const { formatCurrency } = useFormatters();

// Calculate used amount
const usedAmount = computed(() => props.maxQuota - props.balance);
const usedPercentage = computed(() => 100 - props.percentage);

// Donut chart calculations
const radius = 45;
const circumference = 2 * Math.PI * radius;
const remainingStroke = computed(
    () => (props.percentage / 100) * circumference,
);
const usedStroke = computed(() => (usedPercentage.value / 100) * circumference);

// Computed styles based on status
const statusStyles = computed(() => {
    const styles = {
        healthy: {
            remaining: "stroke-emerald-500",
            used: "stroke-slate-200 dark:stroke-slate-600",
            iconBg: "bg-emerald-50 dark:bg-emerald-500/10",
            icon: "text-emerald-500",
            action: "text-emerald-600 dark:text-emerald-400",
            dot: "bg-emerald-500",
        },
        warning: {
            remaining: "stroke-amber-500",
            used: "stroke-slate-200 dark:stroke-slate-600",
            iconBg: "bg-amber-50 dark:bg-amber-500/10",
            icon: "text-amber-500",
            action: "text-amber-600 dark:text-amber-400",
            dot: "bg-amber-500",
        },
        critical: {
            remaining: "stroke-red-500",
            used: "stroke-slate-200 dark:stroke-slate-600",
            iconBg: "bg-red-50 dark:bg-red-500/10",
            icon: "text-red-500",
            action: "text-red-600 dark:text-red-400",
            dot: "bg-red-500",
        },
    };
    return styles[props.status] || styles.critical;
});
</script>

<template>
    <Link
        :href="href"
        class="block relative overflow-hidden py-6 px-5 rounded-2xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-lg hover:scale-[1.02] transition-all duration-300 cursor-pointer group"
    >
        <!-- Header: Title & Icon -->
        <div class="flex items-center justify-between mb-5">
            <h4 class="text-base font-bold text-slate-900 dark:text-white">
                Quota Status
            </h4>
            <div :class="['p-1.5 rounded-lg', statusStyles.iconBg]">
                <CheckCircle
                    v-if="status === 'healthy'"
                    :class="['w-4 h-4', statusStyles.icon]"
                />
                <AlertTriangle v-else :class="['w-4 h-4', statusStyles.icon]" />
            </div>
        </div>

        <!-- Donut Chart Section -->
        <div class="flex items-center gap-5">
            <!-- Donut Chart -->
            <div class="relative w-28 h-28 shrink-0">
                <svg class="w-full h-full -rotate-90" viewBox="0 0 100 100">
                    <!-- Background circle (used) -->
                    <circle
                        cx="50"
                        cy="50"
                        :r="radius"
                        fill="none"
                        stroke-width="10"
                        class="stroke-slate-100 dark:stroke-slate-700"
                    />
                    <!-- Remaining segment -->
                    <circle
                        cx="50"
                        cy="50"
                        :r="radius"
                        fill="none"
                        stroke-width="10"
                        stroke-linecap="round"
                        :class="statusStyles.remaining"
                        :stroke-dasharray="`${remainingStroke} ${circumference}`"
                        class="transition-all duration-700 ease-out"
                    />
                </svg>
                <!-- Center Content -->
                <div
                    class="absolute inset-0 flex flex-col items-center justify-center"
                >
                    <span
                        class="text-2xl font-extrabold text-slate-900 dark:text-white"
                    >
                        {{ percentage }}%
                    </span>
                    <span class="text-xs text-slate-500 dark:text-slate-400">
                        tersisa
                    </span>
                </div>
            </div>

            <!-- Legend -->
            <div class="flex-1 space-y-3">
                <!-- Remaining -->
                <div class="flex items-center gap-2">
                    <span
                        :class="[
                            'w-2.5 h-2.5 rounded-full shrink-0',
                            statusStyles.dot,
                        ]"
                    ></span>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            Tersisa
                        </p>
                        <p
                            class="text-sm font-bold text-slate-900 dark:text-white truncate"
                        >
                            {{ formatCurrency(balance) }}
                        </p>
                    </div>
                </div>
                <!-- Used -->
                <div class="flex items-center gap-2">
                    <span
                        class="w-2.5 h-2.5 rounded-full bg-slate-300 dark:bg-slate-600 shrink-0"
                    ></span>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            Terpakai
                        </p>
                        <p
                            class="text-sm font-bold text-slate-900 dark:text-white truncate"
                        >
                            {{ formatCurrency(usedAmount) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Message & Action -->
        <div class="mt-5 pt-4 border-t border-slate-100 dark:border-slate-700">
            <div class="flex items-center justify-between">
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    {{ message }}
                </p>
                <div
                    v-if="action"
                    :class="[
                        'flex items-center gap-1 text-xs font-semibold',
                        statusStyles.action,
                    ]"
                >
                    <span>Top Up</span>
                    <ArrowUpRight class="w-3 h-3" />
                </div>
            </div>
        </div>
    </Link>
</template>
