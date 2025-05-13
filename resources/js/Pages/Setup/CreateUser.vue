<script setup>
import AuthLayout from "../../Layout/AuthLayout.vue";
import { ref, defineOptions, computed, reactive, onMounted } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import { route } from "ziggy-js";

defineOptions({
    layout: AuthLayout
})

const name = reactive({
    first: '',
    last: '',
    getFullName() {
        return this.first + " " + this.last
    }
})

const profileImageUrl = ref(null);
const profileImageFile = ref(null);

const userForm = useForm({
    name: computed(() => name.getFullName()),
    phone: '',
    email: '',
    password: '',
    redirectTo: 'setup.workspace.create',
    profileImageFile: computed(() => profileImageFile.value),
})

onMounted(() => {
    const savedEmail = localStorage.getItem('userEmail');
    if (savedEmail) {
        userForm.email = savedEmail;
    } else {
        // ...
    }
})

const submitForm = () => {
    userForm.post(route('user.store'))
}

const changePicturePreview = (imageUrl) => {
    const def = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/wcAAwAB/Up0GRQAAAAASUVORK5CYII=';
    if (imageUrl) {
        profileImageUrl.value = imageUrl;
    } else {
        profileImageUrl.value = def;
        profileImageFile.value = null;
    }
}
const handleFileUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            changePicturePreview(e.target.result);
            profileImageFile.value = file;
        }
        if (file.type === "image/png" || file.type === "image/jpeg") {
            reader.readAsDataURL(file);
        } else {
            changePicturePreview(null);
        }
    } else {
        changePicturePreview(null);
    }
}

const passwordState = reactive({
    isShow: false,
    type: computed(() => {
        return passwordState.isShow ? "text" : "password"
    }),
    switch() {
        passwordState.isShow = !passwordState.isShow
    }
})

changePicturePreview(null);

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
                    <img :src="profileImageUrl" alt="Company Logo" title="Company Logo"
                        class="w-20 h-20 object-cover rounded-full bg-blue-600" />
                </div>
                <div class="md:ms-5">
                    <div class="flex flex-col">
                        <h2 class="text-lg roboto-font-bold py-2">Profile picture</h2>
                        <div class="flex flex-row">
                            <label
                                class="flex flex-row border border-gray-600 roboto-font-medium rounded-md px-4 py-2 items-center hover:ring-2 hover:ring-gray-500 focus:outline-none cursor-pointer">
                                <img src="../../../assets/upload.svg" class="mt-1 me-1">
                                <span class="">Upload image</span>
                                <input type="file" class="hidden" @change="handleFileUpload" />
                            </label>
                            <button @click="changePicturePreview(null)"
                                class="border border-gray-600 rounded-md px-4 py-2 roboto-font-medium items-center ms-5 disabled:text-gray-500"
                                :disabled="profileImageFile == null">Remove</button>
                        </div>
                    </div>
                    <p class="roboto-font-bold text-[#B3B3B3] text-xs mt-2 block">*.png, *.jpg files up to 10MB at least
                        400px by 400px</p>
                </div>
            </section>
        </header>
        <main class="flex flex-col">
            <h1 class="roboto-font-bold text-3xl capitalize p-1">{{ null }}</h1>
            <p class="text-[#B3B3B3] text-center roboto-font-light text-sm mb-5 w-72">{{ null }}</p>
            <form @submit.prevent="submitForm" class="flex flex-col w-full space-y-5">
                <div class="flex flex-col space-y-1">
                    <label for="first-name" class="text-[#B3B3B3] text-sm font-medium">
                        First name
                    </label>
                    <input id="first-name" v-model="name.first" type="text" placeholder="Enter your first name..."
                        class="w-full px-3 py-2 bg-[#1C1D21] text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 placeholder-gray-500" />
                </div>
                <div class="flex flex-col space-y-1">
                    <label for="last-name" class="text-[#B3B3B3] text-sm font-medium">
                        Last name
                    </label>
                    <input id="last-name" type="text" v-model="name.last" placeholder="Enter your last name..."
                        class="w-full px-3 py-2 bg-[#1C1D21] text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 placeholder-gray-500" />
                    <p v-if="userForm.errors.name">{{ userForm.errors.name }}</p>
                </div>
                <div class="flex flex-col space-y-1">
                    <label for="tel" class="text-[#B3B3B3] text-sm font-medium">
                        Phone
                    </label>
                    <input id="tel" type="tel" v-model="userForm.phone" placeholder="Enter your phone..."
                        class="w-full px-3 py-2 bg-[#1C1D21] text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 placeholder-gray-500" />
                    <p v-if="userForm.errors.phone">{{ userForm.errors.phone }}</p>
                </div>
                <div class="flex flex-col space-y-1">
                    <label for="password" class="text-[#B3B3B3] text-sm font-medium">
                        Password
                    </label>
                    <div class="relative">
                        <input id="password" :type="passwordState.type" v-model="userForm.password"
                            placeholder="Enter your password"
                            class="w-full px-3 py-2 bg-[#1C1D21] text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 placeholder-gray-500 pr-10" />
                        <svg v-if="!passwordState.isShow" @click="passwordState.switch()" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                            class="absolute top-1/2 right-3 transform -translate-y-1/2 w-6 h-6 text-gray-400 cursor-pointer">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        <svg v-else @click="passwordState.switch()" xmlns="http://www.w3.org/2000/svg" fill="none" 
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                            class="absolute top-1/2 right-3 transform -translate-y-1/2 w-6 h-6 text-gray-400 cursor-pointer">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>

                    </div>
                </div>
                <input type="hidden" name="register" value="true">
                <button type="submit" class="bg-blue-600 rounded-lg px-3 py-2 mt-8 roboto-font-light">
                    Next
                </button>
            </form>
        </main>
    </div>
</template>

<style scoped></style>
