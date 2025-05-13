<script setup>
const props = defineProps({
    task: {
        type: Object,
        required: true
    },
    index: Number
})

</script>

<template>
    <div class="bg-[#2B2C30] shadow px-2 py-2 rounded-lg hover:scale-110 hover:shadow-xl">
        <p class="text-sm">{{ task.date }}</p>
        <p class="roboto-font-bold text-xl tracking-wide">
            {{ task.title }}
        </p>

        <div class="mt-1 mb-5">
            <div v-for="(detail, index) in task.details" :key="index">
                <p class="roboto-font-medium text-lg">{{ detail.value }}</p>
            </div>
        </div>
    </div>
</template>
<style scoped>
div {
    transition: transform 0.3s ease;
}
</style>