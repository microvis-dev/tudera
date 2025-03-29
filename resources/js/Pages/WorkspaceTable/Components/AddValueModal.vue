<script setup>
import { useForm } from '@inertiajs/vue3'
import { route } from 'ziggy-js'

const props = defineProps({
    table: Object,
    column: Object
})
console.log(props.column)

const emit = defineEmits(['close'])

const closeModal = () => {
    emit('close')
}

const valueForm = useForm({
    value: null,
    column_id: props.column.id
})

const save = () => {
    if (valueForm.value) {
        valueForm.post(route('table.values.store', { table: props.table }))
        //valueForm.post(route('table.values.store', { table: props.table, row: props.row, column: props.column }))
        emit('close')
    }
}

</script>

<template>
    <div class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-75">
        <div class="bg-white p-6 rounded shadow-md">
            <h2 class="text-xl font-bold mb-4">Add Value</h2>
            <p>Column: {{ column.name }}</p>
            <input v-model="valueForm.value" required class="mt-5 border p-2 rounded w-full" placeholder="Enter value" />
            <button @click="closeModal"
                class="mt-4 bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 block w-full">Close</button>
            <button @click="save" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded block w-full">Save</button>
        </div>
    </div>
</template>

<style scoped></style>