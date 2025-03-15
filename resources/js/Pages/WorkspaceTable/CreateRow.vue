<script setup>
import { useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

const props = defineProps({
    table: Object
})

const rowForm = useForm({
    name: null,
    type: null
})

const createColumn = () => {
    rowForm.post(route('table.rows.store', { table: props.table.id }))
}

</script>

<template>
    <form @submit.prevent="createColumn" class="space-y-4">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Row Name</label>
            <input v-model="rowForm.name" required type="text" id="name"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
        </div>
        <div>
            <button type="submit"
                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Create Row
            </button>
        </div>
    </form>
</template>

<style scoped></style>