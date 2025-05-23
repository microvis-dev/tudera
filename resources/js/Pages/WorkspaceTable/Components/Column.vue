<script setup>
import { ref, watch, reactive, nextTick, computed, onBeforeUnmount, onMounted } from 'vue'

const props = defineProps({
    column: Object,
    maxRows: Number,
    columnCount: Number
})

const emit = defineEmits(['delete', 'update', 'move-left', 'move-right'])

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
const showSettingsBoolean = ref(false)
const showSetting = () => {
    showSettingsBoolean.value = !showSettingsBoolean.value
}


const handleClickOutside = (event) => {
    const settingsMenu = document.querySelector('.absolute.right-0.top-full')
    const settingsButton = event.target.closest('.cursor-pointer')
    if (
        showSettingsBoolean.value &&
        settingsMenu &&
        !settingsMenu.contains(event.target) &&
        !settingsButton
    ) {
        showSettingsBoolean.value = false
    }
}

onMounted(() => {
    document.addEventListener('mousedown', handleClickOutside)
})
onBeforeUnmount(() => {
    document.removeEventListener('mousedown', handleClickOutside)
})

const arrowState = computed(() => ({
    left: props.column.order > 2,
    right: props.column.order < props.columnCount
}))

// move
const moveLeft = () => { 
    let newOrder = props.column.order - 1
    emit('move-left', props.column, newOrder)
}

const moveRight = () => { 
    let newOrder = props.column.order + 1
    emit('move-right', props.column, newOrder)
}
const changeDisplay = ref('absolute ml-auto')
watch(() => props.maxRows, (newMaxRows) => {
    changeDisplay.value = newMaxRows > 1 ? 'relative' : 'absolute ml-auto'
}, { immediate: true })
</script>

<template>
    <div class="flex items-center justify-between py-2 px-3 transition-colors duration-150 rounded-md group">
        <div @dblclick="enableEditing" class="flex-grow cursor-pointer min-w-0">
            <input v-if="editState.isEditing" v-model="editState.editedName" @blur="saveEdit" @keyup.enter="saveEdit"
                class="w-full bg-[#3e3f45] cursor-pointer text-center border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm text-sm"
                ref="el => editState.nameInputTextField = el" />
            <div v-else class="flex justify-center">
                <span class="font-medium text-sm truncate">{{ column.name }}</span>
            </div>
        </div>
        <div :class="changeDisplay" v-if="column.order > 1" :key="props.maxRows">
            <div @click="showSetting" class="cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                </svg>
            </div>
            <div v-if="showSettingsBoolean"
                class="absolute right-0 top-full mt-1 bg-[#3e3f45] rounded-md shadow-lg z-50 p-2 min-w-[150px] text-center">
                <div class="flex flex-row justify-around">
                    <button @click="moveLeft" v-if="arrowState.left"
                        class="text-sm py-1 px-2 hover:bg-gray-700 rounded text-left">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                        </svg>
                    </button>
                    <button @click="moveRight" v-if="arrowState.right"
                        class="text-sm py-1 px-2 hover:bg-gray-700 rounded text-left">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </button>
                </div>
                <button @click="enableEditing" class="text-sm py-1 px-2 hover:bg-gray-700 rounded">
                    Edit Column
                </button>
                <button @click="deleteColumn" class="text-red-500 text-sm py-1 px-2 hover:bg-gray-700 rounded">
                    Delete Column
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped></style>