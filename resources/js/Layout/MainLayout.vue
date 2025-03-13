<script setup>
import {ref} from "vue"
import { usePage, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { route } from 'ziggy-js';
import dashboardIcon from '../../assets/graphUp.svg';
import lead from '../../assets/lead.svg';
import schedule from '../../assets/schedule.svg';
import WorkspaceSelect from './Components/WorkspaceSelect.vue';

const workspaceDropdownOpen = ref(false);

const handleDropdownChange = (isOpen) => {
  workspaceDropdownOpen.value = isOpen;
};

const sidebarItems = [
  { "img": dashboardIcon, "name": "Dashboard" },
  { "img": lead, "name": "Leads" },
  { "img": schedule, "name": "Schedule" }
]

const page = usePage()

const flashSucess = computed(() => {
  return page.props.flash.success
})

const flashErrors = computed(() => {
  return page.props.flash.error
})

const user = computed(() => {
  return page.props.user
})

const user_workspaces = computed(() => {
  return null
})

</script>

<template>
  <!--
  <div v-if="flashSucess" class="flash-success p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
    {{ flashSucess }}
  </div>
  <div v-if="flashErrors" class="flash-error p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
    {{ flashErrors }}
  </div>
  <div v-if="user" class="p-4 bg-white rounded shadow-md">
    <div class="mb-4">
      <p class="text-lg font-semibold">{{ user.name }}</p>
      <p class="text-gray-600">{{ user.email }}</p>
      <p class="text-gray-600">{{ user.phone_number }}</p>
      <p class="text-gray-600">{{ user.id }}</p>
    </div>
    <div>
      <Link :href="route('auth.destroy', user.id)" method="delete" as="button"
        class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
      Logout
      </Link>
      <br><br>
      <Link :href="route('setup.workspace.create')" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
      Create your first workspace (setup)
      </Link>
      <br><br>
      <Link :href="route('workspaces.index')" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
        Workspaces
      </Link>
    </div>
  </div>
  <div v-if="user_workspaces">
    <span>Workspaces</span>

  </div>
  <slot></slot>
-->

<div class="container w-full h-full">
    <section class="p-8 w-80 h-screen border border-[#2B2C30]">
      <aside class="flex flex-col items-center">
        <div class="w-60 mb-20">
          <img src="../../assets/tuderaLogoWhite.svg">
        </div>
        <WorkspaceSelect @dropdown-change="handleDropdownChange" />
        <div class="w-full mt-6">
          <div 
            class="sidebar-items flex flex-col" 
            :class="{ 'dropdown-open': workspaceDropdownOpen }">
            <div v-for="item in sidebarItems" class="flex flex-row items-center p-5">
              <img class="w-10 h-10 me-3" :src="item.img">
              <h2 class="roboto-font-bold text-xl">{{ item.name }}</h2>
            </div>
          </div>
        </div>
      </aside>
    </section>
  </div>
</template>

<style scoped>
.sidebar-items {
  transition: transform 0.3s ease;
}

.dropdown-open {
  transform: translateY(150px); /* Adjust this value based on your dropdown height */
}
</style>