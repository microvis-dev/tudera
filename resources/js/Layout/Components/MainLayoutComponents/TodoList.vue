<script setup>
import { ref, reactive } from 'vue';
import CreateToDoModal from '@/resources/js/Pages/Dashboard/Components/CreateToDoModal.vue';
const checked = ref(false);
const todoList = ref([
    {
        'name': "DÁP regisztráció", "endDate": "2025/02/03 9:00", "completed": false
    },
    {
        'name': "Lorem Ipsum", "endDate": "2025/04/22 15:00", "completed": false
    },
])

const viewState = reactive({
    addTodoModal: false
})

const showAddTodoModal = () => {
    viewState.addTodoModal = true
}

const hideAddTodoModal = () => {
    viewState.addTodoModal = false
}
const lateToDate = (todo) => {
    const currentDate = new Date();
    const deadline = new Date(todo.endDate);
    return deadline < currentDate;
}
</script>
<template>
    <section class="flex flex-col p-5">
        <h1 class="text-2xl roboto-font-bold mb-2">Todo List</h1>
        <p class="text-sm roboto-font-medium mb-5 text-[#B3B3B3]">Track your todos in a simple sidebar</p>
        <fieldset>
            <div v-for="(todo, index) in todoList" class="flex flex-row py-2 w-full items-center gap-4">
                <div class="w-1/12">
                    <div class="inline-flex items-center">
                        <label class="flex items-center cursor-pointer relative">
                            <input type="checkbox" v-model="todo.completed"
                                class="peer h-5 w-5 cursor-pointer transition-all appearance-none rounded shadow hover:shadow-md border border-slate-300 checked:bg-slate-800 checked:border-slate-800"
                                :id="`check-${index}`" />
                            <span
                                class="absolute text-white opacity-0 peer-checked:opacity-100 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20"
                                    fill="currentColor" stroke="currentColor" stroke-width="1">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </span>
                        </label>
                    </div>
                </div>
                <div class="w-6/12">
                    <h2 class="roboto-font-bold text-md" :class="{'text-gray-500 line-through': todo.completed}">{{ todo.name }}</h2>
                </div>
                <div class="w-7/12 text-center rounded-xl p-1" :class="{ 'text-gray-500 line-through': todo.completed, 'bg-red-500': lateToDate(todo) }">
                    <p>{{ todo.endDate }}</p>
                </div>  
            </div>
        </fieldset>
        <div class="flex justify-center mt-5">
            <button @click="showAddTodoModal" class="w-fit p-2 px-10 rounded-xl bg-blue-600">Add new todo</button>
        </div>
        <CreateToDoModal v-if="viewState.addTodoModal" @exit="hideAddTodoModal" />
    </section>
</template>
<style scoped></style>