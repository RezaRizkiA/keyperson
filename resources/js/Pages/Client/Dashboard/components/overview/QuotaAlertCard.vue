<script setup>
import { Link } from "@inertiajs/vue3";
import { CheckCircle, AlertTriangle } from "lucide-vue-next";
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

// Computed styles based on status
const statusStyles = computed(() => {
    const styles = {
        healthy: {
            border: "border-emerald-500",
            bar: "bg-emerald-500",
            iconBg: "bg-emerald-50 dark:bg-emerald-500/10",
            icon: "text-emerald-500",
            action: "text-emerald-600 dark:text-emerald-400",
        },
        warning: {
            border: "border-amber-500",
            bar: "bg-amber-500",
            iconBg: "bg-amber-50 dark:bg-amber-500/10",
            icon: "text-amber-500",
            action: "text-amber-600 dark:text-amber-400",
        },
        critical: {
            border: "border-red-500",
            bar: "bg-red-500",
            iconBg: "bg-red-50 dark:bg-red-500/10",
            icon: "text-red-500",
            action: "text-red-600 dark:text-red-400",
        },
    };
    return styles[props.status] || styles.critical;
});
</script>

<template>
    <Link
        :href="href"
        :class="[
            'block relative overflow-hidden py-6 px-5 rounded-2xl bg-white dark:bg-slate-800/50 border-l-4 shadow-sm hover:shadow-lg hover:scale-[1.02] transition-all duration-300 cursor-pointer group',
            statusStyles.border,
        ]"
    >
        <!-- Header: Title & Icon -->
        <div class="flex items-start justify-between mb-6">
            <div>
                <h4 class="text-lg font-bold text-slate-900 dark:text-white">
                    Status Kuota
                </h4>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                    {{ message }}
                </p>
            </div>
            <div :class="['p-2 rounded-xl', statusStyles.iconBg]">
                <CheckCircle
                    v-if="status === 'healthy'"
                    :class="['w-5 h-5', statusStyles.icon]"
                />
                <AlertTriangle v-else :class="['w-5 h-5', statusStyles.icon]" />
            </div>
        </div>

        <!-- Main Content: Percentage -->
        <div class="mb-4">
            <div class="flex items-baseline gap-2">
                <span
                    class="text-4xl font-extrabold text-slate-900 dark:text-white"
                >
                    {{ percentage }}%
                </span>
                <span class="text-lg text-slate-500 dark:text-slate-400">
                    remaining
                </span>
            </div>
            <!-- Progress Bar -->
            <div
                class="mt-3 h-2 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden"
            >
                <div
                    :class="[
                        'h-full rounded-full transition-all duration-500',
                        statusStyles.bar,
                    ]"
                    :style="{ width: percentage + '%' }"
                ></div>
            </div>
        </div>

        <!-- Action Text -->
        <p
            v-if="action"
            :class="[
                'text-sm font-medium group-hover:underline',
                statusStyles.action,
            ]"
        >
            {{ action }}
        </p>
        <p
            v-else
            class="text-sm font-medium text-slate-400 dark:text-slate-500"
        >
            {{ formatCurrency(balance) }} tersedia
        </p>
    </Link>
</template>
