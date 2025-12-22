<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { Briefcase, ChevronRight } from "lucide-vue-next";

defineProps({
    client: Object,
    categories: Array,
});
</script>

<template>
    <Head :title="client.company_name + ' Portal'" />

    <div class="min-h-screen bg-slate-50 font-sans">
        <div class="bg-slate-900 text-white py-16 px-6">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-3xl font-bold mb-4">
                    Welcome, {{ client.company_name }} Team
                </h1>
                <p class="text-slate-300">
                    Select a topic below to find the right expert.
                </p>
            </div>
        </div>

        <div class="max-w-4xl mx-auto px-6 -mt-8">
            <div class="grid md:grid-cols-2 gap-4">
                <Link
                    v-for="category in categories"
                    :key="category.id"
                    :href="
                        route('client.experts', [client.slug, category.slug])
                    "
                    class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 hover:shadow-lg hover:border-violet-500 transition-all group flex items-center justify-between"
                >
                    <div class="flex items-center gap-4">
                        <div
                            class="p-3 bg-violet-50 rounded-xl text-violet-600 group-hover:bg-violet-600 group-hover:text-white transition-colors"
                        >
                            <img
                                v-if="category.icon"
                                :src="category.icon"
                                class="w-6 h-6"
                            />
                            <Briefcase v-else class="w-6 h-6" />
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900">
                                {{ category.name }}
                            </h3>
                            <p
                                class="text-xs text-slate-500"
                                v-if="category.skills_count"
                            >
                                {{ category.skills_count }} Topics Available
                            </p>
                            <p class="text-xs text-slate-500" v-else>
                                Browse Experts
                            </p>
                        </div>
                    </div>

                    <ChevronRight
                        class="w-5 h-5 text-slate-300 group-hover:text-violet-500"
                    />
                </Link>
            </div>

            <div
                v-if="categories.length === 0"
                class="text-center py-10 bg-white rounded-2xl mt-4 border border-slate-200"
            >
                <p class="text-slate-500">
                    No categories assigned to your company yet.
                </p>
            </div>
        </div>
    </div>
</template>
