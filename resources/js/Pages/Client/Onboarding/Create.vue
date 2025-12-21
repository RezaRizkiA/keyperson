<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import BaseSelect from "@/Components/BaseSelect.vue";

import {
  Building2,
  Users,
  Globe,
  MapPin,
  FileText,
  ImagePlus,
  UploadCloud,
  X,
  Loader2,
  CheckCircle2,
  Briefcase,
  Stamp,
} from "lucide-vue-next";

// 1. Menerima Data dari Controller (Source of Truth)
const props = defineProps({
  options: Object, // Berisi types, employee_counts, industries
  user_defaults: Object, // Data nama/email user login (opsional dipake)
});

// 2. Inisialisasi Form dengan Inertia useForm
// Data awal null/kosong agar reactive
const form = useForm({
  type: "",
  company_name: "",
  industry: "",
  employee_count: "",
  address: "",
  website: "",
  about: "",
  logo: null, // Akan diisi object File
  cover_image: null, // Akan diisi object File
});

// 3. State untuk Preview Gambar (UX Enhancement)
const logoPreview = ref(null);
const coverPreview = ref(null);

// Helper: Handle File Selection & Preview
const handleFileUpload = (event, fieldName, previewRef) => {
  const file = event.target.files[0];
  if (!file) return;

  // Validasi client-side sederhana untuk tipe file gambar
  if (!file.type.match("image.*")) {
    alert("Mohon pilih file gambar (JPG, PNG, WEBP)");
    return;
  }

  // Set file ke form inertia
  form[fieldName] = file;

  // Buat preview gambar lokal
  const reader = new FileReader();
  reader.onload = e => {
    previewRef.value = e.target.result;
  };
  reader.readAsDataURL(file);
};

// Helper: Hapus File yang dipilih
const removeFile = (fieldName, previewRef) => {
  form[fieldName] = null;
  previewRef.value = null;
  // Reset input file element value agar bisa pilih file yang sama lagi jika perlu
  const inputId = fieldName === "logo" ? "logo-upload" : "cover-upload";
  if (document.getElementById(inputId))
    document.getElementById(inputId).value = "";
};

// 4. Submit Form
const submit = () => {
  // Kita gunakan post karena ada file upload.
  // forceFormData: true memastikan data dikirim sebagai multipart/form-data
  form.post(route("client_onboarding.store"), {
    preserveScroll: true, // Agar tidak scroll ke atas jika ada error
    forceFormData: true,
    onSuccess: () => {
      // Sukses akan diredirect oleh controller,
      // Toast sukses akan muncul otomatis via Layout watcher.
    },
    onError: errors => {
      // Error akan muncul otomatis di bawah input masing-masing
      // Kita bisa tambahkan logic scroll ke error pertama jika form sangat panjang
      console.error("Validation Errors:", errors);
    },
  });
};
</script>

<template>
  <div
    class="min-h-screen bg-slate-50/50 py-12 px-4 sm:px-6 lg:px-8 flex justify-center">
    <div
      class="max-w-4xl w-full space-y-8 bg-white p-8 sm:p-10 rounded-3xl shadow-xl border border-slate-200/60">
      <div class="text-center border-b border-slate-100 pb-8">
        <div
          class="mx-auto h-14 w-14 bg-violet-50 rounded-2xl flex items-center justify-center mb-4 border border-violet-100">
          <Building2 class="h-7 w-7 text-violet-600" />
        </div>
        <h2
          class="text-3xl font-display font-bold text-slate-900 tracking-tight">
          Buat Profil Perusahaan
        </h2>
        <p
          class="mt-3 text-base text-slate-500 max-w-lg mx-auto leading-relaxed">
          Lengkapi identitas profesional organisasi Anda untuk membangun
          kepercayaan dengan para Expert.
        </p>
      </div>

      <form @submit.prevent="submit" class="space-y-10 mt-8">
        <div class="space-y-6">
          <h3
            class="text-lg font-bold text-slate-800 flex items-center gap-2.5">
            <div class="h-6 w-1 bg-violet-500 rounded-full"></div>
            Identitas Utama
          </h3>

          <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-2">
            <div class="col-span-2 sm:col-span-1">
              <label
                for="company_name"
                class="block text-sm font-semibold text-slate-700 mb-2">
                Nama Perusahaan / Instansi <span class="text-red-500">*</span>
              </label>
              <div class="relative group">
                <div
                  class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                  <Building2
                    class="h-5 w-5 text-slate-400 group-focus-within:text-violet-500 transition-colors" />
                </div>
                <input
                  v-model="form.company_name"
                  type="text"
                  id="company_name"
                  class="pl-11 block w-full h-11 rounded-xl border-slate-200 bg-slate-50 focus:bg-white shadow-sm focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 sm:text-sm transition-all duration-200"
                  :class="{
                    'border-red-300 focus:border-red-500 focus:ring-red-200 bg-red-50/30':
                      form.errors.company_name,
                  }"
                  placeholder="Contoh: PT Teknologi Maju Bersama" />
              </div>
              <p
                v-if="form.errors.company_name"
                class="mt-1.5 text-xs text-red-500 font-medium flex items-center gap-1">
                {{ form.errors.company_name }}
              </p>
            </div>

            <div class="col-span-2 sm:col-span-1">
              <label
                for="type"
                class="block text-sm font-semibold text-slate-700 mb-2">
                Tipe Entitas <span class="text-red-500">*</span>
              </label>
              <div class="relative group">
                <BaseSelect
                  v-model="form.type"
                  :options="props.options.types"
                  placeholder="Pilih Tipe Organisasi"
                  :error="form.errors.type">
                  <template #icon>
                    <Stamp class="h-5 w-5 text-slate-400 transition-colors" />
                  </template>
                </BaseSelect>
              </div>
              <p
                v-if="form.errors.type"
                class="mt-1.5 text-xs text-red-500 font-medium">
                {{ form.errors.type }}
              </p>
            </div>
          </div>
        </div>

        <div class="space-y-6 pt-8 border-t border-slate-100">
          <h3
            class="text-lg font-bold text-slate-800 flex items-center gap-2.5">
            <div class="h-6 w-1 bg-violet-500 rounded-full"></div>
            Detail Operasional
          </h3>

          <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-2">
            <div>
              <label
                for="industry"
                class="block text-sm font-semibold text-slate-700 mb-2">
                Industri <span class="text-red-500">*</span>
              </label>
              <div class="relative group">
                <BaseSelect
                  v-model="form.industry"
                  :options="props.options.industries"
                  placeholder="Pilih Kategori Industri"
                  :error="form.errors.industry">
                  <template #icon>
                    <Briefcase
                      class="h-5 w-5 text-slate-400 transition-colors" />
                  </template>
                </BaseSelect>
              </div>
              <p
                v-if="form.errors.industry"
                class="mt-1.5 text-xs text-red-500 font-medium">
                {{ form.errors.industry }}
              </p>
            </div>

            <div>
              <label
                for="employee_count"
                class="block text-sm font-semibold text-slate-700 mb-2">
                Jumlah Karyawan <span class="text-red-500">*</span>
              </label>
              <div class="relative group">
                <BaseSelect
                  v-model="form.employee_count"
                  :options="props.options.employee_counts"
                  placeholder="Pilih Ukuran Perusahaan"
                  :error="form.errors.employee_count">
                  <template #icon>
                    <Users class="h-5 w-5 text-slate-400 transition-colors" />
                  </template>
                </BaseSelect>
              </div>
              <p
                v-if="form.errors.employee_count"
                class="mt-1.5 text-xs text-red-500 font-medium">
                {{ form.errors.employee_count }}
              </p>
            </div>

            <div class="col-span-2">
              <label
                for="website"
                class="block text-sm font-semibold text-slate-700 mb-2">
                Website Resmi
                <span
                  class="text-slate-400 text-xs font-normal ml-1 bg-slate-100 px-2 py-0.5 rounded-full"
                  >(Opsional)</span
                >
              </label>
              <div class="relative group">
                <div
                  class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                  <Globe
                    class="h-5 w-5 text-slate-400 group-focus-within:text-violet-500 transition-colors" />
                </div>
                <input
                  v-model="form.website"
                  type="url"
                  id="website"
                  class="pl-11 block w-full h-11 rounded-xl border-slate-200 bg-slate-50 focus:bg-white shadow-sm focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 sm:text-sm transition-all duration-200"
                  :class="{
                    'border-red-300 focus:border-red-500 bg-red-50/30':
                      form.errors.website,
                  }"
                  placeholder="https://www.perusahaan-anda.com" />
              </div>
              <p class="mt-1.5 text-xs text-slate-500">
                Gunakan format lengkap dengan https://
              </p>
              <p
                v-if="form.errors.website"
                class="mt-1.5 text-xs text-red-500 font-medium">
                {{ form.errors.website }}
              </p>
            </div>

            <div class="col-span-2">
              <label
                for="address"
                class="block text-sm font-semibold text-slate-700 mb-2">
                Alamat Kantor Lengkap <span class="text-red-500">*</span>
              </label>
              <div class="relative group">
                <div class="absolute top-3.5 left-3.5 pointer-events-none">
                  <MapPin
                    class="h-5 w-5 text-slate-400 group-focus-within:text-violet-500 transition-colors" />
                </div>
                <textarea
                  v-model="form.address"
                  id="address"
                  rows="3"
                  class="pl-11 py-3 block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white shadow-sm focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 sm:text-sm transition-all duration-200 resize-none"
                  :class="{
                    'border-red-300 focus:border-red-500 bg-red-50/30':
                      form.errors.address,
                  }"
                  placeholder="Jalan Sudirman No. 1, Jakarta Selatan..."></textarea>
              </div>
              <p
                v-if="form.errors.address"
                class="mt-1.5 text-xs text-red-500 font-medium">
                {{ form.errors.address }}
              </p>
            </div>
          </div>
        </div>

        <div class="space-y-6 pt-8 border-t border-slate-100">
          <h3
            class="text-lg font-bold text-slate-800 flex items-center gap-2.5">
            <div class="h-6 w-1 bg-violet-500 rounded-full"></div>
            Branding & Visual
          </h3>

          <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div>
              <label class="block text-sm font-semibold text-slate-700 mb-2">
                Logo Perusahaan
                <span class="text-slate-400 text-xs font-normal ml-1"
                  >(Square, Opsional)</span
                >
              </label>

              <div class="flex items-start gap-4">
                <div v-if="logoPreview" class="relative group shrink-0">
                  <img
                    :src="logoPreview"
                    class="h-24 w-24 rounded-2xl object-cover border-2 border-slate-200 shadow-sm"
                    alt="Logo Preview" />
                  <button
                    type="button"
                    @click="removeFile('logo', logoPreview)"
                    class="absolute -top-2 -right-2 bg-white text-red-500 border border-slate-200 p-1 rounded-full shadow-md hover:bg-red-50 transition-all"
                    title="Hapus logo">
                    <X class="w-3.5 h-3.5" />
                  </button>
                </div>

                <div class="flex-1">
                  <label
                    for="logo-upload"
                    class="flex justify-center px-6 pt-5 pb-6 border-2 border-slate-200 border-dashed rounded-2xl cursor-pointer hover:border-violet-400 hover:bg-violet-50/50 transition-all group bg-slate-50/50"
                    :class="{
                      'border-red-300 bg-red-50/30': form.errors.logo,
                    }">
                    <div class="space-y-2 text-center">
                      <div
                        class="mx-auto h-10 w-10 bg-white rounded-full flex items-center justify-center border border-slate-100 shadow-sm group-hover:scale-110 transition-transform">
                        <ImagePlus
                          class="h-5 w-5 text-slate-400 group-hover:text-violet-500" />
                      </div>
                      <div class="text-sm text-slate-600">
                        <span
                          class="font-semibold text-violet-600 group-hover:underline"
                          >Upload file</span
                        >
                        <span class="text-slate-400"> atau drag & drop</span>
                      </div>
                      <p class="text-xs text-slate-400">
                        PNG, JPG, WEBP (Max 5MB)
                      </p>
                    </div>
                    <input
                      id="logo-upload"
                      type="file"
                      class="hidden"
                      accept="image/png, image/jpeg, image/jpg, image/webp"
                      @change="e => handleFileUpload(e, 'logo', logoPreview)" />
                  </label>
                  <p
                    v-if="form.errors.logo"
                    class="mt-1.5 text-xs text-red-500 font-medium">
                    {{ form.errors.logo }}
                  </p>
                </div>
              </div>
            </div>

            <div class="col-span-2 sm:col-span-1">
              <label class="block text-sm font-semibold text-slate-700 mb-2">
                Banner Profil
                <span
                  class="text-slate-400 text-xs font-normal ml-1 bg-slate-100 px-2 py-0.5 rounded-full"
                  >(Opsional)</span
                >
              </label>
              <div class="flex flex-col gap-4">
                <div v-if="coverPreview" class="relative group w-full h-32">
                  <img
                    :src="coverPreview"
                    class="w-full h-full rounded-2xl object-cover border-2 border-slate-200 shadow-sm"
                    alt="Cover Preview" />
                  <button
                    type="button"
                    @click="removeFile('cover_image', coverPreview)"
                    class="absolute -top-2 -right-2 bg-white text-red-500 border border-slate-200 p-1 rounded-full shadow-md hover:bg-red-50 transition-all">
                    <X class="w-3.5 h-3.5" />
                  </button>
                </div>

                <label
                  v-if="!coverPreview"
                  for="cover-upload"
                  class="flex justify-center px-6 pt-5 pb-6 border-2 border-slate-200 border-dashed rounded-2xl cursor-pointer hover:border-violet-400 hover:bg-violet-50/50 transition-all group bg-slate-50/50"
                  :class="{
                    'border-red-300 bg-red-50/30': form.errors.cover_image,
                  }">
                  <div class="space-y-2 text-center">
                    <div
                      class="mx-auto h-10 w-10 bg-white rounded-full flex items-center justify-center border border-slate-100 shadow-sm group-hover:scale-110 transition-transform">
                      <UploadCloud
                        class="h-5 w-5 text-slate-400 group-hover:text-violet-500" />
                    </div>
                    <div class="text-sm text-slate-600">
                      <span
                        class="font-semibold text-violet-600 group-hover:underline"
                        >Upload banner</span
                      >
                    </div>
                    <p class="text-xs text-slate-400">Rekomendasi 1200x300px</p>
                  </div>
                  <input
                    id="cover-upload"
                    type="file"
                    class="hidden"
                    accept="image/png, image/jpeg, image/jpg, image/webp"
                    @change="
                      e => handleFileUpload(e, 'cover_image', coverPreview)
                    " />
                </label>
                <p
                  v-if="form.errors.cover_image"
                  class="mt-1.5 text-xs text-red-500 font-medium">
                  {{ form.errors.cover_image }}
                </p>
              </div>
            </div>

            <div class="col-span-2">
              <label
                for="about"
                class="block text-sm font-semibold text-slate-700 mb-2">
                Tentang Perusahaan (Overview)
                <span class="text-red-500">*</span>
              </label>
              <div class="relative group">
                <div class="absolute top-3.5 left-3.5 pointer-events-none">
                  <FileText
                    class="h-5 w-5 text-slate-400 group-focus-within:text-violet-500 transition-colors" />
                </div>
                <textarea
                  v-model="form.about"
                  id="about"
                  rows="5"
                  class="pl-11 py-3 block w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white shadow-sm focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 sm:text-sm transition-all duration-200 resize-none"
                  :class="{
                    'border-red-300 focus:border-red-500 bg-red-50/30':
                      form.errors.about,
                  }"
                  placeholder="Ceritakan profil, visi misi, atau budaya kerja perusahaan Anda..."></textarea>
              </div>
              <div class="flex justify-between mt-2">
                <p
                  v-if="form.errors.about"
                  class="text-xs text-red-500 font-medium">
                  {{ form.errors.about }}
                </p>
                <p
                  class="text-xs text-slate-400 ml-auto font-medium"
                  :class="{ 'text-violet-600': form.about.length >= 50 }">
                  {{ form.about.length }} / 50 Karakter Min.
                </p>
              </div>
            </div>
          </div>
        </div>

        <div
          class="pt-8 border-t border-slate-100 flex flex-col-reverse sm:flex-row items-center justify-between gap-4">
          <p class="text-sm text-slate-500">
            <span class="text-red-500 font-bold">*</span> Wajib diisi
          </p>
          <button
            type="submit"
            :disabled="form.processing"
            class="w-full sm:w-auto inline-flex justify-center items-center gap-2.5 py-3.5 px-8 border border-transparent shadow-lg shadow-violet-200 text-sm font-bold rounded-xl text-white bg-violet-600 hover:bg-violet-700 focus:outline-none focus:ring-4 focus:ring-violet-500/20 transition-all transform active:scale-95 disabled:opacity-70 disabled:cursor-not-allowed disabled:transform-none">
            <Loader2 v-if="form.processing" class="w-5 h-5 animate-spin" />
            <span v-if="!form.processing">Simpan Profil & Lanjutkan</span>
            <span v-else>Memproses Data...</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
