<script setup>
import ToastNotification from '../Components/ToastNotification.vue';
</script>

<template>
    <div class="min-h-screen bg-slate-50 font-sans text-slate-900">

        <ToastNotification />

        <main>
            <slot />
        </main>

    </div>
</template>