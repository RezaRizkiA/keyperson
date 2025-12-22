<script setup>
import { computed } from "vue";
import { Link, usePage } from "@inertiajs/vue3";

// Mengambil assets dari Shared Props (Inertia Middleware)
const page = usePage();
const assets = computed(() => page.props.assets);
const routes = computed(() => page.props.routes);

const currentYear = new Date().getFullYear();

const scrollToTop = () => {
    window.scrollTo({ top: 0, behavior: "smooth" });
};

// Data Social Media (Agar template lebih bersih)
const socialLinks = [
    {
        name: "Facebook",
        url: "#",
        icon: "M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z",
    },
    {
        name: "Instagram",
        url: "#",
        icon: "M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z M17.5 6.5h.01 M16 2H8a4 4 0 0 0-4 4v8a4 4 0 0 0 4 4h8a4 4 0 0 0 4-4V6a4 4 0 0 0-4-4z",
    },
    {
        name: "LinkedIn",
        url: "#",
        icon: "M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z M2 9h4v12H2z M2 4a2 2 0 1 1 2 2 2 2 0 0 1-2-2z",
    },
    {
        name: "Twitter",
        url: "#",
        icon: "M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z",
    },
];

// Data Other Apps
const otherApps = [
    { name: "trainingstudio.id", url: "https://trainingstudio.id/" },
    { name: "kerjapintar.id", url: "https://kerjapintar.id/" },
    { name: "profiling.id", url: "https://profiling.id/" },
    { name: "shiokaya.com", url: "https://shiokaya.com/" },
];
</script>

<template>
    <footer class="bg-slate-900 text-slate-300 mt-auto relative font-sans">
        <div
            class="absolute top-0 left-0 w-full h-1 bg-linear-to-r from-blue-600 via-cyan-500 to-blue-400"
        ></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
            <div
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-12"
            >
                <div class="lg:col-span-1">
                    <Link :href="routes.home" class="inline-block mb-6">
                        <img
                            :src="assets.logoWhiteUrl || assets.logoUrl"
                            class="h-10 w-auto brightness-0 invert opacity-90 hover:opacity-100 transition-opacity"
                            alt="KeyPerson Logo"
                        />
                    </Link>
                    <p class="text-slate-400 text-sm leading-relaxed mb-6">
                        Professional platform connecting you with verified
                        experts for mentorship, coaching, and personal growth.
                    </p>

                    <div class="flex gap-4">
                        <a
                            v-for="social in socialLinks"
                            :key="social.name"
                            :href="social.url"
                            target="_blank"
                            class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:bg-blue-600 hover:text-white transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_0_20px_rgba(59,130,246,0.4)]"
                        >
                            <svg
                                class="w-5 h-5"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                viewBox="0 0 24 24"
                            >
                                <path :d="social.icon"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <div>
                    <h3
                        class="text-white font-bold text-lg mb-6 flex items-center gap-2"
                    >
                        <span class="w-8 h-0.5 bg-blue-500 rounded-full"></span>
                        Explore
                    </h3>
                    <ul class="space-y-3">
                        <li>
                            <Link
                                :href="routes.home"
                                class="text-slate-400 hover:text-blue-400 hover:pl-2 transition-all duration-300 inline-block"
                            >
                                Home</Link
                            >
                        </li>
                        <li>
                            <Link
                                :href="routes.about"
                                class="text-slate-400 hover:text-blue-400 hover:pl-2 transition-all duration-300 inline-block"
                            >
                                About Us</Link
                            >
                        </li>
                        <li>
                            <Link
                                :href="routes.support"
                                class="text-slate-400 hover:text-blue-400 hover:pl-2 transition-all duration-300 inline-block"
                            >
                                Help Center</Link
                            >
                        </li>
                        <li>
                            <Link
                                :href="routes.pricing"
                                class="text-slate-400 hover:text-blue-400 hover:pl-2 transition-all duration-300 inline-block"
                            >
                                Pricing</Link
                            >
                        </li>
                        <li>
                            <Link
                                :href="routes.services"
                                class="text-slate-400 hover:text-blue-400 hover:pl-2 transition-all duration-300 inline-block"
                            >
                                Services</Link
                            >
                        </li>
                        <li>
                            <Link
                                :href="routes.contact"
                                class="text-slate-400 hover:text-blue-400 hover:pl-2 transition-all duration-300 inline-block"
                            >
                                Contact</Link
                            >
                        </li>
                    </ul>
                </div>

                <div>
                    <h3
                        class="text-white font-bold text-lg mb-6 flex items-center gap-2"
                    >
                        <span class="w-8 h-0.5 bg-cyan-500 rounded-full"></span>
                        Legal
                    </h3>
                    <ul class="space-y-3">
                        <li>
                            <Link
                                :href="routes.terms"
                                class="text-slate-400 hover:text-white hover:underline transition-all"
                                >Terms of Service
                            </Link>
                        </li>
                        <li>
                            <Link
                                :href="routes.privacy"
                                class="text-slate-400 hover:text-white hover:underline transition-all"
                                >Privacy Policy
                            </Link>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3
                        class="text-white font-bold text-lg mb-6 flex items-center gap-2"
                    >
                        <span class="w-8 h-0.5 bg-blue-400 rounded-full"></span>
                        Our Ecosystem
                    </h3>
                    <ul class="space-y-3">
                        <li v-for="app in otherApps" :key="app.name">
                            <a
                                :href="app.url"
                                target="_blank"
                                class="group flex items-center gap-2 text-slate-400 hover:text-white transition-colors"
                            >
                                <span
                                    class="w-1.5 h-1.5 rounded-full bg-slate-600 group-hover:bg-blue-500 transition-colors"
                                ></span>
                                {{ app.name }}
                                <svg
                                    class="w-3 h-3 opacity-0 -ml-1 group-hover:opacity-100 group-hover:ml-0 transition-all text-blue-400"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                                    />
                                </svg>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div
                class="border-t border-slate-800/50 mt-16 pt-8 flex flex-col md:flex-row justify-between items-center gap-4"
            >
                <div class="flex items-center gap-3">
                    <img
                        :src="assets.logoSmallUrl"
                        class="w-8 opacity-50 grayscale"
                        alt="logo small"
                    />
                    <p class="text-slate-500 text-sm">
                        &copy; {{ currentYear }}
                        <span class="text-slate-300 font-medium">keyPerson</span
                        >. All rights reserved.
                    </p>
                </div>

                <div class="text-sm text-slate-500 flex items-center gap-1">
                    <span>Produced by</span>
                    <a
                        href="#"
                        class="text-slate-300 hover:text-white font-medium transition-colors border-b border-dashed border-slate-600 hover:border-white"
                    >
                        Kastara Parama Nusantara
                    </a>
                </div>
            </div>
        </div>

        <button
            @click="scrollToTop"
            class="fixed bottom-8 right-8 z-40 bg-blue-600 text-white w-12 h-12 rounded-full shadow-[0_0_30px_rgba(59,130,246,0.4)] flex items-center justify-center hover:bg-blue-700 transition-all transform hover:-translate-y-1 focus:outline-none border border-blue-500/30 opacity-90 hover:opacity-100 hover:shadow-[0_0_40px_rgba(59,130,246,0.6)] group"
            title="Back to top"
        >
            <svg
                class="w-6 h-6 animate-bounce group-hover:animate-none"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M5 10l7-7m0 0l7 7m-7-7v18"
                />
            </svg>
        </button>
    </footer>
</template>
