<script setup>
import { ref } from "vue"
import { usePage, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { route } from 'ziggy-js';
import StatComponent from "./Components/MainLayoutComponents/StatComponent.vue";
import Sidebar from "./Components/MainLayoutComponents/Sidebar.vue";
import Search from "./Components/MainLayoutComponents/Search.vue";
import Profile from "./Components/MainLayoutComponents/Profile.vue";
import MainComponent from "./Components/MainLayoutComponents/MainComponent.vue";
import TodoList from "./Components/MainLayoutComponents/TodoList.vue";

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

        <WorkspaceSelect @dropdown-change="handleDropdownChange" @height-change="updateDropdownHeight" />
  <slot></slot>
  -->
  <div class="w-screen h-screen flex flex-row overflow-hidden">
    <section class="w-2/12">
      <div class="h-screen">
        <Sidebar />
      </div>
    </section>
    <section class="w-10/12 flex flex-col">
      <div class="flex flex-row h-fit">
        <div class="w-4/5">
          <Search />
        </div>
        <span class="h-10 w-0.5 bg-[#2B2C30] mt-1.5"></span>
        <div class="w-1/5">
          <Profile />
        </div>
      </div>
      <div class="flex flex-row h-full">
        <section class="w-full">
          <div class="w-full h-full border-2 border-[#2B2C30]">
            <slot></slot>
          </div>
        </section>
      </div>
    </section>
  </div>
</template>

<style scoped></style>