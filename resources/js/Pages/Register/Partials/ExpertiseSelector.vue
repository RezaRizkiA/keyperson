<script setup>
import { computed } from "vue";

const props = defineProps({
    expertises: Array,
    modelValue: {
        type: Array,
        default: () => [],
    },
});

const emit = defineEmits(["update:modelValue"]);

const selected = computed({
    get: () => props.modelValue,
    set: (val) => emit("update:modelValue", val),
});
</script>

<template>
    <div class="card border border-slate-200 rounded-2xl overflow-hidden mb-0">
        <div class="card-body p-4 max-h-[900px] overflow-y-auto space-y-6">
            <div
                v-for="category in expertises"
                :key="category.id"
                class="space-y-3"
            >
                <label class="font-bold text-slate-900 block">
                    {{ category.name }} <span class="text-red-500">*</span>
                </label>
                <div class="space-y-2 pl-2">
                    <div
                        v-for="expertise in category.childrens_recursive"
                        :key="expertise.id"
                        class="flex items-start"
                    >
                        <div class="flex items-center h-5">
                            <input
                                type="checkbox"
                                :value="expertise.id"
                                v-model="selected"
                                :id="`expert-${expertise.id}`"
                                class="w-4 h-4 text-violet-600 border-slate-300 rounded focus:ring-violet-500 transition-colors"
                            />
                        </div>
                        <div class="ml-3 text-sm">
                            <label
                                :for="`expert-${expertise.id}`"
                                class="font-medium text-slate-700 cursor-pointer"
                            >
                                {{ expertise.name }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
