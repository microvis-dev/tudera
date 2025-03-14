<script setup>
import { reactive, nextTick } from 'vue';

const props = defineProps({
  row: Object,
  column: Object
})

const emit = defineEmits(['delete', 'update'])

const deleteRow = () => {
  if (confirm("Are u sure?")) {
    emit('delete', props.row.id)
  }
}

const editState = reactive({
  isEditing: false,
  editedName: props.row.name,
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
  if (editState.editedName.trim() && editState.editedName !== props.row.name) {
    emit("update", editState.editedName, props.row)
  }

  editState.isEditing = false
}
</script>

<template>
  <div
    class="flex items-center justify-between py-2 px-3 transition-colors duration-150 hover:bg-gray-50 rounded-md group">
    <div @dblclick="enableEditing" class="flex-grow cursor-pointer min-w-0">
      <input v-if="editState.isEditing" v-model="editState.editedName" @blur="saveEdit" @keyup.enter="saveEdit"
        class="w-full border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm text-sm"
        ref="el => editState.nameInputTextField = el" />
      <div v-else class="flex items-center">
        <span class="text-gray-800 font-medium text-sm truncate">{{ row.name }}</span>
        <span v-if="column" class="ml-2 text-xs text-gray-500 font-light">{{ column.name }}</span>
      </div>
    </div>

    <div class="flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity duration-150">
      <button @click="enableEditing"
        class="p-1 text-gray-500 hover:text-blue-600 transition-colors duration-150 rounded hover:bg-blue-50"
        title="Edit row name">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
          <path
            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
        </svg>
      </button>
      <button @click="deleteRow"
        class="p-1 text-gray-500 hover:text-red-600 transition-colors duration-150 rounded hover:bg-red-50"
        title="Delete row">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd"
            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
            clip-rule="evenodd" />
        </svg>
      </button>
    </div>
  </div>
</template>

<style scoped></style>