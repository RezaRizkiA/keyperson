<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { 
    Home, 
    Briefcase, 
    Calendar, 
    CreditCard, 
    Layers, // Icon untuk Expertise
    Settings,
    LogOut,
    X
} from 'lucide-vue-next';

const props = defineProps({
    isSidebarExpanded: Boolean,
    isMobileMenuOpen: Boolean,
});

const emit = defineEmits(['toggleSidebar', 'closeMobileMenu']);
const page = usePage();
const assets = computed(() => page.props.assets || {});

// Cek Role User untuk filter menu (Optional jika ingin sidebar dinamis per role)
const userRoles = computed(() => page.props.auth?.user?.roles || []);
const isAdmin = computed(() => userRoles.value.includes('administrator'));

// Daftar Menu
const menuItems = [
    { 
        label: 'Overview', 
        route: 'dashboard', 
        icon: Home,
        show: true 
    },
    { 
        label: 'Appointments', 
        route: 'dashboard.appointments', 
        icon: Briefcase,
        show: true 
    },
    { 
        label: 'Calendar', 
        route: 'dashboard.calendar', 
        icon: Calendar,
        show: true 
    },
    { 
        label: 'Billing', 
        route: 'dashboard.billing', 
        icon: CreditCard,
        show: true 
    },
    // Menu Khusus Admin
    { 
        label: 'Expertises', 
        route: 'dashboard.expertises', 
        icon: Layers, 
        show: isAdmin.value 
    },
];

const isActive = (routeName) => {
    return page.url && route().current(routeName);
}
</script>

<template>
    <div v-if="isMobileMenuOpen" 
         @click="$emit('closeMobileMenu')" 
         class="fixed inset-0 bg-slate-900/50 z-40 lg:hidden backdrop-blur-sm transition-opacity">
    </div>

    <aside 
        class="fixed top-0 left-0 z-50 h-screen bg-white border-r border-slate-200 transition-all duration-300 ease-in-out flex flex-col"
        :class="[
            isSidebarExpanded ? 'w-64' : 'w-20',
            isMobileMenuOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
        ]"
    >
        
        <div
            class="h-20 flex items-center border-b border-slate-100 transition-all duration-300"
            :class="isSidebarExpanded ? 'px-6 justify-between' : 'justify-center px-0'"
        >
            <Link :href="route('home')" class="flex items-center gap-2 overflow-hidden">
                <img :src="assets.logoSmallUrl" class="h-9 w-auto shrink-0" alt="Logo" />
                
                <span v-if="isSidebarExpanded" class="font-display font-bold text-xl text-slate-900 whitespace-nowrap transition-opacity duration-300">
                    Key<span class="text-violet-600">Person</span>
                </span>
            </Link>

            <button @click="emit('closeMobileMenu')" class="lg:hidden p-1 text-slate-400 hover:text-slate-600 transition-colors">
                <X class="w-5 h-5" />
            </button>
        </div>
        <div class="flex-1 overflow-y-auto py-6 px-3 space-y-1 custom-scrollbar">
            <template v-for="item in menuItems" :key="item.route">
                <Link 
                    v-if="item.show"
                    :href="route(item.route)"
                    class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden"
                    :class="isActive(item.route) 
                        ? 'bg-violet-50 text-violet-700 font-semibold' 
                        : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <component :is="item.icon" 
                        class="w-5 h-5 shrink-0 transition-colors"
                        :class="isActive(item.route) ? 'text-violet-600' : 'text-slate-400 group-hover:text-slate-600'" 
                    />
                    
                    <span v-if="isSidebarExpanded" class="truncate">
                        {{ item.label }}
                    </span>

                    <div v-if="isActive(item.route)" class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-violet-600 rounded-r-full"></div>
                </Link>
            </template>
        </div>

        <div class="p-4 border-t border-slate-100">
            <Link 
                :href="route('logout')" 
                method="post" 
                as="button"
                class="w-full flex items-center gap-3 px-3 py-3 rounded-xl text-slate-500 hover:bg-red-50 hover:text-red-600 transition-all duration-200 group"
            >
                <LogOut class="w-5 h-5 shrink-0 group-hover:text-red-500" />
                <span v-if="isSidebarExpanded" class="font-medium">Sign Out</span>
            </Link>
        </div>
    </aside>
</template>