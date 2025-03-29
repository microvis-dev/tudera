<script setup>
import { ref } from "vue";

const isOpen = ref(false);

const toggleDropdown = () => {
    isOpen.value = !isOpen.value;
};
</script>

<template>
    <div class="flex flex-col pt-5 ps-5 pe-5">
        <div class="flex items-center justify-between bg-[#2B2C30] p-2 cursor-pointer transition-all duration-500 ease-in-out"
            @click="toggleDropdown" :class="isOpen ? 'rounded-t-xl' : 'rounded-xl'">
            <div class="flex items-center w-full overflow-hidden">
                <div class="bg-blue-500 w-10 h-10 rounded-full mr-2 flex items-center justify-center">
                    wkep
                </div>
                <div class="flex flex-col overflow-hidden transition-all duration-500 ease-in-out"
                    :class="isOpen ? 'translate-y-4 opacity-0' : 'translate-y-0 opacity-100'">
                    <p class="text-md roboto-font-bold">UX/UI Squads</p>
                    <div class="ticker-wrapper">
                        <div v-if="!isOpen" class="ticker">
                            <p class="inline-block pe-3 roboto-font-light text-[#B3B3B3]">
                                Dalat room - L4 - Danang Office
                            </p>
                            <p class="inline-block pe-3 roboto-font-light text-[#B3B3B3]">
                                Dalat room - L4 - Danang Office
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-pink-500 w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center">
                use
            </div>
        </div>

        <transition name="slide">
            <div v-if="isOpen" class="p-3 bg-[#2B2C30] rounded-b-xl">
                <div class="flex flex-col overflow-hidden transition-all duration-500 ease-in-out">
                    <p class="text-md roboto-font-bold transition-all duration-500 ease-in-out opacity-0"
                        :class="isOpen ? 'translate-y-0 opacity-100' : 'translate-y-[-10px] opacity-0'">
                        UX/UI Squads
                    </p>
                    <div class="flex flex-col">
                        <p class="inline-block pe-3 roboto-font-light text-[#B3B3B3]">Dalat room - L4 - Danang Office
                        </p>
                        <p class="inline-block pe-3 roboto-font-light text-[#B3B3B3]">Monday, November 13 - 16:00 -
                            16:45 </p>
                    </div>
                    <div class="flex justify-center">
                        <button class="mt-4 bg-blue-500 p-2 px-4 rounded-full">Join meeting</button>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<style scoped>
.ticker-wrapper {
    position: relative;
    width: 130px;
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
