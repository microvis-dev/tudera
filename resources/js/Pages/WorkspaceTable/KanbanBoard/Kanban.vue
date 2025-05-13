<script setup>
import TaskCard from "./Components/TaskCard.vue"
import { computed } from 'vue'
import draggableComponent from 'vue3-draggable-next'
import { router } from "@inertiajs/vue3"
import { route } from "ziggy-js"
import { getLatestDate, formatDate } from "@/resources/js/utils/utils"

const props = defineProps({
    selectedKanban: Object,
    values: Array,
    columns: Array
})

const emit = defineEmits(['back', 'update'])

const table_id = props.columns[0].table_id

const statusColumnId = computed(() => props.selectedKanban?.column_id || null)

function groupRowsByOrder(values) {
    const rowGroups = {}

    values?.forEach((value) => {
        const rowKey = `row_${value.order}`

        if (!rowGroups[rowKey]) {
            rowGroups[rowKey] = { id: rowKey, values: {} }
        }

        rowGroups[rowKey].values[value.column_id] = {
            id: value.id,
            value: value.value,
            updated_at: value.updated_at
        }
    })

    return rowGroups
}

function getTaskValues(row, statColId, columns) {
    return Object.entries(row.values)
        .filter(([colId]) => colId != statColId.toString())
        .map(([colId, valueObj]) => {
            const column = columns?.find((col) => col.id.toString() == colId.toString())

            return {
                columnName: column?.name || 'Unknown',
                colId,
                value: valueObj.value,
                value_id: valueObj.id,
                updated_at: valueObj.updated_at
            }
        })
}

function getStatusValue(task, values) {
    const targetValue = values.find((v) => task.value_id == v.id)

    if (!targetValue) return null

    return values.find((v) => v.order == targetValue.order && v.column_id == targetValue.column_id)
}

function buildKanbanColumns(props, table_id, statusColumnId) {
    const rowGroups = groupRowsByOrder(props.values)
    const statColId = statusColumnId.value
    const options = (props.selectedKanban?.options || [])
        .filter((option) => option.value != 'None')

    return options.map((option) => {
        const columnTasks = Object.values(rowGroups)
            .filter((row) => row.values[statColId]?.value == option.value)
            .map((row) => {
                const taskValues = getTaskValues(row, statColId, props.columns)

                return {
                    id: row.id,
                    title: taskValues[0]?.value || 'Untitled',
                    details: taskValues.slice(1),
                    date: formatDate(getLatestDate(taskValues.map((value) => value.updated_at))),
                    value_id: row.values[statColId].id
                }
            })

        return {
            title: option.value,
            tasks: columnTasks,
            move(task) {
                const parent = getStatusValue(task, props.values)

                router.put(route("table.values.update", {
                    table: table_id,
                    value: parent.id,
                    new_value: option.value
                }))
            }
        }
    })
}

const kanbanColumns = computed(() =>
    buildKanbanColumns(props, table_id, statusColumnId)
)

const updateTasks = (column, event) => {
    const tasks = event.item._underlying_vm_
    
    column.move(tasks)
}

const deleteOption = (column) => {
    const optionToDelete = props.selectedKanban?.options?.find((opt) =>
        opt.value === column.title &&
        opt.column_id === props.selectedKanban.column_id
    )

    if (confirm(`Are you sure you want to delete the "${column.title}" column?`)) {
        router.delete(route('selectvalues.destroy', { selectvalue: optionToDelete.id }), {
            onSuccess: () => {
                const targetValues = props.values
                    .filter((v) => {
                        return v.column_id == optionToDelete.column_id && v.value == optionToDelete.value
                    })

                targetValues.forEach((targetValue) => {
                    router.put(route("table.values.update", {
                        table: table_id,
                        value: targetValue.id,
                        new_value: "None"
                    }))
                })
            }
        })

        emit('back')
    }
}
</script>

<template>
    <div class="w-full">
        <div class="min-h-screen flex justify-evenly py-12">
            <div v-for="(column, index) in kanbanColumns" :key="column.title" class="bg-transparent px-3 w-3/12 py-3">
                <div class="flex flex-row">
                    <p class="roboto-font-bold capitalize tracking-wide text-lg">{{ column.title }}</p>
                    <div @click="deleteOption(column)">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-7 h-7 hover:text-red-500">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                    </div>
                </div>
                <p v-if="column.tasks.length == 0" class="text-gray-400 italic py-4 text-center">
                    No tasks in this column
                </p>
                <draggableComponent v-model="column.tasks" @add="updateTasks(column, $event)" :animation="200"
                    ghost-class="ghost-card" group="tasks" item-key="id">
                    <template #item="{ element }">
                        <TaskCard :index="index" :task="element" class="mt-3 cursor-move"></TaskCard>
                    </template>
                </draggableComponent>
            </div>
        </div>
    </div>
</template>

<style scoped>
.ghost-card {
    opacity: 0.5;
    background: #F7FAFC;
    border: 1px solid #4299e1;
}
</style>