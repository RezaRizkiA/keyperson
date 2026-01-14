<script setup>
import { Link } from "@inertiajs/vue3";
import { Calendar, ChevronRight } from "lucide-vue-next";

const props = defineProps({
    consultations: {
        type: Array,
        default: () => [
            {
                id: 1,
                employee: {
                    name: "John Doe",
                    initials: "JD",
                    color: "from-blue-400 to-blue-600",
                },
                topic: "Financial Planning",
                expert: "Dr. Emily Chen",
                date: "Sep 28, 2023",
                status: "completed",
            },
            {
                id: 2,
                employee: {
                    name: "Alice Smith",
                    initials: "AS",
                    color: "from-pink-400 to-pink-600",
                },
                topic: "Stress Management",
                expert: "Dr. Mark Wilson",
                date: "Sep 22, 2023",
                status: "completed",
            },
            {
                id: 3,
                employee: {
                    name: "Michael Brown",
                    initials: "MB",
                    color: "from-amber-400 to-amber-600",
                },
                topic: "Career Growth",
                expert: "Sarah Johnson",
                date: "Sep 20, 2023",
                status: "completed",
            },
        ],
    },
    viewAllHref: {
        type: String,
        default: "#",
    },
});

// Status styling
const getStatusClasses = (status) => {
    const styles = {
        completed: {
            text: "text-emerald-600 dark:text-emerald-400",
            dot: "bg-emerald-500",
            label: "Completed",
        },
        pending: {
            text: "text-amber-600 dark:text-amber-400",
            dot: "bg-amber-500",
            label: "Pending",
        },
        cancelled: {
            text: "text-red-600 dark:text-red-400",
            dot: "bg-red-500",
            label: "Cancelled",
        },
        scheduled: {
            text: "text-blue-600 dark:text-blue-400",
            dot: "bg-blue-500",
            label: "Scheduled",
        },
    };
    return styles[status] || styles.pending;
};
</script>

<template>
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
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">
                    Recent Consultations
                </h3>
            </div>
            <Link
                :href="viewAllHref"
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
            >
                Employee
            </span>
            <span
                class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider"
            >
                Topic
            </span>
            <span
                class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider"
            >
                Expert
            </span>
            <span
                class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider"
            >
                Date
            </span>
            <span
                class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider"
            >
                Status
            </span>
        </div>

        <!-- Table Body -->
        <div class="divide-y divide-slate-100 dark:divide-slate-700/50">
            <div
                v-for="consultation in consultations"
                :key="consultation.id"
                class="grid grid-cols-5 gap-4 px-6 py-4 items-center hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors"
            >
                <!-- Employee -->
                <div class="flex items-center gap-3">
                    <div
                        class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-semibold bg-linear-to-br"
                        :class="consultation.employee.color"
                    >
                        {{ consultation.employee.initials }}
                    </div>
                    <span
                        class="text-sm font-medium text-slate-900 dark:text-white"
                    >
                        {{ consultation.employee.name }}
                    </span>
                </div>

                <!-- Topic -->
                <span class="text-sm text-slate-600 dark:text-slate-300">
                    {{ consultation.topic }}
                </span>

                <!-- Expert -->
                <span class="text-sm text-slate-600 dark:text-slate-300">
                    {{ consultation.expert }}
                </span>

                <!-- Date -->
                <span class="text-sm text-slate-500 dark:text-slate-400">
                    {{ consultation.date }}
                </span>

                <!-- Status -->
                <span
                    class="inline-flex items-center gap-1.5 text-sm font-medium"
                    :class="getStatusClasses(consultation.status).text"
                >
                    <span
                        class="w-1.5 h-1.5 rounded-full"
                        :class="getStatusClasses(consultation.status).dot"
                    ></span>
                    {{ getStatusClasses(consultation.status).label }}
                </span>
            </div>
        </div>
    </div>
</template>
