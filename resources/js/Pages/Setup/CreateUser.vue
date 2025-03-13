<script setup>
import AuthLayout from "../../Layout/AuthLayout.vue";
import {ref, watchEffect, defineOptions, computed, reactive} from "vue";
import {useForm} from "@inertiajs/vue3";
import {route} from "ziggy-js";

defineOptions({
    layout: AuthLayout
})

const props = defineProps({
    email: String
})

const name = reactive({
    first: '',
    last: '',
    getFullName() {
        return this.first + " " + this.last
    }
})

const userForm = useForm({
    name: computed(() => name.getFullName()),
    phone: '',
    email: props.email,
    password: '',
    redirectTo: 'setup.workspace.create'
})

const submitForm = () => {
    userForm.post(route('user.store'))
}

</script>

<template>
    <div class="flex flex-col h-full items-center">
        <header class="px-20 pt-20 pb-10 w-full flex flex-col items-start">
            <p class="text-sm roboto-font-medium mb-1 text-[#B3B3B3]">1/5</p>
            <h1 class="text-4xl roboto-font-bold">Let's get to know you</h1>
            <div class="flex flex-row py-5">
                <div class="bg-blue-600 w-20 h-20 rounded-full"></div>
                <div class="flex flex-col ms-5">
                    <h2 class="roboto-font-bold text-lg mb-2">Profile picture</h2>
                    <div class="flex flex-row mb-2">
                        <button class="border border-[#5D5E5B] rounded-md p-2 me-2 flex items-start focus:outline-none hover:ring-2 hover:ring-gray-500 hover:border-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#757575" class="bi bi-upload" viewBox="0 0 16 16">
                                <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5" stroke-width="2"/>
                                <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708z" stroke-width="2"/>
                            </svg>
                            <span class=px-6>Upload image</span>
                        </button>
                        <button class="border border-[#5D5E5B] text-[#878787] rounded-lg p-2 px-5" disabled>Remove</button>
                    </div>
                    <p class="text-xs roboto-font-bold text-[#B3B3B3]">*.png, *.jpg files up to 10MB at least 400px by
                        400px</p>
                </div>
            </div>
        </header>
        <main class="flex flex-col">
            <h1 class="roboto-font-bold text-3xl capitalize p-1">{{ null }}</h1>
            <p class="text-[#B3B3B3] text-center roboto-font-light text-sm mb-5 w-72">{{ null }}</p>
            <form @submit.prevent="submitForm" class="flex flex-col w-full space-y-5">
                <div class="flex flex-col space-y-1">
                    <label for="first-name" class="text-[#B3B3B3] text-sm font-medium">
                        First name
                    </label>
                    <input
                        id="first-name"
                        v-model="name.first"
                        type="text"
                        placeholder="Enter your first name..."
                        class="w-full px-3 py-2 bg-[#1C1D21] text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 placeholder-gray-500"
                    />
                </div>
                <div class="flex flex-col space-y-1">
                    <label for="last-name" class="text-[#B3B3B3] text-sm font-medium">
                        Last name
                    </label>
                    <input
                        id="last-name"
                        type="text"
                        v-model="name.last"
                        placeholder="Enter your last name..."
                        class="w-full px-3 py-2 bg-[#1C1D21] text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 placeholder-gray-500"
                    />
                    <p v-if="userForm.errors.name">{{ userForm.errors.name }}</p>
                </div>
                <div class="flex flex-col space-y-1">
                    <label for="tel" class="text-[#B3B3B3] text-sm font-medium">
                        Phone
                    </label>
                    <input
                        id="tel"
                        type="tel"
                        v-model="userForm.phone"
                        placeholder="Enter your phone..."
                        class="w-full px-3 py-2 bg-[#1C1D21] text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 placeholder-gray-500"
                    />
                    <p v-if="userForm.errors.phone">{{ userForm.errors.phone }}</p>
                </div>
                <div class="flex flex-col space-y-1">
                    <label for="password" class="text-[#B3B3B3] text-sm font-medium">
                        Password
                    </label>
                    <input
                        id="password"
                        type="password"
                        v-model="userForm.password"
                        placeholder="Enter your password"
                        class="w-full px-3 py-2 bg-[#1C1D21] text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 placeholder-gray-500"
                    />
                    <p v-if="userForm.errors.password">{{ userForm.errors.password }}</p>
                </div>
                <div class="flex flex-col space-y-1">
                    <label for="email" class="text-[#B3B3B3] text-sm font-medium">
                        Email
                    </label>
                    <input
                        id="email"
                        type="text"
                        v-model="userForm.email"
                        disabled
                        class="w-full px-3 py-2 bg-[#1C1D21] text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 placeholder-gray-500"
                    />
                    <p v-if="userForm.errors.email">{{ userForm.errors.email }}</p>
                </div>
                <input type="hidden" name="register" value="true">
                <button type="submit" class="bg-blue-600 rounded-lg px-3 py-2 mt-8 roboto-font-light">
                    Next
                </button>
            </form>
        </main>
    </div>
</template>
<style scoped>
</style>
