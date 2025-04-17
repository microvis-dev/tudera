<script setup>
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    table: Object
})

const emit = defineEmits(['create'])

const columnForm = useForm({
    type: '', 
    name: ''
})

const send = () => {
    columnForm.post(route('table.columns.store', { table: props.table.id }))
}
</script>
<template>
    <div v-if="!columnForm.type">
        <select required v-model="columnForm.type" id="type"
            class="bg-transparent tracking-wider uppercase">
            <option value="">Choose column type</option>
            <option value="string">Text</option>
            <option value="float">Number</option>
            <option value="datetime">Datetime</option>
            <option value="status">Status</option>
            <option value="ref">Reference</option>
        </select>
    </div>
    <div v-if="columnForm.type">
        <input v-model="columnForm.name" required type="text" id="name" placeholder="Column name"
            class="bg-transparent shadow-sm text-center tracking-wider uppercase text-white" @keyup.enter="send">
    </div>
</template>
<style scoped>
    
</style>