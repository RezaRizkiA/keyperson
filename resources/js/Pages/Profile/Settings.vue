<script setup>
import { ref, computed } from 'vue';
import { Head, useForm, usePage, Link } from '@inertiajs/vue3';
import AppLayout from '../../Layouts/AppLayout.vue';

// Icons
import {
    LayoutDashboard,
    Settings,
    LogOut,
    User,
    Lock,
    Image as ImageIcon,
    Mail,
    Phone,
    MapPin,
    Link2,
    Check,
    Loader2,
    Eye,
    EyeOff,
    Camera,
    Save,
    ChevronRight,
    AlertCircle
} from 'lucide-vue-next';

const props = defineProps({
    user: Object
});

const page = usePage();
const assets = computed(() => page.props.assets);

// --- 1. FORM PROFILE ---
const formProfile = useForm({
    name: props.user.name,
    gender: props.user.gender || 'L',
    email: props.user.email,
    phone: props.user.phone || '',
    address: props.user.address || '',
    slug_name: props.user.slug || '',
});

const submitProfile = () => {
    formProfile.post(route('renew_profile'), {
        preserveScroll: true,
        onSuccess: () => { }
    });
};

// --- 2. FORM PASSWORD ---
const showCurrentPassword = ref(false);
const showNewPassword = ref(false);

const formPassword = useForm({
    current_password: '',
    new_password: '',
    new_password_confirmation: '',
});

const passwordMismatch = computed(() => {
    if (!formPassword.new_password_confirmation) return false;
    return formPassword.new_password !== formPassword.new_password_confirmation;
});

const submitPassword = () => {
    formPassword.post(route('renew_password'), {
        preserveScroll: true,
        onSuccess: () => formPassword.reset(),
    });
};

// --- 3. FORM PICTURE ---
const formPicture = useForm({ picture: null });
const previewUrl = ref(props.user.picture_url || assets.value.userPlaceholderUrl);

const handlePictureChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        formPicture.picture = file;
        previewUrl.value = URL.createObjectURL(file);
    }
};

const submitPicture = () => {
    formPicture.post(route('renew_picture'), {
        preserveScroll: true,
        forceFormData: true,
    });
};
</script>

<template>

    <Head title="Account Settings" />

    <AppLayout>
        <div class="bg-slate-50/50 min-h-screen pt-28 pb-20 font-sans">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <div class="mb-10">
                    <h1 class="font-display text-3xl md:text-4xl font-bold text-slate-900">
                        Account Settings
                    </h1>
                    <p class="text-slate-500 mt-2 text-lg">Manage your personal information and security.</p>
                </div>

                <div class="grid lg:grid-cols-12 gap-8 items-start">

                    <div class="lg:col-span-3 lg:sticky lg:top-28 z-10">
                        <div
                            class="bg-white rounded-3xl p-6 border border-slate-200/60 shadow-xl shadow-slate-200/40 mb-6 text-center relative overflow-hidden">
                            <div
                                class="absolute top-0 left-0 w-full h-20 bg-gradient-to-r from-violet-500 to-fuchsia-500 opacity-10">
                            </div>

                            <div class="relative inline-block mt-4 mb-3">
                                <div class="p-1 bg-white rounded-full">
                                    <img :src="props.user.picture_url || assets.userPlaceholderUrl"
                                        class="w-16 h-16 rounded-full object-cover border-2 border-slate-100 shadow-sm">
                                </div>
                            </div>
                            <h3 class="font-bold text-slate-900 text-lg leading-tight">{{ user.name }}</h3>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1">{{ user.email }}
                            </p>
                        </div>

                        <div class="bg-white rounded-3xl p-3 border border-slate-200/60 shadow-sm">
                            <nav class="space-y-1">
                                <Link :href="route('profile')"
                                    class="group w-full flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 text-slate-600 transition-all duration-200">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-lg bg-slate-100 group-hover:bg-white flex items-center justify-center transition-colors text-slate-500">
                                            <LayoutDashboard class="w-5 h-5" />
                                        </div>
                                        <div>
                                            <div class="font-bold text-sm">Dashboard</div>
                                            <div class="text-[10px] font-medium opacity-60">Back to overview</div>
                                        </div>
                                    </div>
                                    <ChevronRight class="w-4 h-4 text-slate-400" />
                                </Link>

                                <div
                                    class="group w-full flex items-center justify-between p-3 rounded-xl bg-white shadow-md border border-orange-200">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center">
                                            <Settings class="w-5 h-5" />
                                        </div>
                                        <div>
                                            <div class="font-bold text-sm text-slate-900">Settings</div>
                                            <div class="text-[10px] font-medium opacity-60">You are here</div>
                                        </div>
                                    </div>
                                </div>
                            </nav>

                            <div class="mt-3 pt-3 border-t border-slate-100">
                                <Link :href="route('logout')" method="post" as="button"
                                    class="w-full flex items-center gap-3 p-3 rounded-xl text-red-600 hover:bg-red-50 transition-colors font-medium text-sm cursor-pointer">
                                    <LogOut class="w-5 h-5" />
                                    Sign Out
                                </Link>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-9 space-y-8">

                        <div
                            class="bg-white border border-slate-200/60 shadow-xl shadow-slate-200/30 rounded-[2.5rem] p-8 relative overflow-hidden">
                            <h2 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-full bg-violet-50 flex items-center justify-center text-violet-600">
                                    <ImageIcon class="w-5 h-5" />
                                </div>
                                Profile Picture
                            </h2>

                            <form @submit.prevent="submitPicture" class="flex flex-col md:flex-row items-center gap-8">
                                <div class="relative group">
                                    <div
                                        class="w-32 h-32 rounded-full overflow-hidden border-4 border-slate-50 bg-slate-100 shadow-inner">
                                        <img :src="previewUrl" class="w-full h-full object-cover">
                                    </div>
                                    <label
                                        class="absolute inset-0 flex items-center justify-center bg-black/40 text-white rounded-full opacity-0 group-hover:opacity-100 transition-all cursor-pointer backdrop-blur-sm">
                                        <Camera class="w-8 h-8" />
                                        <input type="file" class="hidden" accept="image/*"
                                            @change="handlePictureChange">
                                    </label>
                                </div>

                                <div class="flex-1 text-center md:text-left">
                                    <h4 class="font-bold text-slate-900">Upload New Photo</h4>
                                    <p class="text-sm text-slate-500 mb-4">Supports JPG, PNG or GIF. Max size 800KB.</p>

                                    <button type="submit" :disabled="!formPicture.isDirty || formPicture.processing"
                                        class="px-6 py-2.5 rounded-xl font-bold text-sm transition-all duration-300 flex items-center justify-center md:justify-start gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                        :class="{
                                            'bg-green-600 text-white': formPicture.recentlySuccessful,
                                            'bg-slate-900 text-white hover:bg-slate-800 hover:shadow-lg': !formPicture.recentlySuccessful
                                        }">
                                        <Loader2 v-if="formPicture.processing" class="animate-spin h-4 w-4" />
                                        <Check v-else-if="formPicture.recentlySuccessful" class="h-4 w-4" />
                                        <Save v-else class="h-4 w-4" />

                                        {{ formPicture.processing ? 'Uploading...' : (formPicture.recentlySuccessful ?
                                        'Saved!' : 'Save Photo') }}
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div
                            class="bg-white border border-slate-200/60 shadow-xl shadow-slate-200/30 rounded-[2.5rem] p-8">
                            <div class="flex justify-between items-center mb-8">
                                <h2 class="text-xl font-bold text-slate-900 flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                                        <User class="w-5 h-5" />
                                    </div>
                                    Personal Information
                                </h2>
                                <span v-if="formProfile.isDirty"
                                    class="text-xs font-bold text-amber-500 bg-amber-50 px-3 py-1 rounded-full animate-pulse">
                                    Unsaved Changes
                                </span>
                            </div>

                            <form @submit.prevent="submitProfile" class="space-y-6">
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Full
                                            Name</label>
                                        <div class="relative group">
                                            <User
                                                class="absolute top-3.5 left-4 w-5 h-5 text-slate-400 group-focus-within:text-violet-500 transition-colors" />
                                            <input v-model="formProfile.name" type="text"
                                                class="block w-full pl-12 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 focus:bg-white focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 transition-all text-sm font-medium">
                                        </div>
                                        <p v-if="formProfile.errors.name" class="text-red-500 text-xs mt-1 ml-1">{{
                                            formProfile.errors.name }}</p>
                                    </div>

                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Email
                                            (Read Only)</label>
                                        <div class="relative">
                                            <Mail class="absolute top-3.5 left-4 w-5 h-5 text-slate-400" />
                                            <input :value="formProfile.email" disabled type="email"
                                                class="block w-full pl-12 py-3 bg-slate-100 border border-slate-200 rounded-2xl text-slate-500 cursor-not-allowed text-sm font-medium">
                                        </div>
                                    </div>

                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Gender</label>
                                        <div class="relative group">
                                            <div
                                                class="absolute top-3.5 left-4 w-5 h-5 flex items-center justify-center">
                                                <span
                                                    class="text-lg leading-none text-slate-400 group-focus-within:text-violet-500">⚥</span>
                                            </div>
                                            <select v-model="formProfile.gender"
                                                class="block w-full pl-12 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 focus:bg-white focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 transition-all text-sm font-medium appearance-none">
                                                <option value="L">Male</option>
                                                <option value="P">Female</option>
                                            </select>
                                            <ChevronRight
                                                class="absolute top-3.5 right-4 w-5 h-5 text-slate-400 rotate-90 pointer-events-none" />
                                        </div>
                                    </div>

                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Phone
                                            Number</label>
                                        <div class="relative group">
                                            <Phone
                                                class="absolute top-3.5 left-4 w-5 h-5 text-slate-400 group-focus-within:text-violet-500 transition-colors" />
                                            <input v-model="formProfile.phone" type="text"
                                                class="block w-full pl-12 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 focus:bg-white focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 transition-all text-sm font-medium"
                                                placeholder="+62...">
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label
                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Address</label>
                                    <div class="relative group">
                                        <MapPin
                                            class="absolute top-3.5 left-4 w-5 h-5 text-slate-400 group-focus-within:text-violet-500 transition-colors" />
                                        <input v-model="formProfile.address" type="text"
                                            class="block w-full pl-12 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 focus:bg-white focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 transition-all text-sm font-medium"
                                            placeholder="Full Address">
                                    </div>
                                </div>

                                <div>
                                    <label
                                        class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-2 ml-1 flex items-center gap-2">
                                        Public Profile URL <span
                                            class="bg-violet-100 text-violet-700 text-[10px] px-2 py-0.5 rounded-full">Unique</span>
                                    </label>
                                    <div
                                        class="flex items-center bg-slate-50 border border-slate-200 rounded-2xl focus-within:bg-white focus-within:border-violet-500 focus-within:ring-4 focus-within:ring-violet-500/10 transition-all overflow-hidden">
                                        <div
                                            class="pl-4 pr-2 py-3 bg-transparent flex items-center gap-2 text-slate-500 border-r border-slate-200/50">
                                            <Link2 class="h-4 w-4" />
                                            <span class="text-sm font-medium">keyperson.id/</span>
                                        </div>
                                        <input v-model="formProfile.slug_name" type="text"
                                            class="flex-1 border-none bg-transparent focus:ring-0 text-slate-900 placeholder-slate-400 text-sm font-medium py-3 pl-2"
                                            placeholder="username">
                                    </div>
                                    <p v-if="formProfile.errors.slug_name" class="text-red-500 text-xs mt-1 ml-1">{{
                                        formProfile.errors.slug_name }}</p>
                                </div>

                                <div class="pt-6 border-t border-slate-100 flex items-center justify-end">
                                    <button type="submit" :disabled="!formProfile.isDirty || formProfile.processing"
                                        class="px-8 py-3 rounded-xl font-bold text-sm transition-all duration-300 flex items-center gap-2 shadow-lg shadow-violet-200 transform active:scale-95 disabled:shadow-none disabled:opacity-50 disabled:cursor-not-allowed"
                                        :class="{
                                            'bg-green-600 text-white hover:bg-green-700': formProfile.recentlySuccessful,
                                            'bg-violet-600 text-white hover:bg-violet-700': !formProfile.recentlySuccessful
                                        }">
                                        <Loader2 v-if="formProfile.processing" class="animate-spin h-4 w-4" />
                                        <Check v-else-if="formProfile.recentlySuccessful" class="h-4 w-4" />
                                        <Save v-else class="h-4 w-4" />
                                        {{ formProfile.processing ? 'Saving...' : (formProfile.recentlySuccessful ?
                                        'Saved Successfully' : 'Save Changes') }}
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div
                            class="bg-white border border-slate-200/60 shadow-xl shadow-slate-200/30 rounded-[2.5rem] p-8">
                            <div class="mb-8">
                                <h2 class="text-xl font-bold text-slate-900 flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center text-red-600">
                                        <Lock class="w-5 h-5" />
                                    </div>
                                    Security & Password
                                </h2>
                            </div>

                            <form @submit.prevent="submitPassword" class="space-y-6">
                                <div v-if="user.has_password">
                                    <label
                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Current
                                        Password</label>
                                    <div class="relative group">
                                        <Lock
                                            class="absolute top-3.5 left-4 w-5 h-5 text-slate-400 group-focus-within:text-violet-500 transition-colors" />
                                        <input v-model="formPassword.current_password"
                                            :type="showCurrentPassword ? 'text' : 'password'"
                                            class="block w-full pl-12 pr-12 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 focus:bg-white focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 transition-all text-sm font-medium"
                                            placeholder="••••••••">
                                        <button type="button" @click="showCurrentPassword = !showCurrentPassword"
                                            class="absolute top-0 right-0 h-full px-4 text-slate-400 hover:text-slate-600 focus:outline-none">
                                            <component :is="showCurrentPassword ? EyeOff : Eye" class="h-5 w-5" />
                                        </button>
                                    </div>
                                    <p v-if="formPassword.errors.current_password"
                                        class="text-red-500 text-xs mt-1 ml-1 flex items-center gap-1">
                                        <AlertCircle class="w-3 h-3" /> {{ formPassword.errors.current_password }}
                                    </p>
                                </div>

                                <div class="grid md:grid-cols-2 gap-6">
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">New
                                            Password</label>
                                        <div class="relative group">
                                            <Lock
                                                class="absolute top-3.5 left-4 w-5 h-5 text-slate-400 group-focus-within:text-violet-500 transition-colors" />
                                            <input v-model="formPassword.new_password"
                                                :type="showNewPassword ? 'text' : 'password'"
                                                class="block w-full pl-12 pr-12 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 focus:bg-white focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 transition-all text-sm font-medium"
                                                placeholder="••••••••">
                                            <button type="button" @click="showNewPassword = !showNewPassword"
                                                class="absolute top-0 right-0 h-full px-4 text-slate-400 hover:text-slate-600 focus:outline-none">
                                                <component :is="showNewPassword ? EyeOff : Eye" class="h-5 w-5" />
                                            </button>
                                        </div>

                                        <div class="h-1 w-full bg-slate-100 mt-2 rounded-full overflow-hidden"
                                            v-if="formPassword.new_password">
                                            <div class="h-full bg-violet-500 transition-all duration-500"
                                                :style="{ width: Math.min(formPassword.new_password.length * 10, 100) + '%' }">
                                            </div>
                                        </div>

                                        <p v-if="formPassword.errors.new_password"
                                            class="text-red-500 text-xs mt-1 ml-1 flex items-center gap-1">
                                            <AlertCircle class="w-3 h-3" /> {{ formPassword.errors.new_password }}
                                        </p>
                                    </div>

                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1">Confirm
                                            Password</label>
                                        <div class="relative group">
                                            <Lock class="absolute top-3.5 left-4 w-5 h-5 transition-colors"
                                                :class="passwordMismatch ? 'text-red-400' : 'text-slate-400 group-focus-within:text-violet-500'" />
                                            <input v-model="formPassword.new_password_confirmation"
                                                :type="showNewPassword ? 'text' : 'password'"
                                                class="block w-full pl-12 pr-12 py-3 bg-slate-50 border rounded-2xl text-slate-900 focus:bg-white focus:ring-4 transition-all text-sm font-medium"
                                                :class="passwordMismatch ? 'border-red-300 focus:border-red-500 focus:ring-red-500/10' : 'border-slate-200 focus:border-violet-500 focus:ring-violet-500/10'"
                                                placeholder="••••••••">

                                            <div class="absolute top-0 right-0 h-full px-4 flex items-center pointer-events-none"
                                                v-if="formPassword.new_password_confirmation">
                                                <AlertCircle v-if="passwordMismatch" class="h-5 w-5 text-red-500" />
                                                <Check v-else class="h-5 w-5 text-green-500" />
                                            </div>
                                        </div>
                                        <p v-if="passwordMismatch"
                                            class="text-red-500 text-xs mt-1 ml-1 font-bold animate-pulse">Passwords do
                                            not match.</p>
                                    </div>
                                </div>

                                <div class="pt-6 border-t border-slate-100 flex items-center justify-end">
                                    <button type="submit" :disabled="formPassword.processing"
                                        class="px-8 py-3 rounded-xl font-bold text-sm transition-all duration-300 flex items-center gap-2 shadow-lg shadow-slate-200 transform active:scale-95 disabled:shadow-none disabled:opacity-50 disabled:cursor-not-allowed"
                                        :class="{
                                            'bg-green-600 text-white hover:bg-green-700': formPassword.recentlySuccessful,
                                            'bg-slate-900 text-white hover:bg-slate-800': !formPassword.recentlySuccessful
                                        }">
                                        <Loader2 v-if="formPassword.processing" class="animate-spin h-4 w-4" />
                                        <Check v-else-if="formPassword.recentlySuccessful" class="h-4 w-4" />
                                        <Lock v-else class="h-4 w-4" />
                                        {{ formPassword.processing ? 'Updating...' : (formPassword.recentlySuccessful ?
                                        'Password Updated' : 'Update Password') }}
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </AppLayout>
</template>