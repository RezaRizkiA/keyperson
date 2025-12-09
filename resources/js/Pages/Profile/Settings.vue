<script setup>
import { Head, useForm, usePage } from '@inertiajs/vue3';
// import MainLayout from '../../Layouts/MainLayout.vue';
import AppLayout from '../../Layouts/AppLayout.vue';

import { ref, computed } from 'vue';
import { Lock, Eye, EyeOff, Check, Loader2, XCircle, CheckCircle2, User, Mail, Phone, MapPin, Link2, Save } from 'lucide-vue-next';

const props = defineProps({
    user: Object
});

// 1. Form Profile Information
const formProfile = useForm({
    name: props.user.name,
    gender: props.user.gender || 'L',
    email: props.user.email, // Readonly
    phone: props.user.phone || '',
    address: props.user.address || '',
    slug_name: props.user.slug || '',
});

const submitProfile = () => {
    formProfile.post(route('renew_profile'), {
        preserveScroll: true,
        onSuccess: () => { /* Optional: Show toast */ }
    });
};

// 2. Form Change Password
const showCurrentPassword = ref(false);
const showNewPassword = ref(false);

const formPassword = useForm({
    current_password: '',
    new_password: '',
    new_password_confirmation: '',
});

const passwordMismatch = computed(() => {
    // Hanya cek jika konfirmasi sudah diisi
    if (!formPassword.new_password_confirmation) return false;

    // Return true jika TIDAK sama
    return formPassword.new_password !== formPassword.new_password_confirmation;
});

const submitPassword = () => {
    formPassword.post(route('renew_password'), {
        preserveScroll: true,
        onSuccess: () => formPassword.reset(),
    });
};

// 3. Form Change Picture
const formPicture = useForm({
    picture: null,
});
const previewUrl = ref(props.user.picture_url);

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
        <div class="bg-slate-50 min-h-screen pt-24 pb-20">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

                <div class="mb-10">
                    <h1 class="font-display text-3xl font-bold text-slate-900">Account Settings</h1>
                    <p class="text-slate-500">Manage your profile, password, and account preferences.</p>
                </div>

                <div class="grid lg:grid-cols-3 gap-8">

                    <div class="space-y-8">

                        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                            <h3 class="font-bold text-slate-900 mb-4 border-b border-slate-100 pb-2">Profile Picture
                            </h3>

                            <form @submit.prevent="submitPicture" class="text-center">

                                <div class="relative w-32 h-32 mx-auto mb-4 group">
                                    <div
                                        class="w-32 h-32 rounded-full overflow-hidden border-4 border-slate-100 bg-slate-100 shadow-sm">
                                        <img v-if="previewUrl" :src="previewUrl" class="w-full h-full object-cover">
                                        <div v-else
                                            class="w-full h-full flex items-center justify-center text-slate-400">
                                            <i class="ti ti-user text-4xl"></i>
                                        </div>
                                    </div>

                                    <label
                                        class="absolute inset-0 flex items-center justify-center bg-black/50 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                                        <i class="ti ti-camera text-2xl"></i>
                                        <input type="file" class="hidden" accept="image/*"
                                            @change="handlePictureChange">
                                    </label>
                                </div>

                                <p class="text-xs text-slate-400 mb-4">Allowed JPG, GIF or PNG. Max 800K</p>

                                <button type="submit" :disabled="!formPicture.isDirty || formPicture.processing"
                                    class="w-full py-2 rounded-lg font-bold text-sm transition-all duration-300 flex items-center justify-center gap-2"
                                    :class="{
                                        'bg-green-600 text-white hover:bg-green-700': formPicture.recentlySuccessful,
                                        'bg-red-50 text-red-600 border border-red-200': formPicture.hasErrors,
                                        'bg-violet-600 text-white hover:bg-violet-700': !formPicture.recentlySuccessful && !formPicture.hasErrors,
                                        'opacity-50 cursor-not-allowed': !formPicture.isDirty || formPicture.processing
                                    }">
                                    <template v-if="formPicture.processing">
                                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        Uploading...
                                    </template>

                                    <template v-else-if="formPicture.recentlySuccessful">
                                        <i class="ti ti-check text-lg"></i> Saved Successfully!
                                    </template>

                                    <template v-else-if="formPicture.hasErrors">
                                        <i class="ti ti-alert-circle text-lg"></i> Failed. Try Again.
                                    </template>

                                    <template v-else>
                                        Save New Picture
                                    </template>
                                </button>
                            </form>
                        </div>

                        <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-xl shadow-slate-200/50">
                            <div class="mb-6 pb-4 border-b border-slate-100">
                                <h3 class="font-display text-lg font-bold text-slate-900">Security</h3>
                                <p class="text-sm text-slate-500">Ensure your account is using a strong password.</p>
                            </div>

                            <form @submit.prevent="submitPassword" class="space-y-5">

                                <div v-if="user.has_password">
                                    <label
                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Current
                                        Password</label>
                                    <div class="relative group">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <Lock
                                                class="h-5 w-5 text-slate-400 group-focus-within:text-violet-500 transition-colors" />
                                        </div>
                                        <input v-model="formPassword.current_password"
                                            :type="showCurrentPassword ? 'text' : 'password'"
                                            class="block w-full pl-10 pr-10 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:bg-white focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 transition-all duration-200 sm:text-sm"
                                            placeholder="••••••••">
                                        <button type="button" @click="showCurrentPassword = !showCurrentPassword"
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none">
                                            <component :is="showCurrentPassword ? EyeOff : Eye" class="h-5 w-5" />
                                        </button>
                                    </div>
                                    <p v-if="formPassword.errors.current_password"
                                        class="text-red-500 text-xs mt-2 flex items-center gap-1 font-medium">
                                        <span class="w-1 h-1 rounded-full bg-red-500"></span>
                                        {{ formPassword.errors.current_password }}
                                    </p>
                                </div>

                                <div>
                                    <label
                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">New
                                        Password</label>
                                    <div class="relative group">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <Lock
                                                class="h-5 w-5 text-slate-400 group-focus-within:text-violet-500 transition-colors" />
                                        </div>
                                        <input v-model="formPassword.new_password"
                                            :type="showNewPassword ? 'text' : 'password'"
                                            class="block w-full pl-10 pr-10 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:bg-white focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 transition-all duration-200 sm:text-sm"
                                            placeholder="••••••••">
                                        <button type="button" @click="showNewPassword = !showNewPassword"
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none">
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
                                        class="text-red-500 text-xs mt-2 flex items-center gap-1 font-medium">
                                        <span class="w-1 h-1 rounded-full bg-red-500"></span>
                                        {{ formPassword.errors.new_password }}
                                    </p>
                                </div>

                                <div>
                                    <label
                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Confirm
                                        Password</label>
                                    <div class="relative group">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <Lock class="h-5 w-5 transition-colors"
                                                :class="passwordMismatch ? 'text-red-400' : 'text-slate-400 group-focus-within:text-violet-500'" />
                                        </div>

                                        <input v-model="formPassword.new_password_confirmation"
                                            :type="showNewPassword ? 'text' : 'password'"
                                            class="block w-full pl-10 py-3 bg-slate-50 border rounded-xl text-slate-900 placeholder-slate-400 focus:bg-white focus:ring-4 transition-all duration-200 sm:text-sm"
                                            :class="[
                                                passwordMismatch
                                                    ? 'border-red-300 focus:border-red-500 focus:ring-red-500/10'
                                                    : 'border-slate-200 focus:border-violet-500 focus:ring-violet-500/10'
                                            ]" placeholder="••••••••">

                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none"
                                            v-if="formPassword.new_password_confirmation">
                                            <XCircle v-if="passwordMismatch" class="h-5 w-5 text-red-500" />
                                            <CheckCircle2 v-else class="h-5 w-5 text-green-500" />
                                        </div>
                                    </div>

                                    <p v-if="passwordMismatch"
                                        class="text-red-500 text-xs mt-2 flex items-center gap-1 font-medium animate-pulse">
                                        <span class="w-1 h-1 rounded-full bg-red-500"></span>
                                        Passwords do not match.
                                    </p>
                                </div>

                                <button type="submit" :disabled="formPassword.processing"
                                    class="w-full py-3 mt-2 rounded-xl font-bold text-sm transition-all duration-300 flex items-center justify-center gap-2 shadow-lg shadow-slate-200 transform active:scale-95"
                                    :class="{
                                        'bg-green-600 text-white hover:bg-green-700': formPassword.recentlySuccessful,
                                        'bg-slate-900 text-white hover:bg-slate-800 hover:shadow-slate-900/20': !formPassword.recentlySuccessful,
                                        'opacity-70 cursor-not-allowed': formPassword.processing
                                    }">
                                    <template v-if="formPassword.processing">
                                        <Loader2 class="animate-spin h-4 w-4" />
                                        Updating...
                                    </template>

                                    <template v-else-if="formPassword.recentlySuccessful">
                                        <Check class="h-5 w-5" />
                                        Password Updated
                                    </template>

                                    <template v-else>
                                        Update Password
                                    </template>
                                </button>
                            </form>
                        </div>

                    </div>

                    <div class="lg:col-span-2">
                        <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-xl shadow-slate-200/50">
                            <div class="mb-8 pb-4 border-b border-slate-100 flex justify-between items-end">
                                <div>
                                    <h3 class="font-display text-lg font-bold text-slate-900">Personal Information</h3>
                                    <p class="text-sm text-slate-500">Update your personal details and public profile
                                        URL.</p>
                                </div>
                                <div v-if="formProfile.isDirty"
                                    class="text-xs font-bold text-amber-500 bg-amber-50 px-3 py-1 rounded-full animate-pulse">
                                    Unsaved Changes
                                </div>
                            </div>

                            <form @submit.prevent="submitProfile" class="space-y-6">
                                <div class="grid md:grid-cols-2 gap-6">

                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Full
                                            Name</label>
                                        <div class="relative group">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <User
                                                    class="h-5 w-5 text-slate-400 group-focus-within:text-violet-500 transition-colors" />
                                            </div>
                                            <input v-model="formProfile.name" type="text"
                                                class="block w-full pl-10 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:bg-white focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 transition-all duration-200 sm:text-sm">
                                        </div>
                                        <p v-if="formProfile.errors.name" class="text-red-500 text-xs mt-1">{{
                                            formProfile.errors.name }}</p>
                                    </div>

                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Gender</label>
                                        <div class="relative group">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <Users
                                                    class="h-5 w-5 text-slate-400 group-focus-within:text-violet-500 transition-colors" />
                                            </div>
                                            <select v-model="formProfile.gender"
                                                class="block w-full pl-10 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:bg-white focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 transition-all duration-200 sm:text-sm appearance-none">
                                                <option value="L">Male</option>
                                                <option value="P">Female</option>
                                            </select>
                                            <div
                                                class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Email
                                            Address</label>
                                        <div class="relative">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <Mail class="h-5 w-5 text-slate-400" />
                                            </div>
                                            <input :value="formProfile.email" disabled type="email"
                                                class="block w-full pl-10 py-3 bg-slate-100 border border-slate-200 rounded-xl text-slate-500 cursor-not-allowed sm:text-sm">
                                            <div
                                                class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <span
                                                    class="text-xs bg-slate-200 text-slate-500 px-2 py-0.5 rounded">Locked</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Phone
                                            Number</label>
                                        <div class="relative group">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <Phone
                                                    class="h-5 w-5 text-slate-400 group-focus-within:text-violet-500 transition-colors" />
                                            </div>
                                            <input v-model="formProfile.phone" type="text"
                                                class="block w-full pl-10 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:bg-white focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 transition-all duration-200 sm:text-sm"
                                                placeholder="+62 8...">
                                        </div>
                                    </div>

                                </div>

                                <div>
                                    <label
                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Address</label>
                                    <div class="relative group">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <MapPin
                                                class="h-5 w-5 text-slate-400 group-focus-within:text-violet-500 transition-colors" />
                                        </div>
                                        <input v-model="formProfile.address" type="text"
                                            class="block w-full pl-10 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:bg-white focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 transition-all duration-200 sm:text-sm"
                                            placeholder="Street, City, Country">
                                    </div>
                                </div>

                                <div>
                                    <label
                                        class="block text-xs font-bold text-slate-900 uppercase tracking-wider mb-2 flex items-center gap-2">
                                        Unique URL <span
                                            class="bg-violet-100 text-violet-700 text-[10px] px-2 py-0.5 rounded-full">Public
                                            Profile</span>
                                    </label>
                                    <div
                                        class="flex items-center bg-slate-50 border border-slate-200 rounded-xl focus-within:bg-white focus-within:border-violet-500 focus-within:ring-4 focus-within:ring-violet-500/10 transition-all duration-200 overflow-hidden group">
                                        <div
                                            class="pl-4 pr-1 py-3 bg-transparent flex items-center gap-2 text-slate-500 border-r border-slate-200/50">
                                            <Link2 class="h-4 w-4" />
                                            <span class="text-sm font-medium">keyperson.id/</span>
                                        </div>
                                        <input v-model="formProfile.slug_name" type="text"
                                            class="flex-1 border-none bg-transparent focus:ring-0 text-slate-900 placeholder-slate-400 text-sm py-3 pl-2"
                                            placeholder="username">
                                    </div>
                                    <p v-if="formProfile.errors.slug_name" class="text-red-500 text-xs mt-1">{{
                                        formProfile.errors.slug_name }}</p>
                                </div>

                                <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-4">
                                    <a :href="route('profile')"
                                        class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-slate-50 hover:text-slate-800 transition-all">
                                        Cancel
                                    </a>

                                    <button type="submit" :disabled="!formProfile.isDirty || formProfile.processing"
                                        class="px-8 py-3 rounded-xl font-bold text-sm transition-all duration-300 flex items-center gap-2 shadow-lg shadow-violet-200 transform active:scale-95 disabled:shadow-none disabled:opacity-50 disabled:cursor-not-allowed"
                                        :class="{
                                            'bg-green-600 text-white hover:bg-green-700': formProfile.recentlySuccessful,
                                            'bg-violet-600 text-white hover:bg-violet-700': !formProfile.recentlySuccessful
                                        }">
                                        <template v-if="formProfile.processing">
                                            <Loader2 class="animate-spin h-4 w-4" />
                                            Saving...
                                        </template>

                                        <template v-else-if="formProfile.recentlySuccessful">
                                            <Check class="h-5 w-5" />
                                            Saved!
                                        </template>

                                        <template v-else>
                                            <Save class="h-4 w-4" />
                                            Save Changes
                                        </template>
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