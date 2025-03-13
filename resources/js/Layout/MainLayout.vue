<script setup>
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
      <Link :href="route('logout')" method="delete" as="button"
        class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
      Logout
      </Link>
      <br><br>
      <Link :href="route('setup.workspace.create')" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
      Create your first workspace (setup)
      </Link>
      <br><br>
      <Link :href="route('workspaces')" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
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
          <img src="../../assets/tuderaLogoWhite.svg" alt="Tudera Logo">
        </div>
        <div class="flex flex-col">
          <div class="flex flex-row">
            <p>Logo</p>
            <h2>Dashboard</h2>
          </div>
        </div>
      </aside>
    </section>
  </div>
</template>

<style scoped></style>