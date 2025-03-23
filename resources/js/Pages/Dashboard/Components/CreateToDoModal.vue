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
        <div class="fixed inset-0 flex items-center justify-center z-50 text-white" @click="close">
            <div class="bg-[#2B2C30] p-8 rounded-lg shadow-lg w-11/12 max-w-md relative" @click.stop>
                <span class="absolute top-4 right-4 text-2xl cursor-pointer opacity-70 hover:opacity-100 transition-opacity" @click="close">&times;</span>
                <h2 class="text-xl font-semibold mb-6">Add Todo</h2>
                
                <div class="flex flex-col mb-5">
                        <label class="text-[#B3B3B3] roboto-font-regular">Title</label>
                        <input type="text" :value="getWorkspaceUrl"
                            class="px-3 py-2 bg-[#1C1D21] border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 text-gray-500 text-sm">
                    </div>

                <div class="flex flex-wrap gap-4">
                    <div class="w-full sm:w-[calc(50%-0.5rem)]">
                        <label for="start_date" class="text-[#B3B3B3] roboto-font-regular">Start Date</label>
                        <input v-model="todoForm.start_date" id="start_date" type="datetime-local"
                               class="px-3 py-2 bg-[#1C1D21] h-fit border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 text-gray-500 text-sm" />
                    </div>

                    <div class="w-full sm:w-[calc(50%-0.5rem)]">
                        <label for="start_date" class="text-[#B3B3B3] roboto-font-regular">End Date</label>
                        <input v-model="todoForm.start_date" id="end_date" type="datetime-local"
                               class="px-3 py-2 bg-[#1C1D21] h-fit border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 text-gray-500 text-sm" />
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 sm:flex-row flex-col-reverse">
                    <button type="button" @click="close"
                            class="bg-gray-100 text-gray-800 px-5 py-3 rounded-xl hover:bg-gray-200 hover:drop-shadow-xl transition-colors sm:w-auto w-full">Cancel</button>
                    <button type="submit"
                            class="text-white px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 hover:drop-shadow-xl transition-colors sm:w-auto w-full">Add Todo</button>
                </div>
            </div>
        </div>
    </form>
</template>
