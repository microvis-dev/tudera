<script setup>
import { ref, reactive, computed, onMounted, nextTick, watch } from 'vue';
import { Link, useForm, router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import Column from './Components/Column.vue';
import Value from './Components/Value.vue';
import EmptyValue from './Components/EmptyValue.vue';
import CreateColumnSelect from './Components/CreateColumnSelect.vue';
import DeleteRowModal from './Components/deleteRowModal.vue';

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
    router.delete(route('table.values.destroy', { table: props.workspace_table, value: value }))
}

const sortedTable = computed(() => {
    let returnValue = Array(props.columns.length).fill().map(() => [])

    props.columns.forEach((col, colIndex) => {
        let columnValues = []
        props.table_values.forEach((value) => {
            if (value.column_id === col.id) {
                columnValues[value.order - 1] = value
            }
        })

        returnValue[colIndex] = columnValues
    })

    return returnValue
})

const maxRows = computed(() => {
    return Math.max(...sortedTable.value.map(col => col.length), 0)
})

const showNewRow = ref(true)
const toggleNewRow = () => {
    showNewRow.value = false
}
const showNewColumn = ref(true)
const toggleNewColumn = () => {
    showNewColumn.value = false
}

const saveValue = (value, column, order) => {
    router.post(route('table.values.store', { table: column.table_id }), {
        order: order,
        column_id: column.id,
        value: value
    })
    showNewRow.value = true
}
const mainCheck = ref(false)
const checkboxesState = reactive({
    checkboxes: Array(maxRows.value).fill(false),
    checkAll() {
        this.checkboxes.fill(mainCheck.value);
    },
    refresh() {
        this.checkboxes = Array(maxRows.value).fill(false)
    }
})


watch(() => maxRows.value, () => {
    checkboxesState.refresh();
}, { deep: true });
</script>

<template>
    <div class="max-h-screen p-6 overflow-hidden">
        <div class="max-w-7xl">
            <div class="rounded-lg shadow-md">
                <div class="px-6 py-4">
                    <h1 class="text-2xl roboto-font-bold">{{ workspace_table.name }}</h1>
                    <p class="text-sm roboto-font-medium text-gray-600 mt-1">
                        Workspace: <span class="roboto-font-medium">{{ workspace.name }}</span>
                    </p>
                </div>
            </div>
            <div class="overflow-x-auto w-fit bg-[#2B2C30] border border-slate-500 mt-5">


                <table class="min-w-full table-auto">
                    <thead>
                        <tr>
                            <th class="border-r border-slate-500"><input v-model="mainCheck"
                                    @change="checkboxesState.checkAll" type="checkbox"></th>
                            <th v-for="column in columns" :key="column.id" scope="col"
                                class="text-center text-lg border-r border-slate-500 uppercase tracking-wider min-w-[150px]">
                                <Column :column="column" @delete="deleteColumn" @update="updateColumn" />
                                <input v-if="column.showInput" type="text" class="text-black w-full p-1 border rounded"
                                    @blur="column.showInput = false" @keyup.enter="column.showInput = false"
                                    placeholder="Enter value" />
                            </th>
                            <th v-if="showNewColumn" class="min-w-[80px] border-b border-slate-500"
                                @click="toggleNewColumn()">
                                <p>+</p>
                            </th>
                            <th v-else class="min-w-[80px] border-b border-slate-500" @click="toggleNewColumn()">
                                <CreateColumnSelect :table="workspace_table" />
                            </th>
                        </tr>
                    </thead>

                    <tbody class="">
                        <tr v-for="(rowIndex, index) in maxRows" :key="rowIndex">
                            <td class="border-slate-500 border-r border-t border-b text-center"><input
                                    v-model="checkboxesState.checkboxes[index]" type="checkbox"></td>
                            <td v-for="(column, colIndex) in columns" :key="column.id"
                                class="text-center p-2 border-slate-500 border-r border-t border-b">
                                <Value v-if="sortedTable[colIndex] && sortedTable[colIndex][rowIndex - 1]"
                                    :value="sortedTable[colIndex][rowIndex - 1]" @update="updateValue"
                                    @delete="deleteValue" />
                                <div v-else class="relative group">
                                    <EmptyValue :column="column" :order="rowIndex" @save="saveValue" />
                                </div>
                            </td>
                            <td class="border-b border-slate-500"></td>
                        </tr>
                        <tr class="">
                            <td class="text-center p-2 border-t border-r border-slate-500"><input
                                    v-model="checkboxesState.checkboxes[maxRows]" disabled type="checkbox"></td>
                            <td class="text-center p-2 border-t border-slate-500">
                                <button v-if="columns && columns.length > 0 && showNewRow" @click="toggleNewRow()"
                                    class="w-fit h-5 items-center mx-auto text-[#B3B3B3]">
                                    + Add task
                                </button>
                                <EmptyValue v-else :column="columns.at(0)" :isNewRow="true" @save="saveValue" />
                            </td>
                            <td v-for="column in columns" class="text-center p-2 border-t border-slate-500">
                            </td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="flex justify-center m-5">
        <DeleteRowModal />
    </div>
</template>
<style scoped></style>