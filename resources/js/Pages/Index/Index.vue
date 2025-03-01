<script setup>
import {ref} from 'vue'
import AuthLayout from "../../Layout/AuthLayout.vue";
const counter = ref(0)
const add = (() => counter.value++)
</script>

<template>
    <AuthLayout>
    </AuthLayout>
</template>

<style scoped>

</style>
