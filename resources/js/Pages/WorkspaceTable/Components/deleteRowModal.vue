<script setup>
import { useTuderaStore } from '@/resources/js/state/state';
import { computed, proxyRefs } from 'vue';

const tuderaState = useTuderaStore()

const props = defineProps({
    checkboxes: Array,
    table: Array
})

const emit = defineEmits(['delete'])

const selected = computed(() => {
    return props.checkboxes.filter(checkbox => checkbox === true).length
})

const deleteRows = () => {
    const rowsToDeleteIndices = props.checkboxes
        .map((isChecked, rowIndex) => (isChecked ? rowIndex : null))
        .filter(rowIndex => rowIndex !== null)

    const rowsToDelete = rowsToDeleteIndices.map(rowIndex => {
        return props.table.map(column => column[rowIndex]).filter(value => value)
    })

    const updatedTable = props.table.map(column => {
        let filteredColumn = column.filter((_, rowIndex) => !rowsToDeleteIndices.includes(rowIndex))

        filteredColumn = filteredColumn
            .filter(row => row !== undefined)
            .map((row, index) => {
                return { ...row, order: index + 1 }
            })

        return filteredColumn.length > 0 ? filteredColumn : null
    }).filter(column => column !== null)

    emit('delete', rowsToDelete.flat(), updatedTable)
}
</script>
<template>
    <div class="bg-[#2B2C30] p-5 rounded-xl w-46">
        <div class="flex flex-row">
            <h1 class="me-5 mt-3 text-2xl roboto-font-bold">Selected: {{ selected }}</h1>
            <div class=" hover:bg-red-600 p-2 rounded-xl">
                <button @click="deleteRows" class="flex flex-row"><span class="mt-2 roboto-font-bold">Delete</span><svg
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-10 h-10">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                </button>
            </div>
            <div v-if="selected == 1" class="flex flex-row items-center">
                <div class="hover:bg-[#3e3f45] hover:scale-125 mx-5 p-2 rounded-lg cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5 12 3m0 0 7.5 7.5M12 3v18" />
                    </svg>
                </div>
                <div class="hover:bg-[#3e3f45] hover:scale-125 p-2 rounded-lg cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped></style>