<script setup>
import { ref, onMounted, onUnmounted } from "vue";

const props = defineProps({
    sections: {
        type: Array,
        required: true,
        // Expected: [{ id: String, title: String }, ...]
    },
});

const activeSection = ref("");

// Scroll spy functionality
const handleScroll = () => {
    const scrollPosition = window.scrollY + 200;

    for (let i = props.sections.length - 1; i >= 0; i--) {
        const section = document.getElementById(props.sections[i].id);
        if (section && section.offsetTop <= scrollPosition) {
            activeSection.value = props.sections[i].id;
            break;
        }
    }
};

onMounted(() => {
    window.addEventListener("scroll", handleScroll);
    handleScroll();
});

onUnmounted(() => {
    window.removeEventListener("scroll", handleScroll);
});

const scrollToSection = (id) => {
    const element = document.getElementById(id);
    if (element) {
        const offset = 100;
        const elementPosition = element.offsetTop - offset;
        window.scrollTo({
            top: elementPosition,
            behavior: "smooth",
        });
    }
};
</script>

<template>
    <div class="sticky top-32">
        <div
            class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-2xl p-6"
        >
            <h3
                class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4"
            >
                Table of Contents
            </h3>
            <nav class="space-y-2">
                <button
                    v-for="section in sections"
                    :key="section.id"
                    @click="scrollToSection(section.id)"
                    class="block w-full text-left px-4 py-2 rounded-lg transition-all text-sm"
                    :class="
                        activeSection === section.id
                            ? 'bg-blue-500/20 text-blue-400 font-bold border-l-2 border-blue-500'
                            : 'text-slate-400 hover:text-white hover:bg-slate-700/50'
                    "
                >
                    {{ section.title }}
                </button>
            </nav>
        </div>
    </div>
</template>
