<script setup>
import { Link } from "@inertiajs/vue3";
import { Wallet, Plus, AlertTriangle } from "lucide-vue-next";
import { useFormatters } from "@/composables/useFormatters";
import { computed } from "vue";

const props = defineProps({
    balance: {
        type: Number,
        default: 0,
    },
    totalBudget: {
        type: Number,
        default: 25000000,
    },
    lastTopUp: {
        type: Object,
        default: () => ({
            amount: 10000000,
            date: "Oct 15",
        }),
    },
});

const { formatCurrency } = useFormatters();

// Computed: percentage remaining
const percentageRemaining = computed(() => {
    if (props.totalBudget === 0) return 0;
    return Math.round((props.balance / props.totalBudget) * 100);
});

// Computed: status based on percentage
const balanceStatus = computed(() => {
    const percent = percentageRemaining.value;
    if (percent <= 10) {
        return {
            type: "critical",
            label: "Low Balance Alert",
            bgColor: "bg-red-500/20",
            textColor: "text-red-300",
            borderColor: "border-red-400/30",
            barColor: "bg-red-500",
            percentColor: "text-red-300",
            animate: true,
        };
    } else if (percent <= 30) {
        return {
            type: "warning",
            label: "Balance Warning",
            bgColor: "bg-amber-500/20",
            textColor: "text-amber-300",
            borderColor: "border-amber-400/30",
            barColor: "bg-amber-500",
            percentColor: "text-amber-300",
            animate: false,
        };
    } else {
        return {
            type: "healthy",
            label: "Healthy Balance",
            bgColor: "bg-emerald-500/20",
            textColor: "text-emerald-300",
            borderColor: "border-emerald-400/30",
            barColor: "bg-emerald-500",
            percentColor: "text-emerald-300",
            animate: false,
        };
    }
});
</script>

<template>
    <div
        class="relative overflow-hidden rounded-2xl p-6 lg:p-8 text-white shadow-lg h-full flex flex-col justify-between bg-linear-to-br from-primary to-secondary"
    >
        <!-- Decorative Background Icon -->
        <div
            class="absolute top-0 right-0 p-6 opacity-5 pointer-events-none transform translate-x-4 -translate-y-4"
        >
            <Wallet class="w-40 h-40" />
        </div>

        <div class="relative z-10 flex flex-col h-full justify-between">
            <!-- Header -->
            <div
                class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6"
            >
                <div>
                    <h2 class="text-xl font-bold text-white">
                        Available Balance
                    </h2>
                    <p class="text-sm text-blue-200">
                        Current budget available for employee wellness
                        allocation.
                    </p>
                </div>
                <div
                    class="mt-4 md:mt-0 flex items-center gap-2 px-3 py-1.5 rounded-full border"
                    :class="[
                        balanceStatus.bgColor,
                        balanceStatus.textColor,
                        balanceStatus.borderColor,
                    ]"
                >
                    <AlertTriangle class="w-4 h-4" />
                    <span class="text-sm font-bold">{{
                        balanceStatus.label
                    }}</span>
                </div>
            </div>

            <!-- Main Content -->
            <div class="flex flex-col gap-3">
                <div class="flex justify-between items-end flex-wrap gap-2">
                    <div class="flex items-baseline gap-2">
                        <span
                            class="text-3xl md:text-4xl font-extrabold text-white"
                        >
                            {{ formatCurrency(balance) }}
                        </span>
                        <span
                            class="text-base md:text-lg text-blue-200 font-medium"
                        >
                            / {{ formatCurrency(totalBudget) }}
                        </span>
                    </div>
                    <span
                        class="font-bold text-lg"
                        :class="balanceStatus.percentColor"
                    >
                        {{ percentageRemaining }}% Remaining
                    </span>
                </div>

                <!-- Progress Bar -->
                <div
                    class="w-full bg-white/20 rounded-full h-3 shadow-inner overflow-hidden"
                >
                    <div
                        class="h-full rounded-full transition-all duration-500"
                        :class="[
                            balanceStatus.barColor,
                            balanceStatus.animate ? 'animate-pulse' : '',
                        ]"
                        :style="{ width: percentageRemaining + '%' }"
                    ></div>
                </div>

                <!-- Footer Info -->
                <div class="flex justify-between text-xs text-blue-200 mt-1">
                    <span>Non-expiring deposit balance</span>
                    <span>
                        Last top-up: {{ formatCurrency(lastTopUp.amount) }} ({{
                            lastTopUp.date
                        }})
                    </span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div
                class="pt-6 mt-6 border-t border-white/10 flex flex-wrap items-center gap-3"
            >
                <Link
                    :href="route('topup.create')"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-blue-900 text-sm font-bold rounded-lg shadow-sm hover:bg-slate-100 hover:scale-[1.02] active:scale-95 transition-all"
                >
                    <Plus class="w-4 h-4" />
                    Top-Up Balance
                </Link>
                <button
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/10 text-white border border-white/20 text-sm font-bold rounded-lg hover:bg-white/20 hover:border-white/30 active:scale-95 transition-all"
                >
                    Manage Methods
                </button>
            </div>
        </div>
    </div>
</template>
