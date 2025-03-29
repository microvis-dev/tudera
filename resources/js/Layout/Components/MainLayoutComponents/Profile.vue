<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
const dropdownOpen = ref(false);
const profileRef = ref(null);
const dropdownRef = ref(null);

const props = defineProps({
  "name": String,
  "email": String
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
</script>
<template>
  <div ref="profileRef">
    <section class="flex flex-row h-fit items-center pt-1 justify-start ms-5" @click="openDropdown">
      <div>
        <img src="https://placehold.co/600x400/blue/blue" alt="Company Logo" title="Company Logo"
          class="w-10 h-10 me-2 object-cover rounded-full" />
      </div>
      <div>
        <h3 class="text-md roboto-font-regular text-[#757575]">{{ props.name }}</h3>
        <p class="text-sm roboto-font-regular text-[#757575]">{{ props.email }}</p>
      </div>
      <img v-if="!dropdownOpen" src="../../../../assets/openArrow.svg" class="w-5 h-5 ms-5">
      <img v-else src="../../../../assets/openArrow.svg" class="w-5 h-5 ms-5 rotate-180" </section>
      <div class="relative flex justify-center">
        <transition name="slide-down">
          <div v-if="dropdownOpen" ref="dropdownRef"
            class="absolute min-w-full flex flex-col items-center bg-[#2B2C30] text-white rounded-br-lg rounded-bl-lg shadow-lg py-2 mt-2 z-10">
            <div class="flex flex-col w-[200px] justify-center rounded-[5px] items">
              <button
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
            <label class="inline-flex items-center relative my-2">
              <input class="peer hidden" id="toggle" type="checkbox" />
              <div
                class="relative w-[110px] h-[50px] bg-white peer-checked:bg-zinc-500 rounded-full after:absolute after:content-[''] after:w-[40px] after:h-[40px] after:bg-gradient-to-r from-orange-500 to-yellow-400 peer-checked:after:from-zinc-900 peer-checked:after:to-zinc-900 after:rounded-full after:top-[5px] after:left-[5px] active:after:w-[50px] peer-checked:after:left-[105px] peer-checked:after:translate-x-[-100%] shadow-sm duration-300 after:duration-300 after:shadow-md">
              </div>
              <svg height="0" width="100" viewBox="0 0 24 24" data-name="Layer 1" id="Layer_1"
                xmlns="http://www.w3.org/2000/svg"
                class="fill-white peer-checked:opacity-60 absolute w-6 h-6 left-[13px]">
                <path
                  d="M12,17c-2.76,0-5-2.24-5-5s2.24-5,5-5,5,2.24,5,5-2.24,5-5,5ZM13,0h-2V5h2V0Zm0,19h-2v5h2v-5ZM5,11H0v2H5v-2Zm19,0h-5v2h5v-2Zm-2.81-6.78l-1.41-1.41-3.54,3.54,1.41,1.41,3.54-3.54ZM7.76,17.66l-1.41-1.41-3.54,3.54,1.41,1.41,3.54-3.54Zm0-11.31l-3.54-3.54-1.41,1.41,3.54,3.54,1.41-1.41Zm13.44,13.44l-3.54-3.54-1.41,1.41,3.54,3.54,1.41-1.41Z">
                </path>
              </svg>
              <svg height="512" width="512" viewBox="0 0 24 24" data-name="Layer 1" id="Layer_1"
                xmlns="http://www.w3.org/2000/svg"
                class="fill-black opacity-60 peer-checked:opacity-70 peer-checked:fill-white absolute w-6 h-6 right-[13px]">
                <path
                  d="M12.009,24A12.067,12.067,0,0,1,.075,10.725,12.121,12.121,0,0,1,10.1.152a13,13,0,0,1,5.03.206,2.5,2.5,0,0,1,1.8,1.8,2.47,2.47,0,0,1-.7,2.425c-4.559,4.168-4.165,10.645.807,14.412h0a2.5,2.5,0,0,1-.7,4.319A13.875,13.875,0,0,1,12.009,24Zm.074-22a10.776,10.776,0,0,0-1.675.127,10.1,10.1,0,0,0-8.344,8.8A9.928,9.928,0,0,0,4.581,18.7a10.473,10.473,0,0,0,11.093,2.734.5.5,0,0,0,.138-.856h0C9.883,16.1,9.417,8.087,14.865,3.124a.459.459,0,0,0,.127-.465.491.491,0,0,0-.356-.362A10.68,10.68,0,0,0,12.083,2ZM20.5,12a1,1,0,0,1-.97-.757l-.358-1.43L17.74,9.428a1,1,0,0,1,.035-1.94l1.4-.325.351-1.406a1,1,0,0,1,1.94,0l.355,1.418,1.418.355a1,1,0,0,1,0,1.94l-1.418.355-.355,1.418A1,1,0,0,1,20.5,12ZM16,14a1,1,0,0,0,2,0A1,1,0,0,0,16,14Zm6,4a1,1,0,0,0,2,0A1,1,0,0,0,22,18Z">
                </path>
              </svg>
            </label>

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