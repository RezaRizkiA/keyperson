<script setup>
import { computed } from "vue";
import { Menu, MenuButton, MenuItems, MenuItem } from "@headlessui/vue";
import {
    Menu as MenuIcon,
    Search,
    Bell,
    User,
    Settings,
    LogOut,
} from "lucide-vue-next";
import { Link, usePage } from "@inertiajs/vue3";
import ThemeToggle from "@/Components/ThemeToggle.vue";

const emit = defineEmits(["openMobileMenu"]);

const props = defineProps({
    title: {
        type: String,
        default: "Dashboard",
    },
});

const page = usePage();
const user = computed(() => page.props.auth.user);

const userRoleLabel = computed(() => {
    if (!user.value) return "Guest";

    // Logika deteksi role
    const roles = user.value.roles || [];
    const roleNames = Array.isArray(roles)
        ? roles.map((r) => (typeof r === "string" ? r : r.name))
        : [];

    if (roleNames.includes("administrator")) return "Admin";
    if (roleNames.includes("expert")) return "Expert";
    if (roleNames.includes("client")) return "HRD";

    return "Client";
});

// Helper untuk Judul Halaman berdasarkan Route
const pageTitle = computed(() => {
    const routeName = route().current();
    if (routeName === "dashboard.index") return "Dashboard Overview";
    if (routeName === "dashboard.appointments.index") return "Appointments";

    if (routeName === "dashboard.transactions.index") return "Transactions";
    if (routeName === "dashboard.expertises.index")
        return "Expertise Management";
    if (routeName === "dashboard.clients.index") return "Institutions";
    if (routeName === "dashboard.users.index") return "Users";
    if (routeName === "profile.edit") return "Settings";
    return "Dashboard";
});

const pageSubtitle = computed(() => {
    const routeName = route().current();
    if (routeName === "dashboard.index")
        return "Welcome back, Administrator. Here's what's happening today.";
    return "";
});
</script>

<template>
    <header
        class="h-20 sticky top-0 z-30 px-4 sm:px-8 flex items-center justify-between transition-colors duration-300 bg-white/80 dark:bg-slate-800/50 backdrop-blur-md border-b border-slate-200/80 dark:border-slate-700/50"
    >
        <div class="flex items-center gap-4">
            <button
                @click="$emit('openMobileMenu')"
                class="lg:hidden p-2 text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors"
            >
                <MenuIcon class="w-6 h-6" />
            </button>

            <!-- Search Bar -->
            <div class="relative hidden md:block">
                <Search
                    class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"
                />
                <input
                    type="text"
                    placeholder="Search experts, clients..."
                    class="w-80 pl-10 pr-4 py-2 bg-slate-100 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-700 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-colors"
                />
            </div>
        </div>

        <div class="flex items-center gap-3">
            <!-- Theme Toggle -->
            <ThemeToggle />

            <!-- Notification Bell -->
            <button
                class="relative p-2 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors cursor-pointer"
            >
                <Bell class="w-5 h-5" />
                <!-- Notification Badge -->
                <span
                    class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"
                ></span>
            </button>

            <!-- User Menu -->
            <Menu as="div" class="relative">
                <MenuButton
                    class="flex items-center gap-3 pl-2 pr-3 py-1.5 rounded-full bg-slate-100 dark:bg-slate-700 transition-all border border-slate-200 dark:border-slate-600 cursor-pointer"
                >
                    <img
                        :src="
                            user.picture_url ||
                            'https://ui-avatars.com/api/?name=' +
                                user.name +
                                '&background=3b82f6&color=fff'
                        "
                        class="w-9 h-9 rounded-full object-cover border-2 border-slate-200 dark:border-slate-600 shadow-sm"
                        alt="User"
                    />
                    <div class="text-right hidden md:block">
                        <p
                            class="text-sm font-bold text-slate-900 dark:text-slate-100 leading-none"
                        >
                            {{ user?.name }}
                        </p>
                        <p
                            class="text-[10px] text-slate-500 dark:text-slate-400 uppercase font-bold mt-0.5 tracking-wider"
                        >
                            {{ userRoleLabel }}
                        </p>
                    </div>
                </MenuButton>

                <transition
                    enter-active-class="transition duration-100 ease-out"
                    enter-from-class="transform scale-95 opacity-0"
                    enter-to-class="transform scale-100 opacity-100"
                    leave-active-class="transition duration-75 ease-in"
                    leave-from-class="transform scale-100 opacity-100"
                    leave-to-class="transform scale-95 opacity-0"
                >
                    <MenuItems
                        class="absolute right-0 mt-2 w-56 origin-top-right divide-y divide-slate-100 dark:divide-slate-700 rounded-xl bg-white dark:bg-slate-800 shadow-lg ring-1 ring-black/5 focus:outline-none overflow-hidden border border-slate-200 dark:border-slate-700"
                    >
                        <div class="px-4 py-3 bg-slate-50 dark:bg-slate-900/50">
                            <p
                                class="text-sm font-medium text-slate-700 dark:text-slate-200 truncate"
                            >
                                Signed in as
                            </p>
                            <p
                                class="text-xs text-slate-500 dark:text-slate-400 truncate mt-0.5"
                            >
                                {{ user.email }}
                            </p>
                        </div>
                        <div class="p-1">
                            <MenuItem v-slot="{ active }">
                                <Link
                                    :href="route('profile.edit')"
                                    :class="[
                                        active
                                            ? 'bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400'
                                            : 'text-slate-700 dark:text-slate-300',
                                        'group flex w-full items-center rounded-lg px-3 py-2 text-sm font-medium',
                                    ]"
                                >
                                    <Settings
                                        class="mr-3 h-4 w-4 text-slate-400 group-hover:text-blue-500 dark:group-hover:text-blue-400"
                                    />
                                    Settings
                                </Link>
                            </MenuItem>
                        </div>
                        <div
                            class="p-1 border-t border-slate-100 dark:border-slate-700"
                        >
                            <MenuItem v-slot="{ active }">
                                <Link
                                    :href="route('logout')"
                                    method="post"
                                    as="button"
                                    :class="[
                                        active
                                            ? 'bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400'
                                            : 'text-slate-700 dark:text-slate-300',
                                        'group flex w-full items-center rounded-lg px-3 py-2 text-sm font-medium',
                                    ]"
                                >
                                    <LogOut
                                        class="mr-3 h-4 w-4 text-slate-400 group-hover:text-red-500 dark:group-hover:text-red-400"
                                    />
                                    Logout
                                </Link>
                            </MenuItem>
                        </div>
                    </MenuItems>
                </transition>
            </Menu>
        </div>
    </header>
</template>
