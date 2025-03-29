<script setup>
import AuthLayout from "../../Layout/AuthLayout.vue";
import { ref, watchEffect, defineOptions } from "vue";
import MainLayout from "../../Layout/MainLayout.vue";

const props = defineProps({
    user: Object,
    workspaces: Array
})

</script>
<template>
    <p>{{ user.name }}</p>   
</template>
<style scoped></style>
