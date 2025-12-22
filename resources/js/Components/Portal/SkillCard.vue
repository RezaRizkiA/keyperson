<script setup>
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    skill: {
        type: Object,
        required: true,
    },
    clientSlug: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Link
        :href="route('client.experts', [clientSlug, skill.name])"
        class="block bg-slate-900/50 backdrop-blur-sm border border-blue-500/20 rounded-2xl p-6 hover:border-blue-500/60 hover:shadow-[0_0_30px_rgba(59,130,246,0.3)] transition-all duration-300 group"
    >
        <!-- Icon/Badge -->
        <div
            class="w-12 h-12 rounded-xl bg-blue-500/10 border border-blue-500/30 flex items-center justify-center mb-4 group-hover:bg-blue-500/20 group-hover:scale-110 transition-all"
        >
            <svg
                class="w-6 h-6 text-blue-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"
                />
            </svg>
        </div>

        <!-- Skill Name -->
        <h3
            class="text-xl font-bold text-white mb-2 group-hover:text-blue-400 transition-colors"
        >
            {{ skill.name }}
        </h3>

        <!-- Category Path -->
        <p v-if="skill.sub_category" class="text-sm text-slate-400 mb-3">
            {{ skill.sub_category.category?.name }} •
            {{ skill.sub_category.name }}
        </p>

        <!-- CTA -->
        <div class="flex items-center gap-2 text-blue-400 text-sm font-medium">
            <span>View Experts</span>
            <svg
                class="w-4 h-4 group-hover:translate-x-1 transition-transform"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 5l7 7-7 7"
                />
            </svg>
        </div>
    </Link>
</template>
