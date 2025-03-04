<script setup>
import AuthLayout from "../../Layout/AuthLayout.vue";
import {ref, watchEffect, defineOptions } from "vue";
import UserCreationForm from "../Auth/UserCreationForm.vue";

defineOptions({
    layout: AuthLayout
})
</script>

<template>
    <UserCreationForm/>
</template>
<style scoped>
</style>
