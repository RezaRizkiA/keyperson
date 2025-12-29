<script setup>
import { ref, computed, watch } from "vue";
import { Link, router, useForm, Head } from "@inertiajs/vue3";
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
    Timer,
    Hash,
    AlignLeft,
    AlertCircle,
    ExternalLink,
} from "lucide-vue-next";

defineOptions({ layout: DashboardLayout });

const props = defineProps({
    appointment: Object,
});

// Helper Formatting
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

// Status Config with dark mode support
const statusConfig = {
    pending: {
        class: "bg-yellow-50 dark:bg-yellow-500/20 text-yellow-700 dark:text-yellow-400 border-yellow-200 dark:border-yellow-500/30",
        label: "Waiting Payment",
    },
    need_confirmation: {
        class: "bg-orange-50 dark:bg-orange-500/20 text-orange-700 dark:text-orange-400 border-orange-200 dark:border-orange-500/30",
        label: "Action Needed",
    },
    confirmed: {
        class: "bg-green-50 dark:bg-green-500/20 text-green-700 dark:text-green-400 border-green-200 dark:border-green-500/30",
        label: "Scheduled",
    },
    progress: {
        class: "bg-blue-50 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400 border-blue-200 dark:border-blue-500/30",
        label: "In Session",
    },
    completed: {
        class: "bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-600",
        label: "Completed",
    },
    declined: {
        class: "bg-red-50 dark:bg-red-500/20 text-red-700 dark:text-red-400 border-red-200 dark:border-red-500/30",
        label: "Declined",
    },
};
const getStatus = (status) =>
    statusConfig[status] || {
        class: "bg-gray-100 dark:bg-gray-800",
        label: status,
    };

// Check if meeting link is missing
const isMeetingLinkMissing = computed(() => {
    return (
        !props.appointment.location_url &&
        ["confirmed", "need_confirmation"].includes(props.appointment.status)
    );
});

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
                { status: newStatus },
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

    // 2. Konfirmasi
    Swal.fire({
        title: "Start Session Now?",
        text: 'Status will change to "In Session" and the meeting link will open.',
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#2563eb",
        confirmButtonText: "Yes, Open & Start",
    }).then((result) => {
        if (result.isConfirmed) {
            window.open(props.appointment.location_url, "_blank");

            router.patch(
                route(
                    "dashboard.appointments.update-status",
                    props.appointment.id
                ),
                { status: "progress" },
                { preserveScroll: true }
            );
        }
    });
};

// ==========================================
// RESCHEDULE LOGIC WITH V-CALENDAR
// ==========================================
const showRescheduleModal = ref(false);
const rescheduleForm = ref({ date: "", time: "" });

const dateModel = ref(new Date());
const availableTimeSlots = ref([]);

const formatDateToDB = (date) => {
    const d = new Date(date);
    const month = "" + (d.getMonth() + 1);
    const day = "" + d.getDate();
    const year = d.getFullYear();
    return [year, month.padStart(2, "0"), day.padStart(2, "0")].join("-");
};

const generateTimeSlots = () => {
    const slots = [];
    const startHour = 9;
    const endHour = 17;

    for (let i = startHour; i < endHour; i++) {
        const timeString = `${i.toString().padStart(2, "0")}:00`;
        slots.push({ time: timeString, isAvailable: true });
    }
    availableTimeSlots.value = slots;
};

watch(
    dateModel,
    (newDate) => {
        if (newDate) {
            rescheduleForm.value.date = formatDateToDB(newDate);
            rescheduleForm.value.time = "";
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

    const newDateTime = `${rescheduleForm.value.date} ${rescheduleForm.value.time}:00`;

    router.patch(
        route("dashboard.appointments.reschedule", props.appointment.id),
        { date_time: newDateTime },
        {
            onSuccess: () => {
                showRescheduleModal.value = false;
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
    <Head title="Appointment Details" />
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center gap-4">
            <Link
                :href="route('dashboard.appointments.index')"
                class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors text-slate-500 dark:text-slate-400"
            >
                <ArrowLeft class="w-5 h-5" />
            </Link>
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">
                    Session Details
                </h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    Client:
                    <span class="font-medium text-slate-900 dark:text-white">{{
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

        <!-- Missing Link Alert -->
        <div
            v-if="isMeetingLinkMissing"
            class="p-4 bg-amber-50 dark:bg-amber-500/20 border border-amber-200 dark:border-amber-500/30 rounded-2xl flex items-start gap-3"
        >
            <AlertCircle
                class="w-5 h-5 text-amber-600 dark:text-amber-400 shrink-0 mt-0.5"
            />
            <div>
                <p class="font-bold text-amber-800 dark:text-amber-300">
                    Meeting Link Required
                </p>
                <p class="text-sm text-amber-700 dark:text-amber-400 mt-1">
                    Please add a Zoom/Google Meet link before the session starts
                    so the client knows where to join.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Session Schedule Card -->
                <div
                    class="bg-white dark:bg-slate-800 p-6 md:p-8 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-xl shadow-slate-200/60 dark:shadow-none relative overflow-hidden"
                >
                    <div
                        class="absolute top-0 right-0 w-24 h-24 bg-violet-50 dark:bg-violet-500/10 rounded-bl-full -mr-4 -mt-4 opacity-50 pointer-events-none"
                    ></div>

                    <h3
                        class="font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-3 relative z-10"
                    >
                        <div
                            class="w-8 h-8 rounded-lg bg-violet-100 dark:bg-violet-500/20 flex items-center justify-center text-violet-600 dark:text-violet-400"
                        >
                            <Calendar class="w-4 h-4" />
                        </div>
                        Session Schedule
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div
                            class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700 hover:border-violet-200 dark:hover:border-violet-500/50 hover:bg-violet-50/30 dark:hover:bg-violet-500/10 transition-all group"
                        >
                            <div class="flex items-start gap-3">
                                <div class="mt-1">
                                    <Calendar
                                        class="w-4 h-4 text-slate-400 group-hover:text-violet-500 dark:group-hover:text-violet-400 transition-colors"
                                    />
                                </div>
                                <div>
                                    <span
                                        class="text-xs font-bold text-slate-400 uppercase tracking-wider group-hover:text-violet-400"
                                        >Date</span
                                    >
                                    <div
                                        class="text-base font-bold text-slate-900 dark:text-white mt-0.5"
                                    >
                                        {{ formatDate(appointment.date_time) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700 hover:border-blue-200 dark:hover:border-blue-500/50 hover:bg-blue-50/30 dark:hover:bg-blue-500/10 transition-all group"
                        >
                            <div class="flex items-start gap-3">
                                <div class="mt-1">
                                    <Clock
                                        class="w-4 h-4 text-slate-400 group-hover:text-blue-500 dark:group-hover:text-blue-400 transition-colors"
                                    />
                                </div>
                                <div>
                                    <span
                                        class="text-xs font-bold text-slate-400 uppercase tracking-wider group-hover:text-blue-400"
                                        >Time</span
                                    >
                                    <div
                                        class="text-base font-bold text-slate-900 dark:text-white mt-0.5"
                                    >
                                        {{
                                            formatTime(appointment.date_time)
                                        }}
                                        WIB
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700 hover:border-orange-200 dark:hover:border-orange-500/50 hover:bg-orange-50/30 dark:hover:bg-orange-500/10 transition-all group"
                        >
                            <div class="flex items-start gap-3">
                                <div class="mt-1">
                                    <Timer
                                        class="w-4 h-4 text-slate-400 group-hover:text-orange-500 dark:group-hover:text-orange-400 transition-colors"
                                    />
                                </div>
                                <div>
                                    <span
                                        class="text-xs font-bold text-slate-400 uppercase tracking-wider group-hover:text-orange-400"
                                        >Duration</span
                                    >
                                    <div
                                        class="text-base font-bold text-slate-900 dark:text-white mt-0.5"
                                    >
                                        {{ appointment.hours }}
                                        {{
                                            appointment.hours > 1
                                                ? "Hours"
                                                : "Hour"
                                        }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Discussion Topic Card -->
                <div
                    class="bg-white dark:bg-slate-800 p-6 md:p-8 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-xl shadow-slate-200/60 dark:shadow-none"
                >
                    <h3
                        class="font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-3"
                    >
                        <div
                            class="w-8 h-8 rounded-lg bg-pink-100 dark:bg-pink-500/20 flex items-center justify-center text-pink-600 dark:text-pink-400"
                        >
                            <FileText class="w-4 h-4" />
                        </div>
                        Discussion Topic
                    </h3>

                    <div class="flex flex-wrap items-center gap-2 mb-6">
                        <span
                            class="px-3 py-1.5 rounded-lg bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-xs font-bold text-slate-600 dark:text-slate-300"
                        >
                            {{
                                appointment.skill?.sub_category?.category
                                    ?.name || "N/A"
                            }}
                        </span>
                        <span class="text-slate-300 dark:text-slate-500"
                            >/</span
                        >
                        <span
                            class="px-3 py-1.5 rounded-lg bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-xs font-bold text-slate-600 dark:text-slate-300"
                        >
                            {{ appointment.skill?.sub_category?.name || "N/A" }}
                        </span>
                    </div>

                    <div
                        class="relative bg-gradient-to-br from-violet-600 to-indigo-600 p-6 rounded-2xl shadow-lg shadow-violet-200 dark:shadow-none text-white overflow-hidden mb-6"
                    >
                        <div class="absolute top-0 right-0 p-4 opacity-10">
                            <Hash class="w-24 h-24" />
                        </div>

                        <span
                            class="relative z-10 text-xs font-bold text-violet-200 uppercase tracking-wider border border-violet-400/30 px-2 py-1 rounded"
                        >
                            Skill Focus
                        </span>
                        <h2 class="relative z-10 text-2xl font-bold mt-2">
                            {{ appointment.skill?.name || "N/A" }}
                        </h2>
                    </div>

                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <AlignLeft class="w-4 h-4 text-slate-400" />
                            <span
                                class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider"
                                >Client Notes</span
                            >
                        </div>
                        <div
                            class="bg-slate-50 dark:bg-slate-900/50 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 leading-relaxed text-sm relative"
                        >
                            <div
                                class="absolute top-4 left-4 text-slate-200 dark:text-slate-700"
                            >
                                <svg
                                    class="w-6 h-6 fill-current"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        d="M14.017 21L14.017 18C14.017 16.8954 14.9124 16 16.017 16H19.017C19.5693 16 20.017 15.5523 20.017 15V9C20.017 8.44772 19.5693 8 19.017 8H15.017C14.4647 8 14.017 8.44772 14.017 9V11C14.017 11.5523 13.5693 12 13.017 12H12.017V5H22.017V15C22.017 18.3137 19.3307 21 16.017 21H14.017ZM5.0166 21L5.0166 18C5.0166 16.8954 5.91203 16 7.0166 16H10.0166C10.5689 16 11.0166 15.5523 11.0166 15V9C11.0166 8.44772 10.5689 8 10.0166 8H6.0166C5.46432 8 5.0166 8.44772 5.0166 9V11C5.0166 11.5523 4.56889 12 4.0166 12H3.0166V5H13.0166V15C13.0166 18.3137 10.3303 21 7.0166 21H5.0166Z"
                                    ></path>
                                </svg>
                            </div>
                            <div class="relative z-10 pl-2">
                                {{ appointment.topic || "No notes provided." }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Actions Card -->
                <div
                    class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm"
                >
                    <h3
                        class="font-bold text-slate-900 dark:text-white mb-4 pb-3 border-b border-slate-100 dark:border-slate-700"
                    >
                        My Actions
                    </h3>

                    <!-- Need Confirmation Actions -->
                    <div
                        v-if="appointment.status === 'need_confirmation'"
                        class="space-y-4"
                    >
                        <div class="grid grid-cols-2 gap-3">
                            <button
                                @click="updateStatus('confirmed')"
                                class="flex items-center justify-center gap-2 w-full py-2.5 bg-violet-600 hover:bg-violet-700 text-white rounded-xl text-sm font-bold transition-all shadow-sm shadow-violet-200 dark:shadow-none"
                            >
                                <CheckCircle class="w-4 h-4" /> Accept
                            </button>
                            <button
                                @click="updateStatus('declined')"
                                class="flex items-center justify-center gap-2 w-full py-2.5 bg-white dark:bg-slate-700 border border-red-200 dark:border-red-500/30 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/20 rounded-xl text-sm font-bold transition-all"
                            >
                                <XCircle class="w-4 h-4" /> Decline
                            </button>
                        </div>
                        <button
                            @click="showRescheduleModal = true"
                            class="flex items-center gap-3 w-full px-4 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-medium hover:bg-violet-50 dark:hover:bg-violet-500/20 hover:text-violet-700 dark:hover:text-violet-400 hover:border-violet-200 dark:hover:border-violet-500/30 rounded-xl transition-all text-sm"
                        >
                            <RefreshCw class="w-4 h-4" /> Reschedule
                        </button>
                    </div>

                    <!-- Confirmed Status Actions -->
                    <div
                        v-else-if="appointment.status === 'confirmed'"
                        class="space-y-4"
                    >
                        <!-- Meeting Link Input -->
                        <div>
                            <label
                                class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-2 block"
                                >Meeting Link (Zoom/Gmeet)</label
                            >

                            <!-- Alert if no link -->
                            <div
                                v-if="!appointment.location_url"
                                class="mb-3 p-3 bg-amber-50 dark:bg-amber-500/20 border border-amber-200 dark:border-amber-500/30 rounded-xl flex items-start gap-2"
                            >
                                <AlertCircle
                                    class="w-4 h-4 text-amber-600 dark:text-amber-400 shrink-0 mt-0.5"
                                />
                                <p
                                    class="text-xs text-amber-700 dark:text-amber-400"
                                >
                                    Add a meeting link so the client knows where
                                    to join.
                                </p>
                            </div>

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
                                        class="block w-full pl-10 pr-3 py-2.5 text-sm rounded-xl border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-violet-500 focus:border-violet-500 shadow-sm placeholder-slate-400"
                                    />
                                </div>

                                <button
                                    @click="saveMeetingLink"
                                    :disabled="meetingForm.processing"
                                    class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-violet-600 text-white text-sm font-bold rounded-xl hover:bg-violet-700 focus:ring-4 focus:ring-violet-100 dark:focus:ring-violet-500/30 transition-all shadow-sm disabled:opacity-70 disabled:cursor-not-allowed"
                                >
                                    <Save class="w-4 h-4" />
                                    <span>{{
                                        meetingForm.processing ? "..." : "Save"
                                    }}</span>
                                </button>
                            </div>
                            <p class="mt-2 text-xs text-slate-400">
                                Pastikan link dapat diakses publik atau sertakan
                                passcode jika ada.
                            </p>
                        </div>

                        <!-- Current Meeting Link Display -->
                        <div
                            v-if="appointment.location_url"
                            class="p-3 bg-green-50 dark:bg-green-500/20 border border-green-200 dark:border-green-500/30 rounded-xl"
                        >
                            <div
                                class="flex items-center justify-between gap-2"
                            >
                                <div class="flex items-center gap-2 min-w-0">
                                    <CheckCircle2
                                        class="w-4 h-4 text-green-600 dark:text-green-400 shrink-0"
                                    />
                                    <span
                                        class="text-xs font-bold text-green-700 dark:text-green-400"
                                        >Link Ready</span
                                    >
                                </div>
                                <a
                                    :href="appointment.location_url"
                                    target="_blank"
                                    class="flex items-center gap-1 text-xs font-medium text-green-600 dark:text-green-400 hover:underline"
                                >
                                    Open <ExternalLink class="w-3 h-3" />
                                </a>
                            </div>
                        </div>

                        <button
                            @click="startSession"
                            class="flex items-center justify-center gap-2 w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold transition-all shadow-sm shadow-blue-200 dark:shadow-none"
                        >
                            <PlayCircle class="w-4 h-4" /> Start Session Now
                        </button>

                        <button
                            @click="showRescheduleModal = true"
                            class="flex items-center justify-center gap-3 w-full px-4 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-medium hover:bg-violet-50 dark:hover:bg-violet-500/20 hover:text-violet-700 dark:hover:text-violet-400 hover:border-violet-200 dark:hover:border-violet-500/30 rounded-xl transition-all text-sm"
                        >
                            <RefreshCw class="w-4 h-4" /> Reschedule
                        </button>
                    </div>

                    <!-- In Progress Actions -->
                    <div
                        v-else-if="appointment.status === 'progress'"
                        class="space-y-3"
                    >
                        <div
                            class="p-3 bg-blue-50 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400 text-xs rounded-lg flex items-center gap-2"
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
                            class="flex items-center justify-center gap-2 w-full py-2.5 bg-slate-800 dark:bg-white hover:bg-slate-900 dark:hover:bg-slate-100 text-white dark:text-slate-900 rounded-xl text-sm font-bold transition-all"
                        >
                            <CheckCircle class="w-4 h-4" /> Finish Session
                        </button>
                    </div>

                    <!-- Default State -->
                    <div
                        v-else
                        class="text-sm text-slate-500 dark:text-slate-400 text-center py-2 bg-slate-50 dark:bg-slate-700 rounded-lg"
                    >
                        {{
                            appointment.status === "pending"
                                ? "Waiting for client payment."
                                : "No actions required."
                        }}
                    </div>
                </div>

                <!-- Client Profile Card -->
                <div
                    class="bg-gradient-to-br from-violet-50 to-blue-50 dark:from-violet-500/10 dark:to-blue-500/10 p-6 rounded-3xl border border-violet-100/50 dark:border-violet-500/20 shadow-sm relative overflow-hidden"
                >
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-violet-200/20 dark:bg-violet-500/10 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"
                    ></div>

                    <h3
                        class="font-bold text-slate-900 dark:text-white mb-4 text-sm uppercase tracking-wider flex items-center gap-2 relative z-10"
                    >
                        <User
                            class="w-5 h-5 text-violet-600 dark:text-violet-400"
                        />
                        Client Profile
                    </h3>

                    <div class="flex items-start gap-4 mb-6 relative z-10">
                        <div class="relative shrink-0">
                            <div
                                class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-violet-600 to-blue-500 flex items-center justify-center text-white font-bold text-2xl shadow-md shadow-violet-200/50 dark:shadow-none border-2 border-white dark:border-slate-700"
                            >
                                {{ appointment.user.name.charAt(0) }}
                            </div>
                        </div>
                        <div>
                            <div
                                class="font-bold text-slate-900 dark:text-white text-lg leading-tight"
                            >
                                {{ appointment.user.name }}
                            </div>
                            <div
                                class="text-sm text-slate-500 dark:text-slate-400 mt-1 flex items-center gap-1.5"
                            >
                                <span
                                    class="inline-block w-2 h-2 rounded-full bg-green-500"
                                ></span>
                                Active Client
                            </div>
                        </div>
                    </div>

                    <div
                        class="space-y-3 pt-4 border-t border-violet-100/50 dark:border-violet-500/20 relative z-10"
                    >
                        <div
                            class="flex items-center gap-3 text-sm text-slate-600 dark:text-slate-300 group"
                        >
                            <div
                                class="w-8 h-8 rounded-lg bg-white dark:bg-slate-700 border border-slate-100 dark:border-slate-600 flex items-center justify-center shadow-sm group-hover:border-violet-200 dark:group-hover:border-violet-500/30 transition-colors shrink-0"
                            >
                                <Mail
                                    class="w-4 h-4 text-slate-400 group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors"
                                />
                            </div>
                            <a
                                :href="`mailto:${appointment.user.email}`"
                                class="hover:text-violet-600 dark:hover:text-violet-400 transition-colors truncate font-medium"
                            >
                                {{ appointment.user.email }}
                            </a>
                        </div>
                        <div
                            class="flex items-center gap-3 text-sm text-slate-600 dark:text-slate-300 group"
                        >
                            <div
                                class="w-8 h-8 rounded-lg bg-white dark:bg-slate-700 border border-slate-100 dark:border-slate-600 flex items-center justify-center shadow-sm group-hover:border-violet-200 dark:group-hover:border-violet-500/30 transition-colors shrink-0"
                            >
                                <Phone
                                    class="w-4 h-4 text-slate-400 group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors"
                                />
                            </div>
                            <span class="font-medium">{{
                                appointment.user.phone || "-"
                            }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reschedule Modal -->
        <div
            v-if="showRescheduleModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm"
        >
            <div
                class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl w-full max-w-4xl p-6 md:p-8 max-h-[90vh] overflow-y-auto custom-scrollbar"
            >
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3
                            class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2"
                        >
                            <RefreshCw
                                class="w-5 h-5 text-violet-600 dark:text-violet-400"
                            />
                            Reschedule Session
                        </h3>
                        <p
                            class="text-sm text-slate-500 dark:text-slate-400 mt-1"
                        >
                            Select a new date and time for this appointment.
                        </p>
                    </div>
                    <button
                        @click="showRescheduleModal = false"
                        class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-full transition-colors"
                    >
                        <XCircle class="w-6 h-6 text-slate-400" />
                    </button>
                </div>

                <div class="grid md:grid-cols-2 gap-8">
                    <div class="space-y-3">
                        <label
                            class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider ml-1"
                            >1. Pick a Date</label
                        >
                        <div
                            class="border border-slate-100 dark:border-slate-700 rounded-2xl overflow-hidden p-4 bg-white dark:bg-slate-900/50 shadow-sm flex justify-center"
                        >
                            <DatePicker
                                v-model="dateModel"
                                mode="date"
                                :min-date="new Date()"
                                color="purple"
                                borderless
                                transparent
                                :is-dark="$page.props.darkMode"
                                class="font-sans w-full"
                            />
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label
                            class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider ml-1"
                            >2. Pick a Time</label
                        >

                        <div
                            v-if="rescheduleForm.date"
                            class="grid grid-cols-2 gap-3 max-h-80 overflow-y-auto pr-2 custom-scrollbar"
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
                                        ? 'bg-slate-100 dark:bg-slate-700 border-transparent text-slate-400 cursor-not-allowed line-through opacity-60'
                                        : rescheduleForm.time === slot.time
                                        ? 'bg-violet-600 border-violet-600 text-white shadow-lg shadow-violet-200 dark:shadow-none'
                                        : 'bg-white dark:bg-slate-700 border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:border-violet-300 dark:hover:border-violet-500 hover:bg-violet-50 dark:hover:bg-violet-500/20',
                                ]"
                            >
                                <span>{{ slot.time }}</span>
                                <CheckCircle2
                                    v-if="rescheduleForm.time === slot.time"
                                    class="w-4 h-4"
                                />
                            </button>
                        </div>
                        <div
                            v-else
                            class="text-center py-10 text-slate-400 bg-slate-50 dark:bg-slate-700 rounded-xl border border-dashed border-slate-200 dark:border-slate-600"
                        >
                            Select a date first
                        </div>
                    </div>
                </div>

                <div
                    class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-700 flex justify-end gap-3"
                >
                    <button
                        @click="showRescheduleModal = false"
                        class="px-6 py-3 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-bold text-sm rounded-xl hover:bg-slate-50 dark:hover:bg-slate-600 transition-colors"
                    >
                        Cancel
                    </button>
                    <button
                        @click="submitReschedule"
                        :disabled="!rescheduleForm.date || !rescheduleForm.time"
                        class="px-8 py-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold text-sm rounded-xl hover:bg-violet-600 dark:hover:bg-violet-400 transition-all shadow-lg shadow-slate-900/20 dark:shadow-none disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Confirm New Schedule
                    </button>
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
.dark .custom-scrollbar::-webkit-scrollbar-thumb {
    background: #475569;
}
</style>
