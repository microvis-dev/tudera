<script setup>
import {ref, watchEffect} from "vue";
import {useForm, router} from "@inertiajs/vue3";
import {route} from "ziggy-js";

const props = defineProps({
    workspace_user: Object,
    workspace: Object,
    roles: Object,
    selected_role: Number
})

const roles = props.roles.map(role => {
    return {
        id: role.id,
        name: role.name
    }
})

const form = useForm({
    role: ref(props.selected_role)
})

const submit = () => {
    router.post(route('workspaces.user.update', {
        id: props.workspace.id,
        user: props.workspace_user.id
    }), form);
}
</script>

<template>
    {{workspace_user.name}}
    <br>
    <label v-for="role in roles" :key="role.id">
        <input
            type="radio"
            :value="role.id"
            v-model="form.role"
        />
        {{ role.name }}
    </label>
    <br>
    <button @click="submit">Save</button>
</template>

<style scoped>

</style>
