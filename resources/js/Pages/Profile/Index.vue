<script setup>
import { ref, computed } from "vue";
import { Head, usePage, Link } from "@inertiajs/vue3";
import AppLayout from "../../Layouts/AppLayout.vue";
import { Menu as HeadlessMenu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue'; // Rename agar tidak bentrok dengan Icon Menu

// Components
import HomeTab from "./Tabs/HomeTab.vue";
import AppointmentTab from "./Tabs/AppointmentTab.vue";
import CalendarTab from "./Tabs/CalendarTab.vue";
import MembersTab from "./Tabs/MembersTab.vue";
import ExpertiseTab from "./Tabs/ExpertiseTab.vue";
import PaymentTab from "./Tabs/PaymentTab.vue";

// Icons
import {
    Home,
    Briefcase,
    Calendar as CalendarIcon,
    Users,
    Settings,
    LayoutDashboard,
    CreditCard,
    LogOut,
    Menu as MenuIcon,
    X,
    PanelLeftClose,
    PanelLeftOpen,
    ChevronDown,
    User as UserIcon,
    Bell,
    Moon,
    Search,
    Sun
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
    socialMedias: Array,
    transactions: Array,
});

const page = usePage();
const assets = computed(() => page.props.assets);

// State
const activeTab = ref("home");
const isSidebarExpanded = ref(true); 
const isMobileMenuOpen = ref(false);

const toggleSidebar = () => {
    isSidebarExpanded.value = !isSidebarExpanded.value;
};

const tabs = [
    { id: 'home', label: 'Overview', icon: Home, show: true, activeClass: 'bg-violet-50 text-violet-600 border-r-4 border-violet-600' },
    { id: 'appointments', label: 'Appointments', icon: Briefcase, show: true, activeClass: 'bg-blue-50 text-blue-600 border-r-4 border-blue-600' },
    { id: 'calendar', label: 'Calendar', icon: CalendarIcon, show: true, activeClass: 'bg-emerald-50 text-emerald-600 border-r-4 border-emerald-600' },
    { id: 'transactions', label: 'Billing', icon: CreditCard, show: true, activeClass: 'bg-pink-50 text-pink-600 border-r-4 border-pink-600' },
    { id: 'members', label: 'Community', icon: Users, show: true, activeClass: 'bg-indigo-50 text-indigo-600 border-r-4 border-indigo-600' },
    { id: 'expertises', label: 'Settings', icon: Settings, show: props.isAdmin, activeClass: 'bg-orange-50 text-orange-600 border-r-4 border-orange-600' },
];

const currentTabTitle = computed(() => {
    const tab = tabs.find(t => t.id === activeTab.value);
    return tab ? tab.label : 'Dashboard';
});
</script>

<template>
    <Head title="My Dashboard" />

    <div class="min-h-screen bg-slate-50 font-sans flex">

        <aside 
            class="fixed inset-y-0 left-0 z-50 bg-white border-r border-slate-200 transition-all duration-300 ease-in-out flex flex-col"
            :class="[
                isSidebarExpanded ? 'w-64' : 'w-20',
                isMobileMenuOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
            ]"
        >
            <div class="h-20 flex items-center border-b border-slate-100"
                 :class="isSidebarExpanded ? 'px-6 justify-between' : 'justify-center px-0'">
                
                <Link :href="route('home')" class="flex items-center gap-2 overflow-hidden">
                    <img :src="assets.logoSmallUrl" class="h-9 w-auto shrink-0" alt="Logo">
                    <span v-if="isSidebarExpanded" class="font-display font-bold text-xl text-slate-900 transition-opacity duration-300 whitespace-nowrap">
                        Key<span class="text-violet-600">Person</span>
                    </span>
                </Link>

                <button @click="isMobileMenuOpen = false" class="lg:hidden p-1 text-slate-400">
                    <X class="w-5 h-5" />
                </button>
            </div>

            <div class="flex-1 py-6 space-y-1 custom-scrollbar" 
                 :class="isSidebarExpanded ? 'overflow-y-auto' : 'overflow-visible'">
                <template v-for="tab in tabs" :key="tab.id">
                    <button v-if="tab.show" 
                        @click="activeTab = tab.id; isMobileMenuOpen = false"
                        class="relative w-full flex items-center py-3 transition-all duration-200 group"
                        :class="[
                            isSidebarExpanded ? 'px-6' : 'justify-center px-2',
                            activeTab === tab.id 
                                ? tab.activeClass 
                                : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900'
                        ]"
                    >
                        <component :is="tab.icon" 
                            class="shrink-0 transition-colors duration-200 relative z-10" 
                            :class="[
                                isSidebarExpanded ? 'w-5 h-5 mr-3' : 'w-6 h-6',
                                activeTab === tab.id ? '' : 'text-slate-400 group-hover:text-slate-600'
                            ]" 
                        />
                        
                        <span v-if="isSidebarExpanded" class="font-medium text-sm whitespace-nowrap">
                            {{ tab.label }}
                        </span>

                        <div v-if="!isSidebarExpanded" 
                             class="absolute left-full ml-4 bg-slate-900 text-white text-xs font-bold px-3 py-2 rounded-lg opacity-0 group-hover:opacity-100 transition-all duration-200 pointer-events-none z-[60] whitespace-nowrap shadow-xl flex items-center -translate-x-2 group-hover:translate-x-0">
                            <div class="absolute -left-1 w-2 h-2 bg-slate-900 rotate-45"></div>
                            {{ tab.label }}
                        </div>
                    </button>
                </template>
            </div>

            <div class="p-4 border-t border-slate-100">
                <button @click="toggleSidebar" 
                    class="hidden lg:flex w-full items-center py-2 transition-colors rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600"
                    :class="isSidebarExpanded ? 'px-2' : 'justify-center'">
                    <component :is="isSidebarExpanded ? PanelLeftClose : PanelLeftOpen" class="w-5 h-5" />
                    <span v-if="isSidebarExpanded" class="ml-3 text-xs font-bold uppercase tracking-wider">
                        Collapse
                    </span>
                </button>
            </div>
        </aside>

        <main 
            class="flex-1 min-h-screen transition-all duration-300 ease-in-out flex flex-col"
            :class="isSidebarExpanded ? 'lg:ml-64' : 'lg:ml-20'"
        >
            <header class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-30 px-4 sm:px-8 relative flex items-center justify-between">
                
                <div class="flex items-center gap-4 shrink-0 relative z-20">
                    <button @click="isMobileMenuOpen = true" class="lg:hidden p-2 text-slate-500 hover:bg-slate-100 rounded-lg">
                        <MenuIcon class="w-6 h-6" />
                    </button>
                    <h2 class="text-xl font-bold text-slate-800 hidden sm:block whitespace-nowrap">
                        {{ currentTabTitle }}
                    </h2>
                </div>

                <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-xl px-4 hidden md:block z-10">
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <Search class="h-5 w-5 text-slate-400 group-focus-within:text-violet-500 transition-colors" />
                        </div>
                        <input type="text" 
                            class="block w-full pl-10 pr-4 py-2.5 bg-slate-100/50 border border-transparent rounded-full text-sm text-slate-900 placeholder-slate-500 focus:outline-none focus:bg-white focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 transition-all shadow-sm" 
                            placeholder="Search anything (press '/' to focus)...">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-slate-400 text-xs font-bold bg-white border border-slate-200 rounded px-1.5 py-0.5">/</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2 sm:gap-4 shrink-0 relative z-20">
                    
                    <button class="md:hidden p-2 text-slate-500 hover:bg-slate-100 rounded-full transition-colors">
                        <Search class="w-5 h-5" />
                    </button>

                    <button @click="isDarkMode = !isDarkMode" 
                        class="p-2 text-slate-500 bg-slate-100 rounded-full transition-colors relative group cursor-pointer">
                        <component :is="isDarkMode ? Sun : Moon" class="w-5 h-5" />
                        <span class="sr-only">Toggle Theme</span>
                    </button>

                    <button class="p-2 text-slate-500 bg-slate-100 rounded-full transition-colors relative group cursor-pointer">
                        <Bell class="w-5 h-5" />
                        <span class="absolute top-2 right-2.5 w-2 h-2 bg-red-500 rounded-full ring-2 ring-white"></span>
                    </button>

                    <div class="h-6 w-px bg-slate-200 mx-1"></div>

                    <HeadlessMenu as="div" class="relative">
                        <MenuButton class="flex items-center gap-3 pl-1 pr-1 sm:pl-2 sm:pr-2 py-1 rounded-full hover:bg-slate-100 transition-all border border-transparent hover:border-slate-200 group focus:outline-none">
                            
                            <div class="relative">
                                <img :src="user.profile_photo_url || assets.userPlaceholderUrl" 
                                     class="w-9 h-9 rounded-full object-cover border-2 border-white shadow-sm group-hover:border-violet-100" 
                                     alt="Avatar">
                                <div class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 border-white rounded-full"></div>
                            </div>
                            
                            <div class="hidden lg:flex flex-col items-start mr-1 text-left">
                                <span class="text-sm font-bold text-slate-900 leading-none max-w-[100px] truncate">{{ user.name }}</span>
                                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mt-0.5">
                                    {{ isExpert ? 'Expert' : 'Client' }}
                                </span>
                            </div>

                            <ChevronDown class="w-4 h-4 text-slate-400 group-hover:text-slate-600 hidden lg:block" />
                        </MenuButton>

                        <transition
                            enter-active-class="transition duration-100 ease-out"
                            enter-from-class="transform scale-95 opacity-0"
                            enter-to-class="transform scale-100 opacity-100"
                            leave-active-class="transition duration-75 ease-in"
                            leave-from-class="transform scale-100 opacity-100"
                            leave-to-class="transform scale-95 opacity-0"
                        >
                            <MenuItems class="absolute right-0 mt-2 w-60 origin-top-right divide-y divide-slate-100 rounded-2xl bg-white shadow-xl ring-1 ring-black/5 focus:outline-none overflow-hidden z-50">
                                
                                <div class="px-5 py-4 bg-slate-50 border-b border-slate-100">
                                    <p class="text-sm font-bold text-slate-900 truncate">{{ user.name }}</p>
                                    <p class="text-xs text-slate-500 truncate">{{ user.email }}</p>
                                </div>

                                <div class="p-1.5">
                                    <MenuItem v-slot="{ active }">
                                        <Link :href="route('update_profile')" 
                                            :class="[
                                                active ? 'bg-violet-50 text-violet-700' : 'text-slate-700',
                                                'group flex w-full items-center rounded-xl px-3 py-2.5 text-sm font-medium transition-colors'
                                            ]">
                                            <Settings class="mr-3 h-4 w-4 text-slate-400 group-hover:text-violet-500" />
                                            Account Settings
                                        </Link>
                                    </MenuItem>
                                    <MenuItem v-slot="{ active }">
                                        <a href="#" :class="[
                                                active ? 'bg-violet-50 text-violet-700' : 'text-slate-700',
                                                'group flex w-full items-center rounded-xl px-3 py-2.5 text-sm font-medium transition-colors'
                                            ]">
                                            <UserIcon class="mr-3 h-4 w-4 text-slate-400 group-hover:text-violet-500" />
                                            Public Profile
                                        </a>
                                    </MenuItem>
                                </div>

                                <div class="p-1.5">
                                    <MenuItem v-slot="{ active }">
                                        <Link :href="route('logout')" method="post" as="button"
                                            :class="[
                                                active ? 'bg-red-50 text-red-600' : 'text-slate-600',
                                                'group flex w-full items-center rounded-xl px-3 py-2.5 text-sm font-medium transition-colors'
                                            ]">
                                            <LogOut class="mr-3 h-4 w-4 text-slate-400 group-hover:text-red-500" />
                                            Sign Out
                                        </Link>
                                    </MenuItem>
                                </div>
                            </MenuItems>
                        </transition>
                    </HeadlessMenu>
                </div>

            </header>

            <div class="p-4 sm:p-8 flex-1">
                <div class="max-w-7xl mx-auto h-full">
                    <Transition name="fade" mode="out-in">
                        
                        <div v-if="activeTab === 'home'" key="home" class="h-full">
                            <HomeTab :user="user" :appointments-count="appointmentsCount" :is-expert="isExpert" />
                        </div>

                        <div v-else-if="activeTab === 'expertises' && isAdmin" key="expertises">
                            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                                <div class="p-6 md:p-8 border-b border-slate-100">
                                    <h2 class="text-xl font-bold text-slate-900">Expertise Settings</h2>
                                </div>
                                <div class="p-6 md:p-8">
                                    <ExpertiseTab :expertises="expertises" />
                                </div>
                            </div>
                        </div>

                        <div v-else-if="activeTab === 'appointments'" key="appointments">
                            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden p-6 md:p-8">
                                <div class="mb-6">
                                    <h2 class="text-2xl font-bold text-slate-900">My Appointments</h2>
                                    <p class="text-slate-500 mt-1">Manage your upcoming and past sessions.</p>
                                </div>
                                <AppointmentTab :appointments="appointments" :is-expert="isExpert" />
                            </div>
                        </div>

                        <div v-else-if="activeTab === 'calendar'" key="calendar">
                            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden p-6 md:p-8">
                                <CalendarTab :calendar-events="calendarEvents" />
                            </div>
                        </div>

                        <div v-else-if="activeTab === 'members'" key="members">
                            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden p-6 md:p-8">
                                <MembersTab />
                            </div>
                        </div>

                        <div v-else-if="activeTab === 'transactions'" key="transactions">
                            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden p-6 md:p-8">
                                <PaymentTab :transactions="transactions" :is-expert="isExpert" />
                            </div>
                        </div>

                    </Transition>
                </div>
            </div>
        </main>

        <div v-if="isMobileMenuOpen" 
             @click="isMobileMenuOpen = false"
             class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 lg:hidden">
        </div>

    </div>
</template>

<style scoped>
/* Scrollbar */
.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
.custom-scrollbar:hover::-webkit-scrollbar-thumb { background: #94a3b8; }

/* Transitions */
.fade-enter-active, .fade-leave-active { transition: opacity 0.2s ease, transform 0.2s ease; }
.fade-enter-from { opacity: 0; transform: translateY(10px); }
.fade-leave-to { opacity: 0; transform: translateY(-10px); }
</style>