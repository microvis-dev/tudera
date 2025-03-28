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
        <div class="flex items-center justify-center py-2 px-3 transition-colors duration-150 hover:bg-gray-50 rounded-md w-full">
            <div class="cursor-pointer text-center w-full" @dblclick="enableEditing">
                <input v-if="editState.isEditing" v-model="editState.editedValue" @blur="saveEdit"
                    @keyup.enter="saveEdit"
                    class="w-full text-center border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm text-sm"
                    ref="el => editState.valueInputTextField = el" />
                <div v-else class="text-center">
                    <span class="text-gray-800 font-medium text-sm truncate">{{ value.value }}</span>
                </div>
            </div>
            <button @click="deleteValue"
                class="p-1 text-gray-500 hover:text-red-600 transition-colors duration-150 rounded hover:bg-red-50"
                title="Delete value">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                        clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>
</template>