<script setup>
</script>

<template>
  <div class="flex items-center justify-center min-h-screen bg-[#1C1D21] text-[#F5F7F9]">
    <div class="container flex flex-col w-full h-screen sm:px-4 sm:py-8 xl:flex-row xl:px-20">
      <section class="flex flex-col justify-center items-center w-full h-full sm:rounded-l-lg xl:rounded-l-lg border border-[#2B2C30] xl:w-[678px] xl:max-h-[866px]">
        <slot></slot>
      </section>
      <section class="w-full xl:w-[678px]">
        <div
            class="hidden xl:flex animation rounded-r-lg h-full bg-gradient-to-br from-[#a8b4e7] to-[#2b3a76] justify-center items-center">
          <img src="../../assets/cloud.svg" alt="cloud"
               class="w-2/5 filter drop-shadow-2xl animate-glow"/>
        </div>
      </section>
    </div>
  </div>
</template>
<style scoped>
@keyframes glow {
  100% {
    filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.5));
  }
  0% {
    filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.5));
  }
}

.animate-glow {
  animation: glow 2s infinite;
}
</style>
