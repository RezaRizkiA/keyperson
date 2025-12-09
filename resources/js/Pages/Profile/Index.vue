<script setup>
import { ref } from "vue";
import { Head } from "@inertiajs/vue3";
// import MainLayout from "../../Layouts/MainLayout.vue";
import AppLayout from "../../Layouts/AppLayout.vue";

// Components
import HomeTab from "./Tabs/HomeTab.vue";
import AppointmentTab from "./Tabs/AppointmentTab.vue";
import CalendarTab from "./Tabs/CalendarTab.vue";
import MembersTab from "./Tabs/MembersTab.vue";
import ExpertiseTab from "./Tabs/ExpertiseTab.vue";

// Icons
import {
    Home, // <--- Import Icon Home
    Briefcase,
    Calendar as CalendarIcon,
    Users,
    Settings,
    ChevronRight,
    LayoutDashboard
} from "lucide-vue-next";

// Props
const props = defineProps({
    user: Object,
    roles: Array,
    isExpert: Boolean,
    isClient: Boolean,
    isAdmin: Boolean,
    expertises: Array,
    appointments: Array,
    appointmentsCount: Number,
    calendarEvents: Array,
    socialMedias: Array
});

// DEFAULT TAB SEKARANG 'home'
const activeTab = ref("home");

// Tambahkan menu 'Home' di urutan pertama
const tabs = [
    {
        id: 'home',
        label: 'Dashboard Home',
        desc: 'Profile overview & stats',
        icon: Home,
        show: true,
        activeClass: 'bg-indigo-50 text-indigo-600 ring-1 ring-indigo-200'
    },
    {
        id: 'expertises',
        label: 'Expertise Config',
        desc: 'Manage skills & categories',
        icon: Settings,
        show: props.isAdmin,
        activeClass: 'bg-orange-50 text-orange-600 ring-1 ring-orange-200'
    },
    {
        id: 'appointments',
        label: 'My Appointments',
        desc: 'Upcoming & past sessions',
        icon: Briefcase,
        show: true,
        activeClass: 'bg-violet-50 text-violet-600 ring-1 ring-violet-200'
    },
    {
        id: 'calendar',
        label: 'Schedule Calendar',
        desc: 'Monthly & weekly view',
        icon: CalendarIcon,
        show: true,
        activeClass: 'bg-blue-50 text-blue-600 ring-1 ring-blue-200'
    },
    {
        id: 'members',
        label: 'User Community',
        desc: 'List of registered members',
        icon: Users,
        show: true,
        activeClass: 'bg-emerald-50 text-emerald-600 ring-1 ring-emerald-200'
    },
];
</script>

<template>

    <Head title="My Dashboard" />

    <AppLayout>
        <div class="bg-slate-50 min-h-screen pt-24 pb-20 font-sans">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <div class="grid lg:grid-cols-12 gap-8 items-start">

                    <div class="lg:col-span-3 sticky top-24 z-10 space-y-6">
                        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                            <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex items-center gap-2">
                                <LayoutDashboard class="w-4 h-4 text-slate-400" />
                                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Dashboard Menu
                                </h3>
                            </div>
                            <nav class="flex flex-col p-3 space-y-1">
                                <template v-for="tab in tabs" :key="tab.id">
                                    <button v-if="tab.show" @click="activeTab = tab.id"
                                        class="group relative w-full text-left px-4 py-4 rounded-2xl transition-all duration-300 flex items-center justify-between overflow-hidden"
                                        :class="activeTab === tab.id
                                            ? tab.activeClass
                                            : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'">
                                        <div class="flex items-center gap-4 relative z-10">
                                            <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-300"
                                                :class="activeTab === tab.id ? 'bg-white shadow-sm' : 'bg-slate-100 group-hover:bg-white group-hover:shadow-sm'">
                                                <component :is="tab.icon" class="w-5 h-5" />
                                            </div>
                                            <div>
                                                <div class="font-bold text-sm leading-tight">{{ tab.label }}</div>
                                                <div class="text-[11px] mt-0.5 font-medium opacity-70">{{ tab.desc }}
                                                </div>
                                            </div>
                                        </div>
                                        <ChevronRight v-if="activeTab === tab.id"
                                            class="w-5 h-5 opacity-50 relative z-10" />
                                    </button>
                                </template>
                            </nav>
                        </div>
                    </div>

                    <div class="lg:col-span-9">
                        <div v-if="activeTab === 'home'">
                            <HomeTab :user="user" :appointments-count="appointmentsCount" :is-expert="isExpert" />
                        </div>

                        <div v-else
                            class="bg-white border border-slate-200 shadow-sm rounded-[2rem] min-h-[600px] relative overflow-hidden flex flex-col">

                            <div
                                class="lg:hidden p-5 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Active
                                    View</span>
                                <span
                                    class="px-3 py-1 bg-violet-100 text-violet-700 rounded-full text-xs font-bold capitalize">
                                    {{ activeTab }}
                                </span>
                            </div>

                            <div class="flex-1 p-1">
                                <Transition name="fade" mode="out-in">

                                    <div v-if="activeTab === 'expertises' && isAdmin" class="h-full flex flex-col"
                                        key="expertises">
                                        <div class="p-8 border-b border-slate-100 bg-white sticky top-0 z-20">
                                            <h2 class="text-2xl font-bold text-slate-900">Expertise Management</h2>
                                            <p class="text-slate-500 mt-1">Configure categories and skills.</p>
                                        </div>
                                        <div class="p-8">
                                            <ExpertiseTab :expertises="expertises" />
                                        </div>
                                    </div>

                                    <div v-else-if="activeTab === 'appointments'" class="h-full flex flex-col"
                                        key="appointments">
                                        <AppointmentTab :appointments="appointments" :is-expert="isExpert"
                                            class="h-full border-0 shadow-none rounded-none" />
                                    </div>

                                    <div v-else-if="activeTab === 'calendar'" class="h-full flex flex-col p-8"
                                        key="calendar">
                                        <div class="mb-6">
                                            <h2 class="text-2xl font-bold text-slate-900">Schedule Calendar</h2>
                                            <p class="text-slate-500 mt-1">Visualize your upcoming sessions.</p>
                                        </div>
                                        <CalendarTab :events="calendarEvents" />
                                    </div>

                                    <div v-else-if="activeTab === 'members'" class="h-full flex flex-col p-8"
                                        key="members">
                                        <div class="mb-6">
                                            <h2 class="text-2xl font-bold text-slate-900">User Community</h2>
                                            <p class="text-slate-500 mt-1">Browse registered users and experts.</p>
                                        </div>
                                        <MembersTab />
                                    </div>

                                </Transition>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1), transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.fade-enter-from {
    opacity: 0;
    transform: translateX(15px);
}

.fade-leave-to {
    opacity: 0;
    transform: translateX(-15px);
}
</style>