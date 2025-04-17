<script setup>
import { useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

const props = defineProps({
    table: Object
})

const columnForm = useForm({
    name: null,
    type: null
})

const createColumn = () => {
    columnForm.post(route('table.columns.store', { table: props.table.id }))
}

</script>

<template>
    <div>
        <label for="type" class="block text-sm font-medium text-gray-700">Column Type</label>
        <select required v-model="columnForm.type" id="type"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            <option value="string">String</option>
            <option value="integer">Int</option>
            <option value="float">Float</option>
            <option value="datetime">Datetime</option>
            <option value="status">Status</option>
            <option value="ref">ref</option>
        </select>
    </div>
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Column Name</label>
        <input v-model="columnForm.name" required type="text" id="name"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
    </div>
    <div>
    </div>
</template>

<style scoped></style>