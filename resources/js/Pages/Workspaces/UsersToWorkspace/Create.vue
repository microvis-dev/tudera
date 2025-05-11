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
    <input type="text" placeholder="Invite code" v-model="form.invite">
    <button @click="connect">Connect</button>
</template>

<style scoped>

</style>
