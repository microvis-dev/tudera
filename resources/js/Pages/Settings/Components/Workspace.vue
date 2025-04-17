<script setup>
import { computed, reactive, watchEffect } from 'vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { useTuderaStore } from '@/resources/js/state/state';

const tuderaState = useTuderaStore()
const selectedWorkspace = computed(() => tuderaState.getSelectedWorkspace())

const viewState = reactive({
    formChanged: false, 
    showAccountSettingsForm: false,
    showDeleteUserForm: false,
    showChangePasswordForm: false,
    toggleForm(formName) {
        this.showAccountSettingsForm = false;
        this.showDeleteUserForm = false;
        this.showChangePasswordForm = !showChangePasswordForm;

        this[formName] = !this[formName];
    }
})

const saveUserChanges = () => {
    accountSettingsForm.put(route('user.update', { user: user.value.id }))
}

const deleteUser = () => {
    if (confirm('are you sure?')) {
        accountSettingsForm.delete(route('user.destroy', { user: user.value.id }))
    }
}
</script>
<template>
    <div class="py-6 ps-6">
        <h2 class="text-2xl roboto-font-bold">Workspace Settings</h2>
        <hr>
    </div>
    <div class="p-6 flex-col items-start">
        <section class="h-screen">
            <form>
                <div class="flex flex-col mb-5">
                    <label class="text-[#B3B3B3] roboto-font-regular">Workspace Name</label>
                    <input type="text"
                        class="px-3 py-2 bg-[#1C1D21] border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 placeholder-gray-500 text-sm"
                        v-model="selectedWorkspace.name">
                </div>
            </form>
            <div class="h-80 content-end">
                <div class="flex flex-row justify-around">
                    <button type="submit" disabled
                        class="px-4 py-2 font-medium text-white bg-red-600 rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Delete Workspace
                    </button>
                    <button type="submit" @click="saveUserChanges"
                        class="px-4 py-2 font-medium text-white bg-slate-800 hover:bg-blue-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Save Changes
                    </button>
                </div>
            </div>
        </section>
    </div>
</template>
<style scoped></style>