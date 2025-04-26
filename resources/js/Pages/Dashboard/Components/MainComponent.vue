<script setup>
import { watch, computed, inject, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { useTuderaStore } from '@/resources/js/state/state';
import LastTableCard from './LastTableCard.vue';

const { getSelectedWorkspace } = useTuderaStore()
const selectedWorkspace = computed(() => getSelectedWorkspace())

const addNewTable = () => {
  router.get(route('workspace.table.create', { workspace: selectedWorkspace.value }))
}

const lastTables = computed(() => {
  let tables = selectedWorkspace.value?.tables || []
  let length = tables.length >= 4 ? 4 : tables.length

  return tables
    ? tables.sort((a, b) => new Date(b.updated_at) - new Date(a.updated_at)).slice(0, length)
    : [];
})

</script>
<template>
  <section class="flex flex-row md:flex-col p-5">
    <div v-if="lastTables.value == 1">
      <h1 class="text-3xl roboto-font-bold mb-2">
        Start managing<br>
        your business!
      </h1>
      <p class="text-sm roboto-font-small mb-5 text-[#B3B3B3]">Create your own workspace according to your <br> company.
      </p>
      <button @click="addNewTable" class="w-fit p-2 px-10 rounded-xl bg-blue-600">Add new Table</button>
    </div>
    <div>
      <h1 class="text-3xl roboto-font-bold mb-2">
        Take control of<br>
        your workflow!
      </h1>
      <p class="text-sm roboto-font-small mb-5 text-[#B3B3B3]">Quickly jump back into your team's most <br> recent
        tables and projects.
      </p>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div v-for="table in lastTables" :key="table.id"
          @click="router.get(route('table.show', { table: table.id }))"
          class="cursor-pointer">
          <LastTableCard :table="table" />
        </div>
      </div>
    </div>
  </section>
</template>
<style scoped></style>