<script setup>
import { Head, Link } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { 
    Calendar, Clock, AlertCircle, Wallet, 
    ArrowRight, TrendingUp, Users 
} from 'lucide-vue-next';

// Menggunakan DashboardLayout
defineOptions({ layout: DashboardLayout });

// Menerima props 'stats' dari Controller
const props = defineProps({
    stats: Object,
});

// Helper untuk format Rupiah
const formatCurrency = (amount) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount || 0);
};
</script>

<template>
    <Head title="Expert Dashboard" />

    <div class="space-y-8">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Expert Overview</h1>
                <p class="text-slate-500 mt-1">Welcome back! Here's what's happening with your sessions today.</p>
            </div>
            
            <Link :href="route('dashboard.appointments.index')" 
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-violet-600 hover:bg-violet-700 text-white text-sm font-bold rounded-xl transition-all shadow-sm shadow-violet-200">
                <Calendar class="w-4 h-4" />
                Manage Schedule
            </Link>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <div class="bg-white p-6 rounded-2xl border shadow-sm transition-all duration-300 hover:shadow-md group relative overflow-hidden"
                :class="stats.need_confirmation > 0 ? 'border-orange-200 ring-1 ring-orange-100' : 'border-slate-200'">
                
                <div class="flex justify-between items-start z-10 relative">
                    <div>
                        <p class="text-sm font-bold uppercase tracking-wider" 
                           :class="stats.need_confirmation > 0 ? 'text-orange-600' : 'text-slate-500'">
                            Action Needed
                        </p>
                        <h3 class="text-3xl font-extrabold text-slate-900 mt-2">
                            {{ stats.need_confirmation }}
                        </h3>
                        <p class="text-xs text-slate-400 mt-1">Pending Requests</p>
                    </div>
                    <div class="p-3 rounded-xl" :class="stats.need_confirmation > 0 ? 'bg-orange-100 text-orange-600' : 'bg-slate-100 text-slate-400'">
                        <AlertCircle class="w-6 h-6" />
                    </div>
                </div>

                <Link v-if="stats.need_confirmation > 0" :href="route('dashboard.appointments', { status: 'need_confirmation' })" 
                    class="absolute inset-0 z-20" aria-label="View pending appointments">
                </Link>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm transition-all duration-300 hover:shadow-md group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-bold text-slate-500 uppercase tracking-wider">Upcoming</p>
                        <h3 class="text-3xl font-extrabold text-slate-900 mt-2">
                            {{ stats.upcoming_appointments }}
                        </h3>
                        <p class="text-xs text-slate-400 mt-1">Confirmed & Progress</p>
                    </div>
                    <div class="p-3 bg-blue-50 text-blue-600 rounded-xl group-hover:scale-110 transition-transform">
                        <Clock class="w-6 h-6" />
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm transition-all duration-300 hover:shadow-md group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-bold text-slate-500 uppercase tracking-wider">Total Revenue</p>
                        <h3 class="text-3xl font-extrabold text-slate-900 mt-2 truncate max-w-[180px]" :title="formatCurrency(stats.total_revenue)">
                            {{ formatCurrency(stats.total_revenue) }}
                        </h3>
                        <p class="text-xs text-slate-400 mt-1">Paid Transactions</p>
                    </div>
                    <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl group-hover:scale-110 transition-transform">
                        <Wallet class="w-6 h-6" />
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm transition-all duration-300 hover:shadow-md group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-bold text-slate-500 uppercase tracking-wider">Total Sessions</p>
                        <h3 class="text-3xl font-extrabold text-slate-900 mt-2">
                            {{ stats.total_appointments }}
                        </h3>
                        <p class="text-xs text-slate-400 mt-1">All time history</p>
                    </div>
                    <div class="p-3 bg-violet-50 text-violet-600 rounded-xl group-hover:scale-110 transition-transform">
                        <TrendingUp class="w-6 h-6" />
                    </div>
                </div>
            </div>

        </div>

        <div v-if="stats.total_appointments === 0" class="bg-violet-50 border border-violet-100 rounded-2xl p-8 text-center">
            <div class="mx-auto w-16 h-16 bg-white rounded-full flex items-center justify-center mb-4 shadow-sm text-violet-600">
                <Calendar class="w-8 h-8" />
            </div>
            <h3 class="text-lg font-bold text-violet-900">You are ready to start!</h3>
            <p class="text-slate-600 mt-2 max-w-md mx-auto">
                Your profile is active. Once clients start booking your sessions, they will appear right here.
            </p>
        </div>

    </div>
</template>