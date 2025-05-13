<script setup>
import { ref, reactive, computed, onMounted, nextTick, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import Column from './Components/Column.vue';
import Value from './Components/Value.vue';
import EmptyValue from './Components/EmptyValue.vue';
import CreateColumnSelect from './Components/CreateColumnSelect.vue';
import ModifyRowModal from './Components/ModifyRowModal.vue';
import KanbanSelect from './KanbanBoard/KanbanSelect.vue';
import { useTuderaStore } from '../../state/state';

const props = defineProps({ // use state!
    workspace: Object,
    workspace_table: Object,
    columns: Array,
    table_values: Array,
    status_options: Array
})

const tuderaState = useTuderaStore()

//col
const deleteColumn = (column_id) => {
    router.delete(route('table.columns.destroy', { table: props.workspace_table.id, column: column_id }))
}

const updateColumn = (newColumnName, column) => {
    router.put(route('table.columns.update', { table: props.workspace_table, column: column.id, name: newColumnName }))
}

const saveValue = (value, column, order) => {
    router.post(route('table.values.store', { table: column.table_id }), {
        order: order,
        column_id: column.id,
        value: value
    })

    if (column.type == "status") {
        router.post(route('selectvalues.store'), {
            column_id: column.id,
            value: value
        });
    }

    showNewRow.value = true
}

const updateValue = (newValue, value) => {
    router.put(route('table.values.update', { table: props.workspace_table, value: value, new_value: newValue }))
}

const deleteValue = (value) => {
    router.delete(route('table.values.destroy', { table: props.workspace_table, value: value }))
}

const sortedColumns = computed(() => {
    return [...props.columns].sort((a, b) => a.order - b.order)
})

const sortedTable = computed(() => {
    let tableData = Array(sortedColumns.value.length)
        .fill(null)
        .map(() => [])

    sortedColumns.value.forEach((col, colIndex) => {
        let columnValues = []

        props.table_values.forEach((value) => {
            if (value.column_id === col.id) {
                columnValues[value.order - 1] = value
            }
        })

        tableData[colIndex] = columnValues
    })

    return tableData
})

const maxRows = computed(() => {
    return Math.max(...sortedTable.value.map(col => col.length), 0)
})

const showNewRow = ref(true) // viewState!
const toggleNewRow = () => {
    showNewRow.value = false
}
const showNewColumn = ref(true)
const toggleNewColumn = () => {
    showNewColumn.value = false
}

const closeNewColumn = () => {
    showNewColumn.value = true
}

const checkboxesState = reactive({
    mainCheck: false,
    checkboxes: Array(maxRows.value).fill(false),
    checkAll() {
        this.checkboxes.fill(this.mainCheck)
    },
    reset() {
        this.mainCheck = false
        this.checkboxes = Array(maxRows.value).fill(false)
    },
    isSelected() {
        return this.checkboxes.includes(true)
    }
})
watch(() => maxRows.value, () => {
    checkboxesState.reset()
}, { deep: true })

watch(() => checkboxesState.checkboxes, (newCheckboxes) => {
    if (newCheckboxes.every((ch) => ch)) {
        checkboxesState.mainCheck = true
    }
    else if (checkboxesState.mainCheck && !newCheckboxes.every((ch) => ch)) {
        checkboxesState.mainCheck = false
    }
}, { deep: true })

const deleteRows = (targetRows, updatedTable) => {
    targetRows.forEach((value) => {
        router.delete(route('table.values.destroy', { table: props.workspace_table, value: value.id }))
    })

    updatedTable.forEach((column) => {
        column.forEach((value) => {
            router.put(route('table.values.update', { table: props.workspace_table, value: value, new_value: value.value, order: value.order }))
        })
    })

    checkboxesState.reset() // !
}

const updateRows = (targetValues) => {
    targetValues.forEach((value) => {
        router.put(route('table.values.update', {
            table: props.workspace_table,
            value: value.id
        }), {
            new_value: value.value,
            order: value.order
        })
    })

    checkboxesState.reset()
}

// col move
const colMoveLeft = (col, newOrder) => {
    router.put(route('table.columns.update', { table: props.workspace_table.id, column: col.id }), {
        order: newOrder
    })
}

const colMoveRight = (col, newOrder) => {
    router.put(route('table.columns.update', { table: props.workspace_table.id, column: col.id }), {
        order: newOrder
    })
}

// view
const viewState = reactive({
    table: true,
    kanban: false,
    switch() {
        this.table = !this.table
        this.kanban = !this.kanban
    },
    toggleKanban() {
        this.table = false
        this.kanban = true
    },
    toggleTable() {
        this.table = true
        this.kanban = false
    }
})

// select 
const options = computed(() => {
    const allOptions = props.status_options.flatMap(opt => opt.options)
    const uniqueMap = new Map(allOptions.map(opt => [opt.value, opt]))

    return Array.from(uniqueMap.values())
})
const deleteTable = () => {
    if (confirm("Are you sure you want to delete '" + props.workspace_table.name + "'!")) {
        router.delete(route("table.destroy", {
            table: props.workspace_table.id,
            workspace: props.workspace.id
        }))
    }
}
const updateName = ref(false)
const changeUpdateName = () => {
    updateName.value = !updateName.value
}
const updateTable = () => {
    router.put(route('table.update', props.workspace_table.id), {
        name: props.workspace_table.name,
        workspace: props.workspace.id
    })
    updateName.value = !updateName.value
}

</script>

<template>
    <div class="max-h-screen p-6 overflow-y-auto">
        <div class="max-w-7xl">
            <div class="rounded-lg shadow-md">
                <div class="px-6 py-4">
                    <div class="flex flex-row">
                        <h1 v-if="!updateName" class="text-2xl roboto-font-bold">{{ workspace_table.name }}</h1>
                        <div v-if="updateName">
                            <input v-model="workspace_table.name" type="text"
                                class="bg-[#5D5E5B] w-fit rounded-md px-3 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600" />
                            <button @click="updateTable" class="w-fit ms-4 px-3 rounded-md bg-blue-600">Save</button>
                        </div>
                        <div class="relative group" @click="deleteTable">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-7 h-7 ms-5 hover:text-red-500">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                            <span
                                class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-1 px-2 py-1 text-xs text-white bg-black rounded opacity-0 group-hover:opacity-100">
                                Delete
                            </span>
                        </div>
                        <div class="relative group" @click="changeUpdateName">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-7 h-7 ms-2 hover:text-blue-500">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                            </svg>
                            <span
                                class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-1 px-2 py-1 text-xs text-white bg-black rounded opacity-0 group-hover:opacity-100">
                                Edit
                            </span>
                        </div>
                    </div>
                    <p class="text-sm roboto-font-medium text-gray-600 mt-1">
                        Workspace: <span class="roboto-font-medium">{{ workspace.name }}</span>
                    </p>
                    <div v-if="status_options.length > 0">
                        <KanbanSelect :show="viewState.kanban" :status_options="status_options"
                            @hide-table="viewState.toggleKanban()" @back="viewState.toggleTable()" :columns="columns"
                            :values="table_values" />
                    </div>
                </div>
            </div>
            <div v-if="viewState.table" class="overflow-x-auto w-fit bg-[#2B2C30] border border-slate-500 mt-5">
                <table class="min-w-full table-auto">
                    <thead>
                        <tr>
                            <th class="border-r border-slate-500">
                                <input v-model="checkboxesState.mainCheck" @change="checkboxesState.checkAll"
                                    type="checkbox">
                            </th>
                            <th v-for="column in sortedColumns" :key="column.id" scope="col"
                                class="text-center text-lg border-r border-slate-500 uppercase tracking-wider min-w-[150px]">
                                <Column :column="column" @delete="deleteColumn" @update="updateColumn"
                                    @move-left="colMoveLeft" @move-right="colMoveRight" :maxRows="maxRows" :columnCount="sortedColumns.length" />
                                <input v-if="column.showInput" type="text" class="text-black w-full p-1 border rounded"
                                    @blur="column.showInput = false" @keyup.enter="column.showInput = false"
                                    placeholder="Enter value" />
                            </th>
                            <th v-if="showNewColumn" class="min-w-[80px] border-b border-slate-500 cursor-pointer"
                                @click="toggleNewColumn()">
                                <p>+</p>
                            </th>
                            <th v-else class="min-w-[80px] border-b border-slate-500" @click="toggleNewColumn()">
                                <CreateColumnSelect :table="workspace_table" @close="closeNewColumn" />
                            </th>
                        </tr>
                    </thead>

                    <tbody class="">
                        <tr v-for="(rowIndex, index) in maxRows" :key="rowIndex">
                            <td class="border-slate-500 border-r border-t border-b text-center">
                                <input v-model="checkboxesState.checkboxes[index]" type="checkbox">
                            </td>
                            <td v-for="(columnValues, colIndex) in sortedTable" :key="sortedColumns[colIndex].id"
                                class="text-center p-2 border-slate-500 border-r border-t border-b">
                                <Value v-if="columnValues[rowIndex - 1]" :value="columnValues[rowIndex - 1]"
                                    :options="options" :column="sortedColumns[colIndex]" @update="updateValue"
                                    @delete="deleteValue" />
                                <div v-else class="relative group">
                                    <EmptyValue :options="options" :column="sortedColumns[colIndex]" :order="rowIndex"
                                        @save="saveValue" />
                                </div>
                            </td>
                            <td class="border-b border-slate-500"></td>
                        </tr>
                        <tr class="">
                            <td class="text-center p-2 border-t border-r border-slate-500">
                                <input disabled type="checkbox">
                            </td>
                            <td class="text-center p-2 border-t border-slate-500">
                                <button v-if="sortedColumns && sortedColumns.length > 0 && showNewRow"
                                    @click="toggleNewRow()" class="w-fit h-5 items-center mx-auto text-[#B3B3B3]">
                                    + Add task
                                </button>
                                <EmptyValue v-else :options="options" :column="sortedColumns.at(0)" :isNewRow="true"
                                    @save="saveValue" />
                            </td>
                            <td v-for="column in sortedColumns" class="text-center p-2 border-t border-slate-500">
                            </td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="flex justify-center mb-5 fixed bottom-0 left-0 right-0 z-10">
        <ModifyRowModal v-if="checkboxesState.isSelected()" :checkboxes="checkboxesState.checkboxes"
            :table="sortedTable" @delete="deleteRows" @update="updateRows" />
    </div>
</template>
<style scoped></style>