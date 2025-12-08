<script setup>
import { Head } from '@inertiajs/vue3';
import MainLayout from '../Layouts/MainLayout.vue';
import SupportPage from '../components/SupportPage.vue';

import { ref, onMounted } from 'vue';

const isLoaded = ref(false);

onMounted(() => {
    setTimeout(() => {
        isLoaded.value = true;
    }, 100);
});

</script>

<template>

    <Head title="Support Center" />

    <MainLayout>
        <div class="transition-all duration-700 ease-out transform"
            :class="isLoaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'">
            <SupportPage />
        </div>
    </MainLayout>
</template>