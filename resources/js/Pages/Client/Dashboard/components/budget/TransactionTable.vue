<script setup>
import {
    History,
    Filter,
    ChevronLeft,
    ChevronRight,
    MoreVertical,
} from "lucide-vue-next";
import { ref } from "vue";
import { useFormatters } from "@/composables/useFormatters";

const props = defineProps({
    transactions: {
        type: Array,
        default: () => [],
    },
    pagination: {
        type: Object,
        default: () => ({
            current_page: 1,
            per_page: 5,
            total: 0,
        }),
    },
});

const { formatCurrency } = useFormatters();

// Filter state
const selectedFilter = ref("last_30_days");

// Status color helper
const getStatusClasses = (status) => {
    const classes = {
        completed: {
            badge: "bg-emerald-100 dark:bg-emerald-500/20 text-emerald-800 dark:text-emerald-400",
            dot: "bg-emerald-500",
        },
        pending: {
            badge: "bg-amber-100 dark:bg-amber-500/20 text-amber-800 dark:text-amber-400",
            dot: "bg-amber-500",
        },
        failed: {
            badge: "bg-red-100 dark:bg-red-500/20 text-red-800 dark:text-red-400",
            dot: "bg-red-500",
        },
    };
    return classes[status] || classes.pending;
};

// Amount formatting with sign and color
const getAmountDisplay = (amount, type, status) => {
    const sign = type === "credit" ? "+ " : "- ";
    let color = "text-slate-900 dark:text-white";

    if (status === "pending") {
        color = "text-slate-400 dark:text-slate-500";
    } else if (type === "credit") {
        color = "text-emerald-600 dark:text-emerald-400";
    }

    return { text: sign + formatCurrency(amount), color };
};

// Capitalize first letter
const capitalize = (str) => str.charAt(0).toUpperCase() + str.slice(1);

// Computed pagination display
const paginationDisplay = () => {
    const start =
        (props.pagination.current_page - 1) * props.pagination.per_page + 1;
    const end = Math.min(
        start + props.pagination.per_page - 1,
        props.pagination.total
    );
    return `${start}-${end}`;
};
</script>

<template>
    <div
        class="rounded-2xl bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 shadow-sm overflow-hidden"
    >
        <!-- Header -->
        <div
            class="p-6 border-b border-slate-200 dark:border-slate-700/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4"
        >
            <div class="flex items-center gap-2">
                <History class="w-5 h-5 text-primary dark:text-primary" />
                <h3 class="font-bold text-lg text-slate-900 dark:text-white">
                    Transaction History
                </h3>
            </div>

            <!-- Filter Dropdown -->
            <div class="relative">
                <Filter
                    class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"
                />
                <select
                    v-model="selectedFilter"
                    class="pl-10 pr-8 py-2 text-sm border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-slate-700 dark:text-slate-300 cursor-pointer appearance-none hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors"
                >
                    <option value="last_30_days">Last 30 Days</option>
                    <option value="last_3_months">Last 3 Months</option>
                    <option value="this_year">This Year</option>
                </select>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <!-- Table Header -->
                <thead>
                    <tr
                        class="bg-slate-50 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700"
                    >
                        <th
                            class="py-4 px-6 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider whitespace-nowrap"
                        >
                            Date
                        </th>
                        <th
                            class="py-4 px-6 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider whitespace-nowrap"
                        >
                            Description
                        </th>
                        <th
                            class="py-4 px-6 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider whitespace-nowrap"
                        >
                            Amount (IDR)
                        </th>
                        <th
                            class="py-4 px-6 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider whitespace-nowrap"
                        >
                            Status
                        </th>
                        <th
                            class="py-4 px-6 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider whitespace-nowrap text-right"
                        >
                            Action
                        </th>
                    </tr>
                </thead>

                <!-- Table Body -->
                <tbody
                    class="divide-y divide-slate-100 dark:divide-slate-700/50"
                >
                    <tr
                        v-for="transaction in transactions"
                        :key="transaction.id"
                        class="group hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors"
                    >
                        <!-- Date -->
                        <td
                            class="py-4 px-6 text-sm text-slate-900 dark:text-white font-medium whitespace-nowrap"
                        >
                            {{ transaction.date }}
                        </td>

                        <!-- Description -->
                        <td class="py-4 px-6">
                            <div class="flex flex-col min-w-[200px]">
                                <span
                                    class="text-sm font-semibold text-slate-900 dark:text-white"
                                >
                                    {{ transaction.title }}
                                </span>
                                <span
                                    class="text-xs text-slate-500 dark:text-slate-400"
                                >
                                    {{ transaction.description }}
                                </span>
                            </div>
                        </td>

                        <!-- Amount -->
                        <td class="py-4 px-6 whitespace-nowrap">
                            <span
                                class="text-sm font-bold"
                                :class="
                                    getAmountDisplay(
                                        transaction.amount,
                                        transaction.type,
                                        transaction.status
                                    ).color
                                "
                            >
                                {{
                                    getAmountDisplay(
                                        transaction.amount,
                                        transaction.type,
                                        transaction.status
                                    ).text
                                }}
                            </span>
                        </td>

                        <!-- Status -->
                        <td class="py-4 px-6 whitespace-nowrap">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                :class="
                                    getStatusClasses(transaction.status).badge
                                "
                            >
                                <span
                                    class="w-1.5 h-1.5 rounded-full mr-1.5"
                                    :class="
                                        getStatusClasses(transaction.status).dot
                                    "
                                ></span>
                                {{ capitalize(transaction.status) }}
                            </span>
                        </td>

                        <!-- Action -->
                        <td class="py-4 px-6 text-right">
                            <button
                                class="text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors p-1 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-full"
                            >
                                <MoreVertical class="w-5 h-5" />
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div
            class="px-6 py-4 border-t border-slate-200 dark:border-slate-700/50 flex items-center justify-between"
        >
            <p
                class="text-sm text-slate-500 dark:text-slate-400 hidden sm:block"
            >
                Showing
                <span class="font-bold text-slate-900 dark:text-white">{{
                    paginationDisplay()
                }}</span>
                of
                <span class="font-bold text-slate-900 dark:text-white">{{
                    pagination.total
                }}</span>
                transactions
            </p>
            <div class="flex gap-2 w-full sm:w-auto justify-end">
                <button
                    :disabled="pagination.current_page <= 1"
                    class="p-2 border border-slate-200 dark:border-slate-700 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                >
                    <ChevronLeft class="w-4 h-4" />
                </button>
                <button
                    class="p-2 border border-slate-200 dark:border-slate-700 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                >
                    <ChevronRight class="w-4 h-4" />
                </button>
            </div>
        </div>
    </div>
</template>
