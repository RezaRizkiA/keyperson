<script setup>
import { ref, onMounted, onUnmounted, computed } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import {
    Menu,
    MenuButton,
    MenuItems,
    MenuItem,
    Dialog,
    DialogPanel,
} from "@headlessui/vue";

import {
    ChevronDownIcon,
    UserGroupIcon,
    BriefcaseIcon,
    UserIcon,
    ArrowRightOnRectangleIcon,
    Bars3Icon,
    XMarkIcon,
} from "@heroicons/vue/20/solid";

import ThemeToggle from "@/Components/ThemeToggle.vue";
import { useDashboardRoute } from "@/composables/useDashboardRoute";

// --- 1. SETUP DATA ---
const page = usePage();
const user = computed(() => page.props.auth.user);
const assets = computed(() => page.props.assets);

// Menggunakan composable untuk role dan dashboard route
const { dashboardRoute } = useDashboardRoute();

// --- 2. LOGIC DETEKSI HALAMAN GELAP (DARK HERO) ---
// Daftar route yang memiliki background banner gelap di bagian atas
const darkHeroRoutes = ["home_client", "list_conselor"];

const isDarkPage = computed(() => {
    return darkHeroRoutes.includes(route().current());
});

// --- 3. SCROLL LOGIC ---
const isScrolled = ref(false);
const isMobileMenuOpen = ref(false);

const handleScroll = () => {
    isScrolled.value = window.scrollY > 20;
};

onMounted(() => {
    window.addEventListener("scroll", handleScroll);
});
onUnmounted(() => {
    window.removeEventListener("scroll", handleScroll);
});

// --- 4. DYNAMIC STYLING COMPUTED ---
// Menentukan apakah navbar sedang dalam mode "Transparan di atas Background Gelap"
const isTransparentOnDark = computed(
    () => !isScrolled.value && isDarkPage.value
);

// Warna Teks Menu - menggunakan semantic tokens
const navTextColor = computed(() => {
    return "text-navbar-text hover:text-navbar-text-hover";
});

// Warna Teks Logo - menggunakan semantic tokens
const logoTextColor = computed(() => {
    return "text-foreground";
});

// Logo Image Source
const logoSrc = computed(() => {
    if (isTransparentOnDark.value)
        return assets.value.logoWhiteUrl || assets.value.logoUrl;
    return assets.value.logoSmallUrl;
});

// Helper Active Link
const isActive = (name) => route().current(name);
</script>

<template>
    <header
        class="fixed top-0 left-0 w-full z-50 transition-all duration-500 ease-in-out"
        :class="[
            isScrolled
                ? 'bg-navbar-bg backdrop-blur-md shadow-lg border-b border-border py-3'
                : 'bg-navbar-bg backdrop-blur-sm py-6 border-b border-border/30',
        ]"
    >
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-12">
                <div class="shrink-0 flex items-center cursor-pointer">
                    <Link
                        :href="route('home')"
                        class="flex items-center gap-2 group"
                    >
                        <img
                            :src="assets.logoSmallUrl"
                            class="h-9 w-auto transition-transform group-hover:scale-105"
                            alt="Logo"
                        />
                        <span
                            class="font-display font-bold text-xl tracking-tight transition-colors"
                            :class="logoTextColor"
                        >
                            Key<span class="text-blue-400">Person</span>
                        </span>
                    </Link>
                </div>

                <div class="hidden lg:flex lg:items-center lg:gap-1">
                    <Link
                        :href="route('home')"
                        class="px-4 py-2 text-sm font-medium transition-all duration-200 rounded-lg relative"
                        :class="[
                            navTextColor,
                            isActive('home') ? 'text-blue-400 font-bold' : '',
                        ]"
                    >
                        Home
                        <span
                            v-if="isActive('home')"
                            class="absolute bottom-0 left-1/2 -translate-x-1/2 w-12 h-0.5 bg-blue-400 rounded-full"
                        ></span>
                    </Link>

                    <Link
                        :href="route('about')"
                        class="px-4 py-2 text-sm font-medium transition-all duration-200 rounded-lg relative"
                        :class="[
                            navTextColor,
                            isActive('about') ? 'text-blue-400 font-bold' : '',
                        ]"
                    >
                        About
                        <span
                            v-if="isActive('about')"
                            class="absolute bottom-0 left-1/2 -translate-x-1/2 w-12 h-0.5 bg-blue-400 rounded-full"
                        ></span>
                    </Link>

                    <Link
                        :href="route('services')"
                        class="px-4 py-2 text-sm font-medium transition-all duration-200 rounded-lg relative"
                        :class="[
                            navTextColor,
                            isActive('services')
                                ? 'text-blue-400 font-bold'
                                : '',
                        ]"
                    >
                        Services
                        <span
                            v-if="isActive('services')"
                            class="absolute bottom-0 left-1/2 -translate-x-1/2 w-12 h-0.5 bg-blue-400 rounded-full"
                        ></span>
                    </Link>

                    <Link
                        :href="route('pricing')"
                        class="px-4 py-2 text-sm font-medium transition-all duration-200 rounded-lg relative"
                        :class="[
                            navTextColor,
                            isActive('pricing')
                                ? 'text-blue-400 font-bold'
                                : '',
                        ]"
                    >
                        Pricing
                        <span
                            v-if="isActive('pricing')"
                            class="absolute bottom-0 left-1/2 -translate-x-1/2 w-12 h-0.5 bg-blue-400 rounded-full"
                        ></span>
                    </Link>

                    <Link
                        :href="route('support')"
                        class="px-4 py-2 text-sm font-medium transition-all duration-200 rounded-lg relative"
                        :class="[
                            navTextColor,
                            isActive('support')
                                ? 'text-blue-400 font-bold'
                                : '',
                        ]"
                    >
                        Support
                        <span
                            v-if="isActive('support')"
                            class="absolute bottom-0 left-1/2 -translate-x-1/2 w-12 h-0.5 bg-blue-400 rounded-full"
                        ></span>
                    </Link>

                    <Link
                        :href="route('contact')"
                        class="px-4 py-2 text-sm font-medium transition-all duration-200 rounded-lg relative"
                        :class="[
                            navTextColor,
                            isActive('contact')
                                ? 'text-blue-400 font-bold'
                                : '',
                        ]"
                    >
                        Contact
                        <span
                            v-if="isActive('contact')"
                            class="absolute bottom-0 left-1/2 -translate-x-1/2 w-12 h-0.5 bg-blue-400 rounded-full"
                        ></span>
                    </Link>
                </div>

                <div class="hidden lg:flex items-center gap-4">
                    <!-- Theme Toggle -->
                    <ThemeToggle />

                    <template v-if="!user">
                        <Link
                            :href="route('login')"
                            class="text-sm font-bold transition-colors"
                            :class="navTextColor"
                        >
                            Sign In
                        </Link>

                        <Link
                            :href="route('choose_path')"
                            class="inline-flex items-center justify-center gap-2 rounded-full px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-blue-500/30 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_0_30px_rgba(59,130,246,0.5)] focus:outline-none bg-blue-600 hover:bg-blue-700"
                        >
                            Get Started
                            <!-- <ArrowRightOnRectangleIcon class="h-5 w-5" /> -->
                        </Link>
                    </template>

                    <template v-else>
                        <Menu as="div" class="relative ml-3">
                            <MenuButton
                                class="flex items-center gap-2 rounded-full pl-1 pr-3 py-1 transition-all border hover:shadow-md bg-slate-800/50 hover:bg-slate-700/50 border-blue-500/30 text-white hover:border-blue-500/50"
                            >
                                <img
                                    class="h-8 w-8 rounded-full object-cover border border-blue-500/30"
                                    :src="
                                        user.picture_url ||
                                        assets.userPlaceholderUrl
                                    "
                                    alt=""
                                />
                                <span
                                    class="text-sm font-bold hidden sm:block"
                                    >{{ user.name }}</span
                                >
                                <ChevronDownIcon class="h-4 w-4 opacity-70" />
                            </MenuButton>

                            <transition
                                enter-active-class="transition duration-200 ease-out"
                                enter-from-class="translate-y-2 opacity-0 scale-95"
                                enter-to-class="translate-y-0 opacity-100 scale-100"
                                leave-active-class="transition duration-150 ease-in"
                                leave-from-class="translate-y-0 opacity-100 scale-100"
                                leave-to-class="translate-y-2 opacity-0 scale-95"
                            >
                                <MenuItems
                                    class="absolute right-0 mt-2 w-48 origin-top-right divide-y divide-slate-700 rounded-xl bg-slate-800 shadow-lg ring-1 ring-blue-500/20 focus:outline-none z-50 border border-slate-700"
                                >
                                    <div class="px-1 py-1">
                                        <MenuItem v-slot="{ active }">
                                            <Link
                                                :href="route(dashboardRoute)"
                                                :class="[
                                                    active
                                                        ? 'bg-blue-500/10 text-blue-400'
                                                        : 'text-slate-300',
                                                    'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                                ]"
                                            >
                                                <UserIcon
                                                    class="mr-2 h-5 w-5 text-blue-400"
                                                />
                                                Dashboard
                                            </Link>
                                        </MenuItem>
                                    </div>
                                    <div class="px-1 py-1">
                                        <MenuItem v-slot="{ active }">
                                            <Link
                                                :href="route('logout')"
                                                method="post"
                                                as="button"
                                                :class="[
                                                    active
                                                        ? 'bg-red-500/10 text-red-400'
                                                        : 'text-slate-300',
                                                    'group flex w-full items-center rounded-md px-2 py-2 text-sm cursor-pointer',
                                                ]"
                                            >
                                                <ArrowRightOnRectangleIcon
                                                    class="mr-2 h-5 w-5 text-red-400"
                                                />
                                                Log Out
                                            </Link>
                                        </MenuItem>
                                    </div>
                                </MenuItems>
                            </transition>
                        </Menu>
                    </template>
                </div>

                <div class="flex items-center lg:hidden">
                    <button
                        @click="isMobileMenuOpen = true"
                        class="p-2 rounded-md transition-colors text-white hover:bg-slate-800/50"
                    >
                        <Bars3Icon class="h-6 w-6" aria-hidden="true" />
                    </button>
                </div>
            </div>
        </nav>

        <Dialog
            as="div"
            class="lg:hidden"
            @close="isMobileMenuOpen = false"
            :open="isMobileMenuOpen"
        >
            <div class="fixed inset-0 z-50 bg-slate-900/80 backdrop-blur-sm" />
            <DialogPanel
                class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-slate-900 px-6 py-6 sm:max-w-sm shadow-2xl transition-all duration-300 border-l border-slate-800"
            >
                <div class="flex items-center justify-between mb-8">
                    <Link
                        :href="route('home')"
                        class="-m-1.5 p-1.5 flex items-center gap-2"
                    >
                        <img
                            class="h-8 w-auto"
                            :src="assets.logoSmallUrl"
                            alt=""
                        />
                        <span class="font-display font-bold text-lg text-white"
                            >Key<span class="text-blue-400">Person</span></span
                        >
                    </Link>
                    <button
                        type="button"
                        class="-m-2.5 rounded-md p-2.5 text-slate-300 hover:bg-slate-800"
                        @click="isMobileMenuOpen = false"
                    >
                        <span class="sr-only">Close menu</span>
                        <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                    </button>
                </div>

                <div class="flow-root">
                    <div class="-my-6 divide-y divide-slate-800">
                        <div class="space-y-2 py-6">
                            <Link
                                :href="route('home')"
                                class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-slate-300 hover:bg-slate-800 hover:text-blue-400"
                                :class="
                                    isActive('home')
                                        ? 'bg-slate-800 text-blue-400'
                                        : ''
                                "
                            >
                                Home
                            </Link>
                            <Link
                                :href="route('about')"
                                class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-slate-300 hover:bg-slate-800 hover:text-blue-400"
                                :class="
                                    isActive('about')
                                        ? 'bg-slate-800 text-blue-400'
                                        : ''
                                "
                            >
                                About
                            </Link>
                            <Link
                                :href="route('services')"
                                class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-slate-300 hover:bg-slate-800 hover:text-blue-400"
                                :class="
                                    isActive('services')
                                        ? 'bg-slate-800 text-blue-400'
                                        : ''
                                "
                            >
                                Services
                            </Link>
                            <Link
                                :href="route('pricing')"
                                class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-slate-300 hover:bg-slate-800 hover:text-blue-400"
                                :class="
                                    isActive('pricing')
                                        ? 'bg-slate-800 text-blue-400'
                                        : ''
                                "
                            >
                                Pricing
                            </Link>
                            <Link
                                :href="route('support')"
                                class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-slate-300 hover:bg-slate-800 hover:text-blue-400"
                                :class="
                                    isActive('support')
                                        ? 'bg-slate-800 text-blue-400'
                                        : ''
                                "
                            >
                                Support
                            </Link>
                            <Link
                                :href="route('contact')"
                                class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-slate-300 hover:bg-slate-800 hover:text-blue-400"
                                :class="
                                    isActive('contact')
                                        ? 'bg-slate-800 text-blue-400'
                                        : ''
                                "
                            >
                                Contact
                            </Link>
                        </div>

                        <div class="py-6 space-y-4">
                            <template v-if="user">
                                <div class="flex items-center gap-3 px-3 mb-2">
                                    <img
                                        class="h-10 w-10 rounded-full object-cover"
                                        :src="
                                            user.profile_photo_url ||
                                            assets.userPlaceholderUrl
                                        "
                                    />
                                    <div>
                                        <div class="font-bold text-white">
                                            {{ user.name }}
                                        </div>
                                        <div class="text-xs text-slate-400">
                                            {{ user.email }}
                                        </div>
                                    </div>
                                </div>
                                <Link
                                    :href="route('profile')"
                                    class="block w-full text-center rounded-xl bg-blue-600 px-3 py-2.5 text-base font-semibold text-white shadow-md hover:bg-blue-700"
                                >
                                    Go to Dashboard
                                </Link>
                                <Link
                                    :href="route('logout')"
                                    method="post"
                                    as="button"
                                    class="block w-full text-center rounded-xl border border-slate-700 px-3 py-2.5 text-base font-semibold text-slate-300 hover:bg-slate-800"
                                >
                                    Sign out
                                </Link>
                            </template>

                            <template v-else>
                                <Link
                                    :href="route('login')"
                                    class="-mx-3 block rounded-lg px-3 py-2.5 text-base font-semibold leading-7 text-slate-300 hover:bg-slate-800"
                                >
                                    Log in
                                </Link>
                                <div class="grid grid-cols-2 gap-3 mt-4">
                                    <Link
                                        :href="
                                            route('client_onboarding.create')
                                        "
                                        class="flex flex-col items-center justify-center p-3 rounded-xl bg-blue-500/10 border border-blue-500/30 text-blue-400 active:scale-95 transition-transform"
                                    >
                                        <UserGroupIcon class="h-6 w-6 mb-1" />
                                        <span class="text-xs font-bold"
                                            >Client</span
                                        >
                                    </Link>
                                    <Link
                                        :href="
                                            route('expert_onboarding.create')
                                        "
                                        class="flex flex-col items-center justify-center p-3 rounded-xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 active:scale-95 transition-transform"
                                    >
                                        <BriefcaseIcon class="h-6 w-6 mb-1" />
                                        <span class="text-xs font-bold"
                                            >Expert</span
                                        >
                                    </Link>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </DialogPanel>
        </Dialog>
    </header>
</template>
