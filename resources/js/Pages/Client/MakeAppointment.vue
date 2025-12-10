<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import MainLayout from '../../Layouts/MainLayout.vue';
import {
    Calendar,
    Clock,
    MessageSquare,
    CreditCard,
    User,
    ShieldCheck,
    ArrowLeft
} from 'lucide-vue-next';

const props = defineProps({
    expert: Object,
    backUrl: String
});

// Setup Form
const form = useForm({
    hours: 1, // Default 1 jam
    topic: '', // detail appointment
    date: '',
    time: '',
});

// Format Currency Helper
const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value);
};

// Computed Total Price
const totalPrice = computed(() => {
    return props.expert.price * form.hours;
});

// Increment/Decrement Hours
const updateHours = (amount) => {
    const newVal = form.hours + amount;
    if (newVal >= 1 && newVal <= 8) { // Max 8 jam sehari
        form.hours = newVal;
    }
};

const submit = () => {
    // Gabungkan date & time jika backend membutuhkannya dalam satu field,
    // atau biarkan terpisah sesuai validasi backend Anda.
    // Di sini saya asumsikan backend menerima 'date' dan 'time' terpisah seperti di blade lama.

    form.post(route('appointment_post', props.expert.id), {
        preserveScroll: true,
    });
};
</script>

<template>

    <Head title="Book Appointment" />

    <MainLayout>
        <div class="bg-slate-50 min-h-screen font-sans pt-24 pb-20">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

                <div class="mb-8">
                    <Link :href="backUrl || route('expert_detail', expert.id)"
                        class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-violet-600 transition-colors">
                        <ArrowLeft class="w-4 h-4 mr-1" />
                        Cancel Booking
                    </Link>
                </div>

                <div class="grid lg:grid-cols-12 gap-8 items-start">

                    <div class="lg:col-span-7 space-y-6">
                        <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm">
                            <h2 class="font-display text-xl font-bold text-slate-900 mb-6 flex items-center gap-2">
                                <Calendar class="w-5 h-5 text-violet-500" />
                                Session Details
                            </h2>

                            <form @submit.prevent="submit" id="appointmentForm">

                                <div class="mb-6">
                                    <label
                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Topic
                                        of Discussion</label>
                                    <div class="relative group">
                                        <div class="absolute top-3 left-3 flex items-center pointer-events-none">
                                            <MessageSquare
                                                class="w-5 h-5 text-slate-400 group-focus-within:text-violet-500 transition-colors" />
                                        </div>
                                        <textarea v-model="form.topic" rows="4"
                                            class="block w-full pl-10 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:bg-white focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 transition-all text-sm"
                                            placeholder="Describe what you want to discuss..." required>
                                        </textarea>
                                    </div>
                                    <p v-if="form.errors.topic" class="text-red-500 text-xs mt-1">{{ form.errors.topic}}</p>
                                </div>

                                <div class="grid md:grid-cols-2 gap-6 mb-6">

                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Select
                                            Date</label>
                                        <div class="relative group">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <Calendar
                                                    class="w-5 h-5 text-slate-400 group-focus-within:text-violet-500 transition-colors" />
                                            </div>
                                            <input v-model="form.date" type="date"
                                                class="block w-full pl-10 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:bg-white focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 transition-all text-sm cursor-pointer"
                                                required>
                                        </div>
                                        <p v-if="form.errors.date" class="text-red-500 text-xs mt-1">{{ form.errors.date
                                        }}</p>
                                    </div>

                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Select
                                            Time</label>
                                        <div class="relative group">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <Clock
                                                    class="w-5 h-5 text-slate-400 group-focus-within:text-violet-500 transition-colors" />
                                            </div>
                                            <input v-model="form.time" type="time"
                                                class="block w-full pl-10 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:bg-white focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 transition-all text-sm cursor-pointer"
                                                required>
                                        </div>
                                        <p v-if="form.errors.time" class="text-red-500 text-xs mt-1">{{ form.errors.time
                                        }}</p>
                                    </div>

                                </div>

                                <div
                                    class="flex items-center gap-3 p-4 bg-green-50 border border-green-100 rounded-xl text-green-800 text-xs">
                                    <ShieldCheck class="w-5 h-5 shrink-0" />
                                    <span>Your session is protected. Payment will be held securely until the session is
                                        completed.</span>
                                </div>

                            </form>
                        </div>
                    </div>

                    <div class="lg:col-span-5 space-y-6 sticky top-24">
                        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-xl shadow-slate-200/50">
                            <h3 class="font-display text-lg font-bold text-slate-900 mb-6">Order Summary</h3>

                            <div class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-100">
                                <img :src="expert.user.picture_url" :alt="expert.user.name"
                                    class="w-14 h-14 rounded-full object-cover border-2 border-slate-100">
                                <div>
                                    <h4 class="font-bold text-slate-900">{{ expert.user.name }}</h4>
                                    <p
                                        class="text-xs text-slate-500 font-medium bg-slate-100 px-2 py-0.5 rounded inline-block mt-1">
                                        {{ expert.expertise }}</p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between mb-4">
                                <span class="text-sm font-medium text-slate-600">Duration</span>
                                <div class="flex items-center bg-slate-50 rounded-lg border border-slate-200 p-1">
                                    <button type="button" @click="updateHours(-1)"
                                        class="w-8 h-8 flex items-center justify-center rounded-md hover:bg-white hover:shadow-sm text-slate-500 transition-all disabled:opacity-50"
                                        :disabled="form.hours <= 1">-</button>
                                    <span class="w-8 text-center font-bold text-slate-900 text-sm">{{ form.hours
                                    }}h</span>
                                    <button type="button" @click="updateHours(1)"
                                        class="w-8 h-8 flex items-center justify-center rounded-md hover:bg-white hover:shadow-sm text-slate-500 transition-all">+</button>
                                </div>
                            </div>

                            <div class="space-y-3 mb-6 pb-6 border-b border-slate-100">
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-500">Rate per hour</span>
                                    <span class="font-medium text-slate-900">{{ formatCurrency(expert.price) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-500">Service Fee</span>
                                    <span class="font-medium text-green-600">Free</span>
                                </div>
                            </div>

                            <div class="flex justify-between items-center mb-8">
                                <span class="text-base font-bold text-slate-900">Total Payment</span>
                                <span class="text-2xl font-display font-bold text-violet-600">{{
                                    formatCurrency(totalPrice) }}</span>
                            </div>

                            <button type="submit" form="appointmentForm" :disabled="form.processing"
                                class="w-full py-4 bg-slate-900 text-white rounded-xl font-bold text-base hover:bg-violet-600 transition-all shadow-lg shadow-slate-900/20 flex items-center justify-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed">
                                <span v-if="form.processing"
                                    class="animate-spin rounded-full h-4 w-4 border-2 border-white border-t-transparent"></span>
                                <CreditCard class="w-5 h-5" v-else />
                                {{ form.processing ? 'Processing...' : 'Proceed to Payment' }}
                            </button>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </MainLayout>
</template>