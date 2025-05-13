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
        <div class="flex flex-col mb-3 w-1/5">
            <label class="text-[#B3B3B3] roboto-font-regular">Table name</label>
            <input type="text" v-model="createTableForm.name"
                class="px-3 py-2 bg-[#1C1D21] border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 placeholder-gray-500 text-sm"
                placeholder="Enter your table name...">
        </div>
        <button type="submit" class="px-4 py-2 w-fit text-white bg-blue-500 rounded-md hover:bg-blue-600">
            Create new table
        </button>
    </form>
</template>

<style scoped></style>