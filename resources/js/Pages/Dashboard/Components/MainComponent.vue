<script setup>
import { inject, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import useSelectedWorkspace from '@/resources/js/Composable/useSelectedWorkspace';

const { selectedWorkspace } = useSelectedWorkspace();

const addNewTable = () => {
    router.get(route('workspace.table.create', {workspace: selectedWorkspace.value}))
}

</script>
<template>
    <section class="flex flex-col p-5">
        <h1 class="text-3xl roboto-font-bold mb-2">
            Start managing<br>
            your business!
        </h1>
        <p class="text-sm roboto-font-small mb-5 text-[#B3B3B3]">Create your own workspace according to your <br>
            company.
        </p>
        <button @click="addNewTable" class="w-fit p-2 px-10 border rounded-xl bg-blue-600">Add new Table</button>
    </section>
</template>
<style scoped></style>