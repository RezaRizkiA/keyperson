<script setup>
import { Head, Link } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import {
    ArrowLeft,
    ArrowUpRight,
    ArrowDownLeft,
    Wallet,
    Filter,
} from "lucide-vue-next";
import { ref, computed } from "vue";

defineOptions({ layout: DashboardLayout });

const props = defineProps({
    client: Object,
    ledgers: Array,
});

const filterType = ref("all");

const filteredLedgers = computed(() => {
    if (filterType.value === "all") return props.ledgers;
    if (filterType.value === "credit")
        return props.ledgers.filter((l) => l.type === "credit");
    if (filterType.value === "debit")
        return props.ledgers.filter((l) => l.type === "debit");
    return props.ledgers;
});

const formatCurrency = (amount) => {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount || 0);
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString("id-ID", {
        day: "numeric",
        month: "short",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
};

const getReferenceLabel = (type) => {
    const labels = {
        booking: "Booking",
        topup: "Top Up",
        refund: "Refund",
        adjustment: "Penyesuaian",
    };
    return labels[type] || type;
};

const getLedgerTypeColor = (type) => {
    return type === "credit"
        ? "text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10"
        : "text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-500/10";
};
</script>

<template>
    <Head title="Riwayat Transaksi Kuota" />

    <div class="max-w-4xl mx-auto space-y-8">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <Link
                    :href="route('dashboard.index')"
                    class="p-2 rounded-xl transition-colors bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700"
                >
                    <ArrowLeft
                        class="w-5 h-5 text-slate-600 dark:text-slate-400"
                    />
                </Link>
                <div>
                    <h1
                        class="text-2xl font-bold text-slate-900 dark:text-white"
                    >
                        Riwayat Transaksi
                    </h1>
                    <p class="text-slate-500 dark:text-slate-400">
                        {{ client.company_name }}
                    </p>
                </div>
            </div>

            <Link
                :href="route('topup.create')"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl transition-all"
            >
                <Wallet class="w-4 h-4" />
                Top Up
            </Link>
        </div>

        <!-- Current Balance -->
        <div
            class="p-6 rounded-3xl bg-gradient-to-r from-slate-800 to-slate-900 text-white shadow-lg"
        >
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-400">Saldo Saat Ini</p>
                    <h2 class="text-3xl font-extrabold mt-1">
                        {{ formatCurrency(client.current_balance) }}
                    </h2>
                </div>
                <Wallet class="w-12 h-12 text-white/20" />
            </div>
        </div>

        <!-- Filter Tabs -->
        <div
            class="flex gap-2 p-2 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700"
        >
            <button
                v-for="tab in [
                    { id: 'all', label: 'Semua' },
                    { id: 'credit', label: 'Masuk' },
                    { id: 'debit', label: 'Keluar' },
                ]"
                :key="tab.id"
                @click="filterType = tab.id"
                :class="[
                    'flex-1 py-2.5 px-4 rounded-xl text-sm font-semibold transition-all',
                    filterType === tab.id
                        ? 'bg-slate-900 dark:bg-slate-700 text-white'
                        : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50',
                ]"
            >
                {{ tab.label }}
            </button>
        </div>

        <!-- Ledger List -->
        <div
            class="rounded-3xl shadow-sm overflow-hidden bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50"
        >
            <div class="divide-y divide-slate-100 dark:divide-slate-700/50">
                <div
                    v-for="ledger in filteredLedgers"
                    :key="ledger.id"
                    class="p-5 hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors"
                >
                    <div class="flex items-center gap-4">
                        <div
                            :class="getLedgerTypeColor(ledger.type)"
                            class="p-3 rounded-2xl"
                        >
                            <ArrowUpRight
                                v-if="ledger.type === 'credit'"
                                class="w-5 h-5"
                            />
                            <ArrowDownLeft v-else class="w-5 h-5" />
                        </div>

                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <p
                                    class="font-semibold text-slate-900 dark:text-white"
                                >
                                    {{
                                        getReferenceLabel(ledger.reference_type)
                                    }}
                                </p>
                                <span
                                    :class="[
                                        'px-2 py-0.5 text-xs font-medium rounded-full',
                                        ledger.type === 'credit'
                                            ? 'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400'
                                            : 'bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400',
                                    ]"
                                >
                                    {{
                                        ledger.type === "credit"
                                            ? "Masuk"
                                            : "Keluar"
                                    }}
                                </span>
                            </div>
                            <p
                                class="text-sm text-slate-500 dark:text-slate-400 mt-0.5"
                            >
                                {{ ledger.description }}
                            </p>
                            <p
                                class="text-xs text-slate-400 dark:text-slate-500 mt-1"
                            >
                                {{ formatDate(ledger.created_at) }}
                            </p>
                        </div>

                        <div class="text-right">
                            <p
                                :class="[
                                    'text-lg font-bold',
                                    ledger.type === 'credit'
                                        ? 'text-emerald-600 dark:text-emerald-400'
                                        : 'text-red-600 dark:text-red-400',
                                ]"
                            >
                                {{ ledger.type === "credit" ? "+" : "-"
                                }}{{ formatCurrency(ledger.amount) }}
                            </p>
                            <p
                                class="text-sm text-slate-500 dark:text-slate-400"
                            >
                                Saldo:
                                {{ formatCurrency(ledger.balance_after) }}
                            </p>
                        </div>
                    </div>
                </div>

                <div
                    v-if="filteredLedgers.length === 0"
                    class="p-12 text-center text-slate-500 dark:text-slate-400"
                >
                    <Wallet
                        class="w-12 h-12 text-slate-300 dark:text-slate-600 mx-auto mb-4"
                    />
                    <p>Belum ada transaksi.</p>
                </div>
            </div>
        </div>
    </div>
</template>
