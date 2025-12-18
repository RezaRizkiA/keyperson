<script setup>
import { ref } from "vue";
import { Link, router } from "@inertiajs/vue3"; // Tambahkan router
import Swal from "sweetalert2";

import DashboardLayout from "@/Layouts/DashboardLayout.vue";

import {
    Calendar,
    Clock,
    MapPin,
    CheckCircle,
    XCircle,
    User,
    Mail,
    Phone,
    Briefcase,
    FileText,
    ArrowLeft,
    CreditCard,
    CheckSquare,
    RefreshCw,
    Trash2,
    AlertCircle,
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
const formatCurrency = (amount) =>
    new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(amount);

// Status Config
const statusConfig = {
    pending: {
        class: "bg-yellow-50 text-yellow-700 border-yellow-200",
        label: "Pending Payment",
    },
    need_confirmation: {
        class: "bg-orange-50 text-orange-700 border-orange-200",
        label: "Need Confirmation",
    },
    progress: {
        class: "bg-blue-50 text-blue-700 border-blue-200",
        label: "In Progress",
    },
    confirmed: {
        class: "bg-green-50 text-green-700 border-green-200",
        label: "Confirmed",
    },
    completed: {
        class: "bg-slate-100 text-slate-700 border-slate-200",
        label: "Completed",
    },
    declined: {
        class: "bg-red-50 text-red-700 border-red-200",
        label: "Cancelled",
    },
};

const getStatus = (status) =>
    statusConfig[status] || { class: "bg-gray-100", label: status };

// ==========================================
// LOGIC ACTION (ADMIN)
// ==========================================

// 1. Update Status
const updateStatus = (newStatus) => {
    // GANTI confirm() dengan Swal.fire
    Swal.fire({
        title: "Are you sure?",
        text: `Change status to ${newStatus}?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#7c3aed", // Warna Violet
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, update it!",
    }).then((result) => {
        if (result.isConfirmed) {
            // Baru jalankan router jika user klik Yes
            router.patch(
                route(
                    "dashboard.appointments.update-status",
                    props.appointment.id
                ),
                {
                    status: newStatus,
                },
                {
                    preserveScroll: true,
                    // onSuccess tidak perlu alert manual lagi, biarkan Toast bekerja
                }
            );
        }
    });
};

// 2. Delete Appointment
const deleteAppointment = () => {
    if (
        !confirm(
            "WARNING: This action cannot be undone. Delete this appointment?"
        )
    )
        return;

    router.delete(
        route("dashboard.appointments.destroy", props.appointment.id)
    );
};

// 3. Reschedule Logic
// const showRescheduleModal = ref(false);
// const rescheduleForm = ref({
//     date: '',
//     time: ''
// });

// const submitReschedule = () => {
//     // 1. Validasi Client Side
//     if (!rescheduleForm.value.date || !rescheduleForm.value.time) {
//         alert("Please select both date and time.");
//         return;
//     }

//     // 2. Gabungkan Format
//     // Tips: Tambahkan ':00' (detik) agar sesuai format MySQL 'YYYY-MM-DD HH:mm:ss'
//     const newDateTime = `${rescheduleForm.value.date} ${rescheduleForm.value.time}:00`;

//     // 3. Kirim ke Backend
//     router.patch(route('dashboard.appointments.reschedule', props.appointment.id), {
//         date_time: newDateTime
//     }, {
//         onSuccess: () => {
//             // Berhasil: Tutup Modal & Reset
//             showRescheduleModal.value = false;
//             rescheduleForm.value = { date: '', time: '' };
//             // alert('Berhasil diubah!'); // Opsional
//         },
//         onError: (errors) => {
//             // Gagal: Tampilkan pesan error dari Laravel
//             console.error(errors);
//             // Ambil pesan error pertama (jika ada)
//             const firstError = Object.values(errors)[0];
//             alert(`Gagal reschedule: ${firstError}`);
//         },
//         onFinish: () => {
//             // Dijalankan baik sukses maupun gagal (opsional, misal matikan loading spinner)
//         }
//     });
// };
</script>

<template>
    <div class="space-y-6 relative">
        <div class="flex items-center gap-4">
            <Link
                :href="route('dashboard.appointments.index')"
                class="p-2 hover:bg-slate-100 rounded-lg transition-colors text-slate-500"
            >
                <ArrowLeft class="w-5 h-5" />
            </Link>
            <div>
                <h1 class="text-2xl font-bold text-slate-900">
                    Appointment Details
                </h1>
                <p class="text-sm text-slate-500">
                    Ref ID:
                    <span class="font-mono font-medium"
                        >#{{ appointment.id }}</span
                    >
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
                        <Calendar class="w-5 h-5 text-violet-500" />
                        Session Schedule
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
                    </div>

                    <div
                        v-if="
                            ['confirmed', 'progress'].includes(
                                appointment.status
                            )
                        "
                        class="mt-6"
                    >
                        <span
                            class="text-xs font-bold text-slate-400 uppercase tracking-wider"
                            >Meeting Location</span
                        >
                        <div
                            class="flex items-center gap-3 mt-2 p-3 bg-blue-50 text-blue-700 rounded-xl border border-blue-100"
                        >
                            <MapPin class="w-5 h-5" />
                            <span class="font-medium truncate">{{
                                appointment.location_url ||
                                "Online Meeting (Link TBD)"
                            }}</span>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm"
                >
                    <h3
                        class="font-bold text-slate-900 mb-4 flex items-center gap-2"
                    >
                        <FileText class="w-5 h-5 text-violet-500" />
                        Consultation Topic
                    </h3>

                    <div
                        class="flex flex-wrap items-center gap-2 text-sm text-slate-500 mb-4"
                    >
                        <span
                            class="bg-slate-100 px-3 py-1 rounded-full border border-slate-200"
                        >
                            {{ appointment.skill.sub_category.category.name }}
                        </span>
                        <span>/</span>
                        <span
                            class="bg-slate-100 px-3 py-1 rounded-full border border-slate-200"
                        >
                            {{ appointment.skill.sub_category.name }}
                        </span>
                    </div>

                    <div
                        class="bg-violet-50 p-5 rounded-xl border border-violet-100"
                    >
                        <span
                            class="text-xs font-bold text-violet-600 uppercase tracking-wider mb-1 block"
                            >Specific Skill Focus</span
                        >
                        <h2 class="text-xl font-bold text-violet-900">
                            {{ appointment.skill.name }}
                        </h2>
                    </div>

                    <div class="mt-6">
                        <span
                            class="text-xs font-bold text-slate-400 uppercase tracking-wider"
                            >Client Notes / Problem Description</span
                        >
                        <p
                            class="mt-2 text-slate-600 leading-relaxed bg-slate-50 p-4 rounded-xl border border-slate-100"
                        >
                            {{
                                appointment.topic ||
                                "No specific notes provided by client."
                            }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1 space-y-6">
                <div
                    class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm ring-1 ring-slate-100"
                >
                    <h3
                        class="font-bold text-slate-900 mb-4 flex items-center gap-2 border-b border-slate-100 pb-3"
                    >
                        <CheckSquare class="w-5 h-5 text-violet-600" />
                        Admin Actions
                    </h3>

                    <div class="space-y-3">
                        <div
                            v-if="appointment.status === 'need_confirmation'"
                            class="space-y-3"
                        >
                            <div
                                class="p-3 bg-orange-50 text-orange-700 text-xs rounded-lg mb-2 border border-orange-100"
                            >
                                ⚠️ Expert hasn't confirmed yet. You can force
                                confirm or decline.
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <button
                                    @click="updateStatus('confirmed')"
                                    class="flex items-center justify-center gap-2 w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold transition-all shadow-sm shadow-emerald-200"
                                >
                                    <CheckCircle class="w-4 h-4" /> Confirm
                                </button>
                                <button
                                    @click="updateStatus('declined')"
                                    class="flex items-center justify-center gap-2 w-full py-2.5 bg-white border border-red-200 text-red-600 hover:bg-red-50 rounded-xl text-sm font-bold transition-all"
                                >
                                    <XCircle class="w-4 h-4" /> Decline
                                </button>
                            </div>

                            <!-- <button @click="showRescheduleModal = true" 
                                class="flex items-center gap-3 w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-700 font-medium hover:bg-violet-50 hover:text-violet-700 hover:border-violet-200 rounded-xl transition-all text-left">
                                <RefreshCw class="w-4 h-4" /> Reschedule Date
                            </button> -->
                        </div>

                        <div
                            v-else-if="appointment.status === 'confirmed'"
                            class="space-y-3"
                        >
                            <button
                                @click="updateStatus('progress')"
                                class="flex items-center justify-center gap-2 w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold transition-all shadow-sm shadow-blue-200"
                            >
                                <PlayCircle class="w-4 h-4" /> Start Session
                            </button>

                            <button
                                @click="showRescheduleModal = true"
                                class="flex items-center gap-3 w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-700 font-medium hover:bg-violet-50 hover:text-violet-700 hover:border-violet-200 rounded-xl transition-all text-left"
                            >
                                <RefreshCw class="w-4 h-4" /> Reschedule Session
                            </button>

                            <button
                                @click="updateStatus('declined')"
                                class="flex items-center gap-3 w-full px-4 py-2.5 bg-white border border-red-200 text-red-600 hover:bg-red-50 rounded-xl font-medium transition-all text-left text-sm"
                            >
                                <XCircle class="w-4 h-4" /> Cancel Appointment
                            </button>
                        </div>

                        <div
                            v-else-if="appointment.status === 'progress'"
                            class="space-y-3"
                        >
                            <div
                                class="p-3 bg-blue-50 text-blue-700 text-xs rounded-lg mb-2 border border-blue-100 flex items-center gap-2"
                            >
                                <span class="relative flex h-2 w-2">
                                    <span
                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"
                                    ></span>
                                    <span
                                        class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"
                                    ></span>
                                </span>
                                Session is currently active.
                            </div>

                            <button
                                @click="updateStatus('completed')"
                                class="flex items-center justify-center gap-2 w-full py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-sm font-bold transition-all shadow-sm"
                            >
                                <CheckCircle class="w-4 h-4" /> Finish & Mark
                                Completed
                            </button>
                        </div>

                        <div
                            v-else-if="
                                ['completed', 'declined'].includes(
                                    appointment.status
                                )
                            "
                        >
                            <div
                                class="p-3 bg-slate-50 text-slate-500 text-xs rounded-lg mb-2 text-center"
                            >
                                This session is {{ appointment.status }}. No
                                actions required.
                            </div>
                            <button
                                @click="deleteAppointment"
                                class="flex items-center justify-center gap-2 w-full px-4 py-2.5 text-red-600 hover:bg-red-50 rounded-xl transition-colors text-sm font-bold"
                            >
                                <Trash2 class="w-4 h-4" /> Delete Permanently
                            </button>
                        </div>

                        <div v-else>
                            <div
                                class="p-3 bg-yellow-50 text-yellow-700 text-xs rounded-lg mb-2 border border-yellow-100"
                            >
                                Waiting for payment.
                            </div>
                            <button
                                @click="deleteAppointment"
                                class="flex items-center justify-center gap-2 w-full px-4 py-2.5 text-red-600 hover:bg-red-50 rounded-xl transition-colors text-sm font-bold"
                            >
                                <Trash2 class="w-4 h-4" /> Delete / Cancel
                            </button>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm"
                >
                    <h3
                        class="font-bold text-slate-900 mb-4 text-sm uppercase tracking-wider"
                    >
                        Expert
                    </h3>
                    <div class="flex items-center gap-4 mb-4">
                        <div
                            class="w-12 h-12 rounded-full bg-violet-100 flex items-center justify-center text-violet-700 font-bold text-lg"
                        >
                            {{ appointment.expert.user.name.charAt(0) }}
                        </div>
                        <div>
                            <div class="font-bold text-slate-900">
                                {{ appointment.expert.user.name }}
                            </div>
                            <div class="text-xs text-slate-500">
                                Expert ID: #{{ appointment.expert.id }}
                            </div>
                        </div>
                    </div>
                    <div class="space-y-3 pt-4 border-t border-slate-100">
                        <div
                            class="flex items-center gap-3 text-sm text-slate-600"
                        >
                            <Mail class="w-4 h-4 text-slate-400" />
                            <span class="truncate">{{
                                appointment.expert.user.email
                            }}</span>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm"
                >
                    <h3
                        class="font-bold text-slate-900 mb-4 text-sm uppercase tracking-wider"
                    >
                        Client
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
                            <div class="text-xs text-slate-500">Client</div>
                        </div>
                    </div>
                    <div class="space-y-3 pt-4 border-t border-slate-100">
                        <div
                            class="flex items-center gap-3 text-sm text-slate-600"
                        >
                            <Mail class="w-4 h-4 text-slate-400" />
                            <span class="truncate">{{
                                appointment.user.email
                            }}</span>
                        </div>
                        <div
                            class="flex items-center gap-3 text-sm text-slate-600"
                        >
                            <Phone class="w-4 h-4 text-slate-400" />
                            <span>{{ appointment.user.phone || "-" }}</span>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm"
                >
                    <h3
                        class="font-bold text-slate-900 mb-4 flex items-center gap-2"
                    >
                        <CreditCard class="w-5 h-5 text-emerald-600" />
                        Billing Info
                    </h3>

                    <div v-if="appointment.transaction">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-slate-500">Amount</span>
                            <span class="text-lg font-bold text-slate-900">
                                {{
                                    formatCurrency(
                                        appointment.transaction.amount
                                    )
                                }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-sm text-slate-500">Status</span>
                            <span
                                class="text-xs font-bold px-2 py-1 rounded bg-emerald-50 text-emerald-700 border border-emerald-100"
                                v-if="appointment.transaction.status === 'paid'"
                            >
                                PAID
                            </span>
                            <span
                                class="text-xs font-bold px-2 py-1 rounded bg-slate-100 text-slate-500"
                                v-else
                            >
                                {{
                                    appointment.transaction.status.toUpperCase()
                                }}
                            </span>
                        </div>

                        <div class="pt-4 border-t border-slate-100">
                            <button
                                class="w-full py-2 text-sm text-slate-600 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-colors border border-dashed border-slate-300"
                            >
                                View Transaction Proof
                            </button>
                        </div>
                    </div>

                    <div
                        v-else
                        class="text-center py-4 text-slate-400 text-sm bg-slate-50 rounded-xl border border-slate-100 border-dashed"
                    >
                        No transaction data found.
                    </div>
                </div>
            </div>
        </div>

        <!-- <div v-if="showRescheduleModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 animate-in fade-in zoom-in duration-200">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Reschedule Session</h3>
                        <p class="text-sm text-slate-500">Select a new date and time for this appointment.</p>
                    </div>
                    <button @click="showRescheduleModal = false" class="text-slate-400 hover:text-slate-600">
                        <XCircle class="w-6 h-6" />
                    </button>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">New Date</label>
                        <input type="date" v-model="rescheduleForm.date" 
                            class="w-full rounded-xl border-slate-200 focus:ring-violet-500 focus:border-violet-500 text-sm font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">New Time</label>
                        <input type="time" v-model="rescheduleForm.time" 
                            class="w-full rounded-xl border-slate-200 focus:ring-violet-500 focus:border-violet-500 text-sm font-medium">
                    </div>
                </div>

                <div class="flex gap-3 mt-8">
                    <button @click="showRescheduleModal = false" 
                        class="flex-1 px-4 py-2.5 bg-white border border-slate-200 text-slate-700 font-bold text-sm rounded-xl hover:bg-slate-50 transition-colors">
                        Cancel
                    </button>
                    <button @click="submitReschedule" 
                        class="flex-1 px-4 py-2.5 bg-violet-600 text-white font-bold text-sm rounded-xl hover:bg-violet-700 shadow-md shadow-violet-200 transition-colors">
                        Save Changes
                    </button>
                </div>
            </div>
        </div> -->
    </div>
</template>
