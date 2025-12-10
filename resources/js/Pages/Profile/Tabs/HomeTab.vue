<script setup>
import { computed } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import {
    Users,
    Settings,
    LogOut,
    Briefcase,
    CheckCircle,
    AlertCircle,
} from "lucide-vue-next";

const props = defineProps({
    user: Object,
    appointmentsCount: Number,
    isExpert: Boolean,
});

const assets = computed(() => usePage().props.assets);
</script>

<template>
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 sm:p-10">
            <div class="flex flex-col md:flex-row gap-8 items-start">

                <div class="shrink-0 mx-auto md:mx-0">
                    <div class="relative">
                        <div
                            class="w-32 h-32 sm:w-40 sm:h-40 rounded-full border-4 border-white shadow-lg overflow-hidden bg-slate-100">
                            <img :src="user.picture_url || assets.userPlaceholderUrl" alt="Profile"
                                class="w-full h-full object-cover" />
                        </div>
                        <Link :href="route('update_profile')"
                            class="absolute bottom-2 right-2 p-2 bg-white rounded-full shadow-md text-slate-600 hover:text-violet-600 transition-colors">
                            <Settings class="w-5 h-5" />
                        </Link>
                    </div>
                </div>

                <div class="flex-1 text-center md:text-left space-y-4">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-900">{{ user.name }}</h1>
                        <p class="text-slate-500 font-medium">{{ user.email }}</p>
                        <div v-if="!user.calendar_connected"
                            class="mt-2 inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-50 text-red-600 text-sm font-medium">
                            <AlertCircle class="w-4 h-4" />
                            <span>Calendar not connected</span>
                            <a :href="route('google.calendar.connect')" class="underline hover:text-red-700">Connect
                                Now</a>
                        </div>
                    </div>

                    <div
                        class="flex flex-wrap justify-center md:justify-start gap-6 sm:gap-12 py-4 border-y border-slate-100">
                        <div class="text-center md:text-left">
                            <div
                                class="text-2xl font-bold text-slate-900 flex items-center justify-center md:justify-start gap-2">
                                <Users class="w-5 h-5 text-violet-500" />
                                <span>9</span>
                            </div>
                            <div class="text-xs text-slate-500 uppercase tracking-wider font-semibold mt-1">Members
                            </div>
                        </div>
                        <div class="text-center md:text-left">
                            <div
                                class="text-2xl font-bold text-slate-900 flex items-center justify-center md:justify-start gap-2">
                                <Briefcase class="w-5 h-5 text-violet-500" />
                                <span>{{ appointmentsCount }}</span>
                            </div>
                            <div class="text-xs text-slate-500 uppercase tracking-wider font-semibold mt-1">Appointments
                            </div>
                        </div>
                        <div class="text-center md:text-left">
                            <div
                                class="text-2xl font-bold text-slate-900 flex items-center justify-center md:justify-start gap-2">
                                <CheckCircle class="w-5 h-5 text-violet-500" />
                                <span>7</span>
                            </div>
                            <div class="text-xs text-slate-500 uppercase tracking-wider font-semibold mt-1">Completed
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap justify-center md:justify-start gap-3">
                        <Link v-if="user.roles.includes('client')" :href="route('home_client', user.client?.slug_page)"
                            class="px-5 py-2.5 rounded-xl bg-slate-900 text-white font-semibold text-sm hover:bg-slate-800 transition-colors shadow-lg shadow-slate-900/20">
                            Client Dashboard
                        </Link>
                        <Link v-if="user.roles.includes('expert')" :href="route('expert_detail', user.expert?.id)"
                            class="px-5 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-700 font-semibold text-sm hover:bg-slate-50 transition-colors">
                            Expert Profile
                        </Link>
                        <div class="ml-auto hidden md:block">
                            <Link :href="route('logout')" method="post" as="button"
                                class="px-4 py-2.5 rounded-xl text-red-600 font-medium text-sm hover:bg-red-50 transition-colors flex items-center gap-2">
                                <LogOut class="w-4 h-4" />
                                Sign Out
                            </Link>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>