<script setup>
import { CreditCard } from "lucide-vue-next";
import { useFormatters } from "@/composables/useFormatters";

const props = defineProps({
    totalBalance: {
        type: Number,
        default: 500000000,
    },
    lastTopup: {
        type: Object,
        default: () => ({
            amount: 50000000,
            label: "last week",
        }),
    },
    expenditure: {
        type: Object,
        default: () => ({
            amount: 150000000,
            percentage: 30,
        }),
    },
    creditConversion: {
        type: Object,
        default: () => ({
            rate: 250000, // 1 Credit = IDR rate
            maxCredits: 4, // per employee
        }),
    },
});

const { formatCurrency } = useFormatters();

// Format large numbers (e.g., 50M, 150M)
const formatShortCurrency = (amount) => {
    if (amount >= 1000000000) {
        return `IDR ${(amount / 1000000000).toFixed(1)}B`;
    } else if (amount >= 1000000) {
        return `IDR ${(amount / 1000000).toFixed(0)}M`;
    }
    return formatCurrency(amount);
};
</script>

<template>
    <div
        class="rounded-2xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 shadow-sm p-6"
    >
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white">
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
                {{ formatCurrency(totalBalance) }}
            </h2>
            <div class="flex items-center gap-2">
                <span
                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400"
                >
                    + {{ formatShortCurrency(lastTopup.amount) }} Topup
                </span>
                <span class="text-sm text-slate-400 dark:text-slate-500">
                    {{ lastTopup.label }}
                </span>
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
                <p class="text-sm font-bold text-slate-900 dark:text-white">
                    {{ formatCurrency(expenditure.amount) }}
                </p>
            </div>
            <!-- Progress Bar -->
            <div
                class="h-2.5 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden mb-2"
            >
                <div
                    class="h-full bg-blue-600 rounded-full transition-all duration-500"
                    :style="{ width: expenditure.percentage + '%' }"
                ></div>
            </div>
            <p class="text-xs text-slate-400 dark:text-slate-500 text-right">
                {{ expenditure.percentage }}% of budget used
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
            <div class="p-3 bg-blue-100 dark:bg-blue-500/20 rounded-full">
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
                <h4 class="text-sm font-bold text-slate-900 dark:text-white">
                    Credit Conversion
                </h4>
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    1 Credit =
                    <span
                        class="text-blue-600 dark:text-blue-400 font-semibold"
                    >
                        {{ formatCurrency(creditConversion.rate) }}
                    </span>
                </p>
                <p class="text-xs text-slate-400 dark:text-slate-500">
                    Cap:
                    <span
                        class="font-semibold text-slate-700 dark:text-slate-300"
                    >
                        Max {{ creditConversion.maxCredits }} Credits
                    </span>
                    / employee
                </p>
            </div>
        </div>
    </div>
</template>
