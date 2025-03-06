<script setup>
import { useForm, router, Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import WorkspaceTable from './Components/WorkspaceTable.vue';

const props = defineProps({
    workspace_tables: Array
})

const updateTable = (tableData) => {
    router.put(route('workspace-table.update', tableData.id), {
        name: tableData.name
    })
}

const removeTable = (tableId) => {
    router.delete(route('workspace-table.destroy', tableId))
}

</script>

<template>
    <div v-for="table in workspace_tables" :key="table.id">
        <WorkspaceTable :table="table" @update-table="updateTable" @remove-table="removeTable" />
    </div>
    <Link :href="route('workspace-table.create')"
        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
    Create new table
    </Link>
</template>

<style scoped></style>