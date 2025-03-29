import { usePage } from "@inertiajs/vue3";
import { defineStore } from "pinia";
import { ref, readonly, computed } from 'vue'

export const useTuderaStore = defineStore('TuderaStore', () => {
    const page = usePage()

    const user = computed(() => {
        return page.props.user
    })

    const workspaces = computed(() => {
        return page.props.workspaces
    })

    const selectedWorkspace = ref(workspaces.value[0])

    const flashSucess = computed(() => {
        return page.props.flash.success
    })

    const flashErrors = computed(() => {
        return page.props.flash.error
    })

    const todos = computed(() => {
        return page.props.user.todos
    })

    const calendar = computed(() => {
        return selectedWorkspace.value.calendar
    })

    function getUser() {
        return readonly(user.value)
    }

    function getWorkspaces() {
        return readonly(workspaces.value)
    }

    function getSelectedWorkspace() {
        return readonly(selectedWorkspace.value)
    }

    function setWorkspace(workspace) {
        selectedWorkspace.value = workspace
    }

    function getTodos() {
        return readonly(todos.value)
    }

    function getCalendarEvents() {
        return readonly(calendar.value)
    }

    // flash
    return {
        getUser,
        getWorkspaces,
        getSelectedWorkspace,
        setWorkspace,
        getTodos,
        getCalendarEvents
    }

})
