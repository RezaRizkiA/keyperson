<script setup>
import { ref } from 'vue';

// Menerima data expertises dan value v-model dari parent
const props = defineProps({
    expertises: {
        type: Array,
        default: () => []
    },
    modelValue: {
        type: Array,
        default: () => []
    },
    label: {
        type: String,
        default: 'Choose Specialist'
    }
});

// Emit untuk update v-model ke parent
const emit = defineEmits(['update:modelValue']);

// State Accordion
const openAccordions = ref({});

const toggleAccordion = (parentId, childId) => {
    openAccordions.value[parentId] = openAccordions.value[parentId] === childId ? null : childId;
};

// Handle perubahan checkbox
const handleCheckboxChange = (id, checked) => {
    let newValue = [...props.modelValue];

    if (checked) {
        newValue.push(id);
    } else {
        newValue = newValue.filter(item => item !== id);
    }

    // Kirim data terbaru ke parent
    emit('update:modelValue', newValue);
};
</script>

<template>
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm sticky top-24">
        <h3 class="font-bold text-slate-900 mb-2">{{ label }}</h3>
        <p class="text-xs text-slate-500 mb-4">Select the categories relevant to you.</p>

        <div class="max-h-[600px] overflow-y-auto pr-2 custom-scrollbar">

            <div v-for="parent in expertises" :key="parent.id" class="mb-6">
                <h4
                    class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 border-b border-slate-100 pb-1">
                    {{ parent.name }} <span class="text-red-500">*</span>
                </h4>

                <div class="space-y-2">
                    <div v-for="child in parent.childrens_recursive" :key="child.id"
                        class="border border-slate-100 rounded-lg overflow-hidden transition-all duration-200"
                        :class="openAccordions[parent.id] === child.id ? 'bg-slate-50 border-violet-200' : 'bg-white'">
                        <div @click="toggleAccordion(parent.id, child.id)"
                            class="flex items-center px-3 py-2.5 cursor-pointer hover:bg-slate-50 select-none">
                            <div class="w-4 h-4 rounded-full border flex items-center justify-center mr-3 transition-colors"
                                :class="openAccordions[parent.id] === child.id ? 'border-violet-600' : 'border-slate-300'">
                                <div v-if="openAccordions[parent.id] === child.id"
                                    class="w-2 h-2 rounded-full bg-violet-600"></div>
                            </div>
                            <span class="text-sm font-medium text-slate-700">{{ child.name }}</span>
                        </div>

                        <div v-show="openAccordions[parent.id] === child.id"
                            class="px-3 pb-3 pt-1 pl-10 border-t border-slate-100/50">
                            <div class="space-y-2 mt-2">
                                <div v-for="grandchild in child.childrens_recursive" :key="grandchild.id"
                                    class="flex items-center">
                                    <input :id="`exp-${grandchild.id}`" type="checkbox" :value="grandchild.id"
                                        :checked="modelValue.includes(grandchild.id)"
                                        @change="(e) => handleCheckboxChange(grandchild.id, e.target.checked)"
                                        class="h-4 w-4 text-violet-600 focus:ring-violet-500 border-gray-300 rounded cursor-pointer">
                                    <label :for="`exp-${grandchild.id}`"
                                        class="ml-2 text-sm text-slate-600 cursor-pointer select-none">
                                        {{ grandchild.name }}
                                    </label>
                                </div>

                                <div v-if="!child.childrens_recursive || child.childrens_recursive.length === 0"
                                    class="text-xs text-slate-400 italic">
                                    No sub-skills available.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 5px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #e2e8f0;
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #cbd5e1;
}
</style>