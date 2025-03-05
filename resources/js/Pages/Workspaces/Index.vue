<script setup>
import { Link, router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import Workspace from './Components/Workspace.vue';

const props = defineProps({
  user_workspaces: Array
})

const removeWorkspace = (id) => {
  if (confirm('?')) {
    router.delete(route('workspace.delete', { id }))
  }
}

const updateWorkspaceName = (workspace) => {
  const new_workspace = {
    id: workspace.id,
    name: workspace.name
  }

  router.put(route('workspace.update', new_workspace))
}
</script>

<template>
  <div v-for="workspace in user_workspaces" class="p-4 border-b border-gray-200">
    <Workspace :workspace="workspace" @update-name="updateWorkspaceName" />

    <button @click="removeWorkspace(workspace.workspace_id)" class="ml-4 px-2 py-1 bg-red-500 text-white rounded">
      Remove
    </button>
    <button as="button" class="ml-2 px-2 py-1 bg-blue-500 text-white rounded">
      Edit
    </button>
  </div>
</template>

<style scoped></style>