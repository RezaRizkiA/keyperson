<template>
    <div
        class="bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700/50 overflow-hidden"
    >
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-900/30">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider"
                        >
                            Client Info
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider"
                        >
                            Expert
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider"
                        >
                            Date & Time
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider"
                        >
                            Status
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider"
                        >
                            Payment Status
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider"
                        >
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/30">
                    <template
                        v-if="appointments.data && appointments.data.length > 0"
                    >
                        <tr
                            v-for="appointment in appointments.data"
                            :key="appointment.id"
                            class="hover:bg-slate-900/20 transition-colors"
                        >
                            <!-- Client Info -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="shrink-0">
                                        <div
                                            class="w-10 h-10 rounded-full bg-linear-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm"
                                        >
                                            {{
                                                appointment.client_name
                                                    ? appointment.client_name
                                                          .charAt(0)
                                                          .toUpperCase()
                                                    : "C"
                                            }}
                                        </div>
                                    </div>
                                    <div>
                                        <div
                                            class="text-sm font-semibold text-slate-200"
                                        >
                                            {{
                                                appointment.client_name ||
                                                "Unknown Client"
                                            }}
                                        </div>
                                        <div class="text-xs text-slate-500">
                                            {{
                                                appointment.company_name || "User Retail"
                                            }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Expert -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="shrink-0">
                                        <div
                                            class="w-10 h-10 rounded-full bg-linear-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-white font-bold text-sm"
                                        >
                                            {{
                                                appointment.expert_name
                                                    ? appointment.expert_name
                                                          .charAt(0)
                                                          .toUpperCase()
                                                    : "E"
                                            }}
                                        </div>
                                    </div>
                                    <div>
                                        <div
                                            class="text-sm font-medium text-slate-200"
                                        >
                                            {{
                                                appointment.expert_name ||
                                                "Unknown Expert"
                                            }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Date & Time -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-200">
                                    {{
                                        formatDateTime(appointment.date_time)
                                            .date
                                    }}
                                </div>
                                <div class="text-xs text-slate-400">
                                    {{
                                        formatDateTime(appointment.date_time)
                                            .time
                                    }}
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border"
                                    :class="getStatusColor(appointment.status)"
                                >
                                    {{
                                        appointment.status
                                            ? appointment.status
                                                  .charAt(0)
                                                  .toUpperCase() +
                                              appointment.status.slice(1)
                                            : "Pending"
                                    }}
                                </span>
                            </td>

                            <!-- Payment Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border"
                                    :class="
                                        getPaymentStatusColor(
                                            appointment.payment_status
                                        )
                                    "
                                >
                                    {{
                                        appointment.payment_status
                                            ? appointment.payment_status
                                                  .charAt(0)
                                                  .toUpperCase() +
                                              appointment.payment_status.slice(
                                                  1
                                              )
                                            : "Pending"
                                    }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <Link
                                    :href="
                                        route(
                                            'dashboard.appointments.show',
                                            appointment.id
                                        )
                                    "
                                    class="p-2 text-slate-400 hover:text-blue-400 hover:bg-slate-700 rounded-lg transition-colors inline-flex items-center"
                                    title="View Details"
                                >
                                    <Eye class="w-4 h-4" />
                                </Link>
                            </td>
                        </tr>
                    </template>
                    <tr v-else>
                        <td
                            colspan="6"
                            class="px-6 py-12 text-center text-slate-500"
                        >
                            No appointments found
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div
            v-if="appointments.data && appointments.data.length > 0"
            class="px-6 py-4 border-t border-slate-700/50 flex items-center justify-between"
        >
            <div class="text-sm text-slate-400">
                Showing {{ appointments.from }} to {{ appointments.to }} of
                {{ appointments.total }} appointments
            </div>

            <div class="flex items-center gap-2">
                <Link
                    v-if="appointments.prev_page_url"
                    :href="appointments.prev_page_url"
                    class="px-3 py-1.5 bg-slate-700 hover:bg-slate-600 text-slate-200 text-sm rounded-lg transition-colors"
                    preserve-scroll
                >
                    Previous
                </Link>

                <template v-for="link in appointments.links" :key="link.label">
                    <Link
                        v-if="
                            link.url &&
                            !link.label.includes('Previous') &&
                            !link.label.includes('Next')
                        "
                        :href="link.url"
                        class="px-3 py-1.5 text-sm rounded-lg transition-colors"
                        :class="
                            link.active
                                ? 'bg-blue-500 text-white font-semibold'
                                : 'bg-slate-700 hover:bg-slate-600 text-slate-200'
                        "
                        preserve-scroll
                    >
                        <span v-html="link.label"></span>
                    </Link>
                </template>

                <Link
                    v-if="appointments.next_page_url"
                    :href="appointments.next_page_url"
                    class="px-3 py-1.5 bg-slate-700 hover:bg-slate-600 text-slate-200 text-sm rounded-lg transition-colors"
                    preserve-scroll
                >
                    Next
                </Link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import { Eye } from "lucide-vue-next";
import { formatDateTime } from "@/Utils/dateUtils";
import {
    getStatusColor,
    getPaymentStatusColor,
} from "@/Utils/appointmentUtils";

defineProps({
    appointments: {
        type: Object,
        required: true,
    },
});
</script>
