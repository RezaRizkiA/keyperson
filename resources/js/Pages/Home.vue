<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import MainLayout from '../Layouts/MainLayout.vue';
import HomeHero from '../components/HomeHero.vue';
import LogoCloud from '../components/LogoCloud.vue';
import FeatureSection from '../components/FeatureSection.vue';

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
            <FeatureSection />
        </div>
    </MainLayout>
</template>