<script setup>
import { Head, Link } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import { CheckCircle, Wallet, ArrowRight } from "lucide-vue-next";

defineOptions({ layout: DashboardLayout });

const props = defineProps({
    transaction: Object,
    amount: Number,
});

const formatCurrency = (amount) => {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount || 0);
};
</script>

<template>
    <Head title="Top Up Berhasil" />

    <div class="max-w-xl mx-auto py-12">
        <div
            class="rounded-3xl shadow-lg p-8 text-center bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50"
        >
            <div
                class="w-20 h-20 bg-emerald-100 dark:bg-emerald-500/20 rounded-full flex items-center justify-center mx-auto mb-6"
            >
                <CheckCircle
                    class="w-10 h-10 text-emerald-600 dark:text-emerald-400"
                />
            </div>

            <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">
                Top Up Berhasil!
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mb-6">
                Kuota perusahaan Anda telah ditambahkan.
            </p>

            <div
                class="rounded-2xl p-6 mb-8 bg-emerald-50 dark:bg-emerald-500/10"
            >
                <p
                    class="text-sm text-emerald-600 dark:text-emerald-400 font-medium"
                >
                    Jumlah Top Up
                </p>
                <p
                    class="text-3xl font-extrabold text-emerald-700 dark:text-emerald-300 mt-2"
                >
                    {{ formatCurrency(amount) }}
                </p>
            </div>

            <div class="flex flex-col gap-3">
                <Link
                    :href="route('dashboard.index')"
                    class="w-full py-3 px-6 bg-slate-900 dark:bg-blue-600 hover:bg-slate-800 dark:hover:bg-blue-700 text-white font-bold rounded-xl transition-all flex items-center justify-center gap-2"
                >
                    Ke Dashboard
                    <ArrowRight class="w-4 h-4" />
                </Link>
                <Link
                    :href="route('topup.history')"
                    class="w-full py-3 px-6 font-semibold rounded-xl transition-all bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300"
                >
                    Lihat Riwayat
                </Link>
            </div>
        </div>
    </div>
</template>
