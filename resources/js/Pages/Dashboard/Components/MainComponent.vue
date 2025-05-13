<script setup>
import { computed } from 'vue';
import { router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { useTuderaStore } from '@/resources/js/state/state';
import LastTableCard from './LastTableCard.vue';
import { findLatestDate } from '@/resources/js/utils/utils';

const tuderaState = useTuderaStore()
const selectedWorkspace = computed(() => tuderaState.getSelectedWorkspace())

const addNewTable = () => {
  router.get(route('workspace.table.create', { workspace: selectedWorkspace.value }))
}

const lastTables = computed(() => {
  let tables = selectedWorkspace.value?.tables || []

  const latestDateTables = tables
    .map((table) => {
      const dates = [
        table.updated_at,
        ...table.columns.map((column) => column.updated_at),
        ...table.columns.flatMap((column) => column.values.map((value) => value.updated_at))
      ]

      return {
        ...table,
        updated_at: findLatestDate(dates)
      }
    })

  return latestDateTables
    .sort((t1, t2) => t2.updated_at - t1.updated_at)
    .slice(0, 4)
})

const redirect = (table) => {
  router.get(route('table.show', { table: table.id }))
}
</script>
<template>
  <section class="flex flex-row md:flex-col p-5">
    <div v-if="lastTables == 0"> <!-- 1 ha lesz leads table, 2 ha leads+kanban -->
      <h1 class="text-3xl roboto-font-bold mb-2">
        Start managing<br>
        your business!
      </h1>
      <p class="text-sm roboto-font-small mb-5 text-[#B3B3B3]">Create your own workspace according to your <br> company.
      </p>
      <button @click="addNewTable" class="w-fit p-2 px-10 rounded-xl bg-blue-600">Add new Table</button>
    </div>
    <div v-else>
      <h1 class="text-3xl roboto-font-bold mb-2">
        Take control of<br>
        your workflow!
      </h1>
      <p class="text-sm roboto-font-small mb-5 text-[#B3B3B3]">Quickly jump back into your team's most <br> recent
        tables and projects.
      </p>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div v-for="table in lastTables" :key="table.id" @click="redirect(table)" class="cursor-pointer">
          <LastTableCard :table="table" />
        </div>
      </div>
    </div>
  </section>
</template>