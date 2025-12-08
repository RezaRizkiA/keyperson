<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import MainLayout from '../Layouts/MainLayout.vue';
import PricingPage from '../components/PricingPage.vue';
import { onMounted, ref } from 'vue';

const isLoaded = ref(false);
onMounted(() => { setTimeout(() => isLoaded.value = true, 100); });

const { props } = usePage();
const routes = props.routes;
</script>

<template>

    <Head title="Pricing" />

    <MainLayout>
        <div class="transition-all duration-700 ease-out transform"
            :class="isLoaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'">
            <PricingPage :register-url="routes.register_client" />
        </div>
    </MainLayout>
</template>