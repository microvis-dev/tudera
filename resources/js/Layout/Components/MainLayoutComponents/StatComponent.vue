<script setup>
import { computed } from "vue";
import { CircleProgressBar } from 'circle-progress.vue';

const props = defineProps({
    title: String,
    value: Number,
    previousValue: Number,
    color: String
})

const percentage = computed(() => {
    let value = ((props.value - props.previousValue) / props.previousValue * 100).toFixed(2)
    
    if (isNaN(value)) return 0

    return value == Infinity ? "?" : value // ?
})

</script>
<template>
    <section class="flex flex-row justify-between md:justify-center">
        <div class="flex flex-col items-start md:p-1">
            <h1 class="text-3xl md:text-lg px-2 py-3 mt-3 md:mt-0 md:p-3 roboto-font-bold">{{ title }}</h1>
            <h1 class="text-5xl md:texl-3xl py-3 px-2 md:px-3 roboto-font-bold">{{ value }}</h1>
            <p class="text-[#B3B3B3] px-2 py-3 md:p-3 roboto-font-bold text-md">Last month: {{ previousValue }}</p>
        </div>
        <div class="flex items-center ms-5">
            <CircleProgressBar :color-unfilled="color" color-back="#1A1A1A" :size="150" stroke-width="12" :value="percentage / 10"
                :max="10" rounded>
                <span :style="{ color }" class="text-2xl roboto-font-bold">
                    +{{ percentage }}%
                </span>
            </CircleProgressBar>
        </div>
    </section>
</template>
<style scoped></style>