<script setup>
import { useForm } from '@inertiajs/vue3';

const emit = defineEmits(['exit'])

const todoForm = useForm({
    title: null,
    description: null,
    start_date: null,
    end_date: null
})

const createTodo = () => {
    todoForm.post(route('todolist.store'), {
        onSuccess: () => emit('exit')
    })
}

const close = () => {
    emit('exit')
}
</script>

<template>
    <form @submit.prevent="createTodo">
        <div class="modal">
            <div class="modal-content">
                <span class="close-button" @click="close">&times;</span>
                <h2>Add ToDo</h2>
                <div>
                    <label for="title">Title</label>
                    <input v-model="todoForm.title" id="title" type="text" />

                    <label for="description">Description</label>
                    <textarea v-model="todoForm.description" id="description"></textarea>

                    <label for="start_date">Start Date</label>
                    <input v-model="todoForm.start_date" id="start_date" type="date" />

                    <label for="end_date">End Date</label>
                    <input v-model="todoForm.end_date" id="end_date" type="date" />

                    <button type="submit">Add ToDo</button>
                    <button type="button" @click="close">Exit</button>
                </div>
            </div>
        </div>
    </form>
</template>

<style scoped>
.modal {
    display: flex;
    justify-content: center;
    align-items: center;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    color: black;
}

.modal-content {
    background-color: #fff;
    padding: 20px;
    border-radius: 5px;
    width: 500px;
    max-width: 100%;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    position: relative;
}

.close-button {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 20px;
    cursor: pointer;
}
</style>