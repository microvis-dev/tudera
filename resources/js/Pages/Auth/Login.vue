<script setup>
import { useForm } from '@inertiajs/vue3';

const loginForm = useForm({
    email: null,
    password: null,
})
const login = (() => {
    //loginForm.post(route('login.store'))
    loginForm.post('/login')
})
</script>

<template>
    <header>
        <h1>Login page</h1>
    </header>
    <main>
        <form @submit.prevent="login">
            <div class="w-1/2 mx-auto">
                <div>
                    <label for="email" class="label">E-mail (username)</label>
                    <input id="email" v-model="loginForm.email" type="text" class="input" />
                    <div v-if="loginForm.errors.email" class="input-error">Potential errors</div>
                </div>
                <div class="mt-4">
                    <label for="password" class="label">Password</label>
                    <input id="password" v-model="loginForm.password" type="password" class="input" />
                    <div v-if="loginForm.errors.password" class="input-error">Potential errors</div>
                </div>
                <div class="mt-4">
                    <button class="btn-primary w-full" type="submit">Login</button>
                </div>
            </div>
        </form>

    </main>
    <footer></footer>
</template>

<style scoped></style>