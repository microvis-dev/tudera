<script setup>
import { nextTick, ref } from "vue"
import { usePage, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { route } from 'ziggy-js';
import StatComponent from "./Components/MainLayoutComponents/StatComponent.vue";
import Sidebar from "./Components/MainLayoutComponents/Sidebar.vue";
import Search from "./Components/MainLayoutComponents/Search.vue";
import Profile from "./Components/MainLayoutComponents/Profile.vue";
import MainComponent from "../Pages/Dashboard/Components/MainComponent.vue";
import TodoList from "../Pages/Dashboard/Components/TodoList.vue";
import { useTuderaStore } from "../state/state";

const page = usePage()

const tuderaState = useTuderaStore()
const user = computed(() => tuderaState.getUser())
const workspaces = computed(() => tuderaState.getWorkspaces())
const isSidebarOpen = ref(false)

</script>

<template>
  <div class="w-screen h-screen flex flex-row overflow-auto md:overflow-hidden">
    <section class="w-2/12 md:w-2/12">
      <div class="h-screen relative">

        <!-- Sidebar - hidden on mobile by default, visible on larger screens -->
        <div ref="sidebarRef"
          :class="['transition-all duration-300 md:translate-x-0', isSidebarOpen ? 'translate-x-0 bg-[#2B2C30]' : '-translate-x-full']"
          class="fixed md:relative w-64 md:w-full h-full z-40">
          <Sidebar />
        </div>
        <div v-if="isSidebarOpen" class="fixed inset-0 bg-black bg-opacity-50 z-30 md:hidden"
          @click="isSidebarOpen = false"></div>
      </div>
    </section>
    <section class="w-12/12 md:w-10/12 flex flex-col mt-2.5">
      <button v-if="!isSidebarOpen" @click="isSidebarOpen = !isSidebarOpen"
        class="md:hidden w-fit top-4 left-4 z-50 p-2 rounded-md bg-gray-800 text-white">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>
      <div class="flex flex-row h-fit md:mt-0">
        <div class="w-4/5">
          <Search />
        </div>
        <span class="hidden md:block h-10 w-0.5 bg-[#2B2C30] mt-1.5"></span>
        <div class="w-1/5 mt-1 md:mt-0">
          <Profile :profile-image='user.profile_image' :name='user.name' :email="user.email" />
        </div>
      </div>
      <div class="flex flex-row h-full">
        <section class="w-full">
          <div class="w-full h-full">
            <slot></slot>
          </div>
        </section>
      </div>
    </section>
  </div>
</template>

<style scoped></style>
