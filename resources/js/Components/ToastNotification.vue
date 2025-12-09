<script setup>
import { onMounted, ref, watch, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { CheckCircle, AlertCircle, X } from 'lucide-vue-next';

// Ambil data flash dari Inertia
const page = usePage();
const show = ref(false);
const message = ref('');
const type = ref('success'); // 'success' or 'error'

// Watcher: Memantau jika ada flash message baru dari Backend
watch(() => page.props.flash, (newFlash) => {
    if (newFlash.success) {
        showToast(newFlash.success, 'success');
    } else if (newFlash.error) {
        showToast(newFlash.error, 'error');
    }
}, { deep: true });

// Fungsi menampilkan toast
const showToast = (msg, msgType) => {
    message.value = msg;
    type.value = msgType;
    show.value = true;

    // Auto close setelah 4 detik
    setTimeout(() => {
        show.value = false;
        // Reset flash di Inertia agar tidak muncul lagi saat refresh (opsional tapi bagus)
        page.props.flash = {};
    }, 4000);
};

// Cek saat pertama kali dimuat (jika ada redirect dengan flash)
onMounted(() => {
    if (page.props.flash.success) showToast(page.props.flash.success, 'success');
    if (page.props.flash.error) showToast(page.props.flash.error, 'error');
});
</script>

<template>
    <div class="fixed top-24 right-5 z-50 flex flex-col gap-2 w-full max-w-sm pointer-events-none">

        <Transition enter-active-class="transform ease-out duration-300 transition"
            enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
            leave-active-class="transition ease-in duration-100" leave-from-class="opacity-100"
            leave-to-class="opacity-0">
            <div v-if="show"
                class="pointer-events-auto w-full max-w-sm overflow-hidden rounded-xl bg-white shadow-lg ring-1 ring-black ring-opacity-5 p-4 flex items-start gap-4">

                <div class="flex-shrink-0">
                    <CheckCircle v-if="type === 'success'" class="h-6 w-6 text-green-500" />
                    <AlertCircle v-else class="h-6 w-6 text-red-500" />
                </div>

                <div class="flex-1 pt-0.5">
                    <p class="text-sm font-bold text-slate-900">
                        {{ type === 'success' ? 'Success!' : 'Error!' }}
                    </p>
                    <p class="mt-1 text-sm text-slate-500 leading-relaxed">
                        {{ message }}
                    </p>
                </div>

                <div class="ml-4 flex flex-shrink-0">
                    <button @click="show = false" type="button"
                        class="inline-flex rounded-md bg-white text-slate-400 hover:text-slate-500 focus:outline-none">
                        <X class="h-5 w-5" />
                    </button>
                </div>

            </div>
        </Transition>

    </div>
</template>