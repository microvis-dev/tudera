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
    },
    options: Array 
})

const showButton = ref(true)
const value = ref("")
if (props.column.type == "status") {
    value.value = "None"
    emit('save', value.value, props.column, props.order)
}

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
        class="bg-blue-500 w-8 h-8 flex items-center justify-center hover:bg-blue-700 rounded-full mx-auto opacity-0 group-hover:opacity-100 transition-opacity">
        +
    </button>
    <div v-else>
        <select v-if="column.type == 'status'" v-model="value" @change="save">
            <option v-for="option in options" :key="option.value" :value="option.value">{{ option.value }}</option>
        </select>
        <input v-else v-model="value" type="text" class="w-full p-1 bg-transparent text-center" @keyup.enter="save" placeholder="Enter value" />
    </div>
</template>

<style scoped></style>