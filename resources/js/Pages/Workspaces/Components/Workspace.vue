<script setup>
import { ref, nextTick, defineEmits } from "vue";

const props = defineProps({
    workspace: Object
})

const emit = defineEmits(["update-name"])

const isEditing = ref(false)
const editedName = ref(props.workspace.name)
const inputField = ref(null)

const enableEditing = async () => {
    isEditing.value = true
    await nextTick()
    inputField.value.focus()
}

const saveEdit = (() => {
    if (editedName.value.trim() && editedName.value !== props.workspace.name) {
        emit("update-name", { id: props.workspace.workspace_id, name: editedName.value })
    }

    isEditing.value = false
})


</script>

<template>
    <div @dblclick="enableEditing" class="p-2 cursor-pointer">
        <input v-if="isEditing" v-model="editedName" @blur="saveEdit" @keyup.enter="saveEdit"
            class="border px-2 py-1 rounded" ref="inputField" />
        <span v-else>{{ workspace.name }}</span>
    </div>
</template>

<style scoped></style>