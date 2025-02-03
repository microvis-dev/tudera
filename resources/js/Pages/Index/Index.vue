<script setup>
import {ref} from 'vue'

const counter = ref(0)
const add = (() => counter.value++)
</script>

<template>
  <main>
    <h1>Hello World!</h1>
    <Button @click="add">Click me {{ counter }}</Button>
  </main>
</template>

<style scoped>
  
</style>