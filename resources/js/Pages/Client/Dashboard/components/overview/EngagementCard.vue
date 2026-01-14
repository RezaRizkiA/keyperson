<script setup>
import { Link } from "@inertiajs/vue3";
import { Users, ArrowUpRight, ArrowDownRight } from "lucide-vue-next";
import { computed } from "vue";

const props = defineProps({
    percentage: {
        type: Number,
        default: 0,
    },
    change: {
        type: Number,
        default: 0,
    },
    href: {
        type: String,
        default: "#",
    },
});

// SVG circle circumference: 2 * PI * radius = 2 * 3.14159 * 15.5 ≈ 97.4
const CIRCUMFERENCE = 97.4;

// Computed: stroke-dasharray for donut chart
const dashArray = computed(() => {
    const filled = (props.percentage / 100) * CIRCUMFERENCE;
    return `${filled}, ${CIRCUMFERENCE}`;
});

// Computed: change text and styling
const changeText = computed(() => {
    if (props.change > 0) return `+${props.change}% vs last month`;
    if (props.change < 0) return `${props.change}% vs last month`;
    return "No change vs last month";
});

const isPositiveChange = computed(() => props.change >= 0);
</script>

<template>
    <Link
        :href="href"
        class="block py-6 px-5 rounded-2xl shadow-sm transition-all duration-300 hover:shadow-lg hover:scale-[1.02] cursor-pointer group relative overflow-hidden bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50"
    >
        <!-- Header -->
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-base font-bold text-slate-900 dark:text-white">
                Total Employee Engagement
            </h4>
            <div class="p-2 bg-blue-50 dark:bg-blue-500/10 rounded-xl">
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
                        :stroke-dasharray="dashArray"
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
                    >
                        {{ percentage }}%
                    </span>
                    <span class="text-xs text-slate-500 dark:text-slate-400">
                        Active
                    </span>
                </div>
            </div>
        </div>

        <!-- Comparison -->
        <div class="flex items-center justify-center gap-1 text-sm">
            <component
                :is="isPositiveChange ? ArrowUpRight : ArrowDownRight"
                :class="[
                    'w-4 h-4',
                    isPositiveChange ? 'text-emerald-500' : 'text-red-500',
                ]"
            />
            <span
                :class="[
                    'font-medium group-hover:underline',
                    isPositiveChange
                        ? 'text-emerald-600 dark:text-emerald-400'
                        : 'text-red-600 dark:text-red-400',
                ]"
            >
                {{ changeText }}
            </span>
        </div>
    </Link>
</template>
