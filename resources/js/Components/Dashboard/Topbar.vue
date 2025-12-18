<script setup>
import { computed } from 'vue';
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue';
import { Menu as MenuIcon, ChevronDown, User, Settings, LogOut } from 'lucide-vue-next';
import { Link, usePage } from '@inertiajs/vue3';

const emit = defineEmits(['openMobileMenu']);

const props = defineProps({
    title: {
        type: String,
        default: 'Dashboard' 
    }
});

const page = usePage();
const user = computed(() => page.props.auth.user);

const userRoleLabel = computed(() => {
    if (!user.value) return 'Guest';

    // Logika deteksi role
    const roles = user.value.roles || [];
    const roleNames = Array.isArray(roles) 
        ? roles.map(r => (typeof r === 'string' ? r : r.name)) 
        : [];

    if (roleNames.includes('administrator')) return 'Administrator';
    if (roleNames.includes('expert')) return 'Expert';
    
    return 'Client'; 
});

// Helper untuk Judul Halaman berdasarkan Route
const pageTitle = computed(() => {
    const routeName = route().current();
    if (routeName === 'dashboard') return 'Overview';
    if (routeName === 'dashboard.appointments') return 'Appointments';
    if (routeName === 'dashboard.calendar') return 'Calendar';
    if (routeName === 'dashboard.billing') return 'Billing & Transactions';
    if (routeName === 'dashboard.expertises') return 'Expertise Management';
    if (routeName === 'profile.edit') return 'Account Settings';
    return 'Dashboard';
});
</script>

<template>
    <header class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-30 px-4 sm:px-8 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <button @click="$emit('openMobileMenu')" class="lg:hidden p-2 text-slate-500 hover:bg-slate-100 rounded-lg transition-colors">
                <MenuIcon class="w-6 h-6" />
            </button>
            <h1 class="text-xl font-bold text-slate-800 hidden sm:block">
                {{ pageTitle }}
            </h1>
        </div>

        <div class="flex items-center gap-4">
            <Menu as="div" class="relative">
                <MenuButton class="flex items-center gap-3 pl-2 pr-1 py-1.5 rounded-full hover:bg-slate-100 transition-all border border-transparent hover:border-slate-200">
                    <div class="text-right hidden md:block mr-1">
                        <p class="text-sm font-bold text-slate-900 leading-none">
                            {{ user?.name }}
                        </p>
                        <p class="text-[10px] text-slate-500 uppercase font-bold mt-0.5 tracking-wider">
                            {{ userRoleLabel }}
                        </p>
                    </div>
                    <img :src="user.picture_url || 'https://ui-avatars.com/api/?name='+user.name" class="w-9 h-9 rounded-full object-cover border border-slate-200 shadow-sm" alt="User">
                    <ChevronDown class="w-4 h-4 text-slate-400" />
                </MenuButton>

                <transition
                    enter-active-class="transition duration-100 ease-out"
                    enter-from-class="transform scale-95 opacity-0"
                    enter-to-class="transform scale-100 opacity-100"
                    leave-active-class="transition duration-75 ease-in"
                    leave-from-class="transform scale-100 opacity-100"
                    leave-to-class="transform scale-95 opacity-0"
                >
                    <MenuItems class="absolute right-0 mt-2 w-56 origin-top-right divide-y divide-slate-100 rounded-xl bg-white shadow-lg ring-1 ring-black/5 focus:outline-none overflow-hidden">
                        <div class="px-4 py-3 bg-slate-50">
                            <p class="text-sm font-medium text-slate-900 truncate">Signed in as</p>
                            <p class="text-xs text-slate-500 truncate mt-0.5">{{ user.email }}</p>
                        </div>
                        <div class="p-1">
                            <MenuItem v-slot="{ active }">
                                <Link :href="route('profile.edit')" :class="[active ? 'bg-violet-50 text-violet-700' : 'text-slate-700', 'group flex w-full items-center rounded-lg px-3 py-2 text-sm font-medium']">
                                    <Settings class="mr-3 h-4 w-4 text-slate-400 group-hover:text-violet-500" />
                                    Account Settings
                                </Link>
                            </MenuItem>
                        </div>
                        <div class="p-1 border-t border-slate-100">
                            <MenuItem v-slot="{ active }">
                                <Link :href="route('logout')" method="post" as="button" :class="[active ? 'bg-red-50 text-red-600' : 'text-slate-700', 'group flex w-full items-center rounded-lg px-3 py-2 text-sm font-medium']">
                                    <LogOut class="mr-3 h-4 w-4 text-slate-400 group-hover:text-red-500" />
                                    Sign Out
                                </Link>
                            </MenuItem>
                        </div>
                    </MenuItems>
                </transition>
            </Menu>
        </div>
    </header>
</template>