<script setup>
import { computed, reactive, watchEffect } from 'vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import Account from './Components/Account.vue';
import Workspace from './Components/Workspace.vue';

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
    showWorkspaceSettings: false,
    toggleForm(formName) {
        this.showAccountSettings = false;
        this.showDeleteUser = false;
        this.showAppearanceSettings = false;
        this.showChangePassword = false;
        this.showWorkspaceSettings = false;

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
    <div class="flex flex-col md:flex-row min-h-screen">
        <!-- Sidebar Section -->
        <section class="w-full md:w-1/3 md:max-w-xs lg:max-w-sm xl:max-w-md md:h-screen flex flex-col border-b md:border-b-0 md:border-r border-slate-300 dark:border-slate-700 dark:bg-slate-800">
            <!-- Sidebar Header -->
            <div class="p-4 sm:p-6 flex flex-row justify-start items-center border-b border-slate-300 dark:border-slate-700">
                <h1 class="text-xl sm:text-2xl roboto-font-bold ml-2 sm:ml-0">Settings</h1>
            </div>
            <!-- Sidebar Menu -->
            <nav class="flex flex-col p-4 sm:p-6 md:items-stretch md:flex-grow md:overflow-y-auto items" aria-label="Settings navigation">
                <div class="menuItems w-full">
                    <h2
                        class="my-1 sm:my-2 p-2 rounded-md text-base sm:text-lg roboto-font-bold cursor-pointer text-center md:text-left focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:focus-visible:ring-blue-400"
                        @click="viewState.toggleForm('showAccountSettings')"
                        tabindex="0"
                        role="button"
                        :aria-pressed="viewState.showAccountSettings"
                        :class="viewState.showAccountSettings ? 'bg-slate-200 dark:bg-slate-700 text-slate-900 dark:text-slate-100' : ' hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-slate-800 dark:hover:text-slate-200'"
                    >
                        Account
                    </h2>
                </div>
                <div class="menuItems w-full">
                    <h2
                        class="my-1 sm:my-2 p-2 rounded-md text-base sm:text-lg roboto-font-bold text-center md:text-left cursor-not-allowed"
                        aria-disabled="true"
                    >
                        Notifications
                    </h2>
                </div>
                <div class="menuItems w-full">
                    <h2
                        class="my-1 sm:my-2 p-2 rounded-md text-base sm:text-lg roboto-font-bold cursor-pointer text-center md:text-left focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:focus-visible:ring-blue-400"
                        @click="viewState.toggleForm('showWorkspaceSettings')"
                        tabindex="0"
                        role="button"
                        :aria-pressed="viewState.showWorkspaceSettings"
                        :class="viewState.showWorkspaceSettings ? 'bg-slate-200 dark:bg-slate-700 text-slate-900 dark:text-slate-100' : 'hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-slate-800 dark:hover:text-slate-200'"
                    >
                        Workspace Settings
                    </h2>
                </div>
            </nav>
        </section>

        <!-- Main Content Section -->
        <main class="w-full md:w-2/3 p-4 sm:p-6 md:h-screen md:overflow-y-auto">
            <Account v-if="viewState.showAccountSettings" />
            <Appearance v-if="viewState.showAppearanceSettings" />
            <Workspace v-if="viewState.showWorkspaceSettings" />
            
            <div v-if="!viewState.showAccountSettings && !viewState.showAppearanceSettings && !viewState.showWorkspaceSettings" class="hidden md:flex flex-col items-center justify-center h-full text-center">
                <svg class="w-16 h-16 text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <h3 class="text-xl font-semibold">Settings Panel</h3>
                <p class="mt-1">Select a category from the sidebar to view or modify settings.</p>
            </div>
        </main>
    </div>
</template>

<style scoped>
.menuItems :hover{
    text-decoration: underline;
}
h2:focus::before {
  content: "";
  position: absolute;
  top: 5px;
  left: -10px;
  width: 5px;
  height: 80%;
  border-radius: 5px;
  opacity: 1;
}

.items:hover> :not(:hover) {
  transition: 500ms;
  filter: blur(1px);
  transform: scale(0.95, 0.95);
}</style>