<script setup>
import { useForm, Head } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import { route } from "ziggy-js";
import { QuillEditor } from "@vueup/vue-quill";
import "@vueup/vue-quill/dist/vue-quill.snow.css";
import ToastNotification from "@/Components/ToastNotification.vue";

// ICONS
import {
  Plus,
  Trash2,
  UploadCloud,
  Image as ImageIcon,
  Link as LinkIcon,
  User,
  Banknote,
  Globe,
  Loader2,
  Check,
  Save,
  ChevronDown,
  ChevronRight,
  Info,
  FileText,
  X,
  AlertCircle,
} from "lucide-vue-next";

// PROPS
const props = defineProps({
  user: Object,
  expert: Object,
  categories: Array,
  isEditMode: Boolean,
});


const parseData = (data, defaultValue = []) => {
  if (!data) return defaultValue;
  try {
    return Array.isArray(data) ? data : JSON.parse(data);
  } catch (e) {
    return defaultValue;
  }
};

// --- FORM SETUP ---
const form = useForm({
  title: props.expert?.title || "",
  price: props.expert?.price || "",
  about: props.expert?.about || "",

  // Type (Checkbox Array)
  type: parseData(props.expert?.type, []),

  // REFACTOR SKILLS: Paksa jadi Number agar Vue v-model bisa membaca checkbox
  skills: props.expert?.skills
    ? props.expert.skills.map(s => Number(s.id))
    : [],

  // Experiences
  experiences: parseData(props.expert?.experiences, [
    { position: "", company: "", years: "", description: "" },
  ]),

  // Licenses
  licenses: parseData(props.expert?.licenses, [
    { certification: "", file: null },
  ]).map(item => ({
    ...item,
    file: null,
    existing_file: item.file || item.attachment,
  })),

  // Gallerys
  gallerys: parseData(props.expert?.gallerys, [{ file: null }]).map(item => ({
    ...item,
    file: null,
    existing_file: item.file || item.photos,
  })),

  socials: parseData(props.expert?.socials, [{ key: "instagram", value: "" }]),
});

// --- STATE ---
const openCategories = ref([]); // Untuk Accordion Skill
const galleryPreviews = ref({}); // Untuk Preview Gambar

// --- LOGIC AUTO OPEN ACCORDION (DEBUG MODE) ---
onMounted(() => {
  if (props.isEditMode && form.skills.length > 0) {
    props.categories.forEach(cat => {
      // Antisipasi beda naming convention (snake_case vs camelCase)
      const subs = cat.sub_categories || cat.subCategories || [];

      // Cek apakah ada skill terpilih di kategori ini
      const hasSelectedSkill = subs.some(sub => {
        if (!sub.skills) return false;

        return sub.skills.some(skill => {
          // Pastikan perbandingan tipe datanya aman (Number vs Number)
          return form.skills.includes(Number(skill.id));
        });
      });

      // Jika ada, buka accordion kategori ini
      if (hasSelectedSkill) {
        openCategories.value.push(cat.id);
      }
    });
  }
});

// --- ACTIONS ---

const toggleCategory = catId => {
  if (openCategories.value.includes(catId)) {
    openCategories.value = openCategories.value.filter(id => id !== catId);
  } else {
    openCategories.value.push(catId);
  }
};

const addExperience = () =>
  form.experiences.push({
    position: "",
    company: "",
    years: "",
    description: "",
  });
const removeExperience = index => form.experiences.splice(index, 1);

const addLicense = () => form.licenses.push({ certification: "", file: null });
const removeLicense = index => form.licenses.splice(index, 1);

const handleLicenseUpload = (e, index) => {
  const file = e.target.files[0];
  if (file) form.licenses[index].file = file;
};

const addGallery = () => form.gallerys.push({ file: null });
const removeGallery = index => form.gallerys.splice(index, 1);

const handleGalleryUpload = (e, index) => {
  const file = e.target.files[0];
  if (file) {
    form.gallerys[index].file = file;
    galleryPreviews.value[index] = URL.createObjectURL(file);
  }
};

const addSocial = () => form.socials.push({ key: "instagram", value: "" });
const removeSocial = index => form.socials.splice(index, 1);

const submit = () => {
  form.post(route("expert_onboarding.store"), {
    forceFormData: true,
    preserveScroll: true,
  });
};
</script>

<template>
  <Head title="Expert Registration" />

  <ToastNotification />

  <div class="min-h-screen bg-slate-50">
    <div
      class="bg-white border-b border-slate-200 px-6 py-4 sticky top-0 z-30 shadow-sm">
      <div class="max-w-6xl mx-auto flex justify-between items-center">
        <h1 class="text-xl font-bold text-slate-900">Expert Onboarding</h1>
        <div class="flex items-center gap-2">
          <div class="text-sm text-right hidden md:block">
            <div class="font-bold text-slate-700">{{ user.name }}</div>
            <div class="text-xs text-slate-500">{{ user.email }}</div>
          </div>
        </div>
      </div>
    </div>

    <form @submit.prevent="submit" class="max-w-7xl mx-auto p-6 md:p-10">
      <div class="grid lg:grid-cols-3 gap-8 items-start">
        <div class="lg:col-span-2 space-y-8">
          <div
            class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm">
            <div class="mb-8 pb-4 border-b border-slate-100">
              <h3 class="font-display text-lg font-bold text-slate-900">
                Professional Details
              </h3>
              <p class="text-sm text-slate-500">
                Informasi dasar tentang profesi Anda.
              </p>
            </div>

            <div class="space-y-6">
              <div class="grid md:grid-cols-2 gap-6">
                <div>
                  <label class="label-text"
                    >Headline / Professional Title</label
                  >
                  <div class="relative group">
                    <User class="input-icon" />
                    <input
                      v-model="form.title"
                      type="text"
                      class="input-field pl-10"
                      placeholder="e.g. Senior Product Designer at Gojek" />
                    <p
                      v-if="form.errors.title"
                      class="text-red-500 text-xs mt-1">
                      {{ form.errors.title }}
                    </p>
                  </div>
                </div>
                <div>
                  <label class="label-text">Rate / Hour (IDR)</label>
                  <div class="relative group">
                    <Banknote class="input-icon" />
                    <input
                      v-model="form.price"
                      type="number"
                      class="input-field pl-10"
                      placeholder="e.g. 500000" />
                    <p
                      v-if="form.errors.price"
                      class="text-red-500 text-xs mt-1">
                      {{ form.errors.price }}
                    </p>
                  </div>
                </div>
              </div>

              <div>
                <label class="label-text mb-3"
                  >Saya ingin dikenal sebagai:</label
                >
                <div class="flex flex-wrap gap-3">
                  <label
                    v-for="type in [
                      'Counselor',
                      'Psychologist',
                      'Coach',
                      'Trainer',
                      'Consultant',
                      'Mentor',
                      'Advisor',
                    ]"
                    :key="type"
                    class="cursor-pointer select-none inline-flex items-center px-4 py-2 rounded-xl border transition-all duration-200 text-sm font-medium"
                    :class="
                      form.type.includes(type)
                        ? 'bg-violet-600 text-white border-violet-600 shadow-md'
                        : 'bg-white text-slate-600 border-slate-200 hover:border-violet-300'
                    ">
                    <input
                      type="checkbox"
                      :value="type"
                      v-model="form.type"
                      class="hidden" />
                    <span>{{ type }}</span>
                    <Check
                      v-if="form.type.includes(type)"
                      class="ml-2 w-4 h-4" />
                  </label>
                </div>
                <p v-if="form.errors.type" class="text-red-500 text-xs mt-1">
                  {{ form.errors.type }}
                </p>
              </div>

              <div>
                <label class="label-text mb-2">About Me (Bio)</label>
                <div
                  class="bg-slate-50 rounded-xl overflow-hidden border border-slate-200 focus-within:border-violet-500 transition-all">
                  <QuillEditor
                    theme="snow"
                    v-model:content="form.about"
                    contentType="html"
                    toolbar="minimal"
                    style="height: 150px" />
                </div>
                <p v-if="form.errors.about" class="text-red-500 text-xs mt-1">
                  {{ form.errors.about }}
                </p>
              </div>
            </div>
          </div>

          <div
            class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm">
            <div
              class="mb-8 pb-4 border-b border-slate-100 flex justify-between items-center">
              <div>
                <h3 class="font-display text-lg font-bold text-slate-900">
                  Work Experience
                </h3>
                <p class="text-sm text-slate-500">
                  Riwayat karir profesional Anda.
                </p>
              </div>
              <button
                type="button"
                @click="addExperience"
                class="btn-secondary-sm">
                <Plus class="w-4 h-4" /> Add
              </button>
            </div>

            <div class="space-y-6">
              <div
                v-for="(exp, index) in form.experiences"
                :key="index"
                class="relative p-6 bg-slate-50 border border-slate-100 rounded-2xl group hover:border-violet-200 transition-colors">
                <button
                  type="button"
                  @click="removeExperience(index)"
                  class="absolute top-4 right-4 text-slate-400 hover:text-red-500 bg-white p-1.5 rounded-lg shadow-sm opacity-0 group-hover:opacity-100 transition-all">
                  <Trash2 class="w-4 h-4" />
                </button>
                <div class="grid md:grid-cols-2 gap-4 mb-4">
                  <input
                    v-model="exp.position"
                    type="text"
                    class="input-field"
                    placeholder="Position / Title" />
                  <input
                    v-model="exp.company"
                    type="text"
                    class="input-field"
                    placeholder="Company Name" />
                </div>
                <input
                  v-model="exp.years"
                  type="text"
                  class="input-field mb-4"
                  placeholder="Years (e.g. 2018 - Present)" />
                <textarea
                  v-model="exp.description"
                  rows="2"
                  class="input-field"
                  placeholder="Deskripsi singkat tanggung jawab (Opsional)"></textarea>
              </div>
            </div>
          </div>

          <div
            class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm">
            <div
              class="mb-8 pb-4 border-b border-slate-100 flex justify-between items-center">
              <div>
                <h3 class="font-display text-lg font-bold text-slate-900">
                  Licenses & Certifications
                </h3>
                <p class="text-sm text-slate-500">
                  Dokumen bukti keahlian (PDF/JPG).
                </p>
              </div>
              <button
                type="button"
                @click="addLicense"
                class="btn-secondary-sm">
                <Plus class="w-4 h-4" /> Add
              </button>
            </div>

            <div class="space-y-4">
              <div
                v-for="(lic, index) in form.licenses"
                :key="index"
                class="flex items-start gap-3">
                <div class="flex-1 space-y-2">
                  <input
                    v-model="lic.certification"
                    type="text"
                    class="input-field"
                    placeholder="Nama Sertifikasi / Lisensi (Contoh: AWS Solutions Architect)" />

                  <p
                    v-if="form.errors[`licenses.${index}.certification`]"
                    class="text-red-500 text-xs flex items-center gap-1 mt-1">
                    <AlertCircle class="w-3 h-3" />
                    {{ form.errors[`licenses.${index}.certification`] }}
                  </p>

                  <div>
                    <div v-if="!lic.file" class="flex items-center gap-3">
                      <label
                        class="cursor-pointer group flex items-center gap-2 px-4 py-2 bg-white hover:bg-slate-50 border border-slate-300 border-dashed rounded-xl transition-all hover:border-violet-400">
                        <div
                          class="p-1.5 bg-slate-100 rounded-lg group-hover:bg-violet-100 transition-colors">
                          <UploadCloud
                            class="w-4 h-4 text-slate-500 group-hover:text-violet-600" />
                        </div>
                        <span
                          class="text-sm font-medium text-slate-600 group-hover:text-slate-900"
                          >Upload PDF/Image</span
                        >
                        <input
                          type="file"
                          class="hidden"
                          accept=".pdf,.jpg,.jpeg,.png"
                          @change="e => handleLicenseUpload(e, index)" />
                      </label>

                      <a
                        v-if="lic.existing_file"
                        :href="lic.existing_file"
                        target="_blank"
                        class="flex items-center gap-1.5 text-xs font-medium text-violet-600 hover:text-violet-700 hover:underline px-2 py-1">
                        <LinkIcon class="w-3.5 h-3.5" />
                        Lihat File Saat Ini
                      </a>
                    </div>

                    <div
                      v-else
                      class="flex items-center justify-between p-3 bg-violet-50 border border-violet-200 rounded-xl">
                      <div class="flex items-center gap-3 overflow-hidden">
                        <div
                          class="p-2 bg-white rounded-lg shadow-sm border border-violet-100 text-violet-600">
                          <FileText class="w-5 h-5" />
                        </div>
                        <div class="min-w-0">
                          <p
                            class="text-sm font-bold text-slate-800 truncate pr-2">
                            {{ lic.file.name }}
                          </p>
                          <p class="text-[10px] text-slate-500">
                            Siap diupload •
                            {{ (lic.file.size / 1024).toFixed(0) }} KB
                          </p>
                        </div>
                      </div>

                      <button
                        type="button"
                        @click="lic.file = null"
                        class="p-1.5 hover:bg-white hover:shadow-sm rounded-lg text-slate-400 hover:text-red-500 transition-all"
                        title="Batalkan upload file ini">
                        <X class="w-4 h-4" />
                      </button>
                    </div>

                    <p
                      v-if="form.errors[`licenses.${index}.file`]"
                      class="text-red-500 text-xs mt-2 flex items-center gap-1">
                      <AlertCircle class="w-3 h-3" />
                      {{ form.errors[`licenses.${index}.file`] }}
                    </p>
                  </div>
                </div>

                <button
                  type="button"
                  @click="removeLicense(index)"
                  class="mt-2 p-2 rounded-lg hover:bg-red-50 text-slate-300 hover:text-red-500 transition-colors"
                  title="Hapus baris lisensi ini">
                  <Trash2 class="w-5 h-5" />
                </button>
              </div>
            </div>
          </div>

          <div
            class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm">
            <div
              class="mb-6 pb-4 border-b border-slate-100 flex justify-between items-center">
              <div>
                <h3 class="font-display text-lg font-bold text-slate-900">
                  Professional Gallery
                </h3>
                <p class="text-sm text-slate-500">
                  Portofolio visual kegiatan Anda.
                </p>
              </div>
              <button
                type="button"
                @click="addGallery"
                class="btn-secondary-sm">
                <Plus class="w-4 h-4" /> Add
              </button>
            </div>

            <div
              class="mb-6 p-4 bg-blue-50 border border-blue-100 rounded-xl flex gap-3 text-blue-700 text-sm">
              <Info class="w-5 h-5 shrink-0 mt-0.5" />
              <div>
                <strong>Tips Galeri:</strong> Unggah 3-6 foto yang membangun
                kepercayaan.
                <ul
                  class="list-disc ml-4 mt-1 space-y-0.5 text-xs text-blue-600">
                  <li>Foto saat Anda memberikan materi / training.</li>
                  <li>Suasana ruang kerja atau sesi konsultasi.</li>
                  <li>Dokumentasi achievement atau bersama klien.</li>
                </ul>
              </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
              <div
                v-for="(gal, index) in form.gallerys"
                :key="index"
                class="relative group aspect-square bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl flex items-center justify-center overflow-hidden hover:border-violet-400 transition-all">
                <button
                  type="button"
                  @click="removeGallery(index)"
                  class="absolute top-2 right-2 bg-white rounded-full p-1.5 text-red-500 opacity-0 group-hover:opacity-100 transition-opacity z-20 shadow-sm">
                  <Trash2 class="w-3.5 h-3.5" />
                </button>

                <input
                  type="file"
                  @change="e => handleGalleryUpload(e, index)"
                  class="absolute inset-0 opacity-0 cursor-pointer z-10"
                  accept="image/*" />

                <img
                  v-if="galleryPreviews[index]"
                  :src="galleryPreviews[index]"
                  class="w-full h-full object-cover" />
                <img
                  v-else-if="gal.existing_file"
                  :src="gal.existing_file"
                  class="w-full h-full object-cover" />

                <div v-else class="text-center text-slate-400">
                  <ImageIcon class="w-6 h-6 mx-auto mb-1" />
                  <span class="text-[10px] font-bold uppercase">Upload</span>
                </div>
              </div>
            </div>
            <p v-if="form.errors.gallerys" class="text-red-500 text-xs mt-2">
              {{ form.errors.gallerys }}
            </p>
          </div>

          <div
            class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm">
            <div
              class="mb-8 pb-4 border-b border-slate-100 flex justify-between items-center">
              <h3 class="font-display text-lg font-bold text-slate-900">
                Social Media
              </h3>
              <button type="button" @click="addSocial" class="btn-secondary-sm">
                <Plus class="w-4 h-4" /> Add
              </button>
            </div>
            <div
              v-for="(soc, index) in form.socials"
              :key="index"
              class="flex gap-3 mb-4">
              <div class="w-1/3 min-w-[120px]">
                <div class="relative">
                  <Globe class="absolute left-3 top-3 w-4 h-4 text-slate-400" />
                  <select
                    v-model="soc.key"
                    class="input-field pl-9 appearance-none">
                    <option value="instagram">Instagram</option>
                    <option value="linkedin">LinkedIn</option>
                    <option value="website">Website</option>
                    <option value="tiktok">TikTok</option>
                    <option value="facebook">Facebook</option>
                  </select>
                </div>
              </div>
              <div class="relative flex-1">
                <input
                  v-model="soc.value"
                  type="text"
                  class="input-field"
                  placeholder="URL or Username" />
                <button
                  type="button"
                  @click="removeSocial(index)"
                  class="absolute right-2 top-2.5 text-slate-300 hover:text-red-500">
                  <Trash2 class="w-4 h-4" />
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="lg:col-span-1 sticky top-6 z-20">
          <div
            class="bg-white rounded-3xl border border-slate-200 shadow-lg overflow-hidden">
            <div class="p-6 bg-slate-900 text-white">
              <h3 class="font-bold text-lg">Specialization</h3>
              <p class="text-xs text-slate-400 mt-1">
                Select skills you are expert in.
              </p>
            </div>

            <div class="p-4 max-h-[80vh] overflow-y-auto custom-scrollbar">
              <div
                v-if="form.errors.skills"
                class="mb-4 bg-red-50 text-red-600 p-3 rounded-lg text-sm border border-red-200">
                {{ form.errors.skills }}
              </div>

              <div
                v-for="category in categories"
                :key="category.id"
                class="mb-4 last:mb-0">
                <button
                  type="button"
                  @click="toggleCategory(category.id)"
                  class="w-full flex items-center justify-between p-3 bg-slate-50 hover:bg-slate-100 rounded-xl transition-colors font-bold text-slate-700 text-left">
                  <span class="flex items-center gap-2">
                    {{ category.name }}

                    <span
                      v-if="
                        category.sub_categories.reduce(
                          (acc, sub) =>
                            acc +
                            sub.skills.filter(s => form.skills.includes(s.id))
                              .length,
                          0
                        ) > 0
                      "
                      class="bg-violet-100 text-violet-700 text-[10px] px-2 py-0.5 rounded-full">
                      {{
                        category.sub_categories.reduce(
                          (acc, sub) =>
                            acc +
                            sub.skills.filter(s => form.skills.includes(s.id))
                              .length,
                          0
                        )
                      }}
                    </span>
                  </span>
                  <ChevronDown
                    v-if="openCategories.includes(category.id)"
                    class="w-4 h-4 text-slate-400" />
                  <ChevronRight v-else class="w-4 h-4 text-slate-400" />
                </button>

                <div
                  v-if="openCategories.includes(category.id)"
                  class="mt-2 pl-2 space-y-4">
                  <div v-for="sub in category.sub_categories" :key="sub.id">
                    <h5
                      class="text-xs font-bold text-violet-600 uppercase tracking-wider mb-2 pl-2 border-l-2 border-violet-200">
                      {{ sub.name }}
                    </h5>
                    <div class="space-y-1">
                      <label
                        v-for="skill in sub.skills"
                        :key="skill.id"
                        class="flex items-start gap-3 p-2 rounded-lg hover:bg-slate-50 cursor-pointer transition-colors">
                        <div class="relative flex items-center mt-0.5">
                          <input
                            type="checkbox"
                            :value="skill.id"
                            v-model="form.skills"
                            class="peer h-4 w-4 rounded border-slate-300 text-violet-600 focus:ring-violet-500" />
                        </div>
                        <span
                          class="text-sm text-slate-600 peer-checked:text-slate-900 peer-checked:font-medium">
                          {{ skill.name }}
                        </span>
                      </label>
                    </div>
                  </div>
                  <div
                    v-if="category.sub_categories.length === 0"
                    class="p-3 text-xs text-slate-400 italic">
                    No skills available under this category.
                  </div>
                </div>
              </div>
            </div>

            <div
              class="p-4 border-t border-slate-100 bg-slate-50 text-xs text-slate-500 text-center">
              {{ form.skills.length }} skills selected
            </div>
          </div>
        </div>
      </div>

      <div
        class="mt-10 pt-6 border-t border-slate-100 flex items-center justify-end gap-4">
        <a
          :href="route('dashboard')"
          class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-slate-50 transition-all">
          Cancel
        </a>

        <button
          type="submit"
          :disabled="form.processing"
          class="px-8 py-3 cursor-pointer rounded-xl font-bold text-sm transition-all duration-300 flex items-center gap-2 shadow-lg shadow-violet-200 hover:shadow-xl hover:scale-105 disabled:opacity-50 disabled:scale-100"
          :class="
            form.recentlySuccessful
              ? 'bg-green-600 text-white'
              : 'bg-slate-900 text-white'
          ">
          <Loader2 v-if="form.processing" class="animate-spin h-4 w-4" />
          <Check v-else-if="form.recentlySuccessful" class="h-5 w-5" />
          <Save v-else class="h-4 w-4" />
          {{ form.recentlySuccessful ? "Saved!" : "Save Profile" }}
        </button>
      </div>
    </form>
  </div>
</template>
