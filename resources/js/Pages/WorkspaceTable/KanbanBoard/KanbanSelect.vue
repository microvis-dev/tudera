<script setup>
import { computed, ref } from "vue";
import { useTuderaStore } from "@/resources/js/state/state";
import Kanban from "./Kanban.vue";

const props = defineProps({
    status_options: Array,
    show: {
        type: Boolean,
        default: false
    },
    columns: Array,
    values: Array
});

const emit = defineEmits(['hide-table', 'back']);

const tuderaState = useTuderaStore();

const selectedKanban = ref(null);

// Computed: Disable "Switch to Kanban version" only if nothing selected
const isPlaceholderDisabled = computed(() => selectedKanban.value === null);

const selectKanban = () => {
  if (selectedKanban.value === null) {
    emit('back'); // If selecting "Switch to Kanban version" again, go back
  } else {
    emit('hide-table'); // If a kanban selected, hide table
  }
};

const back = () => {
    selectedKanban.value = null;
    emit('back');
};

const handleUpdate = (event) => {
  // You can fill this in later if you want to handle updates from Kanban
};
</script>

<template>
  <select 
    v-model="selectedKanban" 
    @change="selectKanban" 
    class="bg-[#5D5E5B] peer rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
    
    <option :value="null" :disabled="isPlaceholderDisabled">
      {{ isPlaceholderDisabled ? 'Switch to Kanban version' : 'Go back' }}
    </option>

    <option 
      v-for="kanban in props.status_options" 
      :key="kanban.name" 
      :value="kanban">
      {{ kanban.name }}
    </option>
  </select>

  <Kanban 
    v-if="show" 
    :selectedKanban="selectedKanban" 
    :values="values" 
    :columns="columns" 
    @back="back()" 
    @update="handleUpdate" 
  />
</template>

<style scoped></style>
