<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
const dropdownOpen = ref(false);
const profileRef = ref(null);
const dropdownRef = ref(null);

const props = defineProps({
    "name": String,
    "email": String,
    "profileImage": String
})

const openDropdown = () => {
  dropdownOpen.value = !dropdownOpen.value
}

const handleClickOutside = (event) => {
  if ((profileRef.value && !profileRef.value.contains(event.target)) &&
    (dropdownRef.value && !dropdownRef.value.contains(event.target))) {
    dropdownOpen.value = false;
  }
};

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside);
});

const logout = () => {
  router.delete(route('auth.destroy', { auth: true }))
}

const redirectToSettings = () => {
  router.get(route('settings.index'))
}
</script>
<template>
  <div ref="profileRef" @click="openDropdown">
    <section class="flex flex-row h-fit items-center pt-1 justify-end md:justify-start md:ms-5 xl:justify-evenly">
      <div>
        <img :src="props.profileImage" alt="Profile Image" v-if="props.profileImage"
          class="w-10 h-10 me-2 object-cover rounded-full" />
      </div>
      <div class="hidden md:block">
        <h3 class="text-md roboto-font-regular text-[#757575]">{{ props.name }}</h3>
        <p class="text-sm roboto-font-regular text-[#757575] truncate max-w-[150px]">{{ props.email }}</p>
      </div>
      <img src="../../../../assets/openArrow.svg" class="hidden md:block w-5 h-5 ms-5" :class="[!dropdownOpen ? '' : 'rotate-180']">
     </section>
      <div class="relative flex justify-center">
        <transition name="slide-down">
          <div v-if="dropdownOpen" ref="dropdownRef"
            class="absolute min-w-full flex flex-col items-center bg-[#2B2C30] text-white rounded-br-lg rounded-bl-lg shadow-lg py-2 mt-2 z-10">
            <div class="flex flex-col w-[200px] justify-center rounded-[5px] items">
              <button @click="redirectToSettings"
                class="bg-transparent border-0 p-[10px] text-white flex relative gap-[5px] cursor-pointer rounded-[4px] mb-2 items-center hover:bg-[#21262C] hover:rounded-[10px] focus:bg-[#1A1F24] focus:outline-none">
                <img src="../../../../assets/settings.svg" class="w-8 h-8">
                <span class="text-lg text-center roboto-font-medium ms-2">Settings</span>
              </button>
              <button @click="logout"
                class="bg-transparent border-0 p-[10px] text-white flex relative gap-[5px] cursor-pointer rounded-[4px] mb-2 items-center hover:bg-[#21262C] hover:rounded-[10px] focus:bg-[#1A1F24] focus:outline-none">
                <img src="../../../../assets/logout.svg" class="w-8 h-8">
                <span class="text-lg text-center roboto-font-medium ms-2">Log out</span>
              </button>
            </div>
          </div>
        </transition>
      </div>
  </div>
</template>
<style scoped>
.slide-down-enter-active,
.slide-down-leave-active {
  transition: all 0.2s ease;
  transform-origin: top;
}

.slide-down-enter-from,
.slide-down-leave-to {
  transform: translateY(-10px);
  opacity: 0;
}

.slide-down-enter-to,
.slide-down-leave-from {
  transform: translateY(0);
  opacity: 1;
}

/* Focus state for accessibility */
button:focus::before {
  content: "";
  position: absolute;
  top: 5px;
  left: -10px;
  width: 5px;
  height: 80%;
  background-color: #2F81F7;
  border-radius: 5px;
  opacity: 1;
}

.items:hover> :not(:hover) {
  transition: 500ms;
  filter: blur(1px);
  transform: scale(0.95, 0.95);
}
</style>
