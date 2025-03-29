<script setup>
import { ref } from 'vue'

const emit = defineEmits(['save'])

const props = defineProps({
    column: Object,
    isNewRow: {
        type: Boolean,
        default: false
    },
    order: {
        type: Number,
        default: null
    }
})

const showButton = ref(true)
const value = ref("")

const toggleButton = () => {
    showButton.value = showButton.value = false
}

const save = () => {
    emit('save', value.value, props.column, props.order)
    showButton.value = true
}
</script>

<template>
    <button v-if="showButton && !isNewRow" @click="toggleButton"
        class="bg-blue-500 text-white w-8 h-8 flex items-center justify-center hover:bg-blue-700 rounded-full mx-auto opacity-0 group-hover:opacity-100 transition-opacity">
        +
    </button>
    <input v-else v-model="value" type="text" class="w-full p-1 border border-gray-300 rounded" @keyup.enter="save" placeholder="Enter value" />
</template>

<style scoped></style>