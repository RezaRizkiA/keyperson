<script setup>
import { Link } from '@inertiajs/vue3';
import {
    CreditCard,
    Clock,
    CheckCircle2,
    XCircle,
    ArrowRight
} from 'lucide-vue-next';

const props = defineProps({
    transactions: Array,
    isExpert: Boolean
});

// Helper Format Currency
const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value);
};

// Helper Format Date
const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
};

// Helper Status Color
const getStatusColor = (status) => {
    switch (status) {
        case 'berhasil': return 'bg-green-100 text-green-700 border-green-200';
        case 'pending': return 'bg-orange-100 text-orange-700 border-orange-200';
        case 'expired': return 'bg-red-100 text-red-700 border-red-200';
        default: return 'bg-slate-100 text-slate-700 border-slate-200';
    }
};
</script>

<template>
    <div>
        <div v-if="transactions.length === 0"
            class="text-center py-12 bg-slate-50 rounded-2xl border border-dashed border-slate-300">
            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                <CreditCard class="w-8 h-8 text-slate-300" />
            </div>
            <h3 class="text-slate-900 font-bold mb-1">No Transactions Yet</h3>
            <p class="text-slate-500 text-sm">
                {{ isExpert ? "No income transactions found." : "You haven't made any payments." }}
            </p>
        </div>

        <div v-else class="space-y-4">
            <div v-for="trx in transactions" :key="trx.id"
                class="bg-white p-5 rounded-2xl border border-slate-200 hover:border-violet-200 transition-all shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0"
                        :class="trx.status === 'berhasil' ? 'bg-green-50' : (trx.status === 'pending' ? 'bg-orange-50' : 'bg-red-50')">
                        <CheckCircle2 v-if="trx.status === 'berhasil'" class="w-6 h-6 text-green-500" />
                        <Clock v-else-if="trx.status === 'pending'" class="w-6 h-6 text-orange-500" />
                        <XCircle v-else class="w-6 h-6 text-red-500" />
                    </div>

                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-bold px-2 py-0.5 rounded border uppercase"
                                :class="getStatusColor(trx.status)">
                                {{ trx.status }}
                            </span>
                            <span class="text-xs text-slate-400">#{{ trx.sid }}</span>
                        </div>
                        <h4 class="font-bold text-slate-900 text-sm md:text-base">{{ trx.trx_desc }}</h4>
                        <p class="text-xs text-slate-500 mt-1">
                            {{ formatDate(trx.created_at) }} • via {{ trx.via }}
                        </p>
                    </div>
                </div>

                <div
                    class="flex items-center justify-between md:justify-end gap-6 w-full md:w-auto mt-2 md:mt-0 pt-3 md:pt-0 border-t md:border-t-0 border-slate-100">
                    <div class="text-right">
                        <p class="text-xs text-slate-400 font-medium">Total Amount</p>
                        <p class="font-bold text-slate-900 text-lg">{{ formatCurrency(trx.total) }}</p>
                    </div>

                    <Link v-if="trx.status === 'pending' && !isExpert" :href="route('payment.transaction', trx.sid)"
                        class="px-5 py-2.5 bg-slate-900 text-white text-sm font-bold rounded-xl hover:bg-violet-600 transition-colors shadow-lg shadow-slate-900/20 flex items-center gap-2">
                        Pay Now
                        <ArrowRight class="w-4 h-4" />
                    </Link>

                    <button v-else
                        class="px-4 py-2 bg-slate-50 text-slate-600 text-sm font-bold rounded-xl border border-slate-200 cursor-default">
                        {{ (trx.status === 'berhasil') ? 'Completed' : (trx.status === 'pending') ? 'Pending' : 'Failed' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>