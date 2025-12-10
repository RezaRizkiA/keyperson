<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import MainLayout from '../Layouts/MainLayout.vue';
import HomeHero from '../Components/HomeHero.vue';
import LogoCloud from '../Components/LogoCloud.vue';
import HowItWorks from '../Components/HowItWorks.vue';
import FeatureSection from '../Components/FeatureSection.vue';
import FaqSection from '../Components/FaqSection.vue';

// Props khusus untuk Home (dikirim dari Controller)
defineProps({
    heroImages: Object
});

// Ambil routes dari Shared Data (Middleware)
const { props } = usePage();
const routes = props.routes;
</script>

<template>

    <Head title="Find the Right Person" />

    <MainLayout>
        <div>
            <HomeHero :register-client-url="routes.register_client" :register-expert-url="routes.register_expert"
                :images="heroImages" />

            <LogoCloud />
            <HowItWorks />
            <FeatureSection />
            <FaqSection />
            <section class="py-24 bg-violet-900 text-center text-white px-4 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-full overflow-hidden opacity-20 pointer-events-none">
                    <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-violet-500 blur-3xl"></div>
                    <div class="absolute -bottom-24 -right-24 w-96 h-96 rounded-full bg-fuchsia-500 blur-3xl"></div>
                </div>

                <div class="relative z-10 max-w-4xl mx-auto">
                    <h2 class="text-3xl md:text-5xl font-bold font-display mb-6 tracking-tight">
                        Siap Menghemat Waktu Anda?
                    </h2>
                    <p class="text-violet-200 text-lg md:text-xl mb-10 max-w-2xl mx-auto leading-relaxed">
                        Bergabunglah dengan ekosistem kami. Atur jadwal sebagai profesional atau temukan mentor terbaik
                        untuk kebutuhan Anda.
                    </p>

                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <Link :href="routes.register_expert"
                            class="w-full sm:w-auto inline-flex justify-center items-center px-8 py-4 bg-white text-violet-900 rounded-full font-bold text-lg shadow-[0_4px_14px_0_rgba(255,255,255,0.39)] hover:shadow-[0_6px_20px_rgba(255,255,255,0.23)] hover:bg-violet-50 hover:-translate-y-1 transition-all duration-300">
                        Daftar Sebagai Expert
                        </Link>

                        <Link :href="routes.register_client"
                            class="w-full sm:w-auto inline-flex justify-center items-center px-8 py-4 bg-transparent border-2 border-violet-400 text-white rounded-full font-bold text-lg hover:bg-violet-800 hover:border-violet-800 hover:-translate-y-1 transition-all duration-300">
                        Saya Ingin Konsultasi
                        </Link>
                    </div>
                </div>
            </section>
        </div>
    </MainLayout>
</template>