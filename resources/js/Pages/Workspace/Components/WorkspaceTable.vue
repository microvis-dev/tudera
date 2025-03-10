<script setup>
import { router, Link } from "@inertiajs/vue3";
import { nextTick, defineEmits, reactive } from "vue";
import { route } from "ziggy-js";

const props = defineProps({
  table: Object
})

const emit = defineEmits(["update-table", "remove-table"])

const editState = reactive({
    isEditing: false,
    editedName: props.table.name,
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
    if (editState.editedName.trim() && editState.editedName !== props.table.name) {
        emit("update-table", { id: props.table.id, name: editState.editedName })
    }

    editState.isEditing = false
}

const removeTable = () => {
    if (confirm('Are you sure?')) {
        emit("remove-table", props.table.id)
    }
}

const go = () => {
    router.get(route('workspace.table.show', { table: props.table.id }))
}
</script>

<template>
  <div class="p-4 border-b border-gray-200">
    <div @dblclick="enableEditing" class="p-2 cursor-pointer">
      <input v-if="editState.isEditing" v-model="editState.editedName" @blur="saveEdit" @keyup.enter="saveEdit"
        class="border px-2 py-1 rounded" ref="el => editState.nameInputTextField = el" />
      <span v-else>{{ table.name }}</span>
    </div>
    <button @click="go" class="ml-2 px-2 py-1 bg-green-500 text-white rounded">
      Go
    </button>
    <button v-if="!editState.isEditing" @click="enableEditing" class="ml-2 px-2 py-1 bg-blue-500 text-white rounded">
      Edit
    </button>
    <button @click="removeTable" class="ml-4 px-2 py-1 bg-red-500 text-white rounded">
      Remove
    </button>
  </div>
</template>

<style scoped></style>