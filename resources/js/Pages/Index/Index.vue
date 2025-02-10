<script setup>
import {ref} from 'vue'

const counter = ref(0)
const add = (() => counter.value++)
</script>

<template>
  <main>
    <h1>Hello World!</h1>
    <button @click="add">Click me {{ counter }}</button>
  </main>
</template>

<style scoped>

</style>
