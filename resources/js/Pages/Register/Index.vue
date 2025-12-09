<script setup>
import { computed } from 'vue'; // Hapus 'ref' karena tidak butuh state tab lagi
import { Head } from '@inertiajs/vue3';
import MainLayout from '../../Layouts/MainLayout.vue';

import ClientForm from './Tabs/ClientForm.vue';
import ExpertForm from './Tabs/ExpertForm.vue';

const props = defineProps({
    user: Object,
    client: Object,
    expert: Object,
    expertises: Array,
    initialTab: String // 'client' atau 'expert' (Dikirim dari Controller)
});

// Judul Halaman Dinamis
const pageTitle = computed(() => {
    return props.initialTab === 'client'
        ? 'Register as Organizer'
        : 'Register as Professional';
});

// Deskripsi Halaman Dinamis
const pageDescription = computed(() => {
    return props.initialTab === 'client'
        ? 'Create your profile to start booking professionals, managing events, and organizing schedules.'
        : 'Join our network of experts to offer your services, manage appointments, and grow your career.';
});
</script>

<template>

    <Head :title="pageTitle" />

    <MainLayout>
        <div class="min-h-screen bg-slate-50 pt-24 pb-20">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

                <div class="text-center mb-10">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider mb-4"
                        :class="initialTab === 'client' ? 'bg-violet-100 text-violet-700' : 'bg-orange-100 text-orange-700'">
                        {{ initialTab === 'client' ? 'Client Area' : 'Expert Area' }}
                    </div>

                    <h1 class="font-display text-3xl md:text-4xl font-bold text-slate-900 mb-4">
                        {{ pageTitle }}
                    </h1>
                    <p class="text-slate-500 max-w-2xl mx-auto">
                        {{ pageDescription }}
                    </p>
                </div>

                <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden">

                    <div v-if="initialTab === 'client'">
                        <ClientForm :user="user" :existing-data="client" :expertises="expertises" />
                    </div>

                    <div v-if="initialTab === 'expert'">
                        <ExpertForm :user="user" :existing-data="expert" :expertises="expertises" />
                    </div>

                </div>

            </div>
        </div>
    </MainLayout>
</template>