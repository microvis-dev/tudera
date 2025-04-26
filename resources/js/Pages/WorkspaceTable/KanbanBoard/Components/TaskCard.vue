<script setup>
import Badge from './Badge.vue'; // !

defineProps({
    task: {
        type: Object,
        required: true
    }
});
</script>

<template>
    <div class="bg-white shadow rounded px-3 pt-3 pb-5 border border-white hover:shadow-md">
        <div class="flex justify-between">
            <p class="text-gray-700 font-semibold font-sans tracking-wide text-base my-1">
                {{ task.title }}
            </p>
        </div>

        <div class="mt-2">
            <div v-for="(detail, index) in task.details" :key="index" class="text-gray-600 text-sm mb-1">
                <span class="font-medium">{{ detail.columnName }}:</span> {{ detail.value }}
            </div>
        </div>
        
        <div class="flex mt-4 justify-between items-center">
            <p class="text-gray-600 text-sm">{{ task.date }}</p>
        </div>
    </div>
</template>