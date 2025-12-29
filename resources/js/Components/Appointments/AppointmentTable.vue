<script setup>
import { Link } from "@inertiajs/vue3";
import { ArrowRight } from "lucide-vue-next"; // Icon lain tidak perlu diimport karena tidak dipakai

const props = defineProps({
    appointments: Object,
});

// Helper Status Config
const statusConfig = {
    pending: {
        class: "bg-yellow-50 text-yellow-700 border-yellow-200",
        label: "Waiting Payment",
    },
    need_confirmation: {
        class: "bg-orange-50 text-orange-700 border-orange-200",
        label: "Action Needed",
    },
    confirmed: {
        class: "bg-green-50 text-green-700 border-green-200",
        label: "Scheduled",
    },
    progress: {
        class: "bg-blue-50 text-blue-700 border-blue-200",
        label: "In Session",
    },
    completed: {
        class: "bg-slate-100 text-slate-700 border-slate-200",
        label: "Completed",
    },
    declined: {
        class: "bg-red-50 text-red-700 border-red-200",
        label: "Declined",
    },
};

const getStatus = (status) =>
    statusConfig[status] || { class: "bg-gray-50", label: status };

const paymentConfig = {
    pending:  { class: 'text-amber-600 bg-amber-50 border-amber-100', icon: 'Clock', label: 'Unpaid' },
    berhasil: { class: 'text-emerald-600 bg-emerald-50 border-emerald-100', icon: 'CheckCircle', label: 'Paid' },
    expired:  { class: 'text-slate-500 bg-slate-50 border-slate-200', icon: 'XCircle', label: 'Expired' },
};

const getPayment = (status) => paymentConfig[status] || { class: 'text-slate-400', label: status || '-' };
</script>

<template>
    <div
        class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden"
    >
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr
                        class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider"
                    >
                        <th class="px-6 py-4">Ref ID</th>
                        <th class="px-6 py-4">Client (User)</th>
                        <th class="px-6 py-4">Expert</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Payment Status</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr
                        v-for="appointment in appointments.data"
                        :key="appointment.id"
                        class="group hover:bg-slate-50/80 transition-colors"
                    >
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="font-mono text-sm font-bold text-violet-600"
                            >
                                #{{ appointment.id }}
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xs shrink-0"
                                >
                                    {{ appointment.user.name.charAt(0) }}
                                </div>
                                <div>
                                    <div
                                        class="font-medium text-slate-900 text-sm"
                                    >
                                        {{ appointment.user.name }}
                                    </div>
                                    <div class="text-xs text-slate-500">
                                        {{ appointment.user.email }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-violet-100 flex items-center justify-center text-violet-600 font-bold text-xs shrink-0"
                                >
                                    {{ appointment.expert.user.name.charAt(0) }}
                                </div>
                                <div>
                                    <div
                                        class="font-medium text-slate-900 text-sm"
                                    >
                                        {{ appointment.expert.user.name }}
                                    </div>
                                    <div class="text-xs text-slate-500">
                                        {{ appointment.expert.user.email }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border"
                                :class="getStatus(appointment.status).class"
                            >
                                <span
                                    class="w-1.5 h-1.5 rounded-full bg-current mr-1.5 opacity-60"
                                ></span>
                                {{ getStatus(appointment.status).label }}
                            </span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border"
                                :class="getPayment(appointment.payment_status).class"
                            >
                                <span
                                    class="w-1.5 h-1.5 rounded-full bg-current mr-1.5 opacity-60"
                                ></span>
                                {{ getPayment(appointment.payment_status).label }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-right">
                            <Link
                                :href="
                                    route(
                                        'dashboard.appointments.show',
                                        appointment.id
                                    )
                                "
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-bold text-slate-600 hover:text-violet-600 hover:border-violet-200 hover:bg-violet-50 transition-all shadow-sm"
                            >
                                Manage
                                <ArrowRight class="w-3 h-3" />
                            </Link>
                        </td>
                    </tr>

                    <tr v-if="appointments.data.length === 0">
                        <td
                            colspan="5"
                            class="px-6 py-12 text-center text-slate-400 text-sm"
                        >
                            No appointments found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
