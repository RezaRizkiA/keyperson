<script setup>
import { computed } from "vue";
import {
  Listbox,
  ListboxButton,
  ListboxOptions,
  ListboxOption,
  ListboxLabel,
} from "@headlessui/vue";
import { ChevronsUpDown, Check } from "lucide-vue-next";

const props = defineProps({
  modelValue: [String, Number, Object],
  options: {
    type: Array,
    default: () => [],
  },
  placeholder: {
    type: String,
    default: "Pilih opsi",
  },
  error: String,
});

const emit = defineEmits(["update:modelValue"]);

// Normalisasi opsi: Bisa terima array string ['A', 'B'] atau object [{value:'a', label:'A'}]
const normalizedOptions = computed(() => {
  return props.options.map(opt => {
    if (typeof opt === "object" && opt !== null) {
      return { value: opt.value, label: opt.label || opt.value };
    }
    return { value: opt, label: opt };
  });
});

// Cari label dari value yang terpilih untuk ditampilkan di tombol
const selectedLabel = computed(() => {
  const selected = normalizedOptions.value.find(
    opt => opt.value === props.modelValue
  );
  return selected ? selected.label : null;
});
</script>

<template>
  <div class="relative">
    <Listbox
      :model-value="props.modelValue"
      @update:model-value="value => emit('update:modelValue', value)">
      <div class="relative mt-1">
        <div
          v-if="$slots.icon"
          class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none z-10">
          <slot name="icon" />
        </div>

        <ListboxButton
          class="relative w-full h-11 cursor-pointer rounded-xl py-2 pr-10 text-left shadow-sm focus:outline-none sm:text-sm transition-all duration-200 border"
          :class="[
            // Logika Padding: Kalau ada icon pakai pl-11, kalau tidak pakai pl-3
            $slots.icon ? 'pl-11' : 'pl-3',

            // Logika Error/Normal
            props.error
              ? 'bg-red-50/30 border-red-300 focus:border-red-500 focus:ring-4 focus:ring-red-200'
              : 'bg-slate-50 border-slate-200 hover:bg-white focus:bg-white focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10',
          ]">
          <span
            class="block truncate"
            :class="selectedLabel ? 'text-slate-900' : 'text-slate-400'">
            {{ selectedLabel || props.placeholder }}
          </span>
          <span
            class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
            <ChevronsUpDown class="h-4 w-4 text-slate-400" aria-hidden="true" />
          </span>
        </ListboxButton>

        <transition
          leave-active-class="transition duration-100 ease-in"
          leave-from-class="opacity-100"
          leave-to-class="opacity-0">
          <ListboxOptions
            class="absolute z-50 mt-1 max-h-60 w-full overflow-auto rounded-xl bg-white py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm custom-scrollbar">
            <ListboxOption
              v-slot="{ active, selected }"
              v-for="option in normalizedOptions"
              :key="option.value"
              :value="option.value"
              as="template">
              <li
                :class="[
                  active ? 'bg-violet-50 text-violet-900' : 'text-slate-900',
                  'relative cursor-pointer select-none py-2.5 pl-10 pr-4 transition-colors',
                ]">
                <span
                  :class="[
                    selected ? 'font-semibold text-violet-700' : 'font-normal',
                    'block truncate',
                  ]">
                  {{ option.label }}
                </span>
                <span
                  v-if="selected"
                  class="absolute inset-y-0 left-0 flex items-center pl-3 text-violet-600">
                  <Check class="h-4 w-4" aria-hidden="true" />
                </span>
              </li>
            </ListboxOption>
          </ListboxOptions>
        </transition>
      </div>
    </Listbox>

    <p
      v-if="props.error"
      class="mt-1.5 text-xs text-red-500 font-medium flex items-center gap-1">
      {{ props.error }}
    </p>
  </div>
</template>

<style scoped>
/* Opsional: Style scrollbar agar lebih tipis & modern */
.custom-scrollbar::-webkit-scrollbar {
  width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: #cbd5e1;
  border-radius: 20px;
}
</style>
