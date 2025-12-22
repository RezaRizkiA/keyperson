<script setup>
import { Award } from "lucide-vue-next";

defineProps({
    licenses: {
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
            <Award class="w-4 h-4 inline-block mr-1 -mt-0.5" />
            LICENSES & CERTIFICATIONS
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div
                v-for="(license, index) in licenses"
                :key="index"
                class="bg-slate-900/50 backdrop-blur-sm border border-blue-500/20 rounded-2xl p-6 hover:border-blue-500/60 transition-all"
            >
                <div class="flex items-start gap-4">
                    <div
                        class="w-12 h-12 bg-blue-600/20 border border-blue-500/30 rounded-lg flex items-center justify-center shrink-0"
                    >
                        <Award class="w-6 h-6 text-blue-400" />
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-white mb-1">
                            {{ license.certification }}
                        </h4>
                        <p v-if="license.file" class="text-sm text-slate-400">
                            Verified
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
