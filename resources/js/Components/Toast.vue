<script setup>
import { ref, watch, onMounted } from "vue";
import { CheckCircle2, AlertTriangle, XCircle, Info, X } from "lucide-vue-next";

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    type: {
        type: String,
        default: "success", // success, error, warning, info
        validator: (value) =>
            ["success", "error", "warning", "info"].includes(value),
    },
    title: {
        type: String,
        required: true,
    },
    message: {
        type: String,
        default: "",
    },
    duration: {
        type: Number,
        default: 5000, // 5 seconds
    },
    position: {
        type: String,
        default: "top-center", // top-center, top-right, bottom-center, etc
        validator: (value) =>
            [
                "top-center",
                "top-right",
                "bottom-center",
                "bottom-right",
            ].includes(value),
    },
});

const emit = defineEmits(["close"]);

const visible = ref(false);
let timeout = null;

watch(
    () => props.show,
    (newVal) => {
        if (newVal) {
            visible.value = true;
            if (props.duration > 0) {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    close();
                }, props.duration);
            }
        } else {
            visible.value = false;
        }
    }
);

const close = () => {
    visible.value = false;
    emit("close");
};

const iconComponent = {
    success: CheckCircle2,
    error: XCircle,
    warning: AlertTriangle,
    info: Info,
};

const toastClasses = {
    success: "bg-green-900/90 border-green-500/50 text-green-100",
    error: "bg-red-900/90 border-red-500/50 text-red-100",
    warning: "bg-yellow-900/90 border-yellow-500/50 text-yellow-100",
    info: "bg-blue-900/90 border-blue-500/50 text-blue-100",
};

const iconClasses = {
    success: "text-green-400",
    error: "text-red-400",
    warning: "text-yellow-400",
    info: "text-blue-400",
};

const positionClasses = {
    "top-center": "top-4 left-1/2 transform -translate-x-1/2",
    "top-right": "top-4 right-4",
    "bottom-center": "bottom-4 left-1/2 transform -translate-x-1/2",
    "bottom-right": "bottom-4 right-4",
};
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition ease-out duration-300"
            enter-from-class="opacity-0 translate-y-2"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition ease-in duration-200"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 translate-y-2"
        >
            <div
                v-if="visible"
                :class="[
                    'fixed z-50 min-w-[400px] max-w-md backdrop-blur-sm border-2 rounded-xl shadow-2xl p-4',
                    toastClasses[type],
                    positionClasses[position],
                ]"
            >
                <div class="flex items-start gap-3">
                    <!-- Icon -->
                    <component
                        :is="iconComponent[type]"
                        :class="['w-6 h-6 flex-shrink-0', iconClasses[type]]"
                    />

                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <h4 class="font-bold text-base mb-1">{{ title }}</h4>
                        <p v-if="message" class="text-sm opacity-90">
                            {{ message }}
                        </p>
                    </div>

                    <!-- Close Button -->
                    <button
                        @click="close"
                        class="flex-shrink-0 text-current opacity-60 hover:opacity-100 transition-opacity"
                    >
                        <X class="w-5 h-5" />
                    </button>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>
