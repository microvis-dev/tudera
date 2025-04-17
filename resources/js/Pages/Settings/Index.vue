<script setup>
import { computed, reactive, watchEffect } from 'vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import Account from './Components/Account.vue';
import Appearance from './Components/Appearance.vue';
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
    <div class="flex flex-row">
        <section class="h-screen w-1/3 py-6 ps-6">
            <div class="py-6 ps-6 flex flex-row justify-around border-b border-r border-slate-500">
                <h1 class="text-2xl roboto-font-bold">Settings</h1>
            </div>
            <div class="flex flex-col h-screen justify-start border-r border-slate-500 items-center items">
                <div class="menuItems"><h2 class="my-5 text-2xl roboto-font-bold" @click="viewState.toggleForm('showAccountSettings')">Account</h2></div>
                <div class="menuItems"><h2 class="my-5 text-2xl roboto-font-bold" @click="viewState.toggleForm('showAppearanceSettings')">Apperance</h2></div>
                <div class="menuItems"><h2 class="my-5 text-2xl roboto-font-bold">Notifications</h2></div>
                <div class="menuItems"><h2 class="my-5 text-2xl roboto-font-bold" @click="viewState.toggleForm('showWorkspaceSettings')">Workspace Settings</h2></div>
            </div>
        </section>
        <section class="h-screen w-2/3 py-6">
            <Account v-if="viewState.showAccountSettings" />
            <Appearance v-if="viewState.showAppearanceSettings" />
            <Workspace v-if="viewState.showWorkspaceSettings" />
        </section>
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