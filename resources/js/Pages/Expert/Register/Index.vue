<script setup>
import { useForm, Head } from "@inertiajs/vue3";
import { ref, onMounted, computed } from "vue";
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
    Linkedin,
    Globe,
    Loader2,
    Check,
    Save,
    ChevronDown,
    ChevronUp,
    Info,
    FileText,
    X,
    AlertCircle,
    Search,
    Briefcase,
    Code,
    Palette,
    Scale,
    DollarSign,
    Grid3x3,
    Building2,
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
        ? props.expert.skills.map((s) => Number(s.id))
        : [],

    // Experiences
    experiences: parseData(props.expert?.experiences, [
        {
            position: "",
            company: "",
            start_year: "",
            end_year: "",
            is_current: false,
            description: "",
        },
    ]),

    // Licenses
    licenses: parseData(props.expert?.licenses, [
        { certification: "", file: null },
    ]).map((item) => ({
        ...item,
        file: null,
        existing_file: item.file || item.attachment,
    })),

    // Gallerys
    gallerys: parseData(props.expert?.gallerys, [{ file: null }]).map(
        (item) => ({
            ...item,
            file: null,
            existing_file: item.file || item.photos,
        })
    ),

    socials: parseData(props.expert?.socials, [
        { key: "linkedin", value: "" },
        { key: "website", value: "" },
    ]),
});

// --- STATE ---
const openCategories = ref([]);
const galleryPreviews = ref({});
const skillSearch = ref("");

// Category Icons Map
const categoryIcons = {
    "Technology & IT": Code,
    "Design & Creative": Palette,
    "Legal & Compliance": Scale,
    "Finance & Accounting": DollarSign,
    "Business Management": Building2,
};

// Computed: Filtered categories based on search
const filteredCategories = computed(() => {
    if (!skillSearch.value.trim()) return props.categories || [];

    const search = skillSearch.value.toLowerCase();
    return (props.categories || [])
        .map((cat) => ({
            ...cat,
            sub_categories: (cat.sub_categories || [])
                .map((sub) => ({
                    ...sub,
                    skills: (sub.skills || []).filter((skill) =>
                        skill.name.toLowerCase().includes(search)
                    ),
                }))
                .filter((sub) => sub.skills.length > 0),
        }))
        .filter((cat) => cat.sub_categories.length > 0);
});

// Computed: Selected skills count
const selectedSkillsCount = computed(() => form.skills.length);

// --- LOGIC AUTO OPEN ACCORDION ---
onMounted(() => {
    if (props.isEditMode && form.skills.length > 0 && props.categories) {
        props.categories.forEach((cat) => {
            const subs = cat.sub_categories || cat.subCategories || [];
            const hasSelectedSkill = subs.some((sub) => {
                if (!sub.skills) return false;
                return sub.skills.some((skill) =>
                    form.skills.includes(Number(skill.id))
                );
            });
            if (hasSelectedSkill) {
                openCategories.value.push(cat.id);
            }
        });
    }
});

// --- ACTIONS ---
const toggleCategory = (catId) => {
    if (openCategories.value.includes(catId)) {
        openCategories.value = openCategories.value.filter(
            (id) => id !== catId
        );
    } else {
        openCategories.value.push(catId);
    }
};

const clearAllSkills = () => {
    form.skills = [];
};

const addExperience = () =>
    form.experiences.push({
        position: "",
        company: "",
        start_year: "",
        end_year: "",
        is_current: false,
        description: "",
    });
const removeExperience = (index) => form.experiences.splice(index, 1);

const addLicense = () => form.licenses.push({ certification: "", file: null });
const removeLicense = (index) => form.licenses.splice(index, 1);

const handleLicenseUpload = (e, index) => {
    const file = e.target.files[0];
    if (file) form.licenses[index].file = file;
};

const addGallery = () => form.gallerys.push({ file: null });
const removeGallery = (index) => form.gallerys.splice(index, 1);

const handleGalleryUpload = (e, index) => {
    const file = e.target.files[0];
    if (file) {
        form.gallerys[index].file = file;
        galleryPreviews.value[index] = URL.createObjectURL(file);
    }
};

const addSocial = () => form.socials.push({ key: "linkedin", value: "" });
const removeSocial = (index) => form.socials.splice(index, 1);

const submit = () => {
    form.post(route("expert_onboarding.store"), {
        forceFormData: true,
        preserveScroll: true,
    });
};

// Get category icon
const getCategoryIcon = (name) => categoryIcons[name] || Briefcase;

// Count selected skills in category
const countSelectedInCategory = (category) => {
    return (category.sub_categories || []).reduce(
        (acc, sub) =>
            acc +
            (sub.skills || []).filter((s) => form.skills.includes(Number(s.id)))
                .length,
        0
    );
};
</script>

<template>
    <Head title="Expert Registration" />
    <ToastNotification />

    <div class="min-h-screen bg-slate-950">
        <!-- Header -->
        <header
            class="bg-slate-900/80 backdrop-blur-md border-b border-slate-800 px-6 py-3 sticky top-0 z-50"
        >
            <div class="w-full px-4 xl:px-8 flex justify-between items-center">
                <!-- Logo -->
                <div class="flex items-center gap-2">
                    <Grid3x3 class="w-6 h-6 text-blue-400" />
                    <span class="text-xl font-bold text-white">Keyperson</span>
                </div>

                <!-- Right: Support & Avatar -->
                <div class="flex items-center gap-4">
                    <button
                        class="px-4 py-2 text-sm text-slate-300 hover:text-white flex items-center gap-2 border border-slate-700 hover:border-slate-600 rounded-full transition-all"
                    >
                        <Info class="w-4 h-4" />
                        Support
                    </button>
                    <div
                        class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-sm ring-2 ring-slate-700"
                    >
                        {{ user?.name?.charAt(0) || "U" }}
                    </div>
                </div>
            </div>
        </header>

        <!-- Title Section -->
        <div class="max-w-screen-2xl mx-auto px-6 xl:px-10 pt-8 pb-6">
            <div
                class="flex flex-col md:flex-row md:items-center md:justify-between gap-4"
            >
                <div>
                    <h1 class="text-2xl font-bold text-white">
                        Expert Registration
                    </h1>
                    <p
                        class="text-sm text-slate-400 mt-1 flex items-center gap-2"
                    >
                        <span
                            class="px-2.5 py-1 bg-blue-500/20 text-blue-400 text-xs font-bold rounded-full"
                            >Step 1</span
                        >
                        Profile Details & Skills
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <button
                        type="button"
                        class="px-5 py-2.5 text-sm font-medium text-slate-300 hover:text-white bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-full transition-all"
                    >
                        Save Draft
                    </button>
                    <button
                        type="button"
                        @click="submit"
                        :disabled="form.processing"
                        class="px-6 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-bold text-sm rounded-full hover:from-blue-600 hover:to-blue-700 transition-all shadow-lg shadow-blue-500/25 flex items-center gap-2 disabled:opacity-50"
                    >
                        <Loader2
                            v-if="form.processing"
                            class="animate-spin h-4 w-4"
                        />
                        Next Step
                    </button>
                </div>
            </div>
        </div>

        <form
            @submit.prevent="submit"
            class="max-w-screen-2xl mx-auto px-6 xl:px-10 pb-8"
        >
            <div class="grid lg:grid-cols-3 gap-8 items-start">
                <!-- Left Column - Forms (2/3) -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Profile Essentials -->
                    <div
                        class="bg-slate-900/70 backdrop-blur-sm p-6 rounded-2xl border border-slate-800"
                    >
                        <h3 class="text-lg font-bold text-white mb-6">
                            Profile Essentials
                        </h3>

                        <div class="space-y-5">
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="block text-xs font-medium text-slate-400 mb-2"
                                        >Professional Title</label
                                    >
                                    <input
                                        v-model="form.title"
                                        type="text"
                                        class="w-full bg-slate-800/50 border border-slate-700 rounded-lg text-white placeholder-slate-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-sm py-2.5 px-4"
                                        placeholder="e.g. Senior Solutions Architect"
                                    />
                                    <p
                                        v-if="form.errors.title"
                                        class="text-red-400 text-xs mt-1"
                                    >
                                        {{ form.errors.title }}
                                    </p>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-medium text-slate-400 mb-2"
                                        >Hourly Rate (USD)</label
                                    >
                                    <div class="relative">
                                        <span
                                            class="absolute left-3 top-2.5 text-slate-500 text-sm"
                                            >$</span
                                        >
                                        <input
                                            v-model="form.price"
                                            type="number"
                                            class="w-full bg-slate-800/50 border border-slate-700 rounded-lg text-white placeholder-slate-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-sm py-2.5 pl-7 pr-4"
                                            placeholder="0.00"
                                        />
                                    </div>
                                    <p
                                        v-if="form.errors.price"
                                        class="text-red-400 text-xs mt-1"
                                    >
                                        {{ form.errors.price }}
                                    </p>
                                </div>
                            </div>

                            <!-- Service Type -->
                            <div>
                                <label
                                    class="block text-xs font-medium text-slate-400 mb-3"
                                    >Service Type</label
                                >
                                <div class="flex flex-wrap gap-2">
                                    <label
                                        v-for="type in [
                                            'Consulting',
                                            'Implementation',
                                            'Training',
                                            'Development',
                                        ]"
                                        :key="type"
                                        class="cursor-pointer select-none"
                                    >
                                        <input
                                            type="checkbox"
                                            :value="type"
                                            v-model="form.type"
                                            class="hidden"
                                        />
                                        <span
                                            class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium transition-all"
                                            :class="
                                                form.type.includes(type)
                                                    ? 'bg-blue-500/20 text-blue-400 border border-blue-500/50'
                                                    : 'bg-slate-800 text-slate-400 border border-slate-700 hover:border-slate-600'
                                            "
                                        >
                                            {{ type }}
                                        </span>
                                    </label>
                                </div>
                                <p
                                    v-if="form.errors.type"
                                    class="text-red-400 text-xs mt-1"
                                >
                                    {{ form.errors.type }}
                                </p>
                            </div>

                            <!-- About -->
                            <div>
                                <label
                                    class="block text-xs font-medium text-slate-400 mb-2"
                                    >About</label
                                >
                                <textarea
                                    v-model="form.about"
                                    rows="4"
                                    class="w-full bg-slate-800/50 border border-slate-700 rounded-lg text-white placeholder-slate-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-sm py-2.5 px-4 resize-none overflow-y-auto break-words whitespace-pre-wrap"
                                    style="
                                        word-break: break-word;
                                        overflow-wrap: break-word;
                                    "
                                    placeholder="Describe your professional journey and key value proposition..."
                                ></textarea>
                                <p
                                    v-if="form.errors.about"
                                    class="text-red-400 text-xs mt-1"
                                >
                                    {{ form.errors.about }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Experiences -->
                    <div
                        class="bg-slate-900/70 backdrop-blur-sm p-6 rounded-2xl border border-slate-800"
                    >
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-bold text-white">
                                Experiences
                            </h3>
                            <button
                                type="button"
                                @click="addExperience"
                                class="text-blue-400 hover:text-blue-300 text-sm font-medium flex items-center gap-1"
                            >
                                <Plus class="w-4 h-4" /> Add Another Experience
                            </button>
                        </div>

                        <div class="space-y-4">
                            <div
                                v-for="(exp, index) in form.experiences"
                                :key="index"
                                class="relative p-5 bg-slate-800/50 border border-slate-700 rounded-xl group"
                            >
                                <button
                                    type="button"
                                    @click="removeExperience(index)"
                                    class="absolute top-3 right-3 text-slate-500 hover:text-red-400 transition-colors opacity-0 group-hover:opacity-100"
                                >
                                    <Trash2 class="w-4 h-4" />
                                </button>

                                <div class="grid md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label
                                            class="block text-xs font-medium text-slate-500 mb-1.5"
                                            >Title</label
                                        >
                                        <input
                                            v-model="exp.position"
                                            type="text"
                                            class="w-full bg-slate-900/50 border border-slate-700 rounded-lg text-white placeholder-slate-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 text-sm py-2 px-3"
                                            placeholder="e.g. Senior Therapist"
                                        />
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-medium text-slate-500 mb-1.5"
                                            >Company Name</label
                                        >
                                        <input
                                            v-model="exp.company"
                                            type="text"
                                            class="w-full bg-slate-900/50 border border-slate-700 rounded-lg text-white placeholder-slate-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 text-sm py-2 px-3"
                                            placeholder="e.g. Mindful Wellness Center"
                                        />
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-4 mb-4">
                                    <div>
                                        <label
                                            class="block text-xs font-medium text-slate-500 mb-1.5"
                                            >Start Year</label
                                        >
                                        <input
                                            v-model="exp.start_year"
                                            type="text"
                                            class="w-full bg-slate-900/50 border border-slate-700 rounded-lg text-white placeholder-slate-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 text-sm py-2 px-3"
                                            placeholder="e.g. 2018"
                                        />
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-medium text-slate-500 mb-1.5"
                                            >End Year</label
                                        >
                                        <input
                                            v-model="exp.end_year"
                                            type="text"
                                            :disabled="exp.is_current"
                                            class="w-full bg-slate-900/50 border border-slate-700 rounded-lg text-white placeholder-slate-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 text-sm py-2 px-3 disabled:opacity-50"
                                            placeholder="e.g. 2023"
                                        />
                                    </div>
                                    <div class="flex items-end pb-2">
                                        <label
                                            class="flex items-center gap-2 cursor-pointer"
                                        >
                                            <input
                                                type="checkbox"
                                                v-model="exp.is_current"
                                                class="w-4 h-4 rounded border-slate-600 bg-slate-800 text-blue-500 focus:ring-blue-500/20"
                                            />
                                            <span class="text-sm text-slate-400"
                                                >Current</span
                                            >
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    <label
                                        class="block text-xs font-medium text-slate-500 mb-1.5"
                                        >Describe</label
                                    >
                                    <textarea
                                        v-model="exp.description"
                                        rows="2"
                                        class="w-full bg-slate-900/50 border border-slate-700 rounded-lg text-white placeholder-slate-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 text-sm py-2 px-3 resize-none"
                                        placeholder="Describe your responsibilities and achievements."
                                    ></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Licenses & Certifications -->
                    <div
                        class="bg-slate-900/70 backdrop-blur-sm p-6 rounded-2xl border border-slate-800"
                    >
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-bold text-white">
                                Licenses & Certifications
                            </h3>
                            <button
                                type="button"
                                @click="addLicense"
                                class="text-blue-400 hover:text-blue-300 text-sm font-medium flex items-center gap-1"
                            >
                                <Plus class="w-4 h-4" /> Add Another
                                Certification
                            </button>
                        </div>

                        <div class="space-y-4">
                            <div
                                v-for="(lic, index) in form.licenses"
                                :key="index"
                                class="flex items-start gap-4 p-4 bg-slate-800/50 border border-slate-700 rounded-xl group"
                            >
                                <div class="flex-1">
                                    <label
                                        class="block text-xs font-medium text-slate-500 mb-1.5"
                                        >Name of Certification</label
                                    >
                                    <input
                                        v-model="lic.certification"
                                        type="text"
                                        class="w-full bg-slate-900/50 border border-slate-700 rounded-lg text-white placeholder-slate-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 text-sm py-2 px-3"
                                        placeholder="e.g. Certified Cognitive Behavior"
                                    />
                                    <p
                                        v-if="
                                            form.errors[
                                                `licenses.${index}.certification`
                                            ]
                                        "
                                        class="text-red-400 text-xs mt-1"
                                    >
                                        {{
                                            form.errors[
                                                `licenses.${index}.certification`
                                            ]
                                        }}
                                    </p>
                                </div>

                                <div class="w-40">
                                    <label
                                        class="block text-xs font-medium text-slate-500 mb-1.5"
                                        >Upload Document</label
                                    >
                                    <label
                                        class="flex items-center gap-2 px-3 py-2 bg-slate-900/50 border border-slate-700 rounded-lg cursor-pointer hover:border-slate-600 transition-colors"
                                    >
                                        <UploadCloud
                                            class="w-4 h-4 text-slate-500"
                                        />
                                        <span class="text-sm text-slate-400">{{
                                            lic.file ? "Replace" : "Choose File"
                                        }}</span>
                                        <input
                                            type="file"
                                            class="hidden"
                                            accept=".pdf,.jpg,.jpeg,.png"
                                            @change="
                                                (e) =>
                                                    handleLicenseUpload(
                                                        e,
                                                        index
                                                    )
                                            "
                                        />
                                    </label>
                                    <p
                                        v-if="lic.file"
                                        class="text-xs text-blue-400 mt-1 truncate"
                                    >
                                        {{ lic.file.name }}
                                    </p>
                                    <a
                                        v-else-if="lic.existing_file"
                                        :href="lic.existing_file"
                                        target="_blank"
                                        class="text-xs text-blue-400 hover:underline mt-1 inline-block"
                                        >View current</a
                                    >
                                </div>

                                <button
                                    type="button"
                                    @click="removeLicense(index)"
                                    class="mt-6 text-slate-500 hover:text-red-400 transition-colors opacity-0 group-hover:opacity-100"
                                >
                                    <Trash2 class="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Gallery -->
                    <div
                        class="bg-slate-900/70 backdrop-blur-sm p-6 rounded-2xl border border-slate-800"
                    >
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-bold text-white">
                                Gallery
                            </h3>
                            <button
                                type="button"
                                @click="addGallery"
                                class="text-blue-400 hover:text-blue-300 text-sm font-medium flex items-center gap-1"
                            >
                                <Plus class="w-4 h-4" /> Add Another Photo/Video
                            </button>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div
                                v-for="(gal, index) in form.gallerys"
                                :key="index"
                                class="relative group aspect-square bg-slate-800/50 border-2 border-dashed border-slate-700 rounded-xl flex items-center justify-center overflow-hidden hover:border-blue-500/50 transition-all cursor-pointer"
                            >
                                <button
                                    type="button"
                                    @click="removeGallery(index)"
                                    class="absolute top-2 right-2 bg-slate-900/80 rounded-full p-1.5 text-red-400 opacity-0 group-hover:opacity-100 transition-opacity z-20"
                                >
                                    <Trash2 class="w-3.5 h-3.5" />
                                </button>

                                <input
                                    type="file"
                                    @change="
                                        (e) => handleGalleryUpload(e, index)
                                    "
                                    class="absolute inset-0 opacity-0 cursor-pointer z-10"
                                    accept="image/*,video/*"
                                />

                                <img
                                    v-if="galleryPreviews[index]"
                                    :src="galleryPreviews[index]"
                                    class="w-full h-full object-cover"
                                />
                                <img
                                    v-else-if="gal.existing_file"
                                    :src="gal.existing_file"
                                    class="w-full h-full object-cover"
                                />
                                <div v-else class="text-center text-slate-500">
                                    <ImageIcon class="w-6 h-6 mx-auto mb-1" />
                                    <span class="text-[10px]"
                                        >Click to upload photo or video</span
                                    >
                                </div>
                            </div>
                        </div>
                        <p
                            v-if="form.errors.gallerys"
                            class="text-red-400 text-xs mt-2"
                        >
                            {{ form.errors.gallerys }}
                        </p>
                    </div>

                    <!-- Social Presence -->
                    <div
                        class="bg-slate-900/70 backdrop-blur-sm p-6 rounded-2xl border border-slate-800"
                    >
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-bold text-white">
                                Social Presence
                            </h3>
                            <button
                                type="button"
                                @click="addSocial"
                                class="text-blue-400 hover:text-blue-300 text-sm font-medium flex items-center gap-1"
                            >
                                <Plus class="w-4 h-4" /> Add another link
                            </button>
                        </div>

                        <div class="space-y-4">
                            <div
                                v-for="(soc, index) in form.socials"
                                :key="index"
                                class="flex items-center gap-3"
                            >
                                <div
                                    class="w-10 h-10 bg-slate-800 rounded-lg flex items-center justify-center text-slate-400"
                                >
                                    <Linkedin
                                        v-if="soc.key === 'linkedin'"
                                        class="w-5 h-5"
                                    />
                                    <Globe v-else class="w-5 h-5" />
                                </div>
                                <div class="flex-1 relative">
                                    <input
                                        v-model="soc.value"
                                        type="text"
                                        class="w-full bg-slate-800/50 border border-slate-700 rounded-lg text-white placeholder-slate-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 text-sm py-2.5 px-4 pr-10"
                                        :placeholder="
                                            soc.key === 'linkedin'
                                                ? 'LinkedIn Profile URL'
                                                : 'Personal Website URL'
                                        "
                                    />
                                    <button
                                        v-if="form.socials.length > 1"
                                        type="button"
                                        @click="removeSocial(index)"
                                        class="absolute right-3 top-2.5 text-slate-500 hover:text-red-400"
                                    >
                                        <X class="w-4 h-4" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Skills Sidebar (1/3) -->
                <div class="lg:col-span-1 space-y-4 lg:sticky lg:top-24">
                    <div
                        class="bg-slate-900/70 backdrop-blur-sm rounded-2xl border border-slate-800 overflow-hidden"
                    >
                        <div class="p-5 border-b border-slate-800">
                            <h3 class="text-lg font-bold text-white">
                                Select Your Skills
                            </h3>
                            <p class="text-xs text-slate-500 mt-1">
                                Choose categories that match your expertise.
                            </p>

                            <!-- Search -->
                            <div class="relative mt-4">
                                <Search
                                    class="absolute left-3 top-2.5 w-4 h-4 text-slate-500"
                                />
                                <input
                                    v-model="skillSearch"
                                    type="text"
                                    class="w-full bg-slate-800/50 border border-slate-700 rounded-lg text-white placeholder-slate-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 text-sm py-2 pl-9 pr-4"
                                    placeholder="Search skills..."
                                />
                            </div>
                        </div>

                        <div
                            class="max-h-[55vh] overflow-y-auto custom-scrollbar"
                        >
                            <div
                                v-if="form.errors.skills"
                                class="mx-4 mt-4 bg-red-500/10 text-red-400 p-3 rounded-lg text-sm border border-red-500/20"
                            >
                                {{ form.errors.skills }}
                            </div>

                            <div class="p-4 space-y-1">
                                <div
                                    v-for="category in filteredCategories"
                                    :key="category.id"
                                    class="rounded-xl overflow-hidden"
                                >
                                    <!-- Category Header -->
                                    <button
                                        type="button"
                                        @click="toggleCategory(category.id)"
                                        class="w-full flex items-center justify-between p-3.5 bg-slate-800/60 hover:bg-slate-800/80 rounded-xl transition-colors text-left"
                                        :class="
                                            openCategories.includes(category.id)
                                                ? 'rounded-b-none'
                                                : ''
                                        "
                                    >
                                        <span class="flex items-center gap-3">
                                            <component
                                                :is="
                                                    getCategoryIcon(
                                                        category.name
                                                    )
                                                "
                                                class="w-5 h-5 text-blue-400"
                                            />
                                            <span
                                                class="font-medium text-white text-sm"
                                                >{{ category.name }}</span
                                            >
                                        </span>
                                        <ChevronUp
                                            v-if="
                                                openCategories.includes(
                                                    category.id
                                                )
                                            "
                                            class="w-4 h-4 text-slate-400"
                                        />
                                        <ChevronDown
                                            v-else
                                            class="w-4 h-4 text-slate-400"
                                        />
                                    </button>

                                    <!-- Category Content (Expanded) -->
                                    <div
                                        v-if="
                                            openCategories.includes(category.id)
                                        "
                                        class="bg-slate-800/30 px-4 py-3 rounded-b-xl border-t border-slate-700/50"
                                    >
                                        <div
                                            v-for="sub in category.sub_categories"
                                            :key="sub.id"
                                            class="mb-4 last:mb-0"
                                        >
                                            <!-- Subcategory Header -->
                                            <h5
                                                class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2 pl-1 border-l-2 border-slate-600 ml-1"
                                            >
                                                {{ sub.name }}
                                            </h5>
                                            <!-- Skills List -->
                                            <div class="space-y-0.5">
                                                <label
                                                    v-for="skill in sub.skills"
                                                    :key="skill.id"
                                                    class="flex items-center gap-3 py-2 px-2 rounded-lg hover:bg-slate-700/30 cursor-pointer transition-colors group"
                                                >
                                                    <div
                                                        class="relative flex items-center justify-center w-5 h-5"
                                                    >
                                                        <input
                                                            type="checkbox"
                                                            :value="
                                                                Number(skill.id)
                                                            "
                                                            v-model="
                                                                form.skills
                                                            "
                                                            class="peer appearance-none w-5 h-5 rounded border-2 border-slate-600 bg-slate-800 checked:bg-blue-500 checked:border-blue-500 transition-all cursor-pointer"
                                                        />
                                                        <Check
                                                            class="absolute w-3 h-3 text-white pointer-events-none opacity-0 peer-checked:opacity-100 transition-opacity"
                                                        />
                                                    </div>
                                                    <span
                                                        class="text-sm transition-colors"
                                                        :class="
                                                            form.skills.includes(
                                                                Number(skill.id)
                                                            )
                                                                ? 'text-white font-medium'
                                                                : 'text-slate-400 group-hover:text-slate-300'
                                                        "
                                                    >
                                                        {{ skill.name }}
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                        <div
                                            v-if="
                                                !category.sub_categories ||
                                                category.sub_categories
                                                    .length === 0
                                            "
                                            class="py-3 text-xs text-slate-500 italic text-center"
                                        >
                                            No skills available in this
                                            category.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div
                            class="p-4 border-t border-slate-800 flex items-center justify-between bg-slate-900/50"
                        >
                            <span class="text-sm text-slate-400"
                                >{{ selectedSkillsCount }} skills selected</span
                            >
                            <button
                                v-if="selectedSkillsCount > 0"
                                type="button"
                                @click="clearAllSkills"
                                class="text-xs text-blue-400 hover:text-blue-300 font-medium"
                            >
                                Clear all
                            </button>
                        </div>
                    </div>

                    <!-- Tip Box -->
                    <div
                        class="bg-blue-500/10 border border-blue-500/20 rounded-xl p-4 flex gap-3"
                    >
                        <Info class="w-5 h-5 text-blue-400 shrink-0 mt-0.5" />
                        <div>
                            <h4 class="text-sm font-bold text-blue-400">
                                Tip for Success
                            </h4>
                            <p class="text-xs text-slate-400 mt-1">
                                Experts with at least 5 verified skills appear
                                25% more often in search results.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>
