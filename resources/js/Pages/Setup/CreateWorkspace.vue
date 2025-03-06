<script setup>
import AuthLayout from "../../Layout/AuthLayout.vue";
import { useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import CreateWorkspaceForm from '../../Components/CreateWorkspaceForm.vue';

defineOptions({
    layout: AuthLayout
})

const createWorkspaceForm = useForm({
    name: null
})

const createWorkspace = (() => {
    createWorkspaceForm.post(route('setup.workspace.store'))
})

const SUBMIT_ROUTE = 'setup.workspace.store'

</script>

<template>
    <div class="flex flex-col h-full w-full items-center p-8">
        <header>
            <section class="mb-5">
                <p class="ms-0.5 md:ms-0.7 text-sm roboto-font-light text-[#B3B3B3]">2/2</p>
                <h1 class="text-2xl md:text-3xl roboto-font-bold">Create your workspace</h1>
            </section>
            <section class="flex flex-col md:flex-row">
                <div class="flex flex-col items-center">
                    <img src="https://placehold.co/600x400/blue/blue" alt="Company Logo" title="Company Logo"
                        class="w-20 h-20 object-cover rounded-full" />
                </div>
                <div class="flex flex-col">
                    <h2 class="text-lg roboto-font-bold py-2">Company Logo</h2>
                    <div class="flex flex-row">
                        <button class="flex flex-row border border-gray-600 roboto-font-medium rounded-md px-4 py-2 items-center hover:ring-2 hover:ring-gray-500 focus:outline-none"><img
                                src="../../../assets/upload.svg" class="mt-1 me-1"><span class="">Upload image</span></button>
                        <button class="border border-gray-600 rounded-md px-4 py-2 roboto-font-medium items-center ms-5 disabled:text-gray-500" disabled>Remove</button>
                    </div>
                </div>
                <p class="roboto-font-bold text-[#B3B3B3] text-xs mt-2">*.png, *.jpg files up to 10MB at least 400px by 400px</p>
            </section>
        </header>
        <main>
            <section>
                <form>
                    <div>
                        <label>Company name</label>
                        <input type="text" placeholder="Enter your company name...">
                    </div>
                    <div>
                        <label>Workspace handle</label>
                        <input type="text" placeholder="tudera.com/my-workspace">
                    </div>
                    <div>
                        <label>Billing country</label>
                        <select>
                            <option>Hungary</option>
                        </select>
                    </div>
                    <div>
                        <button>Continue</button>
                    </div>
                </form>
            </section>
        </main>
    </div>
</template>

<style scoped></style>
