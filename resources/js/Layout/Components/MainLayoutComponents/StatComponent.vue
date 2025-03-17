<script setup>
import { ref, computed } from "vue";
const valueProgress = ref(34)
const props = defineProps({
    title: String,
    value: Number,
    previousValue: Number,
    color: String
})
import { useDisplay } from 'vuetify'

const { name } = useDisplay()

const height = computed(() => {
    switch (name.value) {
        case 'xs': return 150
        case 'sm': return 150
        case 'md': return 120
        case 'lg': return 120
        case 'xl': return 120
        default: return 120
    }
})

const width = computed(() => {
    switch (name.value) {
        case 'xs': return 20
        case 'sm': return 20
        case 'md': return 18
        case 'lg': return 18
        case 'xl': return 18
        default: return 18
    }
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
            <v-progress-circular :color="color" :model-value="valueProgress" :size="height" :width="width">
                <template v-slot:default>
                    <span class="roboto-font-bold text-3xl md:text-xl">+ {{ valueProgress }}%</span>
                </template>
            </v-progress-circular>
        </div>
    </section>
</template>
<style scoped></style>