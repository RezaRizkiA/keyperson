<script setup>
import { Head, Link } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import AppointmentCard from '@/Pages/Dashboard/Tabs/Partials/Appointment/Card.vue';
import { Briefcase, ChevronLeft, ChevronRight } from 'lucide-vue-next';

// Menggunakan Persistent Layout
defineOptions({ layout: DashboardLayout });

const props = defineProps({
    appointments: Object, // Object Pagination dari Laravel
});

// Helper untuk mengecek apakah link pagination valid
const isLinkActive = (link) => link.url === null;
</script>

<template>
    <Head title="All Appointments" />

    <div class="space-y-6">
        <div class="bg-white p-6 md:p-8 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 flex items-center gap-2">
                    <Briefcase class="w-6 h-6 text-violet-600" />
                    All Appointments
                </h2>
                <p class="text-slate-500 mt-1 text-sm">
                    Monitoring {{ appointments.total }} sessions across the platform.
                </p>
            </div>
            
            <div class="flex gap-2">
                </div>
        </div>

        <div class="space-y-4">
            <template v-if="appointments.data.length > 0">
                <AppointmentCard
                    v-for="appointment in appointments.data"
                    :key="appointment.id"
                    :appointment="appointment"
                    :is-expert="false"
                    :is-admin="true" 
                />
            </template>

            <div v-else class="bg-white p-12 rounded-2xl border border-dashed border-slate-200 text-center">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <Briefcase class="w-8 h-8 text-slate-300" />
                </div>
                <h3 class="text-slate-900 font-bold">No appointments found</h3>
                <p class="text-slate-500 text-sm mt-1">There are no booking sessions recorded yet.</p>
            </div>
        </div>

        <div v-if="appointments.links.length > 3" class="flex justify-center mt-8">
            <div class="flex flex-wrap gap-1 items-center justify-center bg-white p-2 rounded-xl border border-slate-200 shadow-sm">
                <template v-for="(link, key) in appointments.links" :key="key">
                    
                    <div v-if="link.url === null" 
                         class="px-3 py-1.5 text-sm text-slate-400 border border-transparent rounded-lg" 
                         v-html="link.label">
                    </div>

                    <Link v-else 
                          :href="link.url" 
                          class="px-3 py-1.5 text-sm font-medium rounded-lg border transition-all"
                          :class="link.active 
                            ? 'bg-violet-600 text-white border-violet-600 shadow-md shadow-violet-200' 
                            : 'bg-white text-slate-600 border-transparent hover:bg-slate-50 hover:border-slate-200'"
                          v-html="link.label">
                    </Link>
                </template>
            </div>
        </div>
    </div>
</template>