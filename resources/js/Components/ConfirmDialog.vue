<script setup>
import { ref } from "vue";
import { AlertTriangle } from "lucide-vue-next";

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    title: {
        type: String,
        default: "Confirm Action",
    },
    message: {
        type: String,
        required: true,
    },
    confirmText: {
        type: String,
        default: "Yes, Proceed",
    },
    cancelText: {
        type: String,
        default: "Cancel",
    },
    dangerous: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["confirm", "cancel", "close"]);

const handleConfirm = () => {
    emit("confirm");
    emit("close");
};

const handleCancel = () => {
    emit("cancel");
    emit("close");
};
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="show"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
                @click.self="handleCancel"
            >
                <Transition
                    enter-active-class="transition ease-out duration-300"
                    enter-from-class="opacity-0 scale-95"
                    enter-to-class="opacity-100 scale-100"
                    leave-active-class="transition ease-in duration-200"
                    leave-from-class="opacity-100 scale-100"
                    leave-to-class="opacity-0 scale-95"
                >
                    <div
                        v-if="show"
                        class="bg-slate-800 rounded-2xl shadow-2xl border-2 border-slate-700/50 max-w-md w-full p-6"
                    >
                        <!-- Icon & Title -->
                        <div class="flex items-start gap-4 mb-4">
                            <div
                                :class="[
                                    'flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center',
                                    dangerous
                                        ? 'bg-red-500/20 text-red-400'
                                        : 'bg-yellow-500/20 text-yellow-400',
                                ]"
                            >
                                <AlertTriangle class="w-6 h-6" />
                            </div>
                            <div class="flex-1">
                                <h3
                                    class="text-xl font-bold text-slate-100 mb-2"
                                >
                                    {{ title }}
                                </h3>
                                <p
                                    class="text-sm text-slate-300 leading-relaxed"
                                >
                                    {{ message }}
                                </p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-3 mt-6">
                            <button
                                @click="handleCancel"
                                class="flex-1 px-4 py-2.5 bg-slate-700 hover:bg-slate-600 text-slate-200 font-medium rounded-lg transition-colors"
                            >
                                {{ cancelText }}
                            </button>
                            <button
                                @click="handleConfirm"
                                :class="[
                                    'flex-1 px-4 py-2.5 font-medium rounded-lg transition-colors',
                                    dangerous
                                        ? 'bg-red-500 hover:bg-red-600 text-white'
                                        : 'bg-blue-500 hover:bg-blue-600 text-white',
                                ]"
                            >
                                {{ confirmText }}
                            </button>
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>
