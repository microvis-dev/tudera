<script setup>
import { reactive, nextTick, ref } from 'vue';

const emit = defineEmits(['add', 'update', 'delete'])

const props = defineProps({
    value: Object,
})

// edit
const editState = reactive({
    isEditing: false,
    editedValue: props.value?.value || '',
    nameInputTextField: null
})

const enableEditing = async () => {
    editState.isEditing = true
    await nextTick()
    if (editState.nameInputTextField) {
        editState.nameInputTextField.focus()
    }
}

const saveEdit = () => {
    if (!editState.editedValue) {
        emit('delete', props.value)
    }
    if (editState.editedValue.trim() && editState.editedValue !== props.value?.value) {
        emit("update", editState.editedValue, props.value)
    }

    editState.isEditing = false
}

const saveEditOrDeleteIfEmpty = () => {
    if (!editState.editedValue.trim() && props.value?.value) {
        if (confirm("Are u sure?")) {
            emit("update", "", props.value)
        } else {
            editState.editedValue = props.value.value
        }
    }
    else if (editState.editedValue.trim() && editState.editedValue !== props.value?.value) {
        emit("update", editState.editedValue, props.value)
    }

    editState.isEditing = false
}

const deleteValue = () => {
    if (confirm("Are u sure?")) {
        emit('delete', props.value)
    }
}
</script>

<template>
    <div class="flex flex-col items-center justify-center">
        <div class="flex items-center justify-center py-2 px-3 transition-colors duration-150 rounded-md w-full">
            <div class="cursor-pointer text-center w-full" @dblclick="enableEditing">
                <input v-if="editState.isEditing" v-model="editState.editedValue"
                    @keyup.enter="saveEdit"
                    class="w-full text-center border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm text-sm"
                    ref="el => editState.valueInputTextField = el" />
                <div v-else class="text-center">
                    <span class=" font-medium text-sm truncate">{{ value.value }}</span>
                </div>
            </div>
        </div>
    </div>
</template>