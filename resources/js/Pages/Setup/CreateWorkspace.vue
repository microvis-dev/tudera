<script setup>
import { useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import CreateWorkspaceForm from '../../Components/CreateWorkspaceForm.vue';

defineOptions({
    layout: null
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
    <CreateWorkspaceForm :submitRoute="SUBMIT_ROUTE" />

    <form v-if="false" @submit.prevent="createWorkspace" class="space-y-4 mt-5">
        <div class="flex flex-col">
            <label for="workspace-name" class="mb-2 text-sm font-medium text-gray-700">Workspace Name:</label>
            <input type="text" id="workspace-name" v-model="createWorkspaceForm.name"
                class="p-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-500"
                required>
        </div>
        <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600">
            Create Workspace
        </button>
    </form>
</template>

<style scoped></style>
