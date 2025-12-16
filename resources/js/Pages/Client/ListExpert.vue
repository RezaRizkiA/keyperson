<script setup>
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import MainLayout from '../../Layouts/MainLayout.vue';
import { Search, ArrowLeft, Briefcase, Star, Share2 } from 'lucide-vue-next';

const props = defineProps({
    client: Object,
    expertise: Object,
    experts: Array
});

const searchQuery = ref('');

const filteredExperts = computed(() => {
    if (!searchQuery.value) return props.experts;
    const query = searchQuery.value.toLowerCase();
    return props.experts.filter(expert => {
        const name = expert.user?.name?.toLowerCase() || '';
        const title = expert.expertise?.toLowerCase() || '';
        return name.includes(query) || title.includes(query);
    });
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value);
};
</script>

<template>

    <Head :title="`${expertise.name} Experts`" />

    <MainLayout>
        <div class="bg-slate-50 min-h-screen font-sans">

            <div class="relative bg-slate-900 pt-32 pb-24 px-4 sm:px-6 lg:px-8 overflow-hidden">
                <div class="absolute inset-0 opacity-20">
                    <img v-if="client.banner_url" :src="client.banner_url" class="w-full h-full object-cover blur-sm" />
                    <div v-else class="w-full h-full bg-linear-to-r from-violet-900 to-fuchsia-900"></div>
                </div>
                <div class="absolute inset-0 bg-linear-to-b from-transparent to-slate-50"></div>

                <div class="relative max-w-7xl mx-auto z-10">
                    <Link :href="route('home_client', client.slug_page)"
                        class="inline-flex items-center text-sm font-bold text-slate-400 hover:text-white mb-6 transition-colors">
                        <ArrowLeft class="w-4 h-4 mr-1" />
                        Back to {{ client.section_hero || 'Program' }}
                    </Link>

                    <div class="flex flex-col md:flex-row md:items-end justify-between gap-8">
                        <div>
                            <span
                                class="inline-block py-1 px-3 rounded-full bg-violet-500/20 border border-violet-500/30 text-violet-300 text-xs font-bold uppercase tracking-wider mb-3">
                                {{ expertise.name }}
                            </span>
                            <h1 class="font-display text-4xl md:text-5xl font-bold text-slate-900">
                                Find your <span
                                    class="text-transparent bg-clip-text bg-gradient-to-r from-violet-600 to-fuchsia-600">{{
                                    expertise.name }}</span>
                            </h1>
                        </div>

                        <div class="w-full md:w-96 relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <Search
                                    class="h-5 w-5 text-slate-400 group-focus-within:text-violet-600 transition-colors" />
                            </div>
                            <input v-model="searchQuery" type="text"
                                class="block w-full pl-11 pr-4 py-4 bg-white/80 backdrop-blur-xl border border-white/50 rounded-2xl text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/50 focus:bg-white shadow-lg transition-all"
                                placeholder="Search expert by name...">
                        </div>
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20 -mt-10 relative z-20">
                <div v-if="filteredExperts.length > 0" class="grid md:grid-cols-3 lg:grid-cols-4 gap-6">

                    <Link v-for="expert in filteredExperts" :key="expert.id"
                        :href="`${route('expert_detail', expert.id)}?back=${encodeURIComponent($page.url)}`"
                        class="group bg-white rounded-3xl p-5 border border-slate-100 shadow-sm hover:shadow-[0_20px_40px_-15px_rgba(0,0,0,0.1)] hover:-translate-y-2 transition-all duration-300 flex flex-col">
                        <div class="flex items-start justify-between mb-4">
                            <div class="relative">
                                <div
                                    class="w-16 h-16 rounded-2xl overflow-hidden shadow-md group-hover:shadow-lg transition-all">
                                    <img :src="expert.user?.picture_url" class="w-full h-full object-cover">
                                </div>
                                <div class="absolute -bottom-2 -right-2 bg-white rounded-full p-1 shadow-sm">
                                    <div class="bg-green-500 w-3 h-3 rounded-full border-2 border-white"></div>
                                </div>
                            </div>
                            <div class="flex gap-1 text-yellow-400">
                                <Star class="w-4 h-4 fill-current" />
                                <span class="text-xs font-bold text-slate-700 mt-0.5">5.0</span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h3
                                class="font-display font-bold text-lg text-slate-900 mb-1 line-clamp-1 group-hover:text-violet-600 transition-colors">
                                {{ expert.user?.name }}
                            </h3>
                            <p class="text-xs text-slate-500 font-medium line-clamp-2 min-h-[2.5em]">
                                {{ expert.expertise }}
                            </p>
                        </div>

                        <div class="mt-auto pt-4 border-t border-slate-50 flex items-center justify-between">
                            <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Rate/Hr</div>
                            <div class="font-bold text-slate-900 text-sm">
                                {{ formatCurrency(expert.price) }}
                            </div>
                        </div>
                    </Link>

                </div>

                <div v-else class="text-center py-24 bg-white rounded-3xl border-2 border-dashed border-slate-200">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <Search class="w-8 h-8 text-slate-300" />
                    </div>
                    <h3 class="text-xl font-bold text-slate-900">No Experts Found</h3>
                    <p class="text-slate-500 mt-2">Try adjusting your search query.</p>
                    <button v-if="searchQuery" @click="searchQuery = ''"
                        class="mt-6 text-violet-600 font-bold hover:underline">
                        Clear Search
                    </button>
                </div>
            </div>
        </div>
    </MainLayout>
</template>