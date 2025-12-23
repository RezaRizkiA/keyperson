<script setup>
import { ref, watch, computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import { CheckCircle2, AlertTriangle, XCircle, Info, X } from "lucide-vue-next";

const page = usePage();
const show = ref(false);
const message = ref("");
const type = ref("success");

const flashSuccess = computed(() => page.props.flash?.success);
const flashError = computed(() => page.props.flash?.error);

// Show toast function
const showToast = (msg, msgType) => {
    message.value = msg;
    type.value = msgType;
    show.value = true;

    setTimeout(() => {
        show.value = false;
        if (msgType === "success" && page.props.flash.success)
            page.props.flash.success = null;
        if (msgType === "error" && page.props.flash.error)
            page.props.flash.error = null;
    }, 5000); // Extended to 5 seconds
};

// Watch for flash messages from backend
watch(
    [flashSuccess, flashError],
    ([newSuccess, newError]) => {
        if (newSuccess) {
            showToast(newSuccess, "success");
        } else if (newError) {
            showToast(newError, "error");
        }
    },
    { immediate: true }
);

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

const titleText = {
    success: "Success",
    error: "Error",
    warning: "Warning",
    info: "Information",
};
</script>

<template>
    <!-- Toast positioned at top-center -->
    <Transition
        enter-active-class="transition ease-out duration-300"
        enter-from-class="opacity-0 translate-y-2"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 translate-y-2"
    >
        <div
            v-if="show"
            :class="[
                'fixed top-4 left-1/2 transform -translate-x-1/2 z-50 min-w-[400px] max-w-md backdrop-blur-sm border-2 rounded-xl shadow-2xl p-4',
                toastClasses[type],
            ]"
        >
            <div class="flex items-start gap-3">
                <!-- Icon -->
                <component
                    :is="iconComponent[type]"
                    :class="['w-6 h-6 shrink-0', iconClasses[type]]"
                />

                <!-- Content -->
                <div class="flex-1 min-w-0">
                    <h4 class="font-bold text-base mb-1">
                        {{ titleText[type] }}
                    </h4>
                    <p class="text-sm opacity-90">{{ message }}</p>
                </div>

                <!-- Close Button -->
                <button
                    @click="show = false"
                    class="shrink-0 text-current opacity-60 hover:opacity-100 transition-opacity"
                >
                    <X class="w-5 h-5" />
                </button>
            </div>
        </div>
    </Transition>
</template>
