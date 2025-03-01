<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

const loginForm = useForm({
    email: null,
    password: null,
})

const login = (() => {
    loginForm.post(route('login.store'));
    //loginForm.post('login.store')
})
//remember me
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
                    <div v-if="loginForm.errors.email" class="input-error">{{ loginForm.errors.email }}</div>
                </div>
                <div class="mt-4">
                    <label for="password" class="label">Password</label>
                    <input id="password" v-model="loginForm.password" type="password" class="input" />
                    <div v-if="loginForm.errors.password" class="input-error">{{ loginForm.errors.password }}</div>
                </div>
                <div class="mt-4">
                    <button class="btn-primary w-full" type="submit">Login</button>
                </div>
                <div class="mt-2 text-center">
                    <Link :href="route('user-account.create')" class="text-sm text-gray-500">
                    Need an account? Click here
                    </Link>
                </div>
            </div>
        </form>

    </main>
    <footer></footer>
</template>

<style scoped></style>