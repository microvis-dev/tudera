import { usePage } from "@inertiajs/vue3";
import { defineStore } from "pinia";
import { ref, readonly, computed } from 'vue'

export const useWorkspaceStore = defineStore('WorkspaceStore', () => {
    const selectedWorkspace = ref(null)

    function getWorkspace() {
        return readonly(selectedWorkspace.value)
    }

    function setWorkspace(workspace) {
        selectedWorkspace.value = workspace
    }

    return {
        getWorkspace,
        setWorkspace
    }
})

export const usePageStore = defineStore('PageStore', () => {
    const page = usePage()

    const user = computed(() => {
        return page.props.user
    })

    const workspaces = computed(() => {
        return page.props.workspaces
    })

    function getUser() {
        return readonly(user.value)
    }

    function getWorkspaces() {
        return readonly(workspaces.value)
    }

    return {
        getUser,
        getWorkspaces
    }
})

export const useEventsStore = defineStore('EventStore', () => {
    const page = usePage()

    const todos = computed(() => {
        return page.props.user.todos
    })

    const calendar = computed(() => {
        
    })
    // ...
})