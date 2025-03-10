<script setup>
import { router, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

const props = defineProps({
    workspace: Number
})

const createTableForm = useForm({
    name: null
})

const createTable = () => {
    createTableForm.post(route('workspace.table.store', { workspace: props.workspace }))
}
</script>

<template>
    <form @submit.prevent="createTable" class="space-y-4 mt-5">
        <div class="flex flex-col">
            <label for="workspace-name" class="mb-2 text-sm font-medium text-gray-700">Table Name:</label>
            <input type="text" id="workspace-name" v-model="createTableForm.name"
                class="p-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-500"
                required>
        </div>
        <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600">
            Create new table
        </button>
    </form>
</template>

<style scoped></style>