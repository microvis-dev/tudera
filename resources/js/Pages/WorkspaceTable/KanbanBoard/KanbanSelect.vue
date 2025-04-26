<script setup>
import { ref } from "vue"
import Kanban from "./Kanban.vue"

const props = defineProps({
    status_options: Array,
    show: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['hide-table', 'back'])

const selectedKanban = ref(props.status_options[0])
const selectKanban = ((index) => {
    selectedKanban.value = props.status_options[index]
    emit('hide-table')
})

const back = () => {
    emit('back')
}
</script>

<template>
    <div v-for="(kanban, index) in status_options" class="flex flex-col w-full h-full">
        <button @click="selectKanban(index)" class="bg-blue-600">{{ kanban.name }}</button><br>
    </div>
    <Kanban v-if="show" :selectedKanban="selectedKanban" @back="back()" />
</template>

<style scoped></style>