<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import { Wallet, CreditCard, ArrowLeft, Check } from "lucide-vue-next";
import { ref } from "vue";

defineOptions({ layout: DashboardLayout });

const props = defineProps({
    client: Object,
    predefinedAmounts: Array,
    minAmount: Number,
    maxAmount: Number,
    paymentChannels: {
        type: Array,
        default: () => [],
    },
});

const selectedAmount = ref(null);
const customAmount = ref("");
const selectedPaymentMethod = ref("");

const form = useForm({
    amount: 0,
    payment_method: "",
});

const selectAmount = (amount) => {
    selectedAmount.value = amount;
    customAmount.value = "";
    form.amount = amount;
};

const updateCustomAmount = () => {
    selectedAmount.value = null;
    form.amount = parseInt(customAmount.value) || 0;
};

const selectPaymentMethod = (method) => {
    selectedPaymentMethod.value = method;
    form.payment_method = method;
};

const submit = () => {
    if (form.amount < props.minAmount) {
        alert(
            `Minimum top-up adalah Rp ${props.minAmount.toLocaleString(
                "id-ID"
            )}`
        );
        return;
    }
    if (!form.payment_method) {
        alert("Silakan pilih metode pembayaran");
        return;
    }
    form.post(route("topup.store"));
};

const formatCurrency = (amount) => {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount || 0);
};

const paymentMethods = [
    {
        id: "va",
        name: "Virtual Account",
        description: "Transfer via ATM/Mobile Banking",
    },
    { id: "qris", name: "QRIS", description: "Scan QR dengan e-wallet apapun" },
    {
        id: "cstore",
        name: "Convenience Store",
        description: "Bayar di Alfamart/Indomaret",
    },
];
</script>

<template>
    <Head title="Top Up Kuota" />

    <div class="max-w-4xl mx-auto space-y-8">
        <!-- Header -->
        <div class="flex items-center gap-4">
            <Link
                :href="route('dashboard.index')"
                class="p-2 rounded-xl transition-colors bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700"
            >
                <ArrowLeft class="w-5 h-5 text-slate-600 dark:text-slate-400" />
            </Link>
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">
                    Top Up Kuota
                </h1>
                <p class="text-slate-500 dark:text-slate-400">
                    {{ client.company_name }}
                </p>
            </div>
        </div>

        <!-- Current Balance Card -->
        <div
            class="p-6 rounded-3xl bg-gradient-to-br from-emerald-500 to-emerald-600 text-white shadow-lg"
        >
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-emerald-100">
                        Saldo Saat Ini
                    </p>
                    <h2 class="text-3xl font-extrabold mt-1">
                        {{ formatCurrency(client.current_balance) }}
                    </h2>
                </div>
                <Wallet class="w-12 h-12 text-white/30" />
            </div>
        </div>

        <form @submit.prevent="submit" class="space-y-8">
            <!-- Amount Selection -->
            <div
                class="rounded-3xl shadow-sm p-6 bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50"
            >
                <h3
                    class="text-lg font-bold text-slate-900 dark:text-white mb-4"
                >
                    Pilih Nominal
                </h3>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mb-6">
                    <button
                        v-for="preset in predefinedAmounts"
                        :key="preset.value"
                        type="button"
                        @click="selectAmount(preset.value)"
                        :class="[
                            'p-4 rounded-2xl border-2 text-center font-semibold transition-all',
                            selectedAmount === preset.value
                                ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400'
                                : 'border-slate-200 dark:border-slate-600 hover:border-slate-300 dark:hover:border-slate-500 text-slate-700 dark:text-slate-300',
                        ]"
                    >
                        {{ preset.label }}
                    </button>
                </div>

                <div class="relative">
                    <span
                        class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 dark:text-slate-400 font-medium"
                    >
                        Rp
                    </span>
                    <input
                        v-model="customAmount"
                        @input="updateCustomAmount"
                        type="number"
                        placeholder="Atau masukkan nominal lain..."
                        class="w-full pl-12 pr-4 py-4 border-2 rounded-2xl transition-colors bg-white dark:bg-slate-800/50 border-slate-200 dark:border-slate-600 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:border-emerald-500 focus:ring-0"
                        :min="minAmount"
                        :max="maxAmount"
                    />
                </div>

                <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">
                    Min: {{ formatCurrency(minAmount) }} | Max:
                    {{ formatCurrency(maxAmount) }}
                </p>
            </div>

            <!-- Payment Method Selection -->
            <div
                class="rounded-3xl shadow-sm p-6 bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50"
            >
                <h3
                    class="text-lg font-bold text-slate-900 dark:text-white mb-4"
                >
                    Metode Pembayaran
                </h3>

                <div class="space-y-3">
                    <button
                        v-for="method in paymentMethods"
                        :key="method.id"
                        type="button"
                        @click="selectPaymentMethod(method.id)"
                        :class="[
                            'w-full p-4 rounded-2xl border-2 text-left transition-all flex items-center gap-4',
                            selectedPaymentMethod === method.id
                                ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-500/10'
                                : 'border-slate-200 dark:border-slate-600 hover:border-slate-300 dark:hover:border-slate-500',
                        ]"
                    >
                        <div
                            :class="[
                                'w-6 h-6 rounded-full border-2 flex items-center justify-center',
                                selectedPaymentMethod === method.id
                                    ? 'border-emerald-500 bg-emerald-500'
                                    : 'border-slate-300 dark:border-slate-500',
                            ]"
                        >
                            <Check
                                v-if="selectedPaymentMethod === method.id"
                                class="w-4 h-4 text-white"
                            />
                        </div>
                        <div class="flex-1">
                            <p
                                class="font-semibold text-slate-900 dark:text-white"
                            >
                                {{ method.name }}
                            </p>
                            <p
                                class="text-sm text-slate-500 dark:text-slate-400"
                            >
                                {{ method.description }}
                            </p>
                        </div>
                        <CreditCard
                            class="w-6 h-6 text-slate-400 dark:text-slate-500"
                        />
                    </button>
                </div>
            </div>

            <!-- Summary & Submit -->
            <div
                class="rounded-3xl shadow-sm p-6 bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50"
            >
                <div class="flex items-center justify-between mb-6">
                    <span class="text-slate-600 dark:text-slate-400"
                        >Total Top Up</span
                    >
                    <span
                        class="text-2xl font-bold text-slate-900 dark:text-white"
                    >
                        {{ formatCurrency(form.amount) }}
                    </span>
                </div>

                <button
                    type="submit"
                    :disabled="
                        form.processing ||
                        form.amount < minAmount ||
                        !form.payment_method
                    "
                    :class="[
                        'w-full py-4 rounded-2xl font-bold text-lg transition-all',
                        form.amount >= minAmount && form.payment_method
                            ? 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-lg shadow-emerald-500/20'
                            : 'bg-slate-100 dark:bg-slate-700 text-slate-400 dark:text-slate-500 cursor-not-allowed',
                    ]"
                >
                    <span v-if="form.processing">Memproses...</span>
                    <span v-else>Lanjutkan Pembayaran</span>
                </button>
            </div>
        </form>
    </div>
</template>
