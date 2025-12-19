<script setup>
import { Head } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import CalendarView from '@/Components/CalendarView.vue';

defineOptions({ layout: DashboardLayout });

const props = defineProps({
    events: Array,
});
</script>

<template>
    <Head title="My Calendar" />

    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">My Bookings</h1>
            <p class="text-slate-500 mt-1">Manage your time and upcoming consultations.</p>
        </div>

        <CalendarView :events="events" />
    </div>
</template>