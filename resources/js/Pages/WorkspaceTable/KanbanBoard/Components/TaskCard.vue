<script setup>
import Badge from './Badge.vue';

defineProps({
    task: {
        type: Object,
        required: true
    }
});

function getBadgeColor(type) {
  const colors = {
    'Feature Request': 'green',
    'Design': 'purple',
    'Backend': 'blue',
    'QA': 'orange'
  };
  return colors[type] || 'gray';
}
</script>

<template>
    <div class="bg-white shadow rounded px-3 pt-3 pb-5 border border-white">
        <div class="flex justify-between">
            <p class="text-gray-700 font-semibold font-sans tracking-wide text-base my-1">
                {{ task.title }}
            </p>
        </div>
        <div class="flex mt-4 justify-between items-center">
            <p class="text-gray-600 text-sm">{{ task.date }}</p>
            <Badge v-if="task.type" :color="getBadgeColor(task.type)">{{ task.type }}</Badge>
        </div>
    </div>
</template>
