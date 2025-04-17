<script setup>
import { reactive, nextTick } from 'vue'

const props = defineProps({
    column: Object
})

const emit = defineEmits(['delete', 'update'])

const deleteColumn = () => {
    if (props.column.order > 1 && confirm("Are u sure?")) {
        emit('delete', props.column.id)
    }
}

const editState = reactive({
    isEditing: false,
    editedName: props.column.name,
    nameInputTextField: null
})

const enableEditing = async () => {
    if (props.column.order > 1) {
        editState.isEditing = true
        await nextTick()
        if (editState.nameInputTextField) {
            editState.nameInputTextField.focus()
        }
    }
}

const saveEdit = () => {
    if (editState.editedName.trim() && editState.editedName !== props.column.name) {
        emit("update", editState.editedName, props.column)
    }

    editState.isEditing = false
}
</script>

<template>
    <div
        class="flex items-center justify-between py-2 px-3 transition-colors duration-150 rounded-md group">
        <div @dblclick="enableEditing" class="flex-grow cursor-pointer min-w-0">
            <input v-if="editState.isEditing" v-model="editState.editedName" @blur="saveEdit" @keyup.enter="saveEdit"
                class="w-full bg-transparent text-center border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm text-sm"
                ref="el => editState.nameInputTextField = el" />
            <div v-else class="flex justify-center">
                <span class="font-medium text-sm truncate">{{ column.name }}</span>
            </div>
        </div>
    </div>
</template>

<style scoped></style>