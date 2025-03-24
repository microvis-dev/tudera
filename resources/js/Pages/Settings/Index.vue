<script setup>
import { computed, reactive, watchEffect } from 'vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

const page = usePage()
const user = computed(() => {
    return page.props.user
})

const viewState = reactive({
    formChanged: false
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

</script>

<template>
    <h2>Settings</h2>
    <form @submit.prevent="saveUserChanges" class="space-y-4">
        <div class="flex flex-col">
            <label for="name" class="mb-2 text-sm font-medium text-gray-700">Name:</label>
            <input type="text" id="name" v-model="accountSettingsForm.name"
                class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-black" />
        </div>
        <div class="flex flex-col">
            <label for="phone" class="mb-2 text-sm font-medium text-gray-700">Phone Number:</label>
            <input type="text" id="phone" v-model="accountSettingsForm.phone_number"
                class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-black" />
        </div>
        <div class="flex flex-col">
            <label for="old-password" class="mb-2 text-sm font-medium text-gray-700">Old Password:</label>
            <input type="password" id="old-password" v-model="accountSettingsForm.old_password"
                class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-black" />
        </div>
        <div class="flex flex-col">
            <label for="new-password" class="mb-2 text-sm font-medium text-gray-700">New Password:</label>
            <input type="password" id="new-password" v-model="accountSettingsForm.password"
                class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-black" />
        </div>
        <div class="flex flex-col">
            <label for="password-confirmation" class="mb-2 text-sm font-medium text-gray-700">Confirm New
                Password:</label>
            <input type="password" id="password-confirmation" v-model="accountSettingsForm.password_confirmation"
                class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-black" />
        </div>
        <div class="flex justify-end">
            <button v-if="viewState.formChanged" type="submit"
                class="px-4 py-2 font-medium text-white bg-indigo-600 rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Save
            </button>
        </div>
    </form>
</template>

<style scoped></style>