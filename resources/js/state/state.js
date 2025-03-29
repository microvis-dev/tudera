import {
    usePage
} from "@inertiajs/vue3";
import {
    defineStore
} from "pinia";
import {
    ref,
    readonly,
    computed
} from 'vue'

export const useTuderaStore = defineStore('TuderaStore', () => {
    const page = usePage()

    const user = computed(() => {
        return page.props.user
    })

    const workspaces = computed(() => {
        return user.value.workspaces
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
        return selectedWorkspace.value.calendar_events
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

    function getTables() {
        return readonly(selectedWorkspace.value.tables)
    }

    function getTransformedTables() {
        let tables = []
        let tableIcon = null

        selectedWorkspace.value.tables.forEach((table) => {
            let tableObj = {
                id: table.id,
                name: table.name,
                img: tableIcon,
                url: {
                    name: 'table.show',
                    params: {
                        table: table.id
                    }
                }
            }
            tables.push(tableObj)
        })

        return tables
    }

    function setWorkspace(workspace) {
        selectedWorkspace.value = workspace
    }

    function getTodos() {
        return readonly(todos.value)
    }

    function getWorkspaceEvents() {
        return readonly(calendar.value)
    }

    function getFlashSuccess() {
        return readonly(flashSucess.value)
    }

    function getFlashError() {
        return readonly(flashErrors.value)
    }

    return {
        getUser,
        getWorkspaces,
        getSelectedWorkspace,
        setWorkspace,
        getTodos,
        getWorkspaceEvents,
        getTables,
        getTransformedTables,
        getFlashSuccess,
        getFlashError
    }

})
