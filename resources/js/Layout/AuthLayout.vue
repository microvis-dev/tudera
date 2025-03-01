<script setup>
</script>

<template>
    <div class="grid wrapper content-center h-screen justify-center bg-black">
        <div class="container flex flex-row flex-wrap p-8">
            <section class="login rounded-l-lg w-[678px] h-[866px] border border-[#2B2C30]">
                <slot></slot>
            </section>
            <section class="w-1/2">
                <div class="animation rounded-r-lg w-[678px] h-[866px] bg-gradient-to-br from-[#a8b4e7] to-[#2b3a76] flex justify-center items-center">
                    <img src="../../assets/cloud.svg" alt="cloud" class="w-2/5 filter drop-shadow-2xl animate-glow" />
                </div>
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
