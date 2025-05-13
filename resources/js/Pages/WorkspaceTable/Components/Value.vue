<script setup>
import { reactive, nextTick, ref, computed } from 'vue';
import AddCustomStatusModal from './AddCustomStatusModal.vue';
import { router } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { getInputType, formatDateTimeToISO } from '@/resources/js/utils/utils';

const props = defineProps({
    value: Object,
    column: Object,
    options: Array
})

const emit = defineEmits(['add', 'update', 'delete'])

const inputType = computed(() => getInputType(props.column.type))

const columnOptions = computed(() => {
    if (!props.options || !props.column) return [{ value: "Add new option..." }]

    const filteredOptions = props.options
        .filter(option => option.column_id === props.column.id)

    return [
        { value: "Add new option..." },
        ...filteredOptions
    ]
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
const showModal = ref(false)
const saveEdit = () => {
    if (editState.editedValue == "Add new option...") {
        showModal.value = true
    } else {
        if (!editState.editedValue) {
            emit('delete', props.value)
        }
        if (editState.editedValue !== props.value?.value) {
            emit("update", editState.editedValue, props.value)
        }
    }

    editState.isEditing = false
}

// hideCustomStatusModal
const hideCustomStatusModal = () => {
    showModal.value = false
}

const saveCustomStatus = (column, value) => {
    router.post(route('selectvalues.store'), { column_id: column.id, value: value }, {
        onSuccess: () => {
            editState.editedValue = value;
            saveEdit();
        }
    })
}

const formatValue = (value) => {
    return props.column.type == "datetime" ? formatDateTimeToISO(value) : value
}
</script>

<template>
    <div class="flex flex-col items-center justify-center">
        <div class="flex items-center justify-center py-2 px-3 duration-150 rounded-md w-full">
            <div id="valueEditor" class="cursor-pointer text-center w-full" @dblclick="enableEditing">
                <div v-if="editState.isEditing">
                    <select v-if="column.type == 'status'" v-model="editState.editedValue" @change="saveEdit"
                        id="statusSelect" class="w-full text-center bg-[#3e3f45] cursor-pointer rounded-md shadow-sm text-sm"
                        ref="el => editState.valueInputTextField = el">
                        <option v-for="option in columnOptions" :key="option.value" :value="option.value">
                            {{ option.value }}
                        </option>
                    </select>
                    <input v-else v-model="editState.editedValue" @keyup.enter="saveEdit"
                        class="w-full text-center bg-[#3e3f45] cursor-pointer rounded-md shadow-sm text-sm"
                        ref="el => editState.valueInputTextField = el" :type="inputType" />
                </div>
                <div v-else class="text-center">
                    <span id="valueSpan" class="font-medium text-sm truncate">{{ formatValue(value.value) }}</span>
                </div>
            </div>
        </div>
    </div>
    <div v-if="showModal">
        <AddCustomStatusModal :column="column" @exit="hideCustomStatusModal" @save="saveCustomStatus" />
    </div>
</template>