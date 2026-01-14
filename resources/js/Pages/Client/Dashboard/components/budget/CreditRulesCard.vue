<script setup>
import { CreditCard, ArrowRight, Pencil } from "lucide-vue-next";
import { useFormatters } from "@/composables/useFormatters";

const props = defineProps({
    exchangeRate: {
        type: Number,
        default: 250000,
    },
    allocationLimit: {
        type: Number,
        default: 4,
    },
});

const { formatCurrency } = useFormatters();

// Format exchange rate display
const formattedExchangeRate = () => {
    return formatCurrency(props.exchangeRate);
};
</script>

<template>
    <div
        class="rounded-2xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 shadow-sm p-6 flex flex-col h-full"
    >
        <!-- Header -->
        <div class="flex items-center gap-3 mb-6">
            <div
                class="p-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400"
            >
                <CreditCard class="w-5 h-5" />
            </div>
            <h3 class="font-bold text-lg text-slate-900 dark:text-white">
                Credit Rules
            </h3>
        </div>

        <!-- Rules Content -->
        <div class="flex-1 flex flex-col justify-center gap-4">
            <!-- Exchange Rate -->
            <div
                class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-800 rounded-xl border border-slate-100 dark:border-slate-700"
            >
                <div class="flex flex-col">
                    <span
                        class="text-[10px] sm:text-xs font-bold uppercase text-slate-500 dark:text-slate-400 tracking-wider mb-1"
                    >
                        Exchange Rate
                    </span>
                    <span
                        class="text-base sm:text-lg font-bold text-slate-900 dark:text-white"
                    >
                        1 Credit
                    </span>
                </div>
                <ArrowRight
                    class="w-5 h-5 text-slate-300 dark:text-slate-600"
                />
                <div class="flex flex-col items-end">
                    <span
                        class="text-[10px] sm:text-xs font-bold uppercase text-muted dark:text-muted-foreground tracking-wider mb-1"
                    >
                        Value
                    </span>
                    <span
                        class="text-base sm:text-lg font-bold text-primary dark:text-primary"
                    >
                        {{ formattedExchangeRate() }}
                    </span>
                </div>
            </div>

            <!-- Allocation Limit -->
            <div
                class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-800 rounded-xl border border-slate-100 dark:border-slate-700"
            >
                <div class="flex flex-col">
                    <span
                        class="text-[10px] sm:text-xs font-bold uppercase text-muted dark:text-muted-foreground tracking-wider mb-1"
                    >
                        Allocation Limit
                    </span>
                    <span
                        class="text-base sm:text-lg font-bold text-slate-900 dark:text-white"
                    >
                        {{ allocationLimit }} Credits
                    </span>
                </div>
                <span
                    class="text-xs font-medium px-2 py-1 bg-slate-200 dark:bg-slate-700 rounded text-slate-600 dark:text-slate-400"
                >
                    / Employee
                </span>
            </div>
        </div>

        <!-- Edit Rules Link -->
        <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-700">
            <button
                class="w-full flex items-center justify-center gap-2 text-sm font-bold text-primary dark:text-primary hover:text-primary/80 hover:underline transition-all py-2 rounded-lg hover:bg-primary/10 dark:hover:bg-primary/10"
            >
                <Pencil class="w-4 h-4" />
                Edit Rules
            </button>
        </div>
    </div>
</template>
