<script setup>
import { useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

const props = defineProps({
    table: Object,
    row: Object,
    clumn: Object
})

const valueForm = useForm({
    value: null
})

const createValue = () => {
    valueForm.post(route('table.values.store'))
}

</script>

<template>
    <form @submit.prevent="createValue" class="mt-4">
        <p>Érték:</p>
        <input required v-model="valueForm.value" type="text" name="value" class="border rounded p-2" />
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-2 ml-1">
            Add
        </button>
    </form>
</template>

<style scoped></style>