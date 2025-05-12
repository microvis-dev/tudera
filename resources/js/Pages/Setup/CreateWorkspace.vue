<script setup>
import {computed, reactive, ref, watchEffect} from "vue";
import AuthLayout from "../../Layout/AuthLayout.vue";
import { useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import CreateWorkspaceForm from '../../Components/CreateWorkspaceForm.vue';

defineOptions({
    layout: AuthLayout
})

const SUBMIT_ROUTE = 'setup.workspace.store'

const profileImageUrl = ref(null);
const profileImageFile = ref(null);

const getWorkspaceUrl = computed(() => {
    let str = "tudera.com/" + createWorkspaceForm.name
    return str.normalize("NFD")
        .replace(/[\u0300-\u036f]/g, "")
        .replace(/\s+/g, '-')
        .toLowerCase();

})

const createWorkspaceForm = useForm({
    name: null,
    profileImageFile: null,
})

const createWorkspace = (() => {
    createWorkspaceForm.post(route(SUBMIT_ROUTE))
})

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
            createWorkspaceForm.profileImageFile = file;
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

changePicturePreview(null);


</script>

<template>
    <div class="flex flex-col h-full w-full items-center p-8 md:py-10 md:px-20">
        <header class="w-full max-w-screen">
            <section class="mb-5">
                <p class="ms-0.5 md:ms-0.7 text-sm roboto-font-regular text-[#B3B3B3]">2/2</p>
                <h1 class="text-2xl md:text-4xl roboto-font-bold">Create your workspace</h1>
            </section>
            <section class="flex flex-col md:flex-row">
                <div class="flex flex-col items-center md:mt-5">
                    <img :src="profileImageUrl" alt="Company Logo" title="Company Logo"
                         class="w-20 h-20 object-cover rounded-full bg-blue-600" />
                </div>
                <div class="md:ms-5">
                    <div class="flex flex-col">
                        <h2 class="text-lg roboto-font-bold py-2">Workspace Image</h2>
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
        <main class="w-full max-w-screen mt-5">
            <section>
                <form @submit.prevent="createWorkspace">
                    <div class="flex flex-col mb-5">
                        <label class="text-[#B3B3B3] roboto-font-regular">Workspace name</label>
                        <input type="text" v-model="createWorkspaceForm.name"
                            class="px-3 py-2 bg-[#1C1D21] border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 placeholder-gray-500 text-sm"
                            placeholder="Enter your workspace name...">
                    </div>
                    <div class="flex flex-col mb-5">
                        <label class="text-[#B3B3B3] roboto-font-regular">Workspace handle</label>
                        <input type="text" :value="getWorkspaceUrl" disabled
                            class="px-3 py-2 bg-[#1C1D21] border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 text-gray-500 text-sm">
                    </div>
                    <div class="flex flex-col mb-5">
                        <label class="text-[#B3B3B3] roboto-font-regular">Description (optional)</label>
                        <textarea
                            class="px-3 py-2 bg-[#1C1D21] border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 placeholder-gray-500 text-sm resize-none"
                            rows="3" placeholder="Describe your workspace..."></textarea>
                    </div>
                    <div>
                        <button type="submit"
                            class="bg-blue-600 shadow-lg roboto-font-regular rounded-lg py-2 w-full hover:bg-blue-700 focus:outline-none hover:shadow-blue-500/50 hover:ring-4">Finish</button>
                    </div>
                </form>
            </section>
        </main>
    </div>
</template>

<style scoped></style>
