<script setup>
import { Head, Link } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import { Download, ChevronRight } from "lucide-vue-next";
import { ref } from "vue";

// Components
import BalanceCard from "./components/budget/BalanceCard.vue";
import CreditRulesCard from "./components/budget/CreditRulesCard.vue";
import TransactionTable from "./components/budget/TransactionTable.vue";

defineOptions({ layout: DashboardLayout });

const props = defineProps({
    data: Object,
});

// =========================================================================
// DUMMY DATA (akan diganti dengan props.data dari backend)
// =========================================================================

const budgetData = ref({
    current_balance: 2000000, // Low balance untuk demo alert
    total_budget: 25000000,
    last_topup: {
        amount: 10000000,
        date: "Oct 15",
    },
    credit_rules: {
        exchange_rate: 250000, // 1 Credit = Rp 250.000
        allocation_limit: 4, // max credits per employee
    },
    transactions: [
        {
            id: 1,
            date: "Oct 24, 2023",
            title: "Monthly Budget Top-Up",
            description: "Admin Transfer via BCA",
            amount: 10000000,
            type: "credit",
            status: "completed",
        },
        {
            id: 2,
            date: "Oct 22, 2023",
            title: "Employee Credit Allocation",
            description: "Batch #203 - 12 Employees",
            amount: 3000000,
            type: "debit",
            status: "completed",
        },
        {
            id: 3,
            date: "Oct 20, 2023",
            title: "Wellness Program Reward",
            description: "Q3 High Performers",
            amount: 1500000,
            type: "debit",
            status: "completed",
        },
        {
            id: 4,
            date: "Oct 15, 2023",
            title: "Pending Top-Up",
            description: "Waiting for approval",
            amount: 5000000,
            type: "credit",
            status: "pending",
        },
        {
            id: 5,
            date: "Oct 12, 2023",
            title: "Employee Credit Allocation",
            description: "Batch #202 - 15 Employees",
            amount: 3750000,
            type: "debit",
            status: "completed",
        },
    ],
    pagination: {
        current_page: 1,
        per_page: 5,
        total: 48,
    },
});
</script>

<template>
    <Head title="Settings & Budget" />

    <div class="space-y-6 lg:space-y-8">
        <!-- Breadcrumb & Header -->
        <div class="flex flex-col gap-2">
            <!-- Breadcrumb -->
            <nav
                class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400"
            >
                <Link
                    :href="route('dashboard.client.index')"
                    class="hover:text-primary dark:hover:text-primary transition-colors"
                >
                    Home
                </Link>
                <ChevronRight class="w-4 h-4" />
                <span class="font-medium text-slate-900 dark:text-white"
                    >Budget</span
                >
            </nav>

            <!-- Title & Export -->
            <div
                class="flex flex-col sm:flex-row sm:items-center justify-between gap-4"
            >
                <h1
                    class="text-2xl md:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight"
                >
                    Settings & Budget
                </h1>
                <button
                    class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 text-sm font-semibold rounded-lg shadow-sm transition-all"
                >
                    <Download class="w-4 h-4" />
                    Export Report
                </button>
            </div>
        </div>

        <!-- Top Cards Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <BalanceCard
                    :balance="budgetData.current_balance"
                    :total-budget="budgetData.total_budget"
                    :last-top-up="budgetData.last_topup"
                />
            </div>
            <div class="lg:col-span-1">
                <CreditRulesCard
                    :exchange-rate="budgetData.credit_rules.exchange_rate"
                    :allocation-limit="budgetData.credit_rules.allocation_limit"
                />
            </div>
        </div>

        <!-- Transaction History Section -->
        <TransactionTable
            :transactions="budgetData.transactions"
            :pagination="budgetData.pagination"
        />
    </div>
</template>
