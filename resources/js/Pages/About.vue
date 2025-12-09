<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import MainLayout from '../Layouts/MainLayout.vue';
import AboutPage from '../Components/AboutPage.vue';
import { ref, onMounted } from 'vue';

const isLoaded = ref(false);

onMounted(() => {
    setTimeout(() => {
        isLoaded.value = true;
    }, 100);
});
const { props } = usePage();
const routes = props.routes;
</script>

<template>

    <Head title="About Us" />

    <MainLayout>
        <div class="transition-all duration-700 ease-out transform"
            :class="isLoaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'">
            <AboutPage :register-client-url="routes.register_client"
                :register-expert-url="routes.register_expert" />
        </div>
    </MainLayout>
</template>