<script setup>
import { reactive, nextTick, ref } from 'vue';

const emit = defineEmits(['add', 'update', 'delete'])

const props = defineProps({
    value: Object,
    column: Object,
    options: Array
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

const getValueType = () => {
    switch (props.type) {
        case "status": "select"
        default: "text"
    }
}
</script>

<template>
    <div class="flex flex-col items-center justify-center">
        <div class="flex items-center justify-center py-2 px-3 duration-150 rounded-md w-full">
            <div class="cursor-pointer text-center w-full" @dblclick="enableEditing">
                <div v-if="editState.isEditing">
                    <select v-if="column.type == 'status'" v-model="editState.editedValue" @change="saveEdit">
                        <option v-for="option in options" :key="option.value" :value="option.value">
                            {{ option.value }}
                        </option>
                    </select>
                    <input v-else v-model="editState.editedValue" @keyup.enter="saveEdit"
                        class="w-full text-center bg-[#3e3f45] cursor-pointer rounded-md shadow-sm text-sm"
                        ref="el => editState.valueInputTextField = el" :type="getValueType()" />
                </div>
                <div v-else class="text-center">
                    <span class=" font-medium text-sm truncate">{{ value.value }}</span>
                </div>
            </div>
        </div>
    </div>
</template>