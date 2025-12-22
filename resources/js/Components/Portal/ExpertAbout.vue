<script setup>
defineProps({
    about: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <div>
        <h2
            class="text-sm font-bold text-blue-400 uppercase tracking-wider mb-4"
        >
            ABOUT
        </h2>
        <div
            class="bg-slate-900/50 backdrop-blur-sm border border-blue-500/20 rounded-2xl p-8"
        >
            <div class="text-slate-300 leading-relaxed" v-html="about"></div>
        </div>
    </div>
</template>
