<script setup>
import { Briefcase } from "lucide-vue-next";

defineProps({
    experiences: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <div>
        <h2
            class="text-sm font-bold text-blue-400 uppercase tracking-wider mb-4"
        >
            <Briefcase class="w-4 h-4 inline-block mr-1 -mt-0.5" />
            PROFESSIONAL EXPERIENCE
        </h2>
        <div
            class="bg-slate-900/50 backdrop-blur-sm border border-blue-500/20 rounded-2xl p-8"
        >
            <div class="space-y-8">
                <div
                    v-for="(exp, index) in experiences"
                    :key="index"
                    class="border-l-2 border-blue-500 pl-6 pb-6 last:pb-0 relative"
                >
                    <div
                        class="absolute -left-2 top-0 w-4 h-4 bg-blue-500 rounded-full"
                    ></div>
                    <h4 class="text-lg font-bold text-white mb-1">
                        {{ exp.position }}
                    </h4>
                    <p class="text-blue-400 font-medium mb-2">
                        {{ exp.company }} · {{ exp.years }}
                    </p>
                    <p
                        v-if="exp.description"
                        class="text-slate-300 leading-relaxed"
                    >
                        {{ exp.description }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
