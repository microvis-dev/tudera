import { ref, readonly } from 'vue'

const selectedWorkspace = ref(null)

export default function useSelectedWorkspace() {
  const setWorkspace = (workspace) => {
    selectedWorkspace.value = workspace
  }

  return {
    selectedWorkspace: readonly(selectedWorkspace),
    setWorkspace
  }
}