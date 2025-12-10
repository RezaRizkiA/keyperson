<script setup>
import { reactive, watch } from "vue";
import FullCalendar from "@fullcalendar/vue3";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import interactionPlugin from "@fullcalendar/interaction";

// 1. Sesuaikan nama props dengan yang dikirim dari Controller (calendarEvents)
const props = defineProps({
    calendarEvents: Array,
});

// 2. Gunakan 'reactive' untuk options agar lebih rapi
const calendarOptions = reactive({
    plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
    initialView: "dayGridMonth",
    headerToolbar: {
        left: "prev,next today",
        center: "title",
        right: "dayGridMonth,timeGridWeek,timeGridDay",
    },
    // Masukkan data awal
    events: props.calendarEvents,
    height: "auto",
    eventColor: "#7c3aed", // Violet-600

    // Opsional: Handle klik event untuk melihat detail
    eventClick: (info) => {
        // console.log('Appointment ID:', info.event.id);
        // Bisa ditambahkan logika untuk buka modal detail disini
    }
});

// 3. LOGIKA PENTING: Watcher
// Ini yang membuat "Otomatis". Saat Inertia me-reload data baru dari server,
// watcher ini mendeteksi perubahan pada props dan mengupdate kalender.
watch(
    () => props.calendarEvents,
    (newEvents) => {
        calendarOptions.events = newEvents;
    },
    { deep: true }
);
</script>

<template>
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6">
        <FullCalendar :options="calendarOptions" />
    </div>
</template>

<style>
/* Custom FullCalendar Styles to match theme */
.fc {
    font-family: inherit;
}

.fc-toolbar-title {
    font-size: 1.25rem !important;
    font-weight: 700 !important;
    color: #0f172a;
}

.fc-button-primary {
    background-color: #fff !important;
    border-color: #e2e8f0 !important;
    color: #475569 !important;
    font-weight: 600 !important;
    text-transform: capitalize !important;
    padding: 0.5rem 1rem !important;
    border-radius: 0.75rem !important;
    box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05) !important;
    transition: all 0.2s;
}

.fc-button-primary:hover {
    background-color: #f8fafc !important;
    border-color: #cbd5e1 !important;
    color: #1e293b !important;
}

.fc-button-active {
    background-color: #7c3aed !important;
    border-color: #7c3aed !important;
    color: #fff !important;
}

.fc-daygrid-day-number {
    color: #475569;
    font-weight: 500;
}

.fc-col-header-cell-cushion {
    color: #64748b;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.05em;
    padding-bottom: 1rem !important;
}

.fc-event {
    border-radius: 0.375rem;
    padding: 2px 4px;
    font-size: 0.75rem;
    font-weight: 600;
    border: none;
    cursor: pointer;
}
</style>