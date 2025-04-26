<script setup>
import { cloneVNode, computed, ref } from "vue"
import { useTuderaStore } from "@/resources/js/state/state"
import Kanban from "./Kanban.vue"

const props = defineProps({
    status_options: Array,
    show: {
        type: Boolean,
        default: false
    },
    columns: Array,
    values: Array
})

const emit = defineEmits(['hide-table', 'back'])

const tuderaState = useTuderaStore()

const selectedKanban = ref(props.status_options[0])
const selectKanban = ((index) => {
    selectedKanban.value = props.status_options[index]
    emit('hide-table')
})

const back = () => {
    emit('back')
}

const handleUpdate = (event) => {
    
}

</script>

<template>
    <div v-for="(kanban, index) in status_options" class="flex flex-col w-full h-full">
        <button @click="selectKanban(index)" class="bg-blue-600">{{ kanban.name }}</button><br>
    </div>
    <Kanban v-if="show" :selectedKanban="selectedKanban" :values="values" :columns="columns" @back="back()" @update="handleUpdate" />
</template>

<style scoped></style>