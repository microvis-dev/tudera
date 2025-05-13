<script setup>

import {router, useForm} from "@inertiajs/vue3";
import {route} from "ziggy-js";

const form = useForm({
    invite: '',
})
const connect = () => {
  router.post(route('workspaces.user.store'), form);
};
</script>

<template>
  <form @submit.prevent="connect" class="space-y-4 mt-5">
    <div class="flex flex-col mb-3 w-1/5">
      <label class="text-[#B3B3B3] roboto-font-regular">Invite code</label>
      <input type="text" placeholder="Enter invite code..." v-model="form.invite"
           class="px-3 py-2 bg-[#1C1D21] border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 placeholder-gray-500 text-sm">
    </div>
    <button type="submit" class="px-4 py-2 w-fit text-white bg-blue-500 rounded-md hover:bg-blue-600">
      Connect
    </button>
  </form>
</template>

<style scoped>

</style>
