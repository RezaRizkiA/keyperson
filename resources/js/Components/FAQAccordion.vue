<script setup>
import { ref } from "vue";
import { ChevronDownIcon } from "lucide-vue-next";

defineProps({
    items: {
        type: Array,
        required: true,
        // Expected format: [{ question: String, answer: String }, ...]
    },
    defaultOpen: {
        type: Number,
        default: null,
    },
});

const openIndex = ref(null);

const toggle = (index) => {
    openIndex.value = openIndex.value === index ? null : index;
};
</script>

<template>
    <div class="space-y-4">
        <div
            v-for="(item, index) in items"
            :key="index"
            class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-2xl overflow-hidden transition-all duration-300"
            :class="
                openIndex === index
                    ? 'shadow-[0_0_30px_rgba(59,130,246,0.15)]'
                    : ''
            "
        >
            <!-- Question -->
            <button
                @click="toggle(index)"
                class="w-full flex items-center justify-between p-6 text-left hover:bg-slate-800/80 transition-colors group"
            >
                <span
                    class="font-bold text-lg text-white pr-4 group-hover:text-blue-400 transition-colors"
                >
                    {{ item.question }}
                </span>
                <ChevronDownIcon
                    class="w-6 h-6 text-blue-400 transition-transform duration-300 shrink-0"
                    :class="openIndex === index ? 'rotate-180' : ''"
                />
            </button>

            <!-- Answer -->
            <transition
                enter-active-class="transition-all duration-300 ease-out"
                enter-from-class="opacity-0 max-h-0"
                enter-to-class="opacity-100 max-h-[500px]"
                leave-active-class="transition-all duration-300 ease-in"
                leave-from-class="opacity-100 max-h-[500px]"
                leave-to-class="opacity-0 max-h-0"
            >
                <div v-show="openIndex === index" class="overflow-hidden">
                    <div class="px-6 pb-6 pt-0">
                        <div
                            class="text-slate-300 leading-relaxed border-t border-slate-700/50 pt-4"
                        >
                            {{ item.answer }}
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </div>
</template>
