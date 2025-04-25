<script setup>
import { computed, reactive, ref, inject, onMounted } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import dashboardIcon from '../../../../assets/graphUp.svg';
import lead from '../../../../assets/lead.svg';
import schedule from '../../../../assets/schedule.svg';
import WorkspaceSelect from '../../Components/WorkspaceSelect.vue';
import CreateToDoModal from '@/resources/js/Pages/Dashboard/Components/CreateToDoModal.vue';
import { useTuderaStore } from '@/resources/js/state/state';
const tableIcon = lead // import svg!

const tuderaState = useTuderaStore()

const tables = ref([])

onMounted(async () => {
    tables.value = await tuderaState.getTables()
})

const sidebarItems = computed(() => {
    const defaultItems =  [
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

const updateTables = ((workspace) => {
  tables.value = []

  const workspaceTables = workspace.tables
  workspaceTables.forEach((table) => {
    let tableObj = {
      id: table.id,
      name: table.name,
      img: tableIcon,
      url: { name: 'table.show', params: { table: table.id } }
    }
    tables.value.push(tableObj)
  })
})


const redirectToHome = () => { // click cursor
  router.get(route('index'))
}

// attilamunkaja.html
const viewState = reactive({
    addTodoModal: false
})

const showAddTodoModal = () => {
    viewState.addTodoModal = true
}

const hideAddTodoModal = () => {
    viewState.addTodoModal = false
}

const workspaceDropdownOpen = ref(false);

const handleDropdownChange = (isOpen) => {
  workspaceDropdownOpen.value = isOpen;
};
const dropdownHeight = ref(0);

const updateDropdownHeight = (height) => {
  dropdownHeight.value = height;
  document.documentElement.style.setProperty('--dropdown-height', `${height}px`);
};

</script>
<template>
  <section class="p-8">
    <aside class="flex flex-col items-center">
      <div @click="redirectToHome" class="w-40 mb-20">
        <img src="../../../../assets/tuderaLogoWhite.svg">
      </div>
      <WorkspaceSelect @dropdown-change="handleDropdownChange"
        @height-change="updateDropdownHeight" @select-workspace="updateTables" />
      <div class="w-full">
        <div class="sidebar-items flex flex-col" :class="{ 'dropdown-open': workspaceDropdownOpen }">
          <div v-for="item in sidebarItems"
            class="flex flex-row items-center p-5 mt-3 hover:bg-gray-500 hover:rounded-xl"
            @click="handleRedirect(item.url)">
            <img class="w-7 h-7 me-3" :src="item.img">
            <h2 class="roboto-font-bold text-lg">{{ item.name }}</h2>
          </div>
            <button @click="showAddTodoModal" v-if="route().current('calendar.index')" class="mt-5 p-2 w-full bg-blue-500 text-white rounded hover:bg-blue-600">Add event</button>
            <CreateToDoModal v-if="viewState.addTodoModal" @exit="hideAddTodoModal" :is-personal="false"/>
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
