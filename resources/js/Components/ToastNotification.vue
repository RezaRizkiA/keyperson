<script setup>
import { ref, watch, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { CheckCircle, AlertCircle, X } from 'lucide-vue-next';

const page = usePage();
const show = ref(false);
const message = ref('');
const type = ref('success');

const flashSuccess = computed(() => page.props.flash?.success);
const flashError = computed(() => page.props.flash?.error);

// --- PINDAHKAN FUNGSI INI KE ATAS (SEBELUM WATCH) ---
const showToast = (msg, msgType) => {
    
    message.value = msg;
    type.value = msgType;
    show.value = true;

    setTimeout(() => {
        show.value = false;
        if(msgType === 'success' && page.props.flash.success) page.props.flash.success = null;
        if(msgType === 'error' && page.props.flash.error) page.props.flash.error = null;
    }, 4000);
};

// --- BARU JALANKAN WATCH DI BAWAHNYA ---
watch([flashSuccess, flashError], ([newSuccess, newError]) => {
    if (newSuccess) {
        showToast(newSuccess, 'success');
    } else if (newError) {
        showToast(newError, 'error');
    }
}, { immediate: true });

</script>

<template>
  <div
    class="fixed top-5 right-5 z-50 flex flex-col gap-2 w-full max-w-sm pointer-events-none">
    <Transition
      enter-active-class="transform ease-out duration-300 transition"
      enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
      enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
      leave-active-class="transition ease-in duration-100"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0">
      <div
        v-if="show"
        class="pointer-events-auto w-full max-w-sm overflow-hidden rounded-xl bg-white shadow-lg ring-1 ring-black ring-opacity-5 p-4 flex items-start gap-4">
        <div class="shrink-0">
          <CheckCircle
            v-if="type === 'success'"
            class="h-6 w-6 text-green-500" />
          <AlertCircle v-else class="h-6 w-6 text-red-500" />
        </div>
        <div class="flex-1 pt-0.5">
          <p class="text-sm font-bold text-slate-900">
            {{ type === "success" ? "Success!" : "Error!" }}
          </p>
          <p class="mt-1 text-sm text-slate-500 leading-relaxed">
            {{ message }}
          </p>
        </div>
        <button
          @click="show = false"
          class="ml-4 inline-flex text-slate-400 hover:text-slate-500">
          <X class="h-5 w-5" />
        </button>
      </div>
    </Transition>
  </div>
</template>
