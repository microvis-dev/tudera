<script setup>
import MeetingDropdown from './MeetingDropdown.vue';
const props = defineProps({
    "profileImage": String
})
</script>

<template>
    <section class="flex flex-col p-5 max-h-[400px] overflow-y-auto pb-10">
        <h1 class="text-2xl roboto-font-bold mb-2">Meetings</h1>
        <p class="text-sm roboto-font-light text-[#B3B3B3] mb-2">
            Quickly join your meetings from a simple sidebar.
        </p>
        <MeetingDropdown :profile-image='props.profileImage'/>
        <MeetingDropdown />
        <MeetingDropdown />
    </section>
</template>

<style scoped>
</style>
