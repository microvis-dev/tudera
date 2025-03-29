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

</script>

<template>
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
          <Profile :name='user.name' :email="user.email"/>
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
