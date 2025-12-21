<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { Menu, MenuButton, MenuItems, MenuItem, Dialog, DialogPanel } from '@headlessui/vue';
import {
    ChevronDownIcon,
    UserGroupIcon,
    BriefcaseIcon,
    UserIcon,
    ArrowRightOnRectangleIcon,
    Bars3Icon,
    XMarkIcon
} from '@heroicons/vue/20/solid';

// --- 1. SETUP DATA ---
const page = usePage();
const user = computed(() => page.props.auth.user);
const assets = computed(() => page.props.assets);

// --- 2. LOGIC DETEKSI HALAMAN GELAP (DARK HERO) ---
// Daftar route yang memiliki background banner gelap di bagian atas
const darkHeroRoutes = ['home_client', 'list_conselor'];

const isDarkPage = computed(() => {
    return darkHeroRoutes.includes(route().current());
});

// --- 3. SCROLL LOGIC ---
const isScrolled = ref(false);
const isMobileMenuOpen = ref(false);

const handleScroll = () => {
    isScrolled.value = window.scrollY > 20;
};

onMounted(() => { window.addEventListener('scroll', handleScroll); });
onUnmounted(() => { window.removeEventListener('scroll', handleScroll); });

// --- 4. DYNAMIC STYLING COMPUTED ---
// Menentukan apakah navbar sedang dalam mode "Transparan di atas Background Gelap"
const isTransparentOnDark = computed(() => !isScrolled.value && isDarkPage.value);

// Warna Teks Menu
const navTextColor = computed(() => {
    if (isTransparentOnDark.value) return 'text-white/90 hover:text-white';
    return 'text-slate-600 hover:text-violet-600';
});

// Warna Teks Logo
const logoTextColor = computed(() => {
    if (isTransparentOnDark.value) return 'text-white';
    return 'text-slate-900';
});

// Logo Image Source
const logoSrc = computed(() => {
    if (isTransparentOnDark.value) return assets.value.logoWhiteUrl || assets.value.logoUrl;
    return assets.value.logoSmallUrl;
});

// Helper Active Link
const isActive = (name) => route().current(name);
</script>

<template>
    <header class="fixed top-0 left-0 w-full z-50 transition-all duration-500 ease-in-out" :class="[
        isScrolled
            ? 'bg-white/90 backdrop-blur-md shadow-sm border-b border-gray-100 py-3'
            : 'bg-transparent py-6'
    ]">

        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-12">

                <div class="shrink-0 flex items-center cursor-pointer">
                    <Link :href="route('home')" class="flex items-center gap-2 group">
                        <img :src="assets.logoSmallUrl" class="h-9 w-auto transition-transform group-hover:scale-105" alt="Logo" />
                        <span class="font-display font-bold text-xl tracking-tight transition-colors"
                            :class="logoTextColor">
                            Key<span :class="isTransparentOnDark ? 'text-white' : 'text-violet-600'">Person</span>
                        </span>
                    </Link>
                </div>

                <div class="hidden lg:flex lg:items-center lg:gap-8">
                    <Link v-for="item in ['Home', 'About', 'Support', 'Pricing']" :key="item"
                        :href="route(item.toLowerCase())" class="text-sm font-medium transition-colors duration-200"
                        :class="[
                            navTextColor,
                            isActive(item.toLowerCase()) ? 'font-bold' : ''
                        ]">
                        {{ item }}
                    </Link>
                </div>

                <div class="hidden lg:flex items-center gap-4">

                    <template v-if="!user">
                        <Link :href="route('login')" class="text-sm font-bold transition-colors" :class="navTextColor">
                            Sign In
                        </Link>

                        <Menu as="div" class="relative inline-block text-left">
                            <div>
                                <MenuButton
                                    class="group inline-flex items-center justify-center gap-x-1.5 rounded-full px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-violet-500/30 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-violet-500/50 focus:outline-none bg-gradient-to-r from-violet-600 to-fuchsia-600 hover:from-violet-500 hover:to-fuchsia-500">
                                    Get Started
                                    <ChevronDownIcon
                                        class="-mr-1 h-5 w-5 text-violet-200 group-hover:text-white transition-colors"
                                        aria-hidden="true" />
                                </MenuButton>
                            </div>

                            <transition enter-active-class="transition duration-200 ease-out"
                                enter-from-class="translate-y-2 opacity-0 scale-95"
                                enter-to-class="translate-y-0 opacity-100 scale-100"
                                leave-active-class="transition duration-150 ease-in"
                                leave-from-class="translate-y-0 opacity-100 scale-100"
                                leave-to-class="translate-y-2 opacity-0 scale-95">
                                <MenuItems
                                    class="absolute right-0 mt-3 w-60 origin-top-right divide-y divide-gray-100 rounded-2xl bg-white shadow-xl ring-1 ring-black/5 focus:outline-none overflow-hidden z-50">
                                    <div class="px-1 py-1">
                                        <MenuItem v-slot="{ active }">
                                        <Link :href="route('register_client')" :class="[
                                            active ? 'bg-violet-50' : '',
                                            'group flex w-full items-center px-4 py-3 text-sm text-gray-900 transition-colors'
                                        ]">
                                            <div
                                                class="mr-3 flex h-9 w-9 items-center justify-center rounded-full bg-violet-100 text-violet-600 group-hover:bg-violet-600 group-hover:text-white transition-colors">
                                                <UserGroupIcon class="h-5 w-5" />
                                            </div>
                                            <div class="flex flex-col text-left">
                                                <span class="font-bold text-violet-900">Sebagai Client</span>
                                                <span class="text-xs text-slate-500">Cari mentor & booking</span>
                                            </div>
                                        </Link>
                                        </MenuItem>
                                        <MenuItem v-slot="{ active }">
                                        <Link :href="route('register_expert')" :class="[
                                            active ? 'bg-fuchsia-50' : '',
                                            'group flex w-full items-center px-4 py-3 text-sm text-gray-900 transition-colors'
                                        ]">
                                            <div
                                                class="mr-3 flex h-9 w-9 items-center justify-center rounded-full bg-fuchsia-100 text-fuchsia-600 group-hover:bg-fuchsia-600 group-hover:text-white transition-colors">
                                                <BriefcaseIcon class="h-5 w-5" />
                                            </div>
                                            <div class="flex flex-col text-left">
                                                <span class="font-bold text-fuchsia-900">Sebagai Expert</span>
                                                <span class="text-xs text-slate-500">Tawarkan jasa anda</span>
                                            </div>
                                        </Link>
                                        </MenuItem>
                                    </div>
                                </MenuItems>
                            </transition>
                        </Menu>
                    </template>

                    <template v-else>
                        <Menu as="div" class="relative ml-3">
                            <MenuButton
                                class="flex items-center gap-2 rounded-full pl-1 pr-3 py-1 transition-all border hover:shadow-md"
                                :class="isTransparentOnDark ? 'bg-white/10 hover:bg-white/20 border-white/20 text-white' : 'bg-white hover:bg-slate-50 border-slate-200 text-slate-700'">
                                <img class="h-8 w-8 rounded-full object-cover border"
                                    :class="isTransparentOnDark ? 'border-white/30' : 'border-slate-200'"
                                    :src="user.profile_photo_url || assets.userPlaceholderUrl" alt="" />
                                <span class="text-sm font-bold hidden sm:block">{{ user.name }}</span>
                                <ChevronDownIcon class="h-4 w-4 opacity-70" />
                            </MenuButton>

                            <transition enter-active-class="transition duration-200 ease-out"
                                enter-from-class="translate-y-2 opacity-0 scale-95"
                                enter-to-class="translate-y-0 opacity-100 scale-100"
                                leave-active-class="transition duration-150 ease-in"
                                leave-from-class="translate-y-0 opacity-100 scale-100"
                                leave-to-class="translate-y-2 opacity-0 scale-95">
                                <MenuItems
                                    class="absolute right-0 mt-2 w-48 origin-top-right divide-y divide-gray-100 rounded-xl bg-white shadow-lg ring-1 ring-black/5 focus:outline-none z-50">
                                    <div class="px-1 py-1">
                                        <MenuItem v-slot="{ active }">
                                        <Link :href="route('dashboard')"
                                            :class="[active ? 'bg-violet-50 text-violet-700' : 'text-slate-700', 'group flex w-full items-center rounded-md px-2 py-2 text-sm']">
                                            <UserIcon class="mr-2 h-5 w-5 text-violet-400" />
                                            Dashboard
                                        </Link>
                                        </MenuItem>
                                    </div>
                                    <div class="px-1 py-1">
                                        <MenuItem v-slot="{ active }">
                                        <Link :href="route('logout')" method="post" as="button"
                                            :class="[active ? 'bg-red-50 text-red-700' : 'text-slate-700', 'group flex w-full items-center rounded-md px-2 py-2 text-sm']">
                                            <ArrowRightOnRectangleIcon class="mr-2 h-5 w-5 text-red-400" />
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
                    <button @click="isMobileMenuOpen = true" class="p-2 rounded-md transition-colors"
                        :class="isTransparentOnDark ? 'text-white hover:bg-white/10' : 'text-slate-600 hover:bg-slate-100'">
                        <Bars3Icon class="h-6 w-6" aria-hidden="true" />
                    </button>
                </div>
            </div>
        </nav>

        <Dialog as="div" class="lg:hidden" @close="isMobileMenuOpen = false" :open="isMobileMenuOpen">
            <div class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm" />
            <DialogPanel
                class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-white px-6 py-6 sm:max-w-sm shadow-2xl transition-all duration-300">
                <div class="flex items-center justify-between mb-8">
                    <Link :href="route('home')" class="-m-1.5 p-1.5 flex items-center gap-2">
                        <img class="h-8 w-auto" :src="assets.logoSmallUrl" alt="" />
                        <span class="font-display font-bold text-lg text-slate-900">Key<span
                                class="text-violet-600">Person</span></span>
                    </Link>
                    <button type="button" class="-m-2.5 rounded-md p-2.5 text-slate-700 hover:bg-slate-100"
                        @click="isMobileMenuOpen = false">
                        <span class="sr-only">Close menu</span>
                        <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                    </button>
                </div>

                <div class="flow-root">
                    <div class="-my-6 divide-y divide-slate-100">
                        <div class="space-y-2 py-6">
                            <Link v-for="item in ['Home', 'About', 'Support', 'Pricing']" :key="item"
                                :href="route(item.toLowerCase())"
                                class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-slate-900 hover:bg-slate-50">
                                {{ item }}
                            </Link>
                        </div>

                        <div class="py-6 space-y-4">
                            <template v-if="user">
                                <div class="flex items-center gap-3 px-3 mb-2">
                                    <img class="h-10 w-10 rounded-full object-cover"
                                        :src="user.profile_photo_url || assets.userPlaceholderUrl" />
                                    <div>
                                        <div class="font-bold text-slate-900">{{ user.name }}</div>
                                        <div class="text-xs text-slate-500">{{ user.email }}</div>
                                    </div>
                                </div>
                                <Link :href="route('profile')"
                                    class="block w-full text-center rounded-xl bg-violet-600 px-3 py-2.5 text-base font-semibold text-white shadow-md">
                                    Go to Dashboard
                                </Link>
                                <Link :href="route('logout')" method="post" as="button"
                                    class="block w-full text-center rounded-xl border border-slate-200 px-3 py-2.5 text-base font-semibold text-slate-700 hover:bg-slate-50">
                                    Sign out
                                </Link>
                            </template>

                            <template v-else>
                                <Link :href="route('login')"
                                    class="-mx-3 block rounded-lg px-3 py-2.5 text-base font-semibold leading-7 text-slate-900 hover:bg-slate-50">
                                    Log in
                                </Link>
                                <div class="grid grid-cols-2 gap-3 mt-4">
                                    <Link :href="route('register_client')"
                                        class="flex flex-col items-center justify-center p-3 rounded-xl bg-violet-50 border border-violet-100 text-violet-700 active:scale-95 transition-transform">
                                        <UserGroupIcon class="h-6 w-6 mb-1" />
                                        <span class="text-xs font-bold">Client</span>
                                    </Link>
                                    <Link :href="route('register_expert')"
                                        class="flex flex-col items-center justify-center p-3 rounded-xl bg-fuchsia-50 border border-fuchsia-100 text-fuchsia-700 active:scale-95 transition-transform">
                                        <BriefcaseIcon class="h-6 w-6 mb-1" />
                                        <span class="text-xs font-bold">Expert</span>
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