<script setup>
import { ref } from "vue"
import { usePage, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { route } from 'ziggy-js';

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

        <WorkspaceSelect @dropdown-change="handleDropdownChange" @height-change="updateDropdownHeight" />
  <slot></slot>
  -->
  <div class="w-screen h-screen flex flex-row">
    <section class="w-2/12">
      <div class="h-screen bg-yellow">sidebar</div>
    </section>
    <section class="w-10/12 flex flex-col">
      <div class="flex flex-row h-fit">
        <div class="w-4/5 bg-blue">search</div>
        <div class="w-1/5 bg-green">profile</div>
      </div>
      <div class="flex flex-row h-full">
        <section class="w-8/12">
          <div class="bg-red h-75">Main</div>
          <section class="flex flex-row">
            <div class="bg-red-600 h-48 w-1/2">stat1</div>
            <div class="bg-pink-600 w-1/2">stat2</div>
          </section>
        </section>
        <section class="w-4/12">
          <div class="w-full h-1/2 bg-purple">todoList</div>
          <div class="w-full h-1/2 bg-pink-400">stat3</div>
        </section>
      </div>
    </section>
  </div>
</template>

<style scoped>
</style>