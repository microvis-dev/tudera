<script setup>
import { usePage, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { route } from 'ziggy-js';

const page = usePage()

const user = computed(() => {
  return page.props.user
})
console.log(user)

</script>

<template>
  <div v-if="user" class="p-4 bg-white rounded shadow-md">
    <div class="mb-4">
      <p class="text-lg font-semibold">{{ user.name }}</p>
      <p class="text-gray-600">{{ user.email }}</p>
      <p class="text-gray-600">{{ user.phone_number }}</p>
      <p class="text-gray-600">{{ user.id }}</p>
    </div>
    <div>
      <Link :href="route('logout')" method="delete" as="button"
        class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Logout</Link>
    </div>
  </div>
  <slot></slot>
</template>

<style scoped></style>