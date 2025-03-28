<script setup>
import { ref, reactive, computed, onMounted, nextTick } from 'vue';
import { Link, useForm, router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import axios from 'axios';
import Column from './Components/Column.vue';
import Value from './Components/Value.vue';
import AddValueModal from './Components/AddValueModal.vue';

const props = defineProps({
    workspace: Object,
    workspace_table: Object,
    columns: Array,
    values: Object
})

//col
const deleteColumn = (column_id) => {
    router.delete(route('table.columns.destroy', { table: props.workspace_table.id, column: column_id }))
}

const updateColumn = (newColumnName, column) => {
    router.put(route('table.columns.update', { table: props.workspace_table, column: column.id, name: newColumnName }))
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

const sortedTable = computed(() => {
    // [col][values]
    let returnValue = Array(props.columns.length).fill().map(() => [])
    
    props.columns.forEach((col, index) => {
        let columnValues = []
        props.values.forEach((value) => {
            if (value.column_id == col.id) {
                columnValues.push(value)
            }
        })
        
        columnValues.sort((a, b) => a.order - b.order)
        
        returnValue[index] = columnValues
    })

    return returnValue
})

// add value, todo: put this in component
const showAddValueForm = ref(false)
const toggleAddValueForm = (() => {
    showAddValueForm.value = !showAddValueForm.value
})
 
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
                    <div class="bg-gray-50 border border-gray-200 rounded-md p-8 text-center text-gray-500">
                        <div class="grid grid-cols-{{ columns.length }} gap-4">
                            <div v-for="column in columns" :key="column.id" class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <Column :column="column" @delete="deleteColumn" @update="updateColumn" />
                            </div>
                        </div>
                        <div class="grid grid-cols-{{ columns.length }} gap-4 mt-4">
                            <div v-for="col in sortedTable" class="flex flex-col">
                                <div v-for="value in col" class="p-2 border border-gray-200 rounded">
                                    <Value :value="value" @update="updateValue" @delete="deleteValue" />
                                    <button v-if="col.length == value.order" @click="toggleAddValueForm"
                                        class="mt-2 bg-blue-500 text-white w-10 h-10 flex items-center justify-center hover:bg-blue-700">
                                        +
                                    </button>
                                    <AddValueModal v-if="showAddValueForm" @close="toggleAddValueForm" :table="workspace_table" :column="col.at(0)" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>