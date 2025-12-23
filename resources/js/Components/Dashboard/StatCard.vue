<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";
import { ArrowUpRight } from "lucide-vue-next";

const props = defineProps({
    label: String,
    value: [String, Number],
    icon: [Object, Function],
    iconColor: {
        type: String,
        default: "blue",
    },
    trend: {
        type: String,
        default: null, // e.g., '+12%', '-5%'
    },
    to: {
        type: String,
        default: null, // Route name for navigation
    },
    variant: {
        type: String,
        default: "default", // 'default' or 'gradient'
    },
});

const iconBgColor = computed(() => {
    const colors = {
        blue: "bg-blue-500/10",
        violet: "bg-violet-500/10",
        green: "bg-green-500/10",
        orange: "bg-orange-500/10",
        red: "bg-red-500/10",
    };
    return colors[props.iconColor] || colors.blue;
});

const iconTextColor = computed(() => {
    const colors = {
        blue: "text-blue-400",
        violet: "text-violet-400",
        green: "text-green-400",
        orange: "text-orange-400",
        red: "text-red-400",
    };
    return colors[props.iconColor] || colors.blue;
});

const trendColor = computed(() => {
    if (!props.trend) return "";
    return props.trend.startsWith("+") ? "text-green-400" : "text-red-400";
});

const cardClasses = computed(() => {
    if (props.variant === "gradient") {
        return "bg-gradient-to-br from-blue-500 via-blue-600 to-blue-700 border-blue-400/30";
    }
    return "bg-slate-800/50 border-slate-700/50 hover:border-slate-600/50";
});

const isClickable = computed(() => props.to !== null);
</script>

<template>
    <Link
        v-if="isClickable"
        :href="route(to)"
        class="group relative overflow-hidden backdrop-blur-sm p-6 rounded-xl border transition-all duration-300 cursor-pointer block hover:scale-[1.02] hover:shadow-xl hover:shadow-blue-500/10"
        :class="cardClasses"
    >
        <!-- Hover Overlay Effect -->
        <div
            class="absolute inset-0 bg-linear-to-br from-blue-500/0 to-violet-500/0 group-hover:from-blue-500/5 group-hover:to-violet-500/5 transition-all duration-300"
        ></div>

        <!-- Content -->
        <div class="relative z-10">
            <!-- Header: Label (left) and Icon (right) -->
            <div class="flex items-start justify-between mb-6">
                <p
                    class="text-sm font-medium"
                    :class="
                        variant === 'gradient'
                            ? 'text-blue-100'
                            : 'text-slate-400'
                    "
                >
                    {{ label }}
                </p>

                <div
                    class="w-10 h-10 rounded-lg flex items-center justify-center transition-transform duration-300 group-hover:scale-110"
                    :class="
                        variant === 'gradient' ? 'bg-white/10' : iconBgColor
                    "
                >
                    <component
                        :is="icon"
                        class="w-5 h-5 transition-transform duration-300 group-hover:rotate-3"
                        :class="
                            variant === 'gradient'
                                ? 'text-white'
                                : iconTextColor
                        "
                    />
                </div>
            </div>

            <!-- Value and Trend: Value (left) and Trend (right) -->
            <div class="flex items-end justify-between">
                <h3
                    class="text-3xl font-bold leading-none"
                    :class="
                        variant === 'gradient' ? 'text-white' : 'text-slate-100'
                    "
                >
                    {{ value }}
                </h3>

                <div v-if="trend" class="flex items-center gap-1 mb-1">
                    <span class="text-xs font-semibold" :class="trendColor">
                        {{ trend }}
                    </span>
                </div>
            </div>

            <!-- Click Indicator (arrow) - show on hover at bottom right -->
            <div
                class="absolute bottom-6 right-6 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-1 group-hover:translate-x-0"
            >
                <ArrowUpRight
                    class="w-4 h-4"
                    :class="
                        variant === 'gradient'
                            ? 'text-white/70'
                            : 'text-slate-400'
                    "
                />
            </div>
        </div>
    </Link>

    <!-- Non-clickable variant -->
    <div
        v-else
        class="relative overflow-hidden backdrop-blur-sm p-6 rounded-xl border transition-all duration-200"
        :class="cardClasses"
    >
        <div class="relative z-10">
            <!-- Header: Label (left) and Icon (right) -->
            <div class="flex items-start justify-between mb-6">
                <p
                    class="text-sm font-medium"
                    :class="
                        variant === 'gradient'
                            ? 'text-blue-100'
                            : 'text-slate-400'
                    "
                >
                    {{ label }}
                </p>

                <div
                    class="w-10 h-10 rounded-lg flex items-center justify-center"
                    :class="
                        variant === 'gradient' ? 'bg-white/10' : iconBgColor
                    "
                >
                    <component
                        :is="icon"
                        class="w-5 h-5"
                        :class="
                            variant === 'gradient'
                                ? 'text-white'
                                : iconTextColor
                        "
                    />
                </div>
            </div>

            <!-- Value and Trend: Value (left) and Trend (right) -->
            <div class="flex items-end justify-between">
                <h3
                    class="text-3xl font-bold leading-none"
                    :class="
                        variant === 'gradient' ? 'text-white' : 'text-slate-100'
                    "
                >
                    {{ value }}
                </h3>

                <div v-if="trend" class="flex items-center gap-1 mb-1">
                    <span class="text-xs font-semibold" :class="trendColor">
                        {{ trend }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>
