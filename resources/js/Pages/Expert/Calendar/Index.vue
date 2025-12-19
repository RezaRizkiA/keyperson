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
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Schedule Calendar</h1>
                <p class="text-slate-500 mt-1">View your sessions in a monthly or weekly grid.</p>
            </div>
        </div>

        <CalendarView :events="events" />
    </div>
</template>