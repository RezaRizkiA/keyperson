<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import MainLayout from '../../Layouts/MainLayout.vue';
import { Calendar, Clock, MessageSquare, CreditCard, ArrowLeft, Plus, Minus, AlertCircle } from 'lucide-vue-next';

const props = defineProps({ expert: Object, backUrl: String });

const form = useForm({ hours: 1, topic: '', date: '', time: '' });

const formatCurrency = (value) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value);
const totalPrice = computed(() => props.expert.price * form.hours);
const updateHours = (amount) => { const newVal = form.hours + amount; if (newVal >= 1 && newVal <= 8) form.hours = newVal; };
const submit = () => form.post(route('appointment_post', props.expert.id), { preserveScroll: true });
</script>

<template>

    <Head title="Book Appointment" />

    <MainLayout>
        <div class="bg-slate-50 min-h-screen font-sans pt-28 pb-20">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

                <div class="mb-8">
                    <Link :href="backUrl || route('expert_detail', expert.id)"
                        class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-violet-600 transition-colors">
                        <ArrowLeft class="w-4 h-4 mr-2" /> Cancel Booking
                    </Link>
                </div>

                <div class="grid lg:grid-cols-12 gap-8 items-start">

                    <div class="lg:col-span-7">
                        <div class="bg-white rounded-4xl p-8 border border-slate-100 shadow-xl shadow-slate-200/40">
                            <h2 class="font-display text-2xl font-bold text-slate-900 mb-8 flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-full bg-violet-50 flex items-center justify-center text-violet-600">
                                    <Calendar class="w-5 h-5" />
                                </div>
                                Session Details
                            </h2>

                            <form @submit.prevent="submit" id="appointmentForm" class="space-y-6">
                                <div>
                                    <label
                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Topic</label>
                                    <div class="relative">
                                        <MessageSquare class="absolute top-4 left-4 w-5 h-5 text-slate-400" />
                                        <textarea v-model="form.topic" rows="4" required
                                            placeholder="What would you like to discuss?"
                                            class="block w-full pl-12 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 placeholder-slate-400 focus:bg-white focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 transition-all text-sm resize-none"></textarea>
                                    </div>
                                    <p v-if="form.errors.topic" class="text-red-500 text-xs mt-1 ml-1">{{
                                        form.errors.topic }}</p>
                                </div>

                                <div class="grid md:grid-cols-2 gap-6">
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Date</label>
                                        <div class="relative">
                                            <Calendar class="absolute top-3.5 left-4 w-5 h-5 text-slate-400" />
                                            <input v-model="form.date" type="date" required
                                                class="block w-full pl-12 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 focus:bg-white focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 transition-all text-sm cursor-pointer">
                                        </div>
                                        <p v-if="form.errors.date" class="text-red-500 text-xs mt-1 ml-1">{{
                                            form.errors.date }}</p>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Time</label>
                                        <div class="relative">
                                            <Clock class="absolute top-3.5 left-4 w-5 h-5 text-slate-400" />
                                            <input v-model="form.time" type="time" required
                                                class="block w-full pl-12 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 focus:bg-white focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 transition-all text-sm cursor-pointer">
                                        </div>
                                        <p v-if="form.errors.time" class="text-red-500 text-xs mt-1 ml-1">{{
                                            form.errors.time }}</p>
                                    </div>
                                </div>

                                <div
                                    class="p-4 bg-blue-50 border border-blue-100 rounded-2xl flex gap-3 text-blue-800 text-xs leading-relaxed">
                                    <AlertCircle class="w-5 h-5 shrink-0" />
                                    <span>Please ensure your selected time matches your timezone. The expert will be
                                        notified immediately after payment.</span>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="lg:col-span-5 sticky top-28">
                        <div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-2xl shadow-slate-200/50">
                            <h3 class="font-display text-lg font-bold text-slate-900 mb-6">Order Summary</h3>

                            <div class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-100">
                                <img :src="expert.user.picture_url"
                                    class="w-14 h-14 rounded-2xl object-cover border border-slate-200">
                                <div>
                                    <h4 class="font-bold text-slate-900">{{ expert.user.name }}</h4>
                                    <p
                                        class="text-xs font-bold text-violet-600 bg-violet-50 px-2 py-0.5 rounded inline-block mt-1">
                                        {{ expert.expertise }}</p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between mb-6">
                                <span class="text-sm font-medium text-slate-600">Duration</span>
                                <div class="flex items-center bg-slate-50 rounded-xl border border-slate-200 p-1">
                                    <button @click="updateHours(-1)" :disabled="form.hours <= 1"
                                        class="w-9 h-9 flex items-center justify-center rounded-lg bg-white shadow-sm disabled:opacity-50 hover:text-violet-600 transition-colors">
                                        <Minus class="w-4 h-4" />
                                    </button>
                                    <span class="w-10 text-center font-bold text-slate-900 text-sm">{{ form.hours
                                        }}h</span>
                                    <button @click="updateHours(1)"
                                        class="w-9 h-9 flex items-center justify-center rounded-lg bg-white shadow-sm hover:text-violet-600 transition-colors">
                                        <Plus class="w-4 h-4" />
                                    </button>
                                </div>
                            </div>

                            <div class="space-y-3 mb-8 pb-8 border-b border-slate-100 border-dashed">
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-500">Rate per hour</span>
                                    <span class="font-medium text-slate-900">{{ formatCurrency(expert.price) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-500">Platform Fee</span>
                                    <span class="font-bold text-green-600">Free</span>
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
                                <CreditCard v-else class="w-5 h-5" />
                                {{ form.processing ? 'Processing...' : 'Proceed to Payment' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>