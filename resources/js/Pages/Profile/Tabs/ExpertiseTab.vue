<script setup>
import { ref, computed } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import { Settings, Edit, Trash2, Plus, X } from "lucide-vue-next";

const props = defineProps({
    expertises: Array,
});

const isModalOpen = ref(false);
const modalTitle = ref("Create Core Skill");
const isEditMode = ref(false);

const form = useForm({
    id: null,
    name: "",
    parent_id: "",
    file_ilustration_img: null,
});

// Helper to get parent options (Core Skills and their children)
// Since props.expertises is already flattened, we might need to filter or process it if we want a hierarchical select.
// However, the original Blade code iterated over $expertises for the select options too.
// Let's assume for now we can use the same list or we might need a separate prop for the tree structure if the flat list isn't enough.
// Looking at the controller, it seems $expertises passed to view is flat.
// But the select in Blade used `$expertises` variable which might have been the root ones?
// In AuthController.php:
// $roots = Expertise::whereNull('parent_id')->...->get();
// Then it flattens them into $expertises.
// So the $expertises prop is flat.
// The Blade select loop: @foreach ($expertises as $expertise) ...
// Wait, in Blade `profile.blade.php`:
// @foreach ($expertises as $expertise) <option ...>
// It seems it re-used the same variable name or there was a global sharing?
// Actually, in `profile()` method: `return view('profile', compact('expertises', ...))`
// So `$expertises` is the flat list.
// So we can iterate over props.expertises for the dropdown.

const openModal = (expertise = null, parent = null) => {
    form.reset();
    form.clearErrors();

    if (expertise) {
        // Edit Mode
        isEditMode.value = true;
        modalTitle.value = `Edit ${expertise.name}`;
        form.id = expertise.id;
        form.name = expertise.name;
        form.parent_id = expertise.parent_id || "";
        // We don't pre-fill file input
    } else if (parent) {
        // Add Sub-skill Mode
        isEditMode.value = false;
        modalTitle.value = `Add Skill under ${parent.name}`;
        form.parent_id = parent.id;
    } else {
        // Create Core Skill Mode
        isEditMode.value = false;
        modalTitle.value = "Create Core Skill";
        form.parent_id = "";
    }

    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    form.reset();
};

const submit = () => {
    if (isEditMode.value) {
        form.post(route("update_expertise", form.id), {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route("store_expertise"), {
            onSuccess: () => closeModal(),
        });
    }
};

const deleteExpertise = (id) => {
    if (confirm("Are you sure you want to delete this expertise?")) {
        router.get(route("destroy_expertise", id));
    }
};

const getLevelColor = (level) => {
    switch (level) {
        case 1:
            return "bg-blue-100 text-blue-800";
        case 2:
            return "bg-yellow-100 text-yellow-800";
        case 3:
            return "bg-green-100 text-green-800";
        default:
            return "bg-gray-100 text-gray-800";
    }
};
</script>

<template>
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-slate-900">
                Expertises Management
            </h3>
            <button
                @click="openModal()"
                class="px-4 py-2 bg-red-50 text-red-600 rounded-lg font-bold text-sm hover:bg-red-100 transition-colors"
            >
                Create Core Skill
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr
                        class="border-b border-slate-100 text-slate-500 text-sm uppercase tracking-wider"
                    >
                        <th class="py-4 font-semibold">Expertise Name</th>
                        <th class="py-4 font-semibold text-center">Experts</th>
                        <th class="py-4 font-semibold text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr
                        v-for="expertise in expertises"
                        :key="expertise.id"
                        class="group hover:bg-slate-50 transition-colors"
                    >
                        <td class="py-4">
                            <div class="flex items-center">
                                <span
                                    :class="[
                                        'px-2 py-1 rounded text-xs font-bold mr-3',
                                        getLevelColor(expertise.level),
                                    ]"
                                >
                                    Lvl.{{ expertise.level }}
                                </span>
                                <div>
                                    <h6 class="font-bold text-slate-900">
                                        {{ expertise.name }}
                                    </h6>
                                    <span class="text-xs text-slate-500">
                                        {{
                                            expertise.parent
                                                ? `Skill under ${expertise.parent.name}`
                                                : "Core Skill"
                                        }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 text-center">
                            <span class="font-bold text-slate-900">1</span>
                        </td>
                        <td class="py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button
                                    @click="openModal(expertise)"
                                    class="p-2 text-slate-400 hover:text-violet-600 transition-colors"
                                >
                                    <Edit class="w-5 h-5" />
                                </button>
                                <button
                                    @click="deleteExpertise(expertise.id)"
                                    class="p-2 text-slate-400 hover:text-red-600 transition-colors"
                                >
                                    <Trash2 class="w-5 h-5" />
                                </button>
                                <button
                                    v-if="expertise.level != 3"
                                    @click="openModal(null, expertise)"
                                    :class="[
                                        'ml-2 px-2 py-1 rounded text-xs font-bold',
                                        getLevelColor(expertise.level),
                                    ]"
                                >
                                    Add Skill
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Modal -->
        <div
            v-if="isModalOpen"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm"
        >
            <div
                class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden"
            >
                <div
                    class="px-6 py-4 border-b border-slate-100 flex justify-between items-center"
                >
                    <h5 class="text-lg font-bold text-slate-900">
                        {{ modalTitle }}
                    </h5>
                    <button
                        @click="closeModal"
                        class="text-slate-400 hover:text-slate-600"
                    >
                        <X class="w-6 h-6" />
                    </button>
                </div>

                <form @submit.prevent="submit" class="p-6 space-y-4">
                    <div>
                        <label
                            class="block text-sm font-medium text-slate-700 mb-1"
                            >Skill Name</label
                        >
                        <input
                            v-model="form.name"
                            type="text"
                            class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 outline-none transition-all"
                            required
                        />
                        <div
                            v-if="form.errors.name"
                            class="text-red-500 text-xs mt-1"
                        >
                            {{ form.errors.name }}
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-slate-700 mb-1"
                            >Scope Skill (Under)</label
                        >
                        <select
                            v-model="form.parent_id"
                            class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 outline-none transition-all"
                        >
                            <option value="">Core Skill</option>
                            <option
                                v-for="exp in expertises"
                                :key="exp.id"
                                :value="exp.id"
                            >
                                {{ exp.name }}
                            </option>
                        </select>
                        <div
                            v-if="form.errors.parent_id"
                            class="text-red-500 text-xs mt-1"
                        >
                            {{ form.errors.parent_id }}
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-slate-700 mb-1"
                            >Illustration Image</label
                        >
                        <input
                            @input="
                                form.file_ilustration_img =
                                    $event.target.files[0]
                            "
                            type="file"
                            class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 outline-none transition-all"
                        />
                        <div
                            v-if="form.errors.file_ilustration_img"
                            class="text-red-500 text-xs mt-1"
                        >
                            {{ form.errors.file_ilustration_img }}
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button
                            type="button"
                            @click="closeModal"
                            class="px-4 py-2 rounded-xl text-slate-600 font-medium hover:bg-slate-50 transition-colors"
                        >
                            Close
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 rounded-xl bg-violet-600 text-white font-bold hover:bg-violet-700 transition-colors shadow-lg shadow-violet-200"
                            :disabled="form.processing"
                        >
                            {{ form.processing ? "Saving..." : "Submit Form" }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
