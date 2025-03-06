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
    <div class="flex flex-col h-full items-center px-4 md:px-10 lg:px-20">
        <header class="w-full max-w-screen-md pt-10 pb-6 flex flex-col items-start">
            <p class="text-sm font-medium text-gray-400">1/2</p>
            <h1 class="text-3xl md:text-4xl font-bold text-white">Let's get to know you</h1>

            <div class="flex flex-col md:flex-row items-center py-5 space-y-4 md:space-y-0 md:space-x-5">
                <div class="bg-blue-600 w-20 h-20 rounded-full"></div>
                <div class="flex flex-col">
                    <h2 class="font-bold text-lg mb-2">Profile picture</h2>
                    <div class="flex space-x-2">
                        <button class="border border-gray-600 rounded-md px-4 py-2 flex items-center space-x-2 hover:ring-2 hover:ring-gray-500 focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#757575" class="bi bi-upload">
                                <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5" />
                                <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708z" />
                            </svg>
                            <span>Upload image</span>
                        </button>
                        <button class="border border-gray-600 text-gray-500 rounded-md px-5 py-2" disabled>Remove</button>
                    </div>
                    <p class="text-xs font-bold text-gray-400 mt-2">*.png, *.jpg files up to 10MB at least 400px by 400px</p>
                </div>
            </div>
        </header>

        <main class="w-full max-w-screen-md">
            <form @submit.prevent="submitForm" class="flex flex-col space-y-5">
                <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
                    <div class="flex flex-col w-full">
                        <label for="first-name" class="text-gray-400 text-sm font-medium">First name</label>
                        <input
                            id="first-name"
                            v-model="name.first"
                            type="text"
                            placeholder="Enter your first name..."
                            class="w-full px-3 py-2 bg-[#1C1D21] text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 placeholder-gray-500"
                        />
                    </div>
                    <div class="flex flex-col w-full">
                        <label for="last-name" class="text-gray-400 text-sm font-medium">Last name</label>
                        <input
                            id="last-name"
                            type="text"
                            v-model="name.last"
                            placeholder="Enter your last name..."
                            class="w-full px-3 py-2 bg-[#1C1D21] text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 placeholder-gray-500"
                        />
                    </div>
                </div>

                <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
                    <div class="flex flex-col w-full">
                        <label for="tel" class="text-gray-400 text-sm font-medium">Phone</label>
                        <input
                            id="tel"
                            type="tel"
                            v-model="userForm.phone"
                            placeholder="Enter your phone..."
                            class="w-full px-3 py-2 bg-[#1C1D21] text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 placeholder-gray-500"
                        />
                    </div>
                    <div class="flex flex-col w-full">
                        <label for="password" class="text-gray-400 text-sm font-medium">Password</label>
                        <input
                            id="password"
                            type="password"
                            v-model="userForm.password"
                            placeholder="Enter your password"
                            class="w-full px-3 py-2 bg-[#1C1D21] text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 placeholder-gray-500"
                        />
                    </div>
                </div>

                <div class="w-full">
                    <button type="submit" class="bg-blue-600 text-white font-medium rounded-lg px-8 py-2 w-full mt-4">
                        Next
                    </button>
                </div>
            </form>
        </main>
    </div>
</template>

<style scoped>
</style>
