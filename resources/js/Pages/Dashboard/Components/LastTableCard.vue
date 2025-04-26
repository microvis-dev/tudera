<script setup>
import { compile, computed } from "vue";
import { useTuderaStore } from "@/resources/js/state/state";

const tuderaState = useTuderaStore();

const props = defineProps({
    table: Object
})

const formatDate = () => {
    const dateString = props.table.updated_at;
    if (!dateString) return 'Never updated';

    const date = new Date(dateString);
    const now = new Date();

    const diffMs = now - date;

    const diffSec = Math.floor(diffMs / 1000);
    const diffMin = Math.floor(diffSec / 60);
    const diffHour = Math.floor(diffMin / 60);
    const diffDay = Math.floor(diffHour / 24);

    if (diffDay > 0) {
        return `Updated ${diffDay} ${diffDay === 1 ? 'day' : 'days'} ago`;
    } else if (diffHour > 0) {
        return `Updated ${diffHour} ${diffHour === 1 ? 'hour' : 'hours'} ago`;
    } else if (diffMin > 0) {
        return `Updated ${diffMin} ${diffMin === 1 ? 'minute' : 'minutes'} ago`;
    } else {
        return 'Updated just now';
    }
};

const formattedUpdatedAt = computed(formatDate);


</script>
<template>
    <div
        class="bg-[#2B2C30] cursor-pointer flex flex-col w-10/12 h-5/6 text-center p-5 rounded-lg mt-5 hover:scale-110 hover:drop-shadow-xl">
        <h1 class="roboto-font-bold">{{ table.name }}</h1>
        <p>Potential client contacts</p>
        <div class="flex flex-row justify-evenly text-center">
            <p>-1 entries</p>
            <p>·</p>
            <p>{{ formattedUpdatedAt }}</p>
        </div>
    </div>
</template>
<style scoped>
div {
    transition: transform 0.3s ease-in-out;
}
</style>