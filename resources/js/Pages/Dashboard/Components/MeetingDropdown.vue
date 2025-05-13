<script setup>
import { ref } from "vue";

const isOpen = ref(false);

const toggleDropdown = () => {
    isOpen.value = !isOpen.value;
};
const props = defineProps({
    "name": String,
    "endDate": Date,
})
</script>

<template>
    <div class="flex flex-col items-center text-center pt-5 ps-5 pe-5 overflow-auto w-full">
        <div class="flex items-center justify-between bg-[#2B2C30] p-2 cursor-pointer transition-all duration-500 ease-in-out"
            @click="toggleDropdown" :class="isOpen ? 'rounded-t-xl' : 'rounded-xl'">
            <div class="flex items-center w-full overflow-hidden">
                <div class="flex flex-col overflow-hidden transition-all duration-500 ease-in-out"
                    :class="isOpen ? 'translate-y-4 opacity-0' : 'translate-y-0 opacity-100'">
                    <p class="text-md roboto-font-bold">{{props.name}}</p>
                    <div class="ticker-wrapper">
                        <div v-if="!isOpen" class="ticker">
                            <p class="inline-block pe-3 roboto-font-light text-[#B3B3B3]">
                                {{ props.endDate }}
                            </p>
                            <p class="inline-block pe-3 roboto-font-light text-[#B3B3B3]">
                                {{ props.endDate }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div>
      </div>
        </div>
        <transition name="slide">
            <div v-if="isOpen" class="p-3 bg-[#2B2C30] rounded-b-xl">
                <div class="flex flex-col overflow-hidden transition-all duration-500 ease-in-out">
                    <p class="text-md roboto-font-bold transition-all duration-500 ease-in-out opacity-0"
                        :class="isOpen ? 'translate-y-0 opacity-100' : 'translate-y-[-10px] opacity-0'">
                        {{ props.name }}
                    </p>
                    <div class="flex flex-col">
                        <p class="inline-block pe-3 roboto-font-light text-[#B3B3B3]">{{props.endDate}} </p>
                    </div>
                    <div class="flex justify-center">
                        <button class="mt-4 bg-blue-500 p-2 px-4 rounded-full">XD</button>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<style scoped>
.ticker-wrapper {
    position: relative;
    width: 150px;
    overflow: hidden;
    white-space: nowrap;
}

/* Moving text */
.ticker {
    display: flex;
    animation: ticker 5s linear infinite;
    min-width: max-content;
}

@keyframes ticker {
    from {
        transform: translateX(0);
    }

    to {
        transform: translateX(-50%);
    }
}

/* Smooth slide-down animation */
.slide-enter-active,
.slide-leave-active {
    transition: max-height 0.5s ease-in-out, opacity 0.5s ease-in-out;
}

.slide-enter-from,
.slide-leave-to {
    max-height: 0;
    opacity: 0;
    overflow: hidden;
}

.slide-enter-to,
.slide-leave-from {
    max-height: 250px;
    opacity: 1;
}
</style>
