<script setup>
import TaskCard from "./Components/TaskCard.vue"
import { computed, nextTick, ref, watch } from 'vue'
import draggableComponent from 'vue3-draggable-next'
import { router } from "@inertiajs/vue3"
import { route } from "ziggy-js"

const props = defineProps({
    selectedKanban: Object,
    values: Array,
    columns: Array
})

const emit = defineEmits(['back', 'update'])
const back = () => emit('back')

const table_id = props.columns[0].table_id

const statusColumnId = computed(() => props.selectedKanban?.column_id || null)

const kanbanColumns = computed(() => {
    const rowGroups = {}
    props.values?.forEach(value => {
        const rowKey = `row_${value.order}`
        if (!rowGroups[rowKey]) {
            rowGroups[rowKey] = { id: rowKey, values: {} }
        }

        rowGroups[rowKey].values[value.column_id] = {
            id: value.id,
            value: value.value
        }
    })

    const statColId = statusColumnId.value
    const options = (props.selectedKanban?.options || []).filter(option => option.value !== 'None')

    return options.map((option, i) => {
        const columnTasks = Object.values(rowGroups)
            .filter(row => row.values[statColId]?.value == option.value)
            .map((row, rowIndex) => {
                const taskValues = Object.entries(row.values)
                    .filter(([colId]) => colId != statColId.toString())
                    .map(([colId, valueObj], cIndex) => {
                        const column = props.columns?.find(col => col.id.toString() == colId.toString())

                        return {
                            columnName: column?.name || 'Unknown',
                            colId,
                            value: valueObj.value,
                            value_id: valueObj.id
                        }
                    })

                return {
                    id: row.id,
                    title: taskValues[0]?.value || 'Untitled',
                    details: taskValues.slice(1),
                    date: new Date().toLocaleDateString('en-US', { month: 'short', day: 'numeric' }) + '.',
                    value_id: row.values[statColId].id
                }
            })

        const getStatusValue = (task) => {
            const targetValue = props.values.find((v) => {
                return task.value_id == v.id
            })

            const targetStatusValue = props.values.find((v) => {
                return v.order == targetValue.order && v.column_id == targetValue.column_id
            })

            return targetStatusValue
        }

        return {
            title: option.value,
            tasks: columnTasks,
            move(task) {
                const parent = getStatusValue(task)

                router.put(route("table.values.update", {
                    table: table_id,
                    value: parent.id,
                    new_value: option.value
                }))
            }
        }
    })
})

const update = (column, event) => {
    const tasks = event.item._underlying_vm_;

    column.move(tasks)
}

const deleteOption = (column) => {
    const optionToDelete = props.selectedKanban?.options?.find(opt =>
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
        
    }

    //emit('back')
}
</script>

<template>
    <div class="w-full">
        <div class="min-h-screen flex justify-evenly py-12">
            <div v-for="(column, index) in kanbanColumns" :key="column.title" class="bg-transparent px-3 w-3/12 py-3">
                <button @click="deleteOption(column)">delete</button>
                <p class="roboto-font-bold capitalize tracking-wide text-lg">{{ column.title }}</p>
                <p v-if="column.tasks.length == 0" class="text-gray-400 italic py-4 text-center">
                    No tasks in this column
                </p>
                <draggableComponent v-model="column.tasks" @add="update(column, $event)" :animation="200"
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