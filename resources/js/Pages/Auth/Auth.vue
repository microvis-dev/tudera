<script setup>
import AuthLayout from "../../Layout/AuthLayout.vue";
import { computed, defineOptions, reactive } from "vue";
import { useForm } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import axios from "axios";

defineOptions({
    layout: AuthLayout
})

const viewState = reactive({
    isSignIn: true,
    authMethodDisabled: false,
    passwordField: false,
    errorField: ""
})

const message = computed(() => {
    if (viewState.isSignIn) {
        return "Welcome Back"
    }
    return "Welcome"
})

const authForm = useForm({
    email: null,
    name: null,
    phone_number: null,
    password: null,
    password_confirmation: null,
    is_remember: false,
})

const isEmailExists = async (email) => {
    try {
        const response = await axios.get(route('auth.check_email', { email: email }));

        if (response.data.status == 'error') {
            viewState.errorField = response.data.message
            return false
        }

        return response.data.exists;
    } catch (error) {
        console.error("Error checking email:", error);
        return false;
    }
};


const continueAuth = async () => {
    const isExists = await isEmailExists(authForm.email);

    if (viewState.isSignIn) {
        if (isExists) {
            viewState.authMethodDisabled = true
            viewState.passwordField = true
        } else {
            alert("nem letezo email")
        }
    } else {
        if (!isExists) {
            alert("continue user create")
        } else {
            alert("letezo email")
        }

    }
}

const login = (() => {
    authForm.post(route('auth.store'))
})

</script>

<template>
    <div class="flex flex-col h-full justify-between items-center">
        <header class="p-8 flex justify-center mt-5 w-80">
            <img src="../../../assets/tuderaLogoWhite.svg" alt="Tudera Logo">
        </header>
        <main class="flex flex-col items-center">
            <h1 class="roboto-font-bold text-3xl capitalize p-1">{{ message }}</h1>
            <p class="text-[#B3B3B3] text-center roboto-font-light text-sm mb-5 w-72">{{ message }}, Please enter
                your details</p>
            <form class="flex flex-col w-full">
                <div class="relative flex bg-[#5D5E5B] rounded-lg p-1 w-75 h-11 text-center mb-5">
                    <input :disabled="viewState.authMethodDisabled" type="radio" id="sign-in" :value="true"
                        name="toggle" class="hidden peer/signin" checked v-model="viewState.isSignIn">
                    <input :disabled="viewState.authMethodDisabled" type="radio" id="sign-up" :value="false"
                        name="toggle" class="hidden peer/signup" v-model="viewState.isSignIn">
                    <div
                        class="absolute left-0 top-0 w-1/2 h-full bg-gray-300 rounded-lg transition-all duration-300 peer-checked/signup:left-1/2">
                    </div>
                    <label for="sign-in"
                        class="relative flex-1 text-center text-gray-900 roboto-font-medium cursor-pointer py-1.5 rounded-lg z-10 peer-checked/signin:text-gray-900 peer-checked/signup:text-white">
                        Sign In
                    </label>
                    <label for="sign-up"
                        class="relative flex-1 text-center text-white roboto-font-medium cursor-pointer py-1.5 rounded-lg z-10 peer-checked/signup:text-gray-900 peer-checked/signin:text-white">
                        Sign Up
                    </label>
                </div>
                <div class="relative w-full bg-[#5D5E5B] rounded-md mb-5 h-12">
                    <input type="text" id="name" v-model="authForm.email"
                        class="bg-[#5D5E5B] peer w-full rounded-md px-3 pt-6 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600"
                        placeholder=" " />
                    <label for="name"
                        class="absolute left-3 rounded-md top-1 text-[#B3B3B3] text-sm transition-all peer-placeholder-shown:top-3 peer-placeholder-shown:text-base peer-placeholder-shown:[#B3B3B3] peer-focus:top-1 peer-focus:text-sm peer-focus:text-blue-500 roboto-font-light">Email
                        address</label>
                    <p v-if="authForm.errors.email">{{ authForm.errors.email }}</p>
                </div>
                <div v-if="viewState.passwordField" class="relative w-full bg-[#5D5E5B] rounded-md mb-5 h-12">
                    <input v-model="authForm.password" type="password" id="password"
                        class="bg-[#5D5E5B] peer w-full rounded-md px-3 pt-6 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600"
                        placeholder=" " />
                    <label for="password"
                        class="absolute left-3 rounded-md top-1 text-[#B3B3B3] text-sm transition-all peer-placeholder-shown:top-3 peer-placeholder-shown:text-base peer-placeholder-shown:[#B3B3B3] peer-focus:top-1 peer-focus:text-sm peer-focus:text-blue-500 roboto-font-light">Password</label>
                </div>
                <div class="inline-flex items-center mb-5">
                    <label class="relative flex cursor-pointer items-center rounded-full p-3" for="ripple-on"
                        data-ripple-dark="true">
                        <input id="ripple-on" type="checkbox" v-model="authForm.is_remember"
                            class="peer relative h-5 w-5 cursor-pointer appearance-none rounded border border-slate-300 shadow hover:shadow-md transition-all before:absolute before:top-2/4 before:left-2/4 before:block before:h-12 before:w-12 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-slate-400 before:opacity-0 before:transition-opacity checked:bg-blue-600 checked:before:bg-slate-400 hover:before:opacity-10" />
                        <span
                            class="pointer-events-none absolute top-2/4 left-2/4 -translate-y-2/4 -translate-x-2/4 text-white opacity-0 transition-opacity peer-checked:opacity-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20"
                                fill="currentColor" stroke="currentColor" stroke-width="1">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </span>
                    </label>
                    <label class="cursor-pointer text-[#B3B3B3] roboto-font-regular text-sm" for="ripple-on">
                        Remember me
                    </label>
                </div>
                <p v-if="viewState.errorField">Errors: {{ viewState.errorField }}</p>
                <button type="button" class="bg-blue-600 rounded-md h-12 roboto-font-light"
                    @click.prevent="viewState.passwordField ? login() : continueAuth()">
                    Continue
                </button>
            </form>
        </main>
        <footer class="flex justify-center">
            <p class="text-center roboto-font-light text-sm w-xl mb-5 px-3 text-[#B3B3B3]">Join thousands of
                businesses
                who trust our CRM to
                streamline their customer relationships.
                Log in to access your personalized dashboard, manage leads effortlessly, and gain insights to drive
                smarter
                business decisions.</p>
        </footer>
    </div>
</template>
<style scoped></style>
