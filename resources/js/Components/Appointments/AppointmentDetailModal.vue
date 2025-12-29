<template>
    <Teleport to="body">
        <div
            v-if="show && appointment"
            class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4"
            @click="emit('close')"
        >
            <div
                class="bg-slate-800 rounded-xl border border-slate-700 max-w-2xl w-full shadow-2xl"
                @click.stop
            >
                <!-- Modal Header -->
                <div class="p-6 border-b border-slate-700">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-slate-200">
                                Appointment Details
                            </h3>
                            <p class="text-sm text-slate-400 mt-1">
                                ID: #{{ appointment.id }}
                            </p>
                        </div>
                        <button
                            @click="emit('close')"
                            class="text-slate-400 hover:text-slate-200 transition-colors"
                        >
                            <X class="w-6 h-6" />
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-4">
                    <!-- Client Info -->
                    <div>
                        <label
                            class="text-xs text-slate-500 uppercase tracking-wider"
                            >Client</label
                        >
                        <div class="mt-1 text-slate-200 font-semibold">
                            {{ appointment.client_name }}
                        </div>
                        <div class="text-sm text-slate-400">
                            {{ appointment.company_name || "-" }}
                        </div>
                    </div>

                    <!-- Expert Info -->
                    <div>
                        <label
                            class="text-xs text-slate-500 uppercase tracking-wider"
                            >Expert</label
                        >
                        <div class="mt-1 text-slate-200 font-semibold">
                            {{ appointment.expert_name }}
                        </div>
                    </div>

                    <!-- Date & Time -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label
                                class="text-xs text-slate-500 uppercase tracking-wider"
                                >Date & Time</label
                            >
                            <div class="mt-1 text-slate-200">
                                {{ formatDateTime(appointment.date_time).date }}
                            </div>
                            <div class="text-sm text-slate-400">
                                {{ formatDateTime(appointment.date_time).time }}
                            </div>
                        </div>
                        <div>
                            <label
                                class="text-xs text-slate-500 uppercase tracking-wider"
                                >Duration</label
                            >
                            <div class="mt-1 text-slate-200">
                                {{ appointment.hours }} hour(s)
                            </div>
                        </div>
                    </div>

                    <!-- Status & Payment -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label
                                class="text-xs text-slate-500 uppercase tracking-wider"
                                >Status</label
                            >
                            <div class="mt-1">
                                <span
                                    class="inline-flex px-3 py-1 rounded-full text-sm border"
                                    :class="getStatusColor(appointment.status)"
                                >
                                    {{ appointment.status }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <label
                                class="text-xs text-slate-500 uppercase tracking-wider"
                                >Payment</label
                            >
                            <div class="mt-1">
                                <span
                                    class="inline-flex px-3 py-1 rounded-full text-sm border"
                                    :class="
                                        getPaymentStatusColor(
                                            appointment.payment_status
                                        )
                                    "
                                >
                                    {{ appointment.payment_status }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Price -->
                    <div>
                        <label
                            class="text-xs text-slate-500 uppercase tracking-wider"
                            >Total Price</label
                        >
                        <div class="mt-1 text-slate-200 text-lg font-bold">
                            Rp
                            {{ appointment.price?.toLocaleString() || "-" }}
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div
                    class="p-6 border-t border-slate-700 flex justify-end gap-3"
                >
                    <button
                        @click="emit('close')"
                        class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-200 rounded-lg transition-colors"
                    >
                        Close
                    </button>
                    <Link
                        :href="
                            route('dashboard.appointments.show', appointment.id)
                        "
                        class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors"
                    >
                        View Full Details
                    </Link>
                </div>
            </div>
        </div>
    </Teleport>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import { X } from "lucide-vue-next";
import { formatDateTime } from "@/Utils/dateUtils";
import {
    getStatusColor,
    getPaymentStatusColor,
} from "@/Utils/appointmentUtils";

const props = defineProps({
    show: {
        type: Boolean,
        required: true,
        default: false,
    },
    appointment: {
        type: Object,
        required: false,
        default: null,
    },
});

const emit = defineEmits(["close"]);
</script>
