<script setup>
import { ref } from "vue";
const customValue = ref("");
const emit = defineEmits(['exit']);
const close = () => {
    emit('exit');
};
</script>

<template>
    <form>
        <div class="fixed inset-0 flex items-center justify-center z-50 text-white">
            <div class="bg-[#2B2C30] p-8 rounded-lg shadow-lg w-11/12 max-w-md relative" @click.stop>
                <span
                    class="absolute top-4 right-4 text-2xl cursor-pointer opacity-70 hover:opacity-100 transition-opacity" @click="close">&times;</span>
                <h2 class="text-xl font-semibold mb-6">Create a custom value in Kanban </h2>
                <div class="bg-transparent">
                    <input type="text" placeholder="Add custom value..." v-model="customValue"
                        class="bg-[#5D5E5B] peer rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                </div>
                <div class="border mt-5 p-4 rounded-xl bg-black flex flex-col items-center">
                    <h1 class="">{{ customValue.length === 0 ? '{New value name}' : customValue }}</h1>
                    <div class="bg-[#2B2C30] w-1/2 mt-5 shadow px-2 py-2 rounded-lg flex flex-col items-start">
                        <p>Oct 4.</p>
                        <p class="text-sm">Amazon Deal</p>
                        <p class="roboto-font-bold text-xl tracking-wide">
                            140.000$
                        </p>
                    </div>
                </div>
                <div class="flex justify-evenly gap-3 mt-6 sm:flex-row flex-col-reverse">
                    <button type="button" @click="close"
                        class="bg-gray-100 text-gray-800 px-5 py-3 rounded-xl hover:bg-gray-200 hover:drop-shadow-xl transition-colors sm:w-auto w-full">Cancel</button>
                    <button type="submit"
                        class="text-white px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 hover:drop-shadow-xl transition-colors sm:w-auto w-full">Add
                        custom value</button>
                </div>
            </div>
        </div>
    </form>
</template>
