<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ArrowLeft, Briefcase, Star, BadgeCheck } from "lucide-vue-next";

// Props sesuai yang dikirim ClientPortalController@experts
defineProps({
    client: Object,
    category: Object,
    experts: Array,
});

// Helper untuk format rupiah (Opsional, untuk preview harga)
const formatCurrency = (val) =>
    new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(val);
</script>

<template>
    <Head :title="category.name + ' Experts'" />

    <div class="min-h-screen bg-slate-50 font-sans">
        <div
            class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-sm"
        >
            <div class="max-w-5xl mx-auto px-6 py-4 flex items-center gap-4">
                <Link
                    :href="route('client.home', client.slug)"
                    class="p-2 hover:bg-slate-100 rounded-full transition-colors"
                >
                    <ArrowLeft class="w-6 h-6 text-slate-600" />
                </Link>
                <div>
                    <h1
                        class="text-xl font-bold text-slate-900 flex items-center gap-2"
                    >
                        {{ category.name }} Specialists
                        <span
                            class="bg-violet-100 text-violet-700 text-xs px-2 py-0.5 rounded-full"
                            >{{ experts.length }}</span
                        >
                    </h1>
                    <p class="text-xs text-slate-500">
                        Curated for {{ client.company_name }}
                    </p>
                </div>
            </div>
        </div>

        <div class="max-w-5xl mx-auto px-6 py-8">
            <div class="grid md:grid-cols-3 gap-6">
                <div
                    v-for="expert in experts"
                    :key="expert.id"
                    class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col group"
                >
                    <div
                        class="h-24 bg-linear-to-r from-violet-500 to-indigo-600 relative overflow-hidden"
                    >
                        <div
                            class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"
                        ></div>
                    </div>

                    <div
                        class="px-6 -mt-10 mb-4 flex flex-col items-center text-center"
                    >
                        <img
                            :src="expert.picture"
                            class="w-20 h-20 rounded-2xl border-4 border-white shadow-md object-cover bg-white"
                        />

                        <div class="mt-3">
                            <h3
                                class="font-bold text-lg text-slate-900 flex items-center justify-center gap-1"
                            >
                                {{ expert.name }}
                                <BadgeCheck class="w-4 h-4 text-blue-500" />
                            </h3>
                            <p class="text-sm text-slate-500 font-medium">
                                {{ expert.title }}
                            </p>
                        </div>
                    </div>

                    <div class="px-6 pb-6 mt-auto space-y-4">
                        <div
                            class="flex items-center justify-center gap-2 text-xs text-slate-400 border-t border-slate-100 pt-4"
                        >
                            <span>Starts from</span>
                            <span class="text-slate-900 font-bold">{{
                                formatCurrency(expert.price)
                            }}</span>
                        </div>

                        <Link
                            :href="
                                route('experts.show', expert.id) +
                                `?back=${$page.url}`
                            "
                            class="block w-full text-center py-2.5 bg-slate-50 text-slate-700 border border-slate-200 rounded-xl font-bold text-sm group-hover:bg-slate-900 group-hover:text-white group-hover:border-slate-900 transition-all"
                        >
                            View Profile
                        </Link>
                    </div>
                </div>
            </div>

            <div v-if="experts.length === 0" class="text-center py-20">
                <div class="inline-flex bg-slate-100 p-4 rounded-full mb-4">
                    <Briefcase class="w-8 h-8 text-slate-400" />
                </div>
                <h3 class="text-lg font-bold text-slate-900">
                    No experts found
                </h3>
                <p class="text-slate-500">
                    We are currently curating the best experts for this
                    category.
                </p>
            </div>
        </div>
    </div>
</template>
