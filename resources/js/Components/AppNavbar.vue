<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    user: Object,
    routes: Object,
    currentRouteName: String,
    logoUrl: String,
});

const isMobileMenuOpen = ref(false);
const isScrolled = ref(false);

const handleScroll = () => { isScrolled.value = window.scrollY > 20; };
onMounted(() => { window.addEventListener('scroll', handleScroll); });
onUnmounted(() => { window.removeEventListener('scroll', handleScroll); });

// Helper active class sederhana
const isActive = (name) => {
    if (!props.currentRouteName) return false;
    return props.currentRouteName.includes(name);
};
</script>

<template>
    <header class="fixed top-0 left-0 w-full z-50 transition-all duration-300"
        :class="isScrolled ? 'bg-white/80 backdrop-blur-md shadow-sm border-b border-gray-100' : 'bg-transparent py-4'">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">

                <div class="flex-shrink-0 flex items-center cursor-pointer">
                    <Link :href="routes.home">
                        <img :src="logoUrl" class="h-10 w-auto" alt="KeyPerson Logo" />
                    </Link>
                </div>

                <div class="hidden lg:flex lg:items-center lg:gap-8">
                    <Link :href="routes.home" class="text-sm font-semibold transition-colors duration-200"
                        :class="isActive('home') ? 'text-violet-600' : 'text-slate-600 hover:text-violet-600'">
                        Home
                    </Link>
                    <Link :href="routes.about" class="text-sm font-semibold transition-colors duration-200"
                        :class="isActive('about') ? 'text-violet-600' : 'text-slate-600 hover:text-violet-600'">
                        About
                    </Link>
                    <Link :href="routes.support" class="text-sm font-semibold transition-colors duration-200"
                        :class="isActive('support') ? 'text-violet-600' : 'text-slate-600 hover:text-violet-600'">
                        Support
                    </Link>
                    <Link :href="routes.pricing" class="text-sm font-semibold transition-colors duration-200"
                        :class="isActive('pricing') ? 'text-violet-600' : 'text-slate-600 hover:text-violet-600'">
                        Pricing
                    </Link>
                </div>

                <div class="hidden lg:flex items-center gap-4">
                    <template v-if="!user">
                        <Link :href="routes.login"
                            class="px-5 py-2.5 text-sm font-bold text-white bg-slate-900 rounded-full hover:bg-slate-800 transition-all shadow-lg shadow-slate-300/50">
                            Sign In
                        </Link>
                    </template>
                    <template v-else>
                        <a :href="routes.profile"
                            class="flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-violet-700 bg-violet-50 rounded-full hover:bg-violet-100 transition-all border border-violet-100">
                            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                            Dashboard
                        </a>
                    </template>
                </div>

                <div class="flex items-center lg:hidden">
                    <button @click="isMobileMenuOpen = !isMobileMenuOpen"
                        class="text-slate-600 hover:text-violet-600 focus:outline-none">
                        <svg v-if="!isMobileMenuOpen" xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </nav>

        <transition enter-active-class="transition duration-200 ease-out"
            enter-from-class="transform -translate-y-4 opacity-0" enter-to-class="transform translate-y-0 opacity-100"
            leave-active-class="transition duration-150 ease-in" leave-from-class="transform translate-y-0 opacity-100"
            leave-to-class="transform -translate-y-4 opacity-0">
            <div v-if="isMobileMenuOpen"
                class="lg:hidden absolute top-full left-0 w-full bg-white shadow-xl border-t border-gray-100 px-4 py-6 flex flex-col gap-4">
                <Link :href="routes.home"
                    class="text-base font-medium text-slate-700 hover:text-violet-600 py-2 border-b border-gray-50">Home
                </Link>
                <Link :href="routes.about"
                    class="text-base font-medium text-slate-700 hover:text-violet-600 py-2 border-b border-gray-50">
                    About Us
                </Link>
                <Link :href="routes.support"
                    class="text-base font-medium text-slate-700 hover:text-violet-600 py-2 border-b border-gray-50">
                    Support
                </Link>
                <Link :href="routes.pricing"
                    class="text-base font-medium text-slate-700 hover:text-violet-600 py-2 border-b border-gray-50">
                    Pricing
                </Link>

                <div class="mt-4">
                    <template v-if="!user">
                        <Link :href="routes.login"
                            class="block w-full text-center px-6 py-3 text-base font-bold text-white bg-violet-600 rounded-xl hover:bg-violet-700">
                            Sign In
                        </Link>
                    </template>
                    <template v-else>
                        <a :href="routes.profile"
                            class="block w-full text-center px-6 py-3 text-base font-bold text-violet-700 bg-violet-50 rounded-xl hover:bg-violet-100 border border-violet-200">
                            Go to Profile
                        </a>
                    </template>
                </div>
            </div>
        </transition>
    </header>
</template>