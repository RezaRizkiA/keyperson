<script setup>
import { ref, computed, watch } from "vue";
import { Link, router, useForm } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import Swal from "sweetalert2";

import { DatePicker } from "v-calendar";
import "v-calendar/style.css";

import {
    Calendar,
    MapPin,
    CheckCircle,
    XCircle,
    User,
    Mail,
    Phone,
    FileText,
    ArrowLeft,
    PlayCircle,
    Video,
    Save,
    RefreshCw,
    Clock,
    CheckCircle2,
} from "lucide-vue-next";

defineOptions({ layout: DashboardLayout });

const props = defineProps({
    appointment: Object,
});

// Helper Formatting (Sama)
const formatDate = (date) =>
    new Date(date).toLocaleDateString("id-ID", {
        weekday: "long",
        day: "numeric",
        month: "long",
        year: "numeric",
    });
const formatTime = (date) =>
    new Date(date).toLocaleTimeString("id-ID", {
        hour: "2-digit",
        minute: "2-digit",
    });

// Status Config
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
    statusConfig[status] || { class: "bg-gray-100", label: status };

// ==========================================
// ACTION LOGIC (EXPERT SPECIFIC)
// ==========================================

// 1. Update Status (Accept/Decline/Start/Finish)
const updateStatus = (newStatus) => {
    let title = "Are you sure?";
    let text = `Change status to ${newStatus}?`;
    let confirmBtnText = "Yes, do it!";
    let confirmColor = "#7c3aed";

    if (newStatus === "declined") {
        title = "Decline Appointment?";
        text = "The user will be notified and funds might be refunded.";
        confirmBtnText = "Yes, Decline";
        confirmColor = "#d33";
    }

    Swal.fire({
        title: title,
        text: text,
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: confirmColor,
        confirmButtonText: confirmBtnText,
    }).then((result) => {
        if (result.isConfirmed) {
            router.patch(
                route(
                    "dashboard.appointments.update-status",
                    props.appointment.id
                ),
                {
                    status: newStatus,
                },
                { preserveScroll: true }
            );
        }
    });
};

// 2. Manage Meeting Link (FITUR KHUSUS EXPERT)
const meetingForm = useForm({
    location_url: props.appointment.location_url || "",
});

const saveMeetingLink = () => {
    meetingForm.patch(
        route("dashboard.appointments.update-link", props.appointment.id),
        {
            preserveScroll: true,
            onSuccess: () => {
                Swal.fire("Saved", "Meeting link has been updated.", "success");
            },
        }
    );
};

const startSession = () => {
    // 1. Validasi: Cek apakah Link sudah ada?
    if (!props.appointment.location_url) {
        Swal.fire({
            icon: "warning",
            title: "Missing Link",
            text: "Please save the Meeting Link first before starting the session.",
            confirmButtonColor: "#7c3aed",
        });
        return;
    }

    // 2. Konfirmasi Khusus (Opsional, tapi disarankan)
    Swal.fire({
        title: "Start Session Now?",
        text: 'Status will change to "In Session" and the meeting link will open.',
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#2563eb", // Blue sesuai tombol
        confirmButtonText: "Yes, Open & Start",
    }).then((result) => {
        if (result.isConfirmed) {
            // A. Buka Link di Tab Baru (Langsung)
            window.open(props.appointment.location_url, "_blank");

            // B. Update Status ke Backend (Background Process)
            router.patch(
                route(
                    "dashboard.appointments.update-status",
                    props.appointment.id
                ),
                {
                    status: "progress",
                },
                {
                    preserveScroll: true,
                    onSuccess: () => {
                        // Optional: Toast notif "Session Started" akan muncul otomatis dari global layout
                    },
                }
            );
        }
    });
};

// ==========================================
// [UPDATED] RESCHEDULE LOGIC WITH V-CALENDAR
// ==========================================
const showRescheduleModal = ref(false);
const rescheduleForm = ref({ date: "", time: "" });

// State Baru untuk Calendar
const dateModel = ref(new Date()); // Default hari ini
const availableTimeSlots = ref([]);

// Helper format DB (YYYY-MM-DD)
const formatDateToDB = (date) => {
    const d = new Date(date);
    const month = "" + (d.getMonth() + 1);
    const day = "" + d.getDate();
    const year = d.getFullYear();
    return [year, month.padStart(2, "0"), day.padStart(2, "0")].join("-");
};

// Generate Slot (09:00 - 17:00)
const generateTimeSlots = () => {
    const slots = [];
    const startHour = 9;
    const endHour = 17;

    for (let i = startHour; i < endHour; i++) {
        const timeString = `${i.toString().padStart(2, "0")}:00`;
        // Asumsi Expert bebas pilih waktu (Available true),
        // kecuali Anda mau inject logic 'bookedSlots' dari props
        slots.push({
            time: timeString,
            isAvailable: true,
        });
    }
    availableTimeSlots.value = slots;
};

// Watcher Date Model
watch(
    dateModel,
    (newDate) => {
        if (newDate) {
            rescheduleForm.value.date = formatDateToDB(newDate);
            rescheduleForm.value.time = ""; // Reset jam saat ganti tanggal
            generateTimeSlots();
        }
    },
    { immediate: true }
);

const selectTime = (slot) => {
    if (slot.isAvailable) {
        rescheduleForm.value.time = slot.time;
    }
};

const submitReschedule = () => {
    if (!rescheduleForm.value.date || !rescheduleForm.value.time) {
        Swal.fire("Error", "Please select a date and a time slot.", "error");
        return;
    }

    // Gabung Date & Time untuk dikirim ke Backend
    const newDateTime = `${rescheduleForm.value.date} ${rescheduleForm.value.time}:00`;

    router.patch(
        route("dashboard.appointments.reschedule", props.appointment.id),
        {
            date_time: newDateTime,
        },
        {
            onSuccess: () => {
                showRescheduleModal.value = false;
                // Reset form
                dateModel.value = new Date();
                rescheduleForm.value = { date: "", time: "" };
                Swal.fire(
                    "Success",
                    "Session rescheduled successfully.",
                    "success"
                );
            },
            onError: (errors) => {
                Swal.fire("Error", Object.values(errors)[0], "error");
            },
        }
    );
};
</script>

<template>
    <div class="space-y-6">
        <div class="flex items-center gap-4">
            <Link
                :href="route('dashboard.appointments.index')"
                class="p-2 hover:bg-slate-100 rounded-lg transition-colors text-slate-500"
            >
                <ArrowLeft class="w-5 h-5" />
            </Link>
            <div>
                <h1 class="text-2xl font-bold text-slate-900">
                    Session Details
                </h1>
                <p class="text-sm text-slate-500">
                    Client:
                    <span class="font-medium text-slate-900">{{
                        appointment.user.name
                    }}</span>
                </p>
            </div>
            <div class="ml-auto">
                <span
                    class="px-4 py-2 rounded-xl text-sm font-bold border flex items-center gap-2"
                    :class="getStatus(appointment.status).class"
                >
                    <span
                        class="w-2 h-2 rounded-full bg-current opacity-75"
                    ></span>
                    {{ getStatus(appointment.status).label }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div
                    class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm"
                >
                    <h3
                        class="font-bold text-slate-900 mb-4 flex items-center gap-2"
                    >
                        <Calendar class="w-5 h-5 text-violet-500" /> Schedule
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div
                            class="bg-slate-50 p-4 rounded-xl border border-slate-100"
                        >
                            <span
                                class="text-xs font-bold text-slate-400 uppercase tracking-wider"
                                >Date</span
                            >
                            <div
                                class="text-lg font-semibold text-slate-800 mt-1"
                            >
                                {{ formatDate(appointment.date_time) }}
                            </div>
                        </div>
                        <div
                            class="bg-slate-50 p-4 rounded-xl border border-slate-100"
                        >
                            <span
                                class="text-xs font-bold text-slate-400 uppercase tracking-wider"
                                >Time</span
                            >
                            <div
                                class="text-lg font-semibold text-slate-800 mt-1"
                            >
                                {{ formatTime(appointment.date_time) }} WIB
                            </div>
                        </div>
                        <div
                            class="bg-slate-50 p-4 rounded-xl border border-slate-100"
                        >
                            <span
                                class="text-xs font-bold text-slate-400 uppercase tracking-wider"
                                >Duration</span
                            >
                            <div
                                class="text-lg font-semibold text-slate-800 mt-1"
                            >
                                {{ appointment.hours }}
                                {{ appointment.hours > 1 ? "Hours" : "Hour" }}
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm"
                >
                    <h3
                        class="font-bold text-slate-900 mb-4 flex items-center gap-2"
                    >
                        <FileText class="w-5 h-5 text-violet-500" /> Topic
                    </h3>
                    <div
                        class="flex flex-wrap items-center gap-2 text-sm text-slate-500 mb-4"
                    >
                        <span
                            class="bg-slate-100 px-3 py-1 rounded-full border border-slate-200"
                            >{{
                                appointment.skill.sub_category.category.name
                            }}</span
                        >
                        <span>/</span>
                        <span
                            class="bg-slate-100 px-3 py-1 rounded-full border border-slate-200"
                            >{{ appointment.skill.sub_category.name }}</span
                        >
                    </div>
                    <div
                        class="bg-violet-50 p-5 rounded-xl border border-violet-100"
                    >
                        <span
                            class="text-xs font-bold text-violet-600 uppercase tracking-wider mb-1 block"
                            >Skill Focus</span
                        >
                        <h2 class="text-xl font-bold text-violet-900">
                            {{ appointment.skill.name }}
                        </h2>
                    </div>
                    <div class="mt-6">
                        <span
                            class="text-xs font-bold text-slate-400 uppercase tracking-wider"
                            >Client Notes</span
                        >
                        <p
                            class="mt-2 text-slate-600 leading-relaxed bg-slate-50 p-4 rounded-xl border border-slate-100"
                        >
                            {{ appointment.topic || "No notes provided." }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1 space-y-6">
                <div
                    class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm ring-1 ring-slate-100"
                >
                    <h3
                        class="font-bold text-slate-900 mb-4 pb-3 border-b border-slate-100"
                    >
                        My Actions
                    </h3>

                    <div
                        v-if="appointment.status === 'need_confirmation'"
                        class="grid grid-cols-2 gap-3"
                    >
                        <button
                            @click="updateStatus('confirmed')"
                            class="flex items-center justify-center gap-2 w-full py-2.5 bg-violet-600 hover:bg-violet-700 text-white rounded-xl text-sm font-bold transition-all shadow-sm shadow-violet-200"
                        >
                            <CheckCircle class="w-4 h-4" /> Accept
                        </button>
                        <button
                            @click="updateStatus('declined')"
                            class="flex items-center justify-center gap-2 w-full py-2.5 bg-white border border-red-200 text-red-600 hover:bg-red-50 rounded-xl text-sm font-bold transition-all"
                        >
                            <XCircle class="w-4 h-4" /> Decline
                        </button>
                        <button
                            @click="showRescheduleModal = true"
                            class="flex items-center gap-3 w-full px-4 py-2 bg-slate-50 border border-slate-200 text-slate-700 font-medium hover:bg-violet-50 hover:text-violet-700 hover:border-violet-200 rounded-xl transition-all text-sm"
                        >
                            <RefreshCw class="w-4 h-4" /> Reschedule
                        </button>
                    </div>

                    <div
                        v-else-if="appointment.status === 'confirmed'"
                        class="space-y-4"
                    >
                        <div>
                            <label
                                class="text-xs font-bold text-slate-500 uppercase mb-2 block"
                                >Meeting Link (Zoom/Gmeet)</label
                            >

                            <div class="flex items-stretch gap-3">
                                <div class="relative flex-1">
                                    <div
                                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                                    >
                                        <Video class="h-4 w-4 text-slate-400" />
                                    </div>
                                    <input
                                        type="text"
                                        v-model="meetingForm.location_url"
                                        placeholder="https://zoom.us/j/..."
                                        class="block w-full pl-10 pr-3 py-2.5 text-sm rounded-xl border-slate-200 focus:ring-violet-500 focus:border-violet-500 shadow-sm placeholder-slate-400"
                                    />
                                </div>

                                <button
                                    @click="saveMeetingLink"
                                    :disabled="meetingForm.processing"
                                    class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-violet-600 text-white text-sm font-bold rounded-xl hover:bg-violet-700 focus:ring-4 focus:ring-violet-100 transition-all shadow-sm disabled:opacity-70 disabled:cursor-not-allowed"
                                >
                                    <Save class="w-4 h-4" />
                                    <span>{{
                                        meetingForm.processing
                                            ? "Saving..."
                                            : "Save"
                                    }}</span>
                                </button>
                            </div>
                            <p class="mt-2 text-xs text-slate-400">
                                Pastikan link dapat diakses publik atau sertakan
                                passcode jika ada.
                            </p>
                        </div>

                        <button
                            @click="startSession"
                            class="flex items-center justify-center gap-2 w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold transition-all shadow-sm shadow-blue-200"
                        >
                            <PlayCircle class="w-4 h-4" /> Start Session Now
                        </button>

                        <button
                            @click="showRescheduleModal = true"
                            class="flex items-center justify-center gap-3 w-full px-4 py-2 bg-slate-50 border border-slate-200 text-slate-700 font-medium hover:bg-violet-50 hover:text-violet-700 hover:border-violet-200 rounded-xl transition-all text-sm"
                        >
                            <RefreshCw class="w-4 h-4" /> Reschedule
                        </button>
                    </div>

                    <div
                        v-else-if="appointment.status === 'progress'"
                        class="space-y-3"
                    >
                        <div
                            class="p-3 bg-blue-50 text-blue-700 text-xs rounded-lg flex items-center gap-2"
                        >
                            <span class="relative flex h-2 w-2">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"
                                ></span>
                                <span
                                    class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"
                                ></span>
                            </span>
                            You are live!
                        </div>
                        <button
                            @click="updateStatus('completed')"
                            class="flex items-center justify-center gap-2 w-full py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-sm font-bold transition-all"
                        >
                            <CheckCircle class="w-4 h-4" /> Finish Session
                        </button>
                    </div>

                    <div
                        v-else
                        class="text-sm text-slate-500 text-center py-2 bg-slate-50 rounded-lg"
                    >
                        {{
                            appointment.status === "pending"
                                ? "Waiting for client payment."
                                : "No actions required."
                        }}
                    </div>

                    <div
                        v-if="showRescheduleModal"
                        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm"
                    >
                        <div
                            class="bg-white rounded-[2rem] shadow-xl w-full max-w-4xl p-6 md:p-8 max-h-[90vh] overflow-y-auto custom-scrollbar"
                        >
                            <div class="flex justify-between items-center mb-6">
                                <div>
                                    <h3
                                        class="text-xl font-bold text-slate-900 flex items-center gap-2"
                                    >
                                        <RefreshCw
                                            class="w-5 h-5 text-violet-600"
                                        />
                                        Reschedule Session
                                    </h3>
                                    <p class="text-sm text-slate-500 mt-1">
                                        Select a new date and time for this
                                        appointment.
                                    </p>
                                </div>
                                <button
                                    @click="showRescheduleModal = false"
                                    class="p-2 hover:bg-slate-100 rounded-full transition-colors"
                                >
                                    <XCircle class="w-6 h-6 text-slate-400" />
                                </button>
                            </div>

                            <div class="grid md:grid-cols-2 gap-8">
                                <div class="space-y-3">
                                    <label
                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider ml-1"
                                        >1. Pick a Date</label
                                    >
                                    <div
                                        class="border border-slate-100 rounded-2xl overflow-hidden p-4 bg-white shadow-sm flex justify-center"
                                    >
                                        <DatePicker
                                            v-model="dateModel"
                                            mode="date"
                                            :min-date="new Date()"
                                            color="purple"
                                            borderless
                                            transparent
                                            class="font-sans w-full"
                                        />
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <label
                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider ml-1"
                                        >2. Pick a Time</label
                                    >

                                    <div
                                        v-if="rescheduleForm.date"
                                        class="grid grid-cols-2 gap-3 max-h-[320px] overflow-y-auto pr-2 custom-scrollbar"
                                    >
                                        <button
                                            v-for="slot in availableTimeSlots"
                                            :key="slot.time"
                                            type="button"
                                            @click="selectTime(slot)"
                                            :disabled="!slot.isAvailable"
                                            :class="[
                                                'py-3 px-4 rounded-xl text-sm font-bold border transition-all duration-200 flex items-center justify-between group',
                                                !slot.isAvailable
                                                    ? 'bg-slate-100 border-transparent text-slate-400 cursor-not-allowed line-through opacity-60'
                                                    : rescheduleForm.time ===
                                                      slot.time
                                                    ? 'bg-violet-600 border-violet-600 text-white shadow-lg shadow-violet-200'
                                                    : 'bg-white border-slate-200 text-slate-700 hover:border-violet-300 hover:bg-violet-50',
                                            ]"
                                        >
                                            <span>{{ slot.time }}</span>
                                            <CheckCircle2
                                                v-if="
                                                    rescheduleForm.time ===
                                                    slot.time
                                                "
                                                class="w-4 h-4"
                                            />
                                        </button>
                                    </div>
                                    <div
                                        v-else
                                        class="text-center py-10 text-slate-400 bg-slate-50 rounded-xl border border-dashed border-slate-200"
                                    >
                                        Select a date first
                                    </div>
                                </div>
                            </div>

                            <div
                                class="mt-8 pt-6 border-t border-slate-100 flex justify-end gap-3"
                            >
                                <button
                                    @click="showRescheduleModal = false"
                                    class="px-6 py-3 bg-white border border-slate-200 text-slate-700 font-bold text-sm rounded-xl hover:bg-slate-50 transition-colors"
                                >
                                    Cancel
                                </button>
                                <button
                                    @click="submitReschedule"
                                    :disabled="
                                        !rescheduleForm.date ||
                                        !rescheduleForm.time
                                    "
                                    class="px-8 py-3 bg-slate-900 text-white font-bold text-sm rounded-xl hover:bg-violet-600 transition-all shadow-lg shadow-slate-900/20 disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    Confirm New Schedule
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm"
                >
                    <h3
                        class="font-bold text-slate-900 mb-4 text-sm uppercase tracking-wider"
                    >
                        Client Profile
                    </h3>
                    <div class="flex items-center gap-4 mb-4">
                        <div
                            class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-lg"
                        >
                            {{ appointment.user.name.charAt(0) }}
                        </div>
                        <div>
                            <div class="font-bold text-slate-900">
                                {{ appointment.user.name }}
                            </div>
                            <div class="text-xs text-slate-500">
                                Joined via App
                            </div>
                        </div>
                    </div>
                    <div class="space-y-3 pt-4 border-t border-slate-100">
                        <div
                            class="flex items-center gap-3 text-sm text-slate-600"
                        >
                            <Mail class="w-4 h-4 text-slate-400" />
                            <a
                                :href="`mailto:${appointment.user.email}`"
                                class="hover:text-violet-600 transition-colors truncate"
                            >
                                {{ appointment.user.email }}
                            </a>
                        </div>
                        <div
                            class="flex items-center gap-3 text-sm text-slate-600"
                        >
                            <Phone class="w-4 h-4 text-slate-400" />
                            <span>{{ appointment.user.phone || "-" }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #e2e8f0;
    border-radius: 10px;
}
</style>
