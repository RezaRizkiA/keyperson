<script setup>
import { ref, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    registerClientUrl: String,
    registerExpertUrl: String,
    images: Object
});

const isLoaded = ref(false);

onMounted(() => {
    setTimeout(() => {
        isLoaded.value = true;
    }, 100);
});
</script>

<template>
    <section class="relative bg-white overflow-hidden font-sans section">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-12 lg:pt-24 lg:pb-20">

            <div class="grid lg:grid-cols-2 gap-10 lg:gap-14 items-center">

                <div class="relative z-10 transition-all duration-1000 ease-out transform"
                    :class="isLoaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'">
                    <div class="flex items-center gap-2 mb-6">
                        <span
                            class="bg-violet-50 text-violet-700 py-1.5 px-3 rounded-full text-xs font-bold font-display uppercase tracking-wide inline-flex items-center gap-1.5 shadow-sm border border-violet-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>

                            <span class="leading-none pt-[1px]">keyperson.id</span>
                        </span>
                    </div>

                    <h1
                        class="font-display text-4xl lg:text-5xl font-extrabold tracking-tight text-slate-900 mb-6 leading-[1.15]">
                        <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-violet-600 via-fuchsia-500 to-pink-500">keyPerson</span>
                        –
                        Find the Right Person, Set the Right Time
                    </h1>

                    <p class="text-lg text-slate-600 mb-4 leading-relaxed max-w-lg font-normal">
                        Keyperson.id is a platform that helps you connect with the right professionals — from counselors
                        to mentors, coaches to teachers. We make it easy to search, book, and manage appointments with
                        just a few clicks.
                    </p>

                    <div class="flex items-start gap-3 mb-8 max-w-lg">
                        <div
                            class="mt-1 flex-shrink-0 w-5 h-5 rounded-full bg-green-100 flex items-center justify-center">
                            <svg class="w-3 h-3 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <p class="text-base text-slate-600 leading-relaxed">
                            All appointments you make are automatically synced to your <span
                                class="font-semibold text-slate-800">Google Calendar</span>, ensuring you never miss an
                            important session.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4">
                        <Link :href="registerClientUrl"
                            class="inline-flex justify-center items-center px-6 py-3 text-base font-bold font-display text-white transition-all bg-violet-600 rounded-full shadow-lg hover:bg-violet-700 hover:-translate-y-1 cursor-pointer">
                            Create an Organizer
                        </Link>
                        <Link :href="registerExpertUrl"
                            class="inline-flex justify-center items-center px-6 py-3 text-base font-bold font-display text-violet-700 transition-all bg-white border border-violet-200 rounded-full hover:bg-violet-50 hover:border-violet-300 cursor-pointer">
                            I am Professional
                        </Link>
                    </div>
                </div>

                <div class="relative hidden lg:block">
                    <div
                        class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 bg-fuchsia-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob">
                    </div>
                    <div
                        class="absolute -bottom-8 left-10 w-64 h-64 bg-violet-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000">
                    </div>

                    <div class="relative grid grid-cols-2 gap-3">
                        <div class="space-y-3 animate-scroll-up">
                            <div
                                class="rounded-xl overflow-hidden shadow-xl border-2 border-white rotate-1 hover:rotate-0 transition-transform duration-500">
                                <img :src="images.banner1" alt="Counseling Session" class="w-full h-56 object-cover">
                            </div>
                            <div
                                class="rounded-xl overflow-hidden shadow-xl border-2 border-white -rotate-1 hover:rotate-0 transition-transform duration-500">
                                <img :src="images.banner2" alt="Mentoring" class="w-full h-56 object-cover">
                            </div>
                        </div>

                        <div class="space-y-3 pt-8 animate-scroll-down">
                            <div
                                class="rounded-xl overflow-hidden shadow-xl border-2 border-white -rotate-1 hover:rotate-0 transition-transform duration-500">
                                <img :src="images.banner2" alt="Coaching" class="w-full h-56 object-cover">
                            </div>
                            <div
                                class="rounded-xl overflow-hidden shadow-xl border-2 border-white rotate-1 hover:rotate-0 transition-transform duration-500">
                                <img :src="images.banner1" alt="Teaching" class="w-full h-56 object-cover">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</template>

<style scoped>
/* Animasi tidak berubah */
.animate-blob {
    animation: blob 7s infinite;
}

.animation-delay-2000 {
    animation-delay: 2s;
}

@keyframes blob {
    0% {
        transform: translate(0px, 0px) scale(1);
    }

    33% {
        transform: translate(30px, -50px) scale(1.1);
    }

    66% {
        transform: translate(-20px, 20px) scale(0.9);
    }

    100% {
        transform: translate(0px, 0px) scale(1);
    }
}

.animate-scroll-up {
    animation: floatUp 4s ease-in-out infinite alternate;
}

.animate-scroll-down {
    animation: floatDown 4.5s ease-in-out infinite alternate;
}

@keyframes floatUp {
    0% {
        transform: translateY(0);
    }

    100% {
        transform: translateY(-15px);
    }
}

@keyframes floatDown {
    0% {
        transform: translateY(0);
    }

    100% {
        transform: translateY(15px);
    }
}
</style>