import {
    router,
    usePage
} from "@inertiajs/vue3";
import {
    defineStore
} from "pinia";
import {
    ref,
    readonly,
    computed,
    nextTick
} from 'vue'
import { route } from "ziggy-js";

export const useTuderaStore = defineStore('TuderaStore', () => {
    const page = usePage()

    const errors = computed(() => {
        return page.props.errors
    })

    const user = computed(() => {
        return page.props.user
    })

    const workspaces = computed(() => {
        return user.value.workspaces
    })

    const selectedWorkspace = ref(null) 

    let workspaceInitialized = null;
    function initWorkspace() {
        workspaceInitialized = fetch(route('workspaces.get'))
            .then(response => response.json())
            .then(data => {
                const sessionWorkspaceId = data
                const workspaceFromSession = workspaces.value.find(w => w.id === sessionWorkspaceId)
                selectedWorkspace.value = workspaceFromSession || workspaces.value[0]
                return selectedWorkspace.value;
            })
            .catch(() => {
                selectedWorkspace.value = workspaces.value[0]
                return selectedWorkspace.value;
            });
        
        router.get(route('dashboard.index'))
    }

    initWorkspace()


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
        return selectedWorkspace.value.calendar_events || []
    })

    function getUser() {
        return readonly(user.value)
    }

    function getErrors() {
        return readonly(errors.value)
    }

    function getWorkspaces() {
        return readonly(workspaces.value)
    }

    function getSelectedWorkspace() {
        return readonly(selectedWorkspace.value || workspaces.value[0])
    }

    async function getTables() {
        if (!selectedWorkspace.value) {
            await workspaceInitialized;
        }
        const workspace = getSelectedWorkspace()
        return readonly(workspace?.tables || [])
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
        return new Promise((resolve, reject) => {
            router.post(route('workspaces.change'), {
                workspace_id: workspace.id
            }, {
                preserveState: true,
                onSuccess: (response) => {
                    selectedWorkspace.value = workspace
                    resolve(workspace)
                },
                onError: (error) => {
                    reject(error)
                }
            });
        });
    }

    async function refreshSelectedWorkspace() {
        setWorkspace(workspaces.value.find(workspace => workspace.id == getSelectedWorkspace().id))
        await nextTick()
    }

    function getTodos() {
        return readonly(todos.value)
    }

    function getWorkspaceEvents() {
        return Array.from(calendar.value || [])
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
        getErrors,
        setWorkspace,
        getTodos,
        getWorkspaceEvents,
        getTables,
        getTransformedTables,
        getFlashSuccess,
        getFlashError,
        refreshSelectedWorkspace
    }

})
