<script setup>
import { ref } from 'vue';
import dashboardIcon from '../../../../assets/graphUp.svg';
import lead from '../../../../assets/lead.svg';
import schedule from '../../../../assets/schedule.svg';
import WorkspaceSelect from '../../Components/WorkspaceSelect.vue';
const workspaceDropdownOpen = ref(false);
const sidebarItems = [
  { "img": dashboardIcon, "name": "Dashboard" },
  { "img": lead, "name": "Leads" },
  { "img": schedule, "name": "Schedule" }
]

const handleDropdownChange = (isOpen) => {
  workspaceDropdownOpen.value = isOpen;
};
const dropdownHeight = ref(0);

const updateDropdownHeight = (height) => {
    dropdownHeight.value = height;
    document.documentElement.style.setProperty('--dropdown-height', `${height}px`);
};
</script>
<template>
    <section class="p-8">
        <aside class="flex flex-col items-center">
            <div class="w-40 mb-20">
                <img src="../../../../assets/tuderaLogoWhite.svg">
            </div>
            <WorkspaceSelect @dropdown-change="handleDropdownChange" @height-change="updateDropdownHeight" />
            <div class="w-full">
                <div class="sidebar-items flex flex-col" :class="{ 'dropdown-open': workspaceDropdownOpen }">
                    <div v-for="item in sidebarItems" class="flex flex-row items-center py-5">
                        <img class="w-7 h-7 me-3" :src="item.img">
                        <h2 class="roboto-font-bold text-lg">{{ item.name }}</h2>
                    </div>
                </div>
            </div>
        </aside>
    </section>
</template>
<style scoped>
.sidebar-items {
  transition: transform 0.3s ease;
}

.dropdown-open {
  transform: translateY(var(--dropdown-height, 150px));
}

.bell {
  width: 2rem;
  transform-origin: 50% 0;

  &:hover,
  &:focus {
    animation: bell-swing 1s cubic-bezier(0.36, 0.07, 0.19, 0.97) both;
  }
}
</style>