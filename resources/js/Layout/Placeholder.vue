<script setup>
import dashboardIcon from '../../assets/graphUp.svg';
import lead from '../../assets/lead.svg';
import schedule from '../../assets/schedule.svg';
import WorkspaceSelect from './Components/WorkspaceSelect.vue';
import StatComponent from "./Components/MainLayoutComponents/StatComponent.vue";
const workspaceDropdownOpen = ref(false);

const sidebarItems = [
  { "img": dashboardIcon, "name": "Dashboard" },
  { "img": lead, "name": "Leads" },
  { "img": schedule, "name": "Schedule" }
]

const handleDropdownChange = (isOpen) => {
  workspaceDropdownOpen.value = isOpen;
};
    const getWorkspaceUrl = computed(() => {
  return page.props.workspace?.url || '';
})

const dropdownHeight = ref(0);

const updateDropdownHeight = (height) => {
  dropdownHeight.value = height;
  document.documentElement.style.setProperty('--dropdown-height', `${height}px`);
};
</script>
<template>
    <div class="w-screen h-screen flex flex-row overflow-hidden">
    <section class="p-8 w-2/12 h-screen border border-[#2B2C30]">
      <aside class="flex flex-col items-center">
        <div class="w-40 mb-20">
          <img src="../../assets/tuderaLogoWhite.svg">
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
    <div class="flex flex-row w-full h-fit border-b border-[#2B2C30]">
      <section class="flex flex-row justify-between w-5/6 h-fit px-5 py-4  border-r border-[#2B2C30]">
        <div class="relative">
          <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
        <img src="../../assets/search.svg" class="w-5 h-5">
          </div>
          <input type="text"
        class="pl-10 pr-3 py-2 w-96 bg-[#1C1D21] border border-gray-600 rounded-lg roboto-font-regular focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 text-gray-500 text-sm"
        placeholder="Search">
        </div>
        <div>
          <img src="../../assets/bell.svg" class="w-10 h-10 bell">
        </div>
      </section>
      <section class="flex flex-row w-1/6 h-fit items-center py-4  justify-around">
        <div>
          <img src="https://placehold.co/600x400/blue/blue" alt="Company Logo" title="Company Logo"
        class="w-10 h-10 object-cover rounded-full" />
        </div>
        <div>
          <h3 class="text-md text-[#757575]">Sofia</h3>
          <p class="text-sm text-[#757575]">sofia@gmail.com</p>
        </div>
        <img src="../../assets/openArrow.svg" class="w-5 h-5">
      </section>
    </div>
    <div class="flex flex-col">
      <StatComponent />
  </div>
  </div>
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

@keyframes bell-swing {
  0% {
    transform: rotate(0);
  }

  15% {
    transform: rotate(15deg);
  }

  30% {
    transform: rotate(-13deg);
  }

  45% {
    transform: rotate(10deg);
  }

  60% {
    transform: rotate(-7deg);
  }

  75% {
    transform: rotate(5deg);
  }

  85% {
    transform: rotate(-2deg);
  }

  100% {
    transform: rotate(0);
  }
}
</style>