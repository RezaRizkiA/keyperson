<script setup>
import { reactive, watch } from "vue";
import FullCalendar from "@fullcalendar/vue3";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import interactionPlugin from "@fullcalendar/interaction";
import { router } from "@inertiajs/vue3"; // Import router untuk navigasi

const props = defineProps({
    events: Array,
});

const calendarOptions = reactive({
    plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
    initialView: "dayGridMonth",
    headerToolbar: {
        left: "prev,next today",
        center: "title",
        right: "dayGridMonth,timeGridWeek,timeGridDay",
    },
    events: props.events,
    height: "auto",
    
    // Tampilan Waktu
    slotMinTime: "08:00:00", // Mulai jam 8 pagi
    slotMaxTime: "22:00:00", // Sampai jam 10 malam
    allDaySlot: false, // Matikan slot 'sepanjang hari' karena ini booking jam

    // Interaksi: Klik event mengarah ke detail
    eventClick: (info) => {
        // Arahkan ke halaman detail appointment sesuai ID event
        // Note: Rute ini harusnya dashboard.appointments.show yang sudah kita buat
        router.get(route('dashboard.appointments.show', info.event.id));
    },
    
    // Hover effect cursor
    eventMouseEnter: (info) => {
        info.el.style.cursor = 'pointer';
    }
});

watch(
    () => props.events,
    (newEvents) => {
        calendarOptions.events = newEvents;
    },
    { deep: true }
);
</script>

<template>
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6 calendar-wrapper">
        <FullCalendar :options="calendarOptions" />
    </div>
</template>

<style>
/* Style override untuk menyesuaikan tema */
.fc { font-family: inherit; }
.fc-toolbar-title { font-size: 1.25rem !important; font-weight: 700 !important; color: #0f172a; }
.fc-button-primary { 
    background-color: #fff !important; 
    border-color: #e2e8f0 !important; 
    color: #475569 !important; 
    font-weight: 600 !important;
    text-transform: capitalize !important;
    border-radius: 0.75rem !important;
    box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05) !important;
}
.fc-button-primary:hover { background-color: #f8fafc !important; }
.fc-button-active { 
    background-color: #0f172a !important; /* Slate-900 */
    border-color: #0f172a !important; 
    color: #fff !important; 
}
.fc-event { border: none; border-radius: 6px; padding: 2px 4px; font-weight: 600; font-size: 0.75rem; }
.calendar-wrapper .fc-day-today { background-color: #f8fafc !important; }
</style>