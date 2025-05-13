<script setup>
import { ref, watch, watchEffect } from "vue"
import { computed } from 'vue';
import Sidebar from "./Components/MainLayoutComponents/Sidebar.vue";
import Search from "./Components/MainLayoutComponents/Search.vue";
import Profile from "./Components/MainLayoutComponents/Profile.vue";
import { useTuderaStore, useTuderaViewStore } from "../state/state";
import { createToast } from "mosha-vue-toastify";
import 'mosha-vue-toastify/dist/style.css';

const tuderaState = useTuderaStore()
const tuderaViewState = useTuderaViewStore()
const user = computed(() => tuderaState.getUser())
const isSidebarOpen = ref(false)

const modalState = computed(() => tuderaViewState.getModal());

watch(modalState, (newValue) => {
  if (newValue) {
    console.log("Modal state changed:", newValue);
  }
});

const errors = computed(() => tuderaState.getErrors())
const flashErrors = computed(() => tuderaState.getFlashError())
const flashSuccess = computed(() => tuderaState.getFlashSuccess())

const showMessageToast = (message) => {
  createToast(message, {
    type: "success",
    transition: "slide",
    position: "top-right",
    timeout: 3000
  })
}

const showSuccessToast = (message, timeout = 3000) => {
  createToast(message, {
    type: 'success',
    transition: 'slide',
    position: 'top-right',
    timeout: timeout,
  })
}

const showErrorToast = (message, timeout = 5000) => {
  createToast(message, {
    type: 'danger',
    transition: 'slide',
    position: 'top-right',
    timeout: timeout,
  })
}

watchEffect(() => {
  if (flashErrors.value) {
    showErrorToast(flashErrors.value)
  }
  if (flashSuccess.value) {
    showSuccessToast(flashSuccess.value)
  }

  tuderaState.clearPageProps()
})

</script>

<template>
  <div class="w-screen h-screen flex flex-row overflow-auto md:overflow-hidden">
    <section class="w-0/12 md:w-2/12">
      <div class="h-screen relative">

        <!-- Sidebar - hidden on mobile by default, visible on larger screens -->
        <div ref="sidebarRef"
          :class="['transition-all duration-300 md:translate-x-0', isSidebarOpen ? 'translate-x-0 bg-[#2B2C30]' : '-translate-x-full']"
          class="fixed md:relative w-64 md:w-full h-full z-40">
          <Sidebar />
        </div>
        <div v-if="isSidebarOpen" class="fixed inset-0 bg-black bg-opacity-50 z-30 md:hidden"
          @click="isSidebarOpen = false"></div>
      </div>
    </section>
    <section class="w-12/12 md:w-10/12 flex flex-col mt-2.5">
      <button v-if="!isSidebarOpen" @click="isSidebarOpen = !isSidebarOpen"
        class="md:hidden w-fit top-4 left-4 z-50 p-2 rounded-md bg-gray-800 text-white">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>
      <div class="flex flex-col md:flex-row h-fit md:mt-0">
        <div class="w-full md:w-4/5 px-2 md:px-0">
          <Search />
        </div>
        <span class="hidden md:block h-10 w-0.5 bg-[#2B2C30] mt-1.5"></span>
        <div class="w-full md:w-1/5 mt-1 md:mt-0 px-2 md:px-0">
          <Profile :profile-image='user.profile_image' :name='user.name' :email="user.email" />
        </div>
      </div>
      <div class="flex flex-row h-full pb-4">
        <section class="w-full">
          <div class="w-full h-full">
            <slot></slot>
          </div>
        </section>
      </div>
    </section>
  </div>
</template>

<style scoped></style>
