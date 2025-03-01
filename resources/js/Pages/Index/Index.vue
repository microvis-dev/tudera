<script setup>
import {ref} from 'vue'
import { Link } from '@inertiajs/vue3';

const counter = ref(0)
const add = (() => counter.value++)
</script>

<template>
  <main>
    <h1>Hello World!</h1>
    <button @click="add">Click me {{ counter }}</button>
    <p class="italic">Hello Tailwind CSS!</p>
    <Link href="/login">Login</Link>
  </main>
</template>

<style scoped>

</style>
