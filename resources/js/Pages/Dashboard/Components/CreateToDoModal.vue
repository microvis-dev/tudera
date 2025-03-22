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
        <div class="fixed inset-0 flex items-center justify-center z-50 text-white">
            <div class="bg-[#2B2C30] p-8 rounded-lg shadow-lg w-11/12 max-w-md relative">
                <span class="absolute top-4 right-4 text-2xl cursor-pointer opacity-70 hover:opacity-100 transition-opacity" @click="close">&times;</span>
                <h2 class="text-xl font-semibold mb-6">Add Todo</h2>
                
                <div class="mb-4">
                    <label for="title" class="block mb-2 text-sm text-gray-600">Title</label>
                    <input v-model="todoForm.title" id="title" type="text" placeholder="Enter title..." 
                           class="w-full px-3 py-3 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" />
                </div>

                <div class="mb-4">
                    <label for="description" class="block mb-2 text-sm text-gray-600">Description</label>
                    <textarea v-model="todoForm.description" id="description" placeholder="Enter description..." rows="3"
                             class="w-full px-3 py-3 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500"></textarea>
                </div>

                <div class="flex flex-wrap gap-4">
                    <div class="w-full sm:w-[calc(50%-0.5rem)]">
                        <label for="start_date" class="block mb-2 text-sm text-gray-600">Start Date</label>
                        <input v-model="todoForm.start_date" id="start_date" type="datetime-local"
                               class="w-full px-3 py-3 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" />
                    </div>

                    <div class="w-full sm:w-[calc(50%-0.5rem)]">
                        <label for="end_date" class="block mb-2 text-sm text-gray-600">End Date</label>
                        <input v-model="todoForm.end_date" id="end_date" type="datetime-local"
                               class="w-full px-3 py-3 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" />
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 sm:flex-row flex-col-reverse">
                    <button type="button" @click="close"
                            class="bg-gray-100 text-gray-800 px-5 py-3 rounded-md hover:bg-gray-200 transition-colors sm:w-auto w-full">Cancel</button>
                    <button type="submit"
                            class="bg-blue-500 text-white px-5 py-3 rounded-md hover:bg-blue-600 transition-colors sm:w-auto w-full">Add Todo</button>
                </div>
            </div>
        </div>
    </form>
</template>
