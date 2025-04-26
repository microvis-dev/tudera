<script setup>
import { compile, computed } from "vue";
import { useTuderaStore } from "@/resources/js/state/state";

const tuderaState = useTuderaStore();

const props = defineProps({
    table: Object
})




</script>
<template>
    <div class="bg-[#2B2C30] cursor-pointer flex flex-col w-10/12 h-5/6 text-center p-5 rounded-lg mt-5 hover:scale-110 hover:drop-shadow-xl">
        <h1 class="roboto-font-bold">{{ table.name }}</h1>
        <p>Potential client contacts</p>
        <div class="flex flex-row justify-evenly text-center">
            <p>-1 entries</p>
            <p>·</p>
            <p>Updated 2h ago</p>
        </div>
    </div>
</template>
<style scoped>
    div {
        transition: transform 0.3s ease-in-out;
    }
</style>