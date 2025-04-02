<script setup>
import { computed, reactive, watchEffect } from 'vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import Account from './Components/Account.vue';
import Appearance from './Components/Appearance.vue';

const page = usePage()

const user = computed(() => {
    return page.props.user
})

const flash = computed(() => {
    return page.props.flash
})

const viewState = reactive({
    formChanged: false,
    showAccountSettings: false,
    showDeleteUser: false,
    showChangePassword: false,
    showAppearanceSettings: false, 
    toggleForm(formName) {
        this.showAccountSettings = false;
        this.showDeleteUser = false;
        this.showAppearanceSettings = false;
        this.showChangePassword = false;

        this[formName] = !this[formName];
    }
});

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
    <!-- <h1>Settings</h1>
    <button class="px-4 py-2 font-medium text-white bg-blue-600 rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
        Manage account access
    </button>
    <br><br>
    <button @click="viewState.toggleForm('showAccountSettingsForm')" class="px-4 py-2 font-medium text-white bg-blue-600 rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
        Change name/phone
    </button>
    <form @submit.prevent="saveUserChanges" v-if="viewState.showAccountSettingsForm">
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
        <div class="flex justify-end">
            <button v-if="viewState.formChanged" type="submit"
                class="px-4 py-2 font-medium text-white bg-indigo-600 rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Save
        </button>
        </div>
    </form>
    <button @click="viewState.toggleForm('showChangePasswordForm')" class="px-4 py-2 font-medium text-white bg-blue-600 rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
        Change password
    </button>
    <form v-if="viewState.showChangePasswordForm" @submit.prevent="saveUserChanges" class="space-y-4">
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
    <button @click="viewState.toggleForm('showDeleteUserForm')" class="px-4 py-2 font-medium text-white bg-blue-600 rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
        Delete account
    </button>
    <form v-if="viewState.showDeleteUserForm" @submit.prevent="deleteUser" class="space-y-4">
        <div class="flex flex-col">
            <label for="old-password" class="mb-2 text-sm font-medium text-gray-700">Password</label>
            <input type="password" id="old-password" v-model="accountSettingsForm.password"
                class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-black" />
        </div>
        <div class="flex justify-end">
            <button v-if="accountSettingsForm.password" type="submit"
                class="px-4 py-2 font-medium text-white bg-red-600 rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                Delete
            </button>
        </div>
    </form> -->
    <div class="flex flex-row">
        <section class="h-screen w-1/3 py-6 ps-6">
            <div class="py-6 ps-6 flex flex-row justify-around border-b border-r border-slate-500">
                <h1 class="text-2xl roboto-font-bold">Settings</h1>
            </div>
            <div class="flex flex-col h-screen justify-start border-r border-slate-500 items-center">
                <h2 class="my-5 text-2xl roboto-font-bold" @click="viewState.toggleForm('showAccountSettings')">Account</h2>
                <h2 class="my-5 text-2xl roboto-font-bold" @click="viewState.toggleForm('showAppearanceSettings')">Apperance</h2>
                <h2 class="my-5 text-2xl roboto-font-bold">Notifactions</h2>
                <h2 class="my-5 text-2xl roboto-font-bold">Workspace Settings</h2>
            </div>
        </section>
        <section class="h-screen w-2/3 py-6">
            <Account v-if="viewState.showAccountSettings" />
            <Appearance v-if="viewState.showAppearanceSettings" />
        </section>
    </div>
</template>

<style scoped></style>