<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import MainLayout from '../Layouts/MainLayout.vue';
import PrivacyPage from '../components/PrivacyPage.vue';
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

    <Head title="Privacy Policy" />

    <MainLayout>
        <div class="transition-all duration-700 ease-out transform"
            :class="isLoaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'">
            <PrivacyPage :terms-url="routes.terms" />
        </div>
    </MainLayout>
</template>