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
                            <span class="w-2 h-2 rounded-full bg-violet-600 animate-pulse"></span>
                            <span>Platform Booking No. #1</span>
                        </span>
                    </div>

                    <h1
                        class="font-display text-4xl lg:text-5xl font-extrabold tracking-tight text-slate-900 mb-6 leading-[1.15]">
                        Atur Jadwal Konsultasi <br>
                        <span
                            class="text-transparent bg-clip-text bg-linear-to-r from-violet-600 via-fuchsia-500 to-pink-500">Tanpa
                            Drama Chat</span>
                    </h1>

                    <p class="text-lg text-slate-600 mb-8 leading-relaxed max-w-lg font-normal">
                        keyPerson membantu profesional dan klien menemukan waktu yang tepat secara instan.
                        Otomatisasi booking, pembayaran, hingga reminder dalam satu link sederhana.
                    </p>

                    <div class="space-y-3 mb-8">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-slate-700">Terintegrasi dengan <strong>Google Calendar</strong></span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-slate-700">Pembayaran Otomatis & Aman</span>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4">
                        <Link :href="registerClientUrl"
                            class="inline-flex justify-center items-center px-8 py-3.5 text-base font-bold font-display text-white transition-all bg-violet-600 rounded-full shadow-lg hover:bg-violet-700 hover:shadow-violet-200 hover:-translate-y-1">
                            Cari Professional
                        </Link>

                        <Link :href="registerExpertUrl"
                            class="inline-flex justify-center items-center px-8 py-3.5 text-base font-bold font-display text-slate-700 transition-all bg-white border border-slate-200 rounded-full hover:bg-slate-50 hover:border-violet-300 hover:text-violet-700">
                            Saya Penyedia Jasa
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

                    <div class="relative grid grid-cols-2 gap-4">
                        <div class="space-y-4 animate-scroll-up">
                            <div
                                class="rounded-2xl overflow-hidden shadow-2xl border-4 border-white transform rotate-2 hover:rotate-0 transition-all duration-500">
                                <img :src="images.banner1" alt="Sesi Konsultasi"
                                    class="w-full h-64 object-cover hover:scale-110 transition-transform duration-700">
                            </div>
                        </div>
                        <div class="space-y-4 pt-12 animate-scroll-down">
                            <div
                                class="rounded-2xl overflow-hidden shadow-2xl border-4 border-white transform -rotate-2 hover:rotate-0 transition-all duration-500">
                                <img :src="images.banner2" alt="Meeting Online"
                                    class="w-full h-64 object-cover hover:scale-110 transition-transform duration-700">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</template>

<style scoped>
/* Gunakan style animasi yang sudah ada sebelumnya */
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
    animation: floatUp 5s ease-in-out infinite alternate;
}

.animate-scroll-down {
    animation: floatDown 6s ease-in-out infinite alternate;
}

@keyframes floatUp {
    0% {
        transform: translateY(0);
    }

    100% {
        transform: translateY(-20px);
    }
}

@keyframes floatDown {
    0% {
        transform: translateY(0);
    }

    100% {
        transform: translateY(20px);
    }
}
</style>