<script setup>
import { computed, reactive, watchEffect } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { useTuderaStore } from '@/resources/js/state/state';

const tuderaState = useTuderaStore()
const user = computed(() => tuderaState.getUser())

const viewState = reactive({
    formChanged: false,
    showAccountSettingsForm: false,
    showDeleteUserForm: false,
    showChangePasswordForm: false,
    toggleForm(formName) {
        const shouldBecomeVisible = !this[formName];

        
        this.showAccountSettingsForm = false;
        this.showDeleteUserForm = false;
        this.showChangePasswordForm = false;

        if (shouldBecomeVisible) {
            this[formName] = true;
        }
    }
})

const accountSettingsForm = useForm({
    name: user.value.name,
    phone_number: user.value.phone_number,
    old_password: "",
    password: "",
    password_confirmation: "",
    isChanged() {
        return this.name != user.value.name ||
            this.phone_number != user.value.phone_number ||
            this.old_password != "" ||
            this.password != "" ||
            this.password_confirmation != ""
    }
})

watchEffect(() => {
    viewState.formChanged = accountSettingsForm.isChanged();
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
        <h2 class="text-2xl roboto-font-bold">Account</h2>
        <hr>
    </div>
    <div class="p-6 flex-col items-start">
        <section class="h-screen">
            <form>
                <div class="flex flex-col mb-5">
                    <label class="text-[#B3B3B3] roboto-font-regular">Name</label>
                    <input type="text"
                        class="px-3 py-2 bg-[#1C1D21] border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 placeholder-gray-500 text-sm"
                        v-model="accountSettingsForm.name">
                </div>
            </form>
            <form class="flex flex-col mb-5">
                <label class="mb-2 text-[#B3B3B3] roboto-font-regular">Password</label>
                <button @click.prevent="viewState.toggleForm('showChangePasswordForm')"
                    class="px-4 py-2 w-fit font-medium text-white bg-blue-600 rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Change password
                </button>
            </form>
            <form v-if="viewState.showChangePasswordForm" @submit.prevent="saveUserChanges" class="space-y-4">
                <div class="flex flex-col mb-5">
                    <label class="text-[#B3B3B3] roboto-font-regular">Old Password</label>
                    <input type="password"
                        class="px-3 py-2 bg-[#1C1D21] border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 placeholder-gray-500 text-sm"
                        v-model="accountSettingsForm.old_password">
                </div>
                <div class="flex flex-col mb-5">
                    <label class="text-[#B3B3B3] roboto-font-regular">New Password</label>
                    <input type="password"
                        class="px-3 py-2 bg-[#1C1D21] border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 placeholder-gray-500 text-sm"
                        v-model="accountSettingsForm.password">
                </div>
                <div class="flex flex-col mb-5">
                    <label class="text-[#B3B3B3] roboto-font-regular">Confirm New Password</label>
                    <input type="password"
                        class="px-3 py-2 bg-[#1C1D21] border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 placeholder-gray-500 text-sm"
                        v-model="accountSettingsForm.password_confirmation">
                </div>
                <div class="flex justify-end">
                    <button type="submit"
                        :disabled="accountSettingsForm.old_password === '' && accountSettingsForm.password === '' && accountSettingsForm.password_confirmation === ''"
                        class="px-4 py-2 font-medium text-white bg-indigo-600 rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        :class="{ 'opacity-50 cursor-not-allowed': accountSettingsForm.old_password === '' && accountSettingsForm.password === '' && accountSettingsForm.password_confirmation === '' }">
                        Save
                    </button>
                </div>
            </form>
            <div class="h-80 content-end">
                <div class="flex flex-row justify-around">
                    <button type="submit"
                        class="px-4 py-2 font-medium text-white bg-red-600 rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Delete Account
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