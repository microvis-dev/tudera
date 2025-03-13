<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import { Link, useForm, router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import axios from 'axios';
import Column from './Components/Column.vue';

const props = defineProps({
    workspace: Object,
    workspace_table: Object,
    columns: Array
})
/*
const getColumns = async () => {
    await axios.get(route('table.columns.index', { table: props.workspace_table.id }))
        .then((response) => {
            return response.data
        })
        .catch((error) => {
            console.log(error)
        })
}

const colums = computed(() => {
    return getColumns()
})
console.log()
*/



</script>

<template>
    <div class="min-h-screen bg-gray-100 p-6">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h1 class="text-2xl font-bold text-gray-800">{{ workspace_table.name }}</h1>
                    <p class="text-sm text-gray-600 mt-1">
                        Workspace: <span class="font-medium">{{ workspace.name }}</span>
                    </p>
                </div>

                <div class="p-6">
                    <Link :href="route('table.columns.create', { table: workspace_table })"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Add new column
                    </Link>
                    <div class="bg-gray-50 border border-gray-200 rounded-md p-8 text-center text-gray-500">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th v-for="column in columns" :key="column.id"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <Column :name="column.name" />
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>