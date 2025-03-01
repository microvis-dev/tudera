<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

const registerForm = useForm({
    email: null,
    name: null,
    phone_number: null,
    password: null,
    password_confirmation: null
})

const register = (() => {
    registerForm.post(route('user-account.store'));
})
</script>

<template>
    <form @submit.prevent="register">
        <div class="w-1/2 mx-auto">
            <div>
                <label for="email" class="label">E-mail</label>
                <input id="email" v-model="registerForm.email" type="text" class="input" />
                <div v-if="registerForm.errors.email" class="input-error">{{ registerForm.errors.email }}</div>
            </div>
            <div>
                <label for="name" class="label">Name</label>
                <input id="name" v-model="registerForm.name" type="text" class="input" />
                <div v-if="registerForm.errors.name" class="input-error">{{ registerForm.errors.name }}</div>
            </div>
            <div class="mt-4">
                <label for="phone" class="label">Phone number</label>
                <input id="phone" v-model="registerForm.phone_number" type="tel" class="input" />
                <div v-if="registerForm.errors.phone_number" class="input-error">{{ registerForm.errors.phone_number }}</div>
            </div>
            <div class="mt-4">
                <label for="password" class="label">Password</label>
                <input id="password" v-model="registerForm.password" type="password" class="input" />
                <div v-if="registerForm.errors.password" class="input-error">{{ registerForm.errors.password }}</div>
            </div>
            <div class="mt-4">
                <label for="password_confirmation" class="label">Password confirmation</label>
                <input id="password_confirmation" v-model="registerForm.password_confirmation" type="password"
                    class="input" />
                <div v-if="registerForm.errors.password_confirmation" class="input-error">{{ password_confirmation }}</div>
            </div>
            <div class="mt-4">
                <button class="btn-primary w-full" type="submit">Create account</button>
            </div>
            <div class="mt-2 text-center">
                <Link :href="route('login')" class="text-sm text-gray-500">
                Already have an account? Click here
                </Link>
            </div>
        </div>
    </form>
</template>

<style scoped></style>