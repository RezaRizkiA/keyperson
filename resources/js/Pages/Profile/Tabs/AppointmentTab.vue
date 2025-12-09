<script setup>
import { Link } from "@inertiajs/vue3";
import { MoreHorizontal, Briefcase, Clock } from "lucide-vue-next";

const props = defineProps({
    appointments: Array,
    isExpert: Boolean,
});

const formatDate = (dateString) => {
    const options = {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    };
    return new Date(dateString).toLocaleDateString("en-US", options);
};

const getStatusColor = (status) => {
    switch (status) {
        case "pending":
            return "bg-yellow-100 text-yellow-800";
        case "confirmed":
            return "bg-green-100 text-green-800";
        case "completed":
            return "bg-blue-100 text-blue-800";
        case "cancelled":
            return "bg-red-100 text-red-800";
        default:
            return "bg-gray-100 text-gray-800";
    }
};
</script>

<template>
    <div class="space-y-6">
        <div
            v-if="appointments.length === 0"
            class="text-center py-12 bg-white rounded-3xl shadow-sm border border-slate-100"
        >
            <Briefcase class="w-12 h-12 text-slate-300 mx-auto mb-4" />
            <h3 class="text-lg font-medium text-slate-900">
                No appointments yet
            </h3>
            <p class="text-slate-500 mt-1">
                Your scheduled appointments will appear here.
            </p>
        </div>

        <div v-else class="grid gap-4">
            <div
                v-for="appointment in appointments"
                :key="appointment.id"
                class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-shadow"
            >
                <div
                    class="flex flex-col md:flex-row justify-between md:items-center gap-4"
                >
                    <div class="flex items-start gap-4">
                        <div
                            class="w-12 h-12 rounded-full bg-violet-100 flex items-center justify-center text-violet-600 font-bold text-lg"
                        >
                            {{
                                isExpert
                                    ? appointment.user.name.charAt(0)
                                    : appointment.expert.user.name.charAt(0)
                            }}
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900">
                                {{
                                    isExpert
                                        ? appointment.user.name
                                        : appointment.expert.user.name
                                }}
                            </h4>
                            <p class="text-sm text-slate-500">
                                {{
                                    isExpert
                                        ? appointment.user.email
                                        : appointment.expert.user.email
                                }}
                            </p>
                            <div
                                class="flex items-center gap-4 mt-2 text-sm text-slate-600"
                            >
                                <div class="flex items-center gap-1">
                                    <Clock class="w-4 h-4 text-slate-400" />
                                    {{ formatDate(appointment.date_time) }}
                                </div>
                                <div
                                    v-if="!isExpert"
                                    class="flex items-center gap-1"
                                >
                                    <span class="font-semibold text-slate-900"
                                        >Rp
                                        {{
                                            new Intl.NumberFormat(
                                                "id-ID"
                                            ).format(appointment.expert.price)
                                        }}</span
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span
                            :class="[
                                'px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wide',
                                getStatusColor(appointment.status),
                            ]"
                        >
                            {{ appointment.status }}
                        </span>
                        <Link
                            :href="route('payment.show', appointment.id)"
                            class="p-2 text-slate-400 hover:text-violet-600 transition-colors"
                        >
                            <MoreHorizontal class="w-5 h-5" />
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
