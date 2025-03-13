<script setup>
const props = defineProps({
    name: String
})

const deleteColumn = () => {
    console.log("delete")
}
</script>

<template>
    <div class="flex items-center justify-between p-2 border-b">
        <span>{{ name }}</span>
        <button @click="deleteColumn" class="text-red-500 hover:text-red-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-10.707a1 1 0 00-1.414-1.414L10 8.586 7.707 6.293a1 1 0 00-1.414 1.414L8.586 10l-2.293 2.293a1 1 0 001.414 1.414L10 11.414l2.293 2.293a1 1 0 001.414-1.414L11.414 10l2.293-2.293z"
                    clip-rule="evenodd" />
            </svg>
        </button>
    </div>
</template>

<style scoped></style>