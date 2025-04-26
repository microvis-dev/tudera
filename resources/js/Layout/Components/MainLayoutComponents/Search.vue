<script setup>
    
</script>
<template>
    <section class="flex flex-row justify-between h-fit px-5 py-2">
        <div class="relative">
          <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
        <img src="../../../../assets/search.svg" class="w-5 h-5">
          </div>
          <input type="text"
        class="pl-10 pr-3 py-2 w-fit md:w-96 bg-[#1C1D21] border border-gray-600 rounded-lg roboto-font-regular focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 text-gray-500 text-sm"
        placeholder="Search">
        </div>
        <div>
          <img src="../../../../assets/bell.svg" class="w-10 h-10 bell">
        </div>
      </section>
</template>
<style scoped>
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