<script setup>

</script>
<template>
    <div class="py-6 ps-6">
        <h2 class="text-2xl roboto-font-bold">Apperance</h2>
        <hr>
    </div>
    <div class="p-6 flex flex-row justify-around m-5 h-6/6 rounded-xl">
        <div class="w-72 rounded-2xl p-1 cursor-pointer">
            <div class="rounded-2xl overflow-hidden bg-white">
                <div class="p-4 flex flex-col items-center">
                    <div class="w-28 h-28 mb-4 relative">
                        <img src="../../../../assets/tuderaLogoWhite.svg"
                            class="rounded-full shadow-md w-full h-full object-cover">
                    </div>
                    <div class="flex space-x-3 w-full mb-4">
                        <div class="flex-1 h-12 rounded-lg bg-gray-200"></div>
                        <div class="flex-1 h-12 rounded-lg bg-gray-200"></div>
                    </div>
                    <div class="h-12 rounded-lg bg-gray-200 w-full mb-4"></div>
                </div>
                <div class="p-3 text-center roboto-font-bold text-black text-lg">Light</div>
            </div>
        </div>

        <div class="w-72 rounded-2xl p-1 cursor-pointer">
            <div class="rounded-2xl overflow-hidden bg-[#2B2C30]">
                <div class="p-4 flex flex-col items-center">
                    <div class="w-28 h-28 mb-4 relative">
                        <img src="../../../../assets/tuderaLogoWhite.svg"
                            class="shadow-md w-full h-full object-fit">
                    </div>
                    <div class="flex space-x-3 w-full mb-4">
                        <div class="flex-1 h-12 rounded-lg bg-gray-200"></div>
                        <div class="flex-1 h-12 rounded-lg bg-gray-200"></div>
                    </div>
                    <div class="h-12 rounded-lg bg-gray-200 w-full mb-4"></div>
                </div>
                <div class="p-3 text-center roboto-font-bold text-lg">Dark</div>
            </div>
        </div>

    </div>
</template>
<style scoped>
.lightmode {
    background-image: url("../../../../assets/lightMode.png");
    background-position: center;
}
</style>