<script setup>
import TaskCard from "./Components/TaskCard.vue"
import { computed } from 'vue'
import draggableComponent from 'vue3-draggable-next'

const props = defineProps({
    selectedKanban: Object,
    values: Array,
    columns: Array
})

const emit = defineEmits(['back', 'update'])
const back = () => emit('back')

const statusColumnId = computed(() => props.selectedKanban.column_id)

const kanbanColumns = computed(() => {
    const rowGroups = {}
    props.values?.forEach(value => {
        const rowKey = `row_${value.order}`
        if (!rowGroups[rowKey]) {
            rowGroups[rowKey] = { id: rowKey, values: {} }
        }
        rowGroups[rowKey].values[value.column_id] = value.value
    })

    const statColId = statusColumnId.value
    const options = (props.selectedKanban?.options || []).filter(option => option.value !== 'None')

    return options.map(option => {
        const columnTasks = Object.values(rowGroups)
            .filter(row => row.values[statColId] == option.value)
            .map(row => {
                const taskValues = Object.entries(row.values)
                    .filter(([colId]) => colId != statColId.toString())
                    .map(([colId, value]) => {
                        const column = props.columns?.find(col => col.id.toString() == colId.toString())
                        return {
                            columnName: column?.name || 'Unknown',
                            colId,
                            value
                        }
                    })

                return {
                    id: row.id,
                    title: taskValues[0]?.value || 'Untitled',
                    details: taskValues.slice(1),
                    date: new Date().toLocaleDateString('en-US', { month: 'short', day: 'numeric' }) + '.',
                }
            })

        return {
            title: option.value,
            tasks: columnTasks
        }
    })
})

const update = (e) => {
    // console.log(e)
    emit('update', e)
}
</script>

<template>
    <div class="w-full">
        <div class="min-h-screen flex justify-evenly py-12">
            <div v-for="column in kanbanColumns" :key="column.title"
                class="bg-transparent px-3 w-3/12 py-3">
                <p class="roboto-font-bold capitalize tracking-wide text-lg">{{ column.title }}</p>
                <p v-if="column.tasks.length == 0" class="text-gray-400 italic py-4 text-center">
                    No tasks in this column
                </p>
                <draggableComponent v-model="column.tasks" @end="update" :animation="200" ghost-class="ghost-card"
                    group="tasks" item-key="id">
                    <template #item="{ element }">
                        <TaskCard :task="element" class="mt-3 cursor-move"></TaskCard>
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