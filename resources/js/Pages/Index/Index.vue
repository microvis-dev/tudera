<script setup>
import {ref} from 'vue'
import { Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

const counter = ref(0)
const add = (() => counter.value++)
</script>

<template>
  <main>
    <h1>Hello World!</h1>
    <button @click="add">Click me {{ counter }}</button>
    <p class="italic">Hello Tailwind CSS!</p>
    <Link :href="route('login')">Login</Link><br> <!--MainLayout-->
    <Link :href="route('logout')" method="delete" as="button">Logout</Link><br>
    <Link :href="route('user-account.create')">Sign up</Link>
  </main>
</template>

<style scoped>

</style>
