<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3'; // Hook untuk ambil data shared
import AppNavbar from '../Components/AppNavbar.vue';
import AppFooter from '../Components/AppFooter.vue';
import ToastNotification from '../Components/ToastNotification.vue';

// Mengakses data yang dikirim dari HandleInertiaRequests.php
const page = usePage();
const user = computed(() => page.props.auth.user);
const routes = computed(() => page.props.routes);
const assets = computed(() => page.props.assets);

// Component name berguna untuk menandai menu aktif
const componentName = computed(() => page.component);
</script>

<template>
    <div class="flex flex-col min-h-screen font-sans">
        <ToastNotification />

        <AppNavbar :user="user" :routes="routes" :logo-url="assets.logoUrl"
            :current-route-name="componentName.toLowerCase()" />

        <main class="flex-grow">
            <Transition name="page" mode="out-in" appear>
                <slot :key="componentName" />
            </Transition>
        </main>

        <AppFooter :routes="routes" :logo-url="assets.logoWhiteUrl" :logo-small-url="assets.logoSmallUrl" />
    </div>
</template>