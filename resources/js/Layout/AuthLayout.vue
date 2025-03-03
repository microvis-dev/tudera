<script setup>
</script>

<template>
    <div class="wrapper grid content-center h-screen justify-center bg-black text-white">
        <div class="flex flex-row flex-wrap xl:p-8">
            <section class="rounded-l-lg w-screen h-screen xl:w-[678px] xl:h-[866px] border border-[#2B2C30]">
                <slot></slot>
            </section>
            <section class="hidden xl:flex xl:w-1/2 justify-center items-center bg-gradient-to-br from-[#a8b4e7] to-[#2b3a76] rounded-r-lg xl:w-[678px] xl:h-[866px]">
                <img src="../../assets/cloud.svg" alt="cloud" class="w-2/5 filter drop-shadow-2xl animate-glow" />
            </section>
        </div>
    </div>
</template>

<style scoped>
.wrapper {
    background-color: #1C1D21;
    color: #F5F7F9;
}
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
