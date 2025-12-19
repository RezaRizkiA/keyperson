<script setup>
import { Head, Link } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { 
    Calendar, Clock, Search, Filter, MoreHorizontal, 
    Video, ExternalLink, User as UserIcon, MapPin, 
    CreditCard, CheckCircle2, AlertCircle, XCircle 
} from 'lucide-vue-next';

defineOptions({ layout: DashboardLayout });

const props = defineProps({
    appointments: Object, 
});

// --- HELPER FORMAT TANGGAL ---
const formatDate = (date) => new Date(date).toLocaleDateString('id-ID', { 
    day: 'numeric', month: 'short', year: 'numeric' 
});
const formatTime = (date) => new Date(date).toLocaleTimeString('id-ID', { 
    hour: '2-digit', minute: '2-digit' 
});

// --- HELPER STATUS APPOINTMENT ---
const getStatusBadge = (status) => {
    const config = {
        pending: { class: 'bg-slate-100 text-slate-600 border-slate-200', label: 'Waiting' },
        need_confirmation: { class: 'bg-orange-50 text-orange-700 border-orange-200', label: 'Action Needed' },
        confirmed: { class: 'bg-violet-50 text-violet-700 border-violet-200', label: 'Scheduled' },
        progress: { class: 'bg-blue-50 text-blue-700 border-blue-200', label: 'Live Session' },
        completed: { class: 'bg-emerald-50 text-emerald-700 border-emerald-200', label: 'Completed' },
        declined: { class: 'bg-red-50 text-red-700 border-red-200', label: 'Declined' },
    };
    return config[status] || { class: 'bg-gray-100 text-gray-600', label: status };
};

// --- HELPER STATUS PEMBAYARAN ---
const getPaymentBadge = (status) => {
    // Normalisasi string agar tidak case sensitive
    const s = status ? status.toLowerCase() : 'pending';

    if (s === 'berhasil' || s === 'settlement' || s === 'success') {
        return { 
            class: 'bg-emerald-100 text-emerald-700 border-emerald-200', 
            icon: CheckCircle2, 
            label: 'PAID' 
        };
    } else if (s === 'pending') {
        return { 
            class: 'bg-yellow-50 text-yellow-700 border-yellow-200', 
            icon: Clock, 
            label: 'UNPAID' 
        };
    } else {
        return { 
            class: 'bg-red-50 text-red-700 border-red-200', 
            icon: XCircle, 
            label: 'FAILED' 
        };
    }
};
</script>

<template>
    <Head title="My Schedule" />

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">My Schedule</h1>
                <p class="text-slate-500 mt-1">Manage your upcoming consultation sessions.</p>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-xs tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4">Client Info</th>
                            <th class="px-6 py-4">Topic / Skill</th>
                            <th class="px-6 py-4">Date & Time</th>
                            <th class="px-6 py-4">Status</th> <th class="px-6 py-4">Payment</th> <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        
                        <tr v-for="appointment in appointments.data" :key="appointment.id" 
                            class="hover:bg-slate-50/50 transition-colors group">
                            
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img 
                                        :src="appointment.user.picture_url || `https://ui-avatars.com/api/?name=${appointment.user.name}&background=random`" 
                                        class="w-10 h-10 rounded-full object-cover border border-slate-200"
                                        alt="Client"
                                    >
                                    <div>
                                        <div class="font-bold text-slate-900">{{ appointment.user.name }}</div>
                                        <div class="text-xs text-slate-500">{{ appointment.user.email }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 max-w-[200px]">
                                <div class="font-bold text-slate-800 truncate">{{ appointment.skill.name }}</div>
                                <div class="text-xs text-slate-500 mt-0.5">
                                    {{ appointment.skill.sub_category.name }}
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2 text-slate-700">
                                    <Calendar class="w-4 h-4 text-violet-500" />
                                    <span class="font-medium">{{ formatDate(appointment.date_time) }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-slate-500 mt-1 text-xs">
                                    <Clock class="w-3.5 h-3.5" />
                                    <span>{{ formatTime(appointment.date_time) }} WIB</span>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] uppercase font-bold border tracking-wide inline-flex items-center gap-1"
                                    :class="getStatusBadge(appointment.status).class">
                                    {{ getStatusBadge(appointment.status).label }}
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md border text-xs font-bold"
                                    :class="getPaymentBadge(appointment.payment_status).class">
                                    <component :is="getPaymentBadge(appointment.payment_status).icon" class="w-3.5 h-3.5" />
                                    <span>{{ getPaymentBadge(appointment.payment_status).label }}</span>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a v-if="appointment.location_url && ['confirmed', 'progress'].includes(appointment.status)" 
                                       :href="appointment.location_url" 
                                       target="_blank"
                                       class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors border border-transparent hover:border-blue-100"
                                       title="Join Meeting"
                                    >
                                        <Video class="w-4 h-4" />
                                    </a>

                                    <Link :href="route('dashboard.appointments.show', appointment.id)" 
                                          class="flex items-center gap-2 px-3 py-1.5 bg-white border border-slate-200 hover:border-violet-300 hover:text-violet-600 rounded-lg text-sm font-bold text-slate-600 transition-all shadow-sm">
                                        Details
                                        <ExternalLink class="w-3.5 h-3.5" />
                                    </Link>
                                </div>
                            </td>
                        </tr>

                        <tr v-if="appointments.data.length === 0">
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                        <Calendar class="w-8 h-8 text-slate-300" />
                                    </div>
                                    <h3 class="text-slate-900 font-bold text-lg">No appointments found</h3>
                                    <p class="text-slate-500 text-sm mt-1">Your schedule is currently empty.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="appointments.links.length > 3" class="px-6 py-4 border-t border-slate-100 flex items-center justify-between">
                <div class="text-xs text-slate-500">
                    Showing {{ appointments.from }} to {{ appointments.to }} of {{ appointments.total }} results
                </div>
                <div class="flex gap-1">
                    <Link v-for="(link, k) in appointments.links" :key="k"
                          :href="link.url || '#'"
                          v-html="link.label"
                          class="px-3 py-1 text-xs rounded border transition-colors"
                          :class="link.active ? 'bg-violet-600 text-white border-violet-600' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'"
                    />
                </div>
            </div>
        </div>
    </div>
</template>