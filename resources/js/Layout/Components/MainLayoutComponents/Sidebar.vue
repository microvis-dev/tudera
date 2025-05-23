<script setup>
import { computed, ref, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import dashboardIcon from '../../../../assets/graphUp.svg';
import lead from '../../../../assets/lead.svg';
import schedule from '../../../../assets/schedule.svg';
import WorkspaceSelect from '../../Components/WorkspaceSelect.vue';
import { useTuderaStore } from '@/resources/js/state/state';

const tuderaState = useTuderaStore()
const selectedWorkspace = computed(() => tuderaState.getSelectedWorkspace())
const isLoading = ref(true)
const tables = ref([])

onMounted(async () => {
    try {
        await tuderaState.getWorkspaceInitPromise()
        await updateTables(tuderaState.getSelectedWorkspace())
    } catch (error) {
        console.error("Error initializing workspace:", error)
    } finally {
        isLoading.value = false
    }
})

const sidebarItems = computed(() => {
    const defaultItems = [
        { img: dashboardIcon, name: "Dashboard", url: { name: "dashboard.index", params: null } },
        { img: schedule, name: "Schedule", url: { name: "calendar.index", params: null } }
    ]
    return [...defaultItems, ...tables.value]
})

const handleRedirect = (url) => {
    if (url.params) {
        router.get(route(url.name, url.params));
    } else {
        router.get(route(url.name));
    }
}

const updateTables = async (workspace) => {
    tables.value = []

    if (!workspace || !workspace.tables) {
        const workspaceTables = await tuderaState.getTables()
        processTablesData(workspaceTables)
    } else {
        processTablesData(workspace.tables)
    }
}

const processTablesData = (workspaceTables) => {
    if (!workspaceTables || !Array.isArray(workspaceTables)) return

    [...workspaceTables]
    .sort((t1, t2) => new Date(t1.created_at) - new Date(t2.created_at))
    .forEach((table) => {
        let tableObj = {
            id: table.id,
            name: table.name,
            img: lead,
            url: { name: 'table.show', params: { table: table.id } }
        }
        
        tables.value.push(tableObj)
    })
}


const redirectToHome = () => {
  router.get(route('dashboard.index'))
}

const showAddTodoModal = () => {
    tuderaState.changeModal()
}

const workspaceDropdownOpen = ref(false)

const handleDropdownChange = (isOpen) => {
    workspaceDropdownOpen.value = isOpen
}

const dropdownHeight = ref(0)

const updateDropdownHeight = (height) => {
    dropdownHeight.value = height
    document.documentElement.style.setProperty('--dropdown-height', `${height}px`)
}

const addNewTable = () => {
    router.get(route('workspace.table.create', { workspace: selectedWorkspace.value }))
}

const handleWorkspaceChange = async (workspace) => {
    await updateTables(workspace)
}
</script>

<template>
    <section class="p-8 h-screen">
        <aside class="flex flex-col items-center h-full">
            <div @click="redirectToHome" class="w-40 mb-20 cursor-pointer">
                <img src="../../../../assets/tuderaLogoWhite.svg">
            </div>
            <WorkspaceSelect
                @dropdown-change="handleDropdownChange"
                @height-change="updateDropdownHeight"
                @select-workspace="handleWorkspaceChange"
            />
            <div class="w-full flex-grow">
                <div v-if="isLoading" class="flex justify-center items-center mt-10">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-white"></div>
                </div>
                <div v-else class="sidebar-items flex flex-col overflow-y-auto h-full max-h-[calc(100vh-250px)]"
                     :class="{ 'dropdown-open': workspaceDropdownOpen }">
                    <div v-for="item in sidebarItems" :key="item.name"
                         class="flex flex-row items-center p-5 mt-3 hover:bg-gray-500 hover:rounded-xl cursor-pointer"
                         @click="handleRedirect(item.url)">
                        <img class="w-7 h-7 me-3" :src="item.img">
                        <h2 class="roboto-font-bold text-lg">{{ item.name }}</h2>
                    </div>
                    <button @click="showAddTodoModal" v-if="route().current('calendar.index')"
                            class="mt-5 p-2 w-full bg-blue-500 text-white rounded hover:bg-blue-600">
                        Add event
                    </button>
                    <button @click="addNewTable"
                            class="mt-5 p-2 w-full bg-blue-500 text-white rounded hover:bg-blue-600">
                        Add new Table
                    </button>
                </div>
            </div>
        </aside>
    </section>
</template>
<style scoped>
.sidebar-items {
  transition: transform 0.3s ease;
}
.dropdown-open {
  transform: translateY(var(--dropdown-height, 150px));
}
.bell {
  width: 2rem;
  transform-origin: 50% 0;

  &:hover,
  &:focus {
    animation: bell-swing 1s cubic-bezier(0.36, 0.07, 0.19, 0.97) both;
  }
}
</style>
