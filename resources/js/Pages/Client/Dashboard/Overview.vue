<script setup>
import { Head, Link } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import { Plus, History, ChevronRight } from "lucide-vue-next";
import { useFormatters } from "@/composables/useFormatters";

// Components
import QuotaAlertCard from "./components/overview/QuotaAlertCard.vue";
import EngagementCard from "./components/overview/EngagementCard.vue";
import TopInterestCard from "./components/overview/TopInterestCard.vue";
import ExpertRatingCard from "./components/overview/ExpertRatingCard.vue";
import BudgetOverviewCard from "./components/overview/BudgetOverviewCard.vue";
import QuickActionsCard from "./components/overview/QuickActionsCard.vue";
import RecentConsultationsCard from "./components/overview/RecentConsultationsCard.vue";

defineOptions({ layout: DashboardLayout });

const props = defineProps({
    data: Object,
});

// Menggunakan composables
const { formatCurrency } = useFormatters();
</script>

<template>
    <Head title="HRD Dashboard" />

    <div class="space-y-8">
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
                    >Strategic Overview</span
                >
            </nav>

            <!-- Title & Actions -->
            <div
                class="flex flex-col sm:flex-row sm:items-center justify-between gap-4"
            >
                <div>
                    <h1
                        class="text-2xl md:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight"
                    >
                        {{ data.company_name }}
                    </h1>
                    <p class="text-slate-500 dark:text-slate-400 mt-1">
                        Kelola kuota dan pantau aktivitas booking karyawan Anda.
                    </p>
                </div>

                <!-- action button -->
                <div class="flex gap-3">
                    <Link
                        :href="route('topup.history')"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-sm font-semibold rounded-xl transition-all"
                    >
                        <History class="w-4 h-4" />
                        Riwayat
                    </Link>
                    <Link
                        :href="route('topup.create')"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-emerald-500/20"
                    >
                        <Plus class="w-4 h-4" />
                        Top Up Kuota
                    </Link>
                </div>
            </div>
        </div>

        <!-- Data Cards -->
        <div>
            <p class="text-xl font-bold text-slate-900 dark:text-white mb-4">
                Strategic Analytics
            </p>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                <!-- Quota Alert Card -->
                <QuotaAlertCard
                    :status="data.quota_status"
                    :message="data.quota_message"
                    :percentage="data.quota_percentage"
                    :balance="data.quota_balance"
                    :action="data.quota_action"
                    :href="route('dashboard.client.budget')"
                />

                <!-- Employee Engagement Card -->
                <EngagementCard
                    :percentage="data.engagement_percentage"
                    :change="data.engagement_change"
                />

                <!-- Top Interest Categories Card -->
                <TopInterestCard />

                <!-- Expert Rating Card -->
                <ExpertRatingCard :rating="4.8" :total-sessions="118" />
            </div>
        </div>

        <!-- Budget Overview & Quick Actions Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Budget Overview Card -->
            <BudgetOverviewCard />

            <!-- Quick Actions Card -->
            <QuickActionsCard />
        </div>

        <!-- Recent Consultations Card (Full Width) -->
        <RecentConsultationsCard />
    </div>
</template>
