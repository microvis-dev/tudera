<script setup>

const props = defineProps({
    workspace: Object,
    table: Object
})
</script>

<template>
    <div class="min-h-screen bg-gray-100 p-6">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h1 class="text-2xl font-bold text-gray-800">{{ table.name }}</h1>
                    <p class="text-sm text-gray-600 mt-1">
                        Workspace: <span class="font-medium">{{ workspace.name }}</span>
                    </p>
                </div>
                
                <div class="p-6">
                    <div class="bg-gray-50 border border-gray-200 rounded-md p-8 text-center text-gray-500">
                        Table content will be displayed here
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
  
</style>