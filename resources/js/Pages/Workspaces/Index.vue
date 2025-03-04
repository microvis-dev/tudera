<script setup>

const props = defineProps({
    user_workspaces: Array
})

</script>

<template>
  <div v-for="workspace in user_workspaces">
    {{ workspace }}
  </div>
</template>

<style scoped>
  
</style>