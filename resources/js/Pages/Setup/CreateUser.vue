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
    <div class="flex flex-col h-full w-full items-center p-8 md:py-10 md:px-20">
        <header class="w-full max-w-screen">
            <section class="mb-5">
                <p class="ms-0.5 md:ms-0.7 text-sm roboto-font-regular text-[#B3B3B3]">1/2</p>
                <h1 class="text-2xl md:text-4xl roboto-font-bold">Let's get to know you</h1>
            </section>
            <section class="flex flex-col md:flex-row">
                <div class="flex flex-col items-center md:mt-5">
                    <img src="https://placehold.co/600x400/blue/blue" alt="Company Logo" title="Company Logo"
                        class="w-20 h-20 object-cover rounded-full" />
                </div>
                <div class="md:ms-5">
                    <div class="flex flex-col">
                        <h2 class="text-lg roboto-font-bold py-2">Profile picture</h2>
                        <div class="flex flex-row">
                            <button class="flex flex-row border border-gray-600 roboto-font-medium rounded-md px-4 py-2 items-center hover:ring-2 hover:ring-gray-500 focus:outline-none"><img src="../../../assets/upload.svg" class="mt-1 me-1"><span class="">Upload image</span></button>
                            <button class="border border-gray-600 rounded-md px-4 py-2 roboto-font-medium items-center ms-5 disabled:text-gray-500" disabled>Remove</button>
                        </div>
                    </div>
                    <p class="roboto-font-bold text-[#B3B3B3] text-xs mt-2 block">*.png, *.jpg files up to 10MB at least 400px by 400px</p>
                </div>
            </section>
        </header>
        <main class="w-full max-w-screen mt-5">
            <section>
                <form>
                    <div class="flex flex-col mb-5">
                        <label class="text-[#B3B3B3] roboto-font-regular">First name</label>
                        <input type="text" class="px-3 py-2 bg-[#1C1D21] border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 placeholder-gray-500 text-sm" placeholder="Enter your first name...">
                    </div>
                    <div class="flex flex-col mb-5">
                        <label class="text-[#B3B3B3] roboto-font-regular">Last name</label>
                        <input type="text" class="px-3 py-2 bg-[#1C1D21] border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 placeholder-gray-500 text-sm" placeholder="Enter your last name...">
                    </div>
                    <div class="flex flex-col mb-5">
                        <label class="text-[#B3B3B3] roboto-font-regular">Phone</label>
                        <input type="tel" class="px-3 py-2 bg-[#1C1D21] border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 placeholder-gray-500 text-sm resize-none" placeholder="Enter your phone number...">
                    </div>
                    <div class="flex flex-col mb-5">
                        <label class="text-[#B3B3B3] roboto-font-regular">Password</label>
                        <input type="password" class="px-3 py-2 bg-[#1C1D21] border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 placeholder-gray-500 text-sm resize-none" placeholder="Enter your password...">
                    </div>
                    <div>
                        <button type="submit" class="bg-blue-600 shadow-lg roboto-font-regular rounded-lg py-2 w-full hover:bg-blue-700 focus:outline-none hover:shadow-blue-500/50 hover:ring-4">Next</button>
                    </div>
                </form>
            </section>
        </main>
    </div>
</template>

<style scoped>
</style>
