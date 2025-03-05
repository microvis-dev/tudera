<script setup>
import { Link, router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import Workspace from './Components/Workspace.vue';

const props = defineProps({
  user_workspaces: Array
})

const updateWorkspaceName = (workspace) => {
  const new_workspace = {
    id: workspace.id,
    name: workspace.name
  }

  router.put(route('workspace.update', new_workspace))
}

const removeWorkspace = (id) => {
  router.delete(route('workspace.delete', { id }))
}
</script>

<template>
  <br><br>
  <div>
    <Link :href="route('workspace.create')" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
    Create new workspace
    </Link>
  </div>
  <div v-for="workspace in user_workspaces" class="p-4 border-b border-gray-200">
    <Workspace :workspace="workspace" @update-name="updateWorkspaceName" @remove-workspace="removeWorkspace" />
  </div>
</template>

<style scoped></style>