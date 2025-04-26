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
    const options = props.selectedKanban?.options || []

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
                    details: taskValues
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
    <button @click="back" class="bg-blue-600 px-4 py-2 text-white rounded mb-4">Back</button>
    <div class="flex justify-center">
        <div class="min-h-screen flex overflow-x-scroll py-12">
            <div v-for="column in kanbanColumns" :key="column.title"
                class="bg-gray-100 rounded-lg px-3 py-3 column-width mr-4">
                <p class="text-gray-700 font-semibold font-sans tracking-wide text-sm">{{ column.title }}</p>
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
.column-width {
    min-width: 320px;
    width: 320px;
}

.ghost-card {
    opacity: 0.5;
    background: #F7FAFC;
    border: 1px solid #4299e1;
}
</style>