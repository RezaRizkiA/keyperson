<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import {
    LayoutDashboard,
    Users,
    Building2,
    UserCircle,
    Briefcase,
    Calendar,
    CreditCard,
    Layers,
    Settings,
    LogOut,
    X,
    ChevronLeft,
    ChevronRight,
    Wallet,
    UserPlus,
} from "lucide-vue-next";

const props = defineProps({
    isSidebarExpanded: Boolean,
    isMobileMenuOpen: Boolean,
});

const emit = defineEmits(["toggleSidebar", "closeMobileMenu"]);
const page = usePage();
const assets = computed(() => page.props.assets || {});

// Cek Role User untuk filter menu
const userRoles = computed(() => page.props.auth?.user?.roles || []);
const isAdmin = computed(() => userRoles.value.includes("administrator"));
const isClient = computed(() => userRoles.value.includes("client"));

// Daftar Menu
const menuSections = computed(() => [
    {
        title: "MAIN",
        items: [
            {
                label: "Dashboard",
                route: "dashboard.index",
                icon: LayoutDashboard,
                show: true,
            },
        ],
    },
    {
        title: "MANAGEMENT",
        items: [
            {
                label: "Experts",
                route: "dashboard.experts.index",
                icon: Users,
                show: isAdmin.value,
            },
            {
                label: "Institutions",
                route: "dashboard.clients.index",
                icon: Building2,
                show: isAdmin.value,
            },
            {
                label: "Users",
                route: "dashboard.users.index",
                icon: UserCircle,
                show: isAdmin.value,
            },
        ],
    },
    {
        title: "SCHEDULING",
        items: [
            {
                label: "Appointments",
                route: "dashboard.appointments.index",
                icon: Briefcase,
                show: true,
            },
            {
                label: "Skills & Expertise",
                route: "dashboard.expertises.index",
                icon: Layers,
                show: isAdmin.value,
            },
        ],
    },
    {
        title: "FINANCIALS",
        items: [
            {
                label: "Transactions",
                route: "dashboard.transactions.index",
                icon: CreditCard,
                show: true,
            },
            {
                label: "Top Up Quota",
                route: "topup.create",
                icon: Wallet,
                show: isClient.value,
            },
        ],
    },
    {
        title: "COMPANY",
        items: [
            {
                label: "Employees",
                route: "employees.index",
                icon: UserPlus,
                show: isClient.value,
            },
        ],
    },
]);

const isActive = (routeName) => {
    return page.url && route().current(routeName);
};
</script>

<template>
    <!-- Mobile Overlay -->
    <div
        v-if="isMobileMenuOpen"
        @click="$emit('closeMobileMenu')"
        class="fixed inset-0 bg-slate-900/50 dark:bg-slate-900/70 z-40 lg:hidden backdrop-blur-sm transition-opacity"
    ></div>

    <aside
        class="fixed top-0 left-0 z-50 h-screen transition-all duration-300 ease-in-out flex flex-col bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800"
        :class="[
            isSidebarExpanded ? 'w-64' : 'w-20',
            isMobileMenuOpen
                ? 'translate-x-0'
                : '-translate-x-full lg:translate-x-0',
        ]"
    >
        <!-- Header with Logo -->
        <div
            class="h-20 flex items-center border-b border-slate-200 dark:border-slate-800 transition-all duration-300 relative"
            :class="
                isSidebarExpanded
                    ? 'px-6 justify-between'
                    : 'justify-center px-0'
            "
        >
            <Link
                :href="route('home')"
                class="flex items-center gap-2 overflow-hidden"
            >
                <div
                    class="w-9 h-9 rounded-lg bg-blue-500 flex items-center justify-center shrink-0"
                >
                    <span class="text-white font-bold text-lg">K</span>
                </div>

                <span
                    v-if="isSidebarExpanded"
                    class="font-display font-bold text-lg text-slate-900 dark:text-slate-100 whitespace-nowrap transition-opacity duration-300"
                >
                    Admin Portal
                </span>
            </Link>

            <button
                @click="emit('closeMobileMenu')"
                class="lg:hidden p-1 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 transition-colors"
            >
                <X class="w-5 h-5" />
            </button>

            <!-- Toggle Button for Desktop -->
            <button
                v-if="isSidebarExpanded"
                @click="emit('toggleSidebar')"
                class="hidden lg:flex absolute -right-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-full items-center justify-center text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors"
            >
                <ChevronLeft class="w-4 h-4" />
            </button>
            <button
                v-else
                @click="emit('toggleSidebar')"
                class="hidden lg:flex absolute -right-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-full items-center justify-center text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors"
            >
                <ChevronRight class="w-4 h-4" />
            </button>
        </div>

        <!-- Navigation Menu -->
        <div
            class="flex-1 overflow-y-auto py-6 px-3 space-y-6 custom-scrollbar"
        >
            <template v-for="section in menuSections" :key="section.title">
                <div v-if="section.items.some((item) => item.show)">
                    <p
                        v-if="isSidebarExpanded"
                        class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider px-3 mb-2"
                    >
                        {{ section.title }}
                    </p>
                    <div class="space-y-1">
                        <template
                            v-for="item in section.items"
                            :key="item.route"
                        >
                            <Link
                                v-if="item.show"
                                :href="route(item.route)"
                                class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden"
                                :class="
                                    isActive(item.route)
                                        ? 'bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 font-semibold'
                                        : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-200'
                                "
                            >
                                <component
                                    :is="item.icon"
                                    class="w-5 h-5 shrink-0 transition-colors"
                                    :class="
                                        isActive(item.route)
                                            ? 'text-blue-600 dark:text-blue-400'
                                            : 'text-slate-400 dark:text-slate-500 group-hover:text-slate-600 dark:group-hover:text-slate-300'
                                    "
                                />

                                <span v-if="isSidebarExpanded" class="truncate">
                                    {{ item.label }}
                                </span>

                                <div
                                    v-if="isActive(item.route)"
                                    class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-blue-500 rounded-r-full"
                                ></div>
                            </Link>
                        </template>
                    </div>
                </div>
            </template>
        </div>

        <!-- Settings and Logout -->
        <div
            class="p-4 border-t border-slate-200 dark:border-slate-800 space-y-2"
        >
            <Link
                :href="route('profile.edit')"
                class="w-full flex items-center gap-3 px-3 py-3 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-200 transition-all duration-200 group"
            >
                <Settings
                    class="w-5 h-5 shrink-0 group-hover:text-slate-600 dark:group-hover:text-slate-300"
                />
                <span v-if="isSidebarExpanded" class="font-medium"
                    >Settings</span
                >
            </Link>

            <Link
                :href="route('logout')"
                method="post"
                as="button"
                class="w-full flex items-center gap-3 px-3 py-3 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-red-50 dark:hover:bg-red-500/10 hover:text-red-600 dark:hover:text-red-400 transition-all duration-200 group"
            >
                <LogOut
                    class="w-5 h-5 shrink-0 group-hover:text-red-500 dark:group-hover:text-red-400"
                />
                <span v-if="isSidebarExpanded" class="font-medium">Logout</span>
            </Link>
        </div>
    </aside>
</template>
