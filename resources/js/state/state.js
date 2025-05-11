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
} from 'vue'
import { route } from "ziggy-js";


export const useTuderaViewStore = defineStore('TuderaViewStore', ()=> {
    const showAddTodoModal = ref(false)

    function changeAddTodoModal(){
        showAddTodoModal.value = !showAddTodoModal.value
    }
    const addTodoModal = computed(() => {
        return showAddTodoModal
    })
    function getModal(){
        return readonly(addTodoModal)
    }
    return{
        getModal,
        changeAddTodoModal
    }
})

export const useTuderaStore = defineStore('TuderaStore', () => {
    const page = usePage()

    const showModal = ref(false)

    const errors = computed(() => {
        return page.props.errors
    })

    function changeModal() {
        showModal.value = !showModal.value;
    }

    const modal = computed(() => {
        return showModal
    })

    const user = computed(() => {
        return page.props.user
    })

    const workspaces = computed(() => {
        return user.value.workspaces
    })

    const selectedWorkspace = ref(null)
    let workspaceInitialized = null;

    function getWorkspaceInitPromise() {
        return workspaceInitialized;
    }

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

        return workspaceInitialized;
    }

    initWorkspace();

    const reactiveSelectedWorkspace = computed(() => {
        return getWorkspaces()
            .find((workspace) => {
                return (selectedWorkspace.value?.id || workspaces.value[0]?.id) === workspace.id
            })
    })

    function getSelectedWorkspace() {
        return readonly(reactiveSelectedWorkspace.value || workspaces.value[0])
    }

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
        return getSelectedWorkspace()?.calendar_events || []
    })

    function getModal() {
        return readonly(modal)
    }

    function getUser() {
        return readonly(user.value)
    }

    function getErrors() {
        return readonly(errors.value)
    }

    function getWorkspaces() {
        return readonly(workspaces.value)
    }

    async function getTables() {
        if (!selectedWorkspace.value) {
            await workspaceInitialized;
        }

        const workspace = reactiveSelectedWorkspace.value || workspaces.value[0];
        return readonly(workspace?.tables || [])
    }

    function getTransformedTables() {
        if (!reactiveSelectedWorkspace.value || !reactiveSelectedWorkspace.value.tables) {
            return [];
        }

        let tables = []
        let tableIcon = null

        reactiveSelectedWorkspace.value.tables.forEach((table) => {
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
                onSuccess: () => {
                    selectedWorkspace.value = workspace
                    resolve(workspace)
                },
                onError: (error) => {
                    reject(error)
                }
            });
        });
    }

    function getTodos() {
        return readonly(todos.value)
    }

    function getWorkspaceEvents() {
        return Array.from(calendar.value || [])
    }

    function getFlashSuccess() {
        return flashSucess.value ?? ""
    }

    function getFlashError() {
        return flashErrors.value ?? ""
    }

    function clearPageProps() {
        page.props.errors = ""
        page.props.flash.errors = ""
        page.props.flash.success = ""
    }

    return {
        getUser,
        getWorkspaces,
        getSelectedWorkspace,
        changeModal,
        getErrors,
        setWorkspace,
        getModal,
        getTodos,
        getWorkspaceEvents,
        getTables,
        getTransformedTables,
        getFlashSuccess,
        getFlashError,
        clearPageProps,
        getWorkspaceInitPromise
    }
})
