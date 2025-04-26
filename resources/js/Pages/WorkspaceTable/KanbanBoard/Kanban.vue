<script setup>
import TaskCard from "./Components/TaskCard.vue";
import { computed, reactive } from 'vue';
import draggableComponent from 'vue3-draggable-next';
import { useTuderaStore } from "@/resources/js/state/state";

const props = defineProps({
    selectedKanban: Object
})

const emit = defineEmits(['back'])

const tuderaState = useTuderaStore()

const back = (() => emit('back'))

const columns = computed(() => {
    let options = props.selectedKanban.options
    let result = []


    options.forEach((option) => {
        let tasks = []
        let taskObj = {
            title: option.value,
            tasks: tasks
        }

        result.push(taskObj)
    })

    return result
})
console.log(props.selectedKanban)

const columnsS = reactive([
    {
        title: "Backlog", // col name
        tasks: [
            {
                id: 1, // values
                title: "Add discount code to checkout page",
                date: "Sep 14",
                type: "Feature Request"
            },
            {
                id: 2,
                title: "Provide documentation on integrations",
                date: "Sep 12"
            },
            {
                id: 3,
                title: "Design shopping cart dropdown",
                date: "Sep 9",
                type: "Design"
            },
            {
                id: 4,
                title: "Add discount code to checkout page",
                date: "Sep 14",
                type: "Feature Request"
            },
            {
                id: 5,
                title: "Test checkout flow",
                date: "Sep 15",
                type: "QA"
            }
        ]
    },
    {
        title: "In Progress",
        tasks: [
            {
                id: 6,
                title: "Design shopping cart dropdown",
                date: "Sep 9",
                type: "Design"
            },
            {
                id: 7,
                title: "Add discount code to checkout page",
                date: "Sep 14",
                type: "Feature Request"
            },
            {
                id: 8,
                title: "Provide documentation on integrations",
                date: "Sep 12",
                type: "Backend"
            }
        ]
    },
    {
        title: "Review",
        tasks: [
            {
                id: 9,
                title: "Provide documentation on integrations",
                date: "Sep 12"
            },
            {
                id: 10,
                title: "Design shopping cart dropdown",
                date: "Sep 9",
                type: "Design"
            },
            {
                id: 11,
                title: "Add discount code to checkout page",
                date: "Sep 14",
                type: "Feature Request"
            },
            {
                id: 12,
                title: "Design shopping cart dropdown",
                date: "Sep 9",
                type: "Design"
            },
            {
                id: 13,
                title: "Add discount code to checkout page",
                date: "Sep 14",
                type: "Feature Request"
            }
        ]
    },
    {
        title: "Done",
        tasks: [
            {
                id: 14,
                title: "Add discount code to checkout page",
                date: "Sep 14",
                type: "Feature Request"
            },
            {
                id: 15,
                title: "Design shopping cart dropdown",
                date: "Sep 9",
                type: "Design"
            },
            {
                id: 16,
                title: "Add discount code to checkout page",
                date: "Sep 14",
                type: "Feature Request"
            }
        ]
    }
])
</script>

<template>
    <button @click="back" class="bg-blue-600">Back</button>
    <div id="app">
        <div class="flex justify-center">
            <div class="min-h-screen flex overflow-x-scroll py-12">
                <div v-for="column in columns" :key="column.title"
                    class="bg-gray-100 rounded-lg px-3 py-3 column-width mr-4">
                    <p class="text-gray-700 font-semibold font-sans tracking-wide text-sm">{{ column.title }}</p>

                    <draggableComponent v-model="column.tasks" :animation="200" ghost-class="ghost-card" group="tasks"
                        item-key="id">
                        <template #item="{ element }">
                            <task-card :task="element" class="mt-3 cursor-move"></task-card>
                        </template>
                    </draggableComponent>
                </div>
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