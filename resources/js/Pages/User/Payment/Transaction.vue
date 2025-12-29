<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, computed, onMounted, onUnmounted } from "vue";
import {
    Clock,
    CreditCard,
    Copy,
    CheckCircle2,
    AlertCircle,
    ArrowLeft,
    RefreshCw,
    Calendar,
    User,
} from "lucide-vue-next";

const props = defineProps({
    transaction: Object,
    qrCodeImage: String,
    appointment: Object,
});

// Copy to clipboard state
const copied = ref(false);

// Countdown timer
const timeRemaining = ref("");
const isExpired = ref(false);

// Format currency
const formatCurrency = (value) => {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(value);
};

// Copy payment number
const copyPaymentNo = () => {
    if (props.transaction.paymentNo) {
        navigator.clipboard.writeText(props.transaction.paymentNo);
        copied.value = true;
        setTimeout(() => (copied.value = false), 2000);
    }
};

// Calculate countdown
const updateCountdown = () => {
    const expiry = new Date(props.transaction.expired_date);
    const now = new Date();
    const diff = expiry - now;

    if (diff <= 0) {
        isExpired.value = true;
        timeRemaining.value = "Expired";
        return;
    }

    const hours = Math.floor(diff / (1000 * 60 * 60));
    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((diff % (1000 * 60)) / 1000);
    timeRemaining.value = `${hours.toString().padStart(2, "0")}:${minutes
        .toString()
        .padStart(2, "0")}:${seconds.toString().padStart(2, "0")}`;
};

// Poll for payment status
const checkPaymentStatus = () => {
    router.reload({ only: ["transaction"] });
};

let countdownInterval;
let pollInterval;

onMounted(() => {
    updateCountdown();
    countdownInterval = setInterval(updateCountdown, 1000);
    // Poll every 30 seconds (less intrusive)
    pollInterval = setInterval(checkPaymentStatus, 30000);
});

onUnmounted(() => {
    if (countdownInterval) clearInterval(countdownInterval);
    if (pollInterval) clearInterval(pollInterval);
});

// Payment method badge style
const getMethodBadge = computed(() => {
    const method = props.transaction.via?.toLowerCase();
    const badges = {
        qris: "bg-purple-100 dark:bg-purple-500/20 text-purple-700 dark:text-purple-400",
        va: "bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400",
        "bank transfer":
            "bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400",
        "e-wallet":
            "bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400",
    };
    return (
        badges[method] ||
        "bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300"
    );
});
</script>

<template>
    <Head title="Payment Pending" />

    <div
        class="bg-slate-50 dark:bg-slate-900 min-h-screen font-sans pt-12 pb-20"
    >
        <div class="max-w-2xl mx-auto px-4 sm:px-6">
            <!-- Back Button -->
            <Link
                :href="route('dashboard.appointments.index')"
                class="inline-flex items-center gap-2 text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100 mb-6 transition-colors"
            >
                <ArrowLeft class="w-4 h-4" />
                Back to Appointments
            </Link>

            <!-- Main Card -->
            <div
                class="bg-white dark:bg-slate-800 rounded-3xl p-8 border border-slate-200 dark:border-slate-700 shadow-xl"
            >
                <!-- Header -->
                <div class="text-center mb-8">
                    <div
                        class="w-16 h-16 mx-auto mb-4 rounded-full bg-amber-100 dark:bg-amber-500/20 flex items-center justify-center"
                    >
                        <Clock
                            class="w-8 h-8 text-amber-600 dark:text-amber-400"
                        />
                    </div>
                    <h1
                        class="text-2xl font-bold text-slate-900 dark:text-slate-100"
                    >
                        Waiting for Payment
                    </h1>
                    <p class="text-slate-500 dark:text-slate-400 mt-2">
                        Complete payment before the timer expires
                    </p>
                </div>

                <!-- Countdown Timer -->
                <div
                    class="mb-8 p-4 rounded-2xl text-center"
                    :class="
                        isExpired
                            ? 'bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/30'
                            : 'bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/30'
                    "
                >
                    <p
                        class="text-xs font-bold uppercase tracking-wider mb-1"
                        :class="
                            isExpired
                                ? 'text-red-600 dark:text-red-400'
                                : 'text-amber-600 dark:text-amber-400'
                        "
                    >
                        Time Remaining
                    </p>
                    <p
                        class="text-3xl font-mono font-bold"
                        :class="
                            isExpired
                                ? 'text-red-700 dark:text-red-400'
                                : 'text-amber-700 dark:text-amber-400'
                        "
                    >
                        {{ timeRemaining }}
                    </p>
                </div>

                <!-- Payment Method Badge -->
                <div class="flex justify-center mb-6">
                    <span
                        class="px-4 py-2 rounded-full text-sm font-bold"
                        :class="getMethodBadge"
                    >
                        {{ transaction.via }} - {{ transaction.channel }}
                    </span>
                </div>

                <!-- QR Code (for QRIS) -->
                <div v-if="qrCodeImage" class="mb-8 flex flex-col items-center">
                    <div
                        class="p-4 bg-white rounded-2xl border-2 border-slate-200 dark:border-slate-600 shadow-lg"
                    >
                        <img
                            :src="qrCodeImage"
                            alt="QR Code"
                            class="w-64 h-64"
                        />
                    </div>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-3">
                        Scan with any e-wallet app
                    </p>
                </div>

                <!-- VA / Payment Number (for non-QRIS) -->
                <div
                    v-else-if="transaction.paymentNo"
                    class="mb-8 p-4 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-200 dark:border-slate-700"
                >
                    <p
                        class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-2"
                    >
                        {{ transaction.paymentName || "Payment Number" }}
                    </p>
                    <div class="flex items-center gap-3">
                        <p
                            class="flex-1 text-2xl font-mono font-bold text-slate-900 dark:text-slate-100 tracking-wider"
                        >
                            {{ transaction.paymentNo }}
                        </p>
                        <button
                            @click="copyPaymentNo"
                            class="flex items-center gap-2 px-4 py-2 rounded-xl transition-colors"
                            :class="
                                copied
                                    ? 'bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400'
                                    : 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-300 dark:hover:bg-slate-600'
                            "
                        >
                            <Copy v-if="!copied" class="w-4 h-4" />
                            <CheckCircle2 v-else class="w-4 h-4" />
                            {{ copied ? "Copied!" : "Copy" }}
                        </button>
                    </div>
                </div>

                <!-- Amount -->
                <div
                    class="mb-8 p-4 bg-violet-50 dark:bg-violet-500/10 rounded-2xl border border-violet-200 dark:border-violet-500/30 flex justify-between items-center"
                >
                    <p
                        class="text-sm font-bold text-violet-600 dark:text-violet-400"
                    >
                        Total Amount
                    </p>
                    <p
                        class="text-2xl font-bold text-violet-700 dark:text-violet-400"
                    >
                        {{ formatCurrency(transaction.total) }}
                    </p>
                </div>

                <!-- Appointment Info -->
                <div
                    v-if="appointment"
                    class="mb-6 p-4 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-200 dark:border-slate-700"
                >
                    <p
                        class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-3"
                    >
                        Booking Details
                    </p>
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center gap-2">
                            <User class="w-4 h-4 text-slate-400" />
                            <span class="text-slate-600 dark:text-slate-300"
                                >Expert:</span
                            >
                            <span
                                class="font-bold text-slate-900 dark:text-slate-100"
                                >{{
                                    appointment.expert?.user?.name || "Expert"
                                }}</span
                            >
                        </div>
                        <div class="flex items-center gap-2">
                            <Calendar class="w-4 h-4 text-slate-400" />
                            <span class="text-slate-600 dark:text-slate-300"
                                >Schedule:</span
                            >
                            <span
                                class="font-bold text-slate-900 dark:text-slate-100"
                                >{{
                                    new Date(
                                        appointment.date_time
                                    ).toLocaleString("id-ID")
                                }}</span
                            >
                        </div>
                    </div>
                </div>

                <!-- Instructions -->
                <div
                    class="p-4 bg-blue-50 dark:bg-blue-500/10 rounded-2xl border border-blue-200 dark:border-blue-500/30"
                >
                    <div class="flex items-start gap-3">
                        <AlertCircle
                            class="w-5 h-5 text-blue-600 dark:text-blue-400 shrink-0 mt-0.5"
                        />
                        <div class="text-sm text-blue-700 dark:text-blue-300">
                            <p class="font-bold mb-1">Payment Instructions</p>
                            <ul
                                class="list-disc list-inside space-y-1 opacity-90"
                            >
                                <li v-if="qrCodeImage">
                                    Scan the QR code above with your e-wallet
                                    app
                                </li>
                                <li v-else>
                                    Transfer the exact amount to the payment
                                    number above
                                </li>
                                <li>Payment will be verified automatically</li>
                                <li>
                                    This page will refresh when payment is
                                    confirmed
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Refresh Button -->
                <button
                    @click="checkPaymentStatus"
                    class="w-full mt-6 py-3 flex items-center justify-center gap-2 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 rounded-xl font-medium transition-colors"
                >
                    <RefreshCw class="w-4 h-4" />
                    Check Payment Status
                </button>
            </div>
        </div>
    </div>
</template>
