<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import { Link, useForm, router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import axios from 'axios';
import Column from './Components/Column.vue';
import Row from './Components/Row.vue';
import Value from './Components/Value.vue';

const props = defineProps({
    workspace: Object,
    workspace_table: Object,
    columns: Array,
    rows: Array,
    values: Object
})

//col
const deleteColumn = (column_id) => {
    router.delete(route('table.columns.destroy', { table: props.workspace_table.id, column: column_id }))
}

const updateColumn = (newColumnName, column) => {
    router.put(route('table.columns.update', { table: props.workspace_table, column: column.id, name: newColumnName }))
}

//row
const deleteRow = (row_id) => {
    router.delete(route('table.rows.destroy', { table: props.workspace_table, row: row_id }))
}

const updateRow = (newRowName, row) => {
    router.put(route('table.rows.destroy', { table: props.workspace_table, row: row, name: newRowName }))
}

//value
const findValue = (row, column) => {
    return Object.values(props.values).find(v => v.row_id == row.id && v.column_id == column.id)
}

const getValue = (row, column) => {
    const value = findValue(row, column)
    return value ? value : null
}

const getValueValue = (row, column) => {
    const value = findValue(row, column)
    return value ? value.value : null
}

const updateValue = (newValue, value) => {
    if (newValue.length == 0) {
        router.delete(route('table.values.destroy', { table: props.workspace_table }))
    } else {
        router.put(route('table.values.update', { table: props.workspace_table, value: value, new_value: newValue }))
    }
}

const updateValueDeleteIfEmpty = (newValue, valueObj) => {
    if (newValue == "" && valueObj && valueObj.id) {
        router.delete(route('table.values.destroy', { table: props.workspace_table.id, value: valueObj.id }))
    }
    else if (valueObj && valueObj.id) {
        router.put(route('table.values.update', { table: props.workspace_table.id, value: valueObj.id }), {
            value: newValue
        })
    }
}

const deleteValue = (value) => {
    console.log(value)
    router.delete(route('table.values.destroy', { table: props.workspace_table, value: value }))
}

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
                    </Link><br><br>
                    <Link :href="route('table.rows.create', { table: workspace_table })"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Add new row
                    </Link>
                    <div class="bg-gray-50 border border-gray-200 rounded-md p-8 text-center text-gray-500">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th v-for="column in columns" :key="column.id"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <Column :column="column" @delete="deleteColumn" @update="updateColumn" />
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="(row, rIndex) in rows" :key="row.id">
                                    <td v-for="(column, cIndex) in columns" :key="column.id">
                                        <Row v-if="cIndex == 0" :row="row" @delete="deleteRow" @update="updateRow" />
                                        <Value v-else :table="workspace_table" :row="row" :column="column" :x="cIndex"
                                            :y="rIndex" :value="getValue(row, column)" @update="updateValue" @delete="deleteValue" />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>