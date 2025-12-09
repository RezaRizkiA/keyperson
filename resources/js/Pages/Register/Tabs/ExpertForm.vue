<script setup>
import { useForm } from '@inertiajs/vue3';
import ExpertiseSelector from '../../../Components/ExpertiseSelector.vue';

const props = defineProps({
    user: Object,
    existingData: Object,
    expertises: Array
});

const form = useForm({
    // Sesuaikan dengan field expert di database Anda
    title: props.existingData?.title || '',
    bio: props.existingData?.bio || '',
    expertise_id: props.existingData?.expertise_id || [], // Array ID Expertise
});

const submit = () => {
    form.post(route('register_expert_post'));
};
</script>

<template>
    <form @submit.prevent="submit" class="p-8 md:p-12">
        <div class="grid lg:grid-cols-3 gap-10">

            <div class="lg:col-span-2 space-y-6">
                <div class="border-b border-slate-100 pb-2 mb-4">
                    <h3 class="font-display text-lg font-bold text-slate-900">Professional Details</h3>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Professional Title</label>
                    <input v-model="form.title" type="text"
                        class="w-full rounded-xl border-slate-300 focus:border-violet-500 focus:ring-violet-500"
                        placeholder="e.g. Senior Clinical Psychologist">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Bio / About Me</label>
                    <textarea v-model="form.bio" rows="5"
                        class="w-full rounded-xl border-slate-300 focus:border-violet-500 focus:ring-violet-500"
                        placeholder="Describe your experience and specialization..."></textarea>
                </div>
            </div>

            <div class="lg:col-span-1">
                <ExpertiseSelector :expertises="expertises" v-model="form.expertise_id"
                    label="Choose Your Specialist" />
            </div>

        </div>

        <div class="mt-10 pt-6 border-t border-slate-100 flex justify-end gap-4">
            <button type="submit" :disabled="form.processing"
                class="px-8 py-3 rounded-xl bg-violet-600 text-white font-bold hover:bg-violet-700 shadow-lg disabled:opacity-70">
                {{ form.processing ? 'Saving...' : 'Register as Expert' }}
            </button>
        </div>
    </form>
</template>