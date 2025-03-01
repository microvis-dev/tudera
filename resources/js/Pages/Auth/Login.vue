<script setup>
import AuthLayout from "../../Layout/AuthLayout.vue";
</script>

<template>
    <AuthLayout>
        <div class="flex flex-col h-full justify-between items-center">
            <header class="p-8 flex justify-center mt-5 w-80">
                <img src="../../../assets/tuderaLogoWhite.svg" alt="Tudera Logo">
            </header>
            <main class="flex flex-col items-center">
                <h1 class="roboto-font-bold text-3xl capitalize p-1">Welcome Back</h1>
                <p class="text-[#B3B3B3] roboto-font-light text-sm mb-5 ">Welcome back, Please enter your details</p>
                <form class="flex flex-col w-full">
                    <div class="relative flex bg-[#5D5E5B] rounded-lg p-1 w-75 h-11 text-center mb-5">
                        <input type="radio" id="sign-in" value="signin" name="toggle" class="hidden peer/signin" checked>
                        <input type="radio" id="sign-up" value="signup" name="toggle" class="hidden peer/signup">
                        <div
                            class="absolute left-0 top-0 w-1/2 h-full bg-gray-300 rounded-lg transition-all duration-300 peer-checked/signup:left-1/2"></div>
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
                        <input type="text" id="name"
                               class="bg-[#5D5E5B] peer w-full rounded-md px-3 pt-6 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600"
                               placeholder=" "/>
                        <label for="name"
                               class="absolute left-3 rounded-md top-1 text-[#B3B3B3] text-sm transition-all peer-placeholder-shown:top-3 peer-placeholder-shown:text-base peer-placeholder-shown:[#B3B3B3] peer-focus:top-1 peer-focus:text-sm peer-focus:text-blue-500 roboto-font-light">Email
                            address</label>
                    </div>
                    <button class="bg-blue-600 rounded-md h-10 roboto-font-light">Continue</button>
                </form>
            </main>
            <footer class="flex justify-center">
                <p class="text-center roboto-font-light text-sm w-xl mb-5 px-3 text-[#B3B3B3]">Join thousands of businesses
                    who trust our CRM to
                    streamline their customer relationships.
                    Log in to access your personalized dashboard, manage leads effortlessly, and gain insights to drive
                    smarter
                    business decisions.</p>
            </footer>
        </div>
    </AuthLayout>
</template>

<style scoped>
</style>
