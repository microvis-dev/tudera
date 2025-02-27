<script setup>
import { useForm } from '@inertiajs/vue3';

const loginForm = useForm({
    email: null,
    password: null,
    phone_number: null
})
const login = (() => {
    loginForm.post(route('login.store'))
})
</script>

<template>
    <header>
        <h1>Login page</h1>
    </header>
    <main>

        <form @submit.prevent="login"></form>

    </main>
    <footer></footer>
</template>

<style scoped></style>