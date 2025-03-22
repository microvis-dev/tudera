<script setup>
import { reactive } from 'vue';
import { router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import CreateToDoModal from '@/resources/js/Pages/Dashboard/Components/CreateToDoModal.vue';

const viewState = reactive({
    addTodoModal: false
})

const showAddTodoModal = () => {
    viewState.addTodoModal = true
}

const hideAddTodoModal = () => {
    viewState.addTodoModal = false
}
</script>
<template>
    <section class="flex flex-col p-5">
        <h1 class="text-2xl roboto-font-bold mb-2">Todo List</h1>
        <p class="text-sm roboto-font-medium mb-5 text-[#B3B3B3]">Track your todos in a simple sidebar</p>
        <button @click="showAddTodoModal" class="w-fit p-2 px-10 border rounded-xl bg-blue-600">Add new todo</button>
    </section>
    <CreateToDoModal v-if="viewState.addTodoModal"  @exit="hideAddTodoModal" />
</template>
<style scoped>
    
</style>