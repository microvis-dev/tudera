<script setup>
import { ref, reactive, computed, onMounted, nextTick } from 'vue';
import { Link, useForm, router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import axios from 'axios';
import Column from './Components/Column.vue';
import Value from './Components/Value.vue';
import AddValueModal from './Components/AddValueModal.vue';

const props = defineProps({ // use state
    workspace: Object,
    workspace_table: Object,
    columns: Array,
    table_values: Array
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
    return Object.values(props.table_values).find(v => v.row_id == row.id && v.column_id == column.id)
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
        props.table_values.forEach((value) => {
            if (value.column_id == col.id) {
                columnValues.push(value)
            }
        })

        columnValues.sort((a, b) => a.order - b.order)

        returnValue[index] = columnValues
    })

    return returnValue
})

const maxRows = computed(() => {
    return Math.max(...sortedTable.value.map(col => col.length), 0);
});

// add value, todo: put this in component
const showAddValueForm = ref(false)
const toggleAddValueForm = ((column) => {
    selectedColumn.value = column
    showAddValueForm.value = !showAddValueForm.value
})

const selectedColumn = ref(null)

</script>

<template>
    <div class="max-h-screen p-6 overflow-hidden">
        <div class="max-w-7xl">
            <div class="rounded-lg shadow-md">
                <div class="px-6 py-4">
                    <h1 class="text-2xl roboto-font-bold">{{ workspace_table.name }}</h1>
                    <p class="text-sm text-gray-600 mt-1">
                        Workspace: <span class="font-medium">{{ workspace.name }}</span>
                    </p>
                </div>
            </div>
                <div class="overflow-x-auto w-fit bg-[#2B2C30] border border-slate-500">


                    <table class="min-w-full table-auto">
                        <thead>
                            <tr>
                                <th v-for="column in columns" :key="column.id" scope="col"
                                    class="text-center text-lg font-medium border-r border-slate-500 uppercase tracking-wider min-w-[150px]">
                                    <Column :column="column" @delete="deleteColumn" @update="updateColumn" />
                                </th>
                                <th class="min-w-[80px]">
                                    <Link :href="route('table.columns.create', { table: workspace_table })"
                                        class="hover:bg-blue-700 text-white font-bold px-6 py-3 text-left rounded">
                                    +
                                    </Link>
                                </th>
                            </tr>
                        </thead>
                        
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="rowIndex in maxRows" :key="rowIndex">
                                <td v-for="(column, colIndex) in columns" :key="column.id" class="text-center p-2 border-r">
                                    <Value 
                                        v-if="sortedTable[colIndex] && sortedTable[colIndex][rowIndex-1]" 
                                        :value="sortedTable[colIndex][rowIndex-1]" 
                                        @update="updateValue" 
                                        @delete="deleteValue" 
                                    />
                                    <button 
                                        v-else 
                                        @click="toggleAddValueForm(column)" 
                                        class="bg-blue-500 text-white w-8 h-8 flex items-center justify-center hover:bg-blue-700 rounded-full mx-auto">
                                        +
                                    </button>
                                </td>
                                <td></td>
                            </tr>
                            <tr class="bg-gray-50">
                                <td v-for="column in columns" class="text-center p-2 border-r">
                                    <button 
                                        @click="toggleAddValueForm(column)" 
                                        class="bg-green-500 text-white w-10 h-10 flex items-center justify-center hover:bg-green-700 rounded-full mx-auto">
                                        +
                                    </button>
                                </td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                        <AddValueModal v-if="showAddValueForm" @close="showAddValueForm = false" :table="workspace_table" :column="selectedColumn" />
                </div>
            </div>
        </div>
</template>
<style scoped></style>