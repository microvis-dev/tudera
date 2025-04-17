<script setup>
import { ref, watch, nextTick, provide, reactive, computed, watchEffect } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { useTuderaStore } from "../../state/state";

const props = defineProps({
  modelValue: {
    type: Boolean,
    default: false
  },
})

const tuderaState = useTuderaStore()

const user = computed(() => {
  return tuderaState.getUser()
})
const workspaces = computed(() => {
  return tuderaState.getWorkspaces()
})

watch(workspaces, () => { // lehetne jobb
  let updatedWorkspace = workspaces.value.find(workspace => workspace.id === selectedWorkspace.value.id);
  emit('select-workspace', updatedWorkspace)
}, { deep: true });

const emit = defineEmits(['update:modelValue', 'dropdown-change', 'height-change', 'select-workspace']);

const selectedWorkspace = computed(() => tuderaState.getSelectedWorkspace())
emit('select-workspace', selectedWorkspace.value)
const dropdownOpen = ref(false);
const dropdownElement = ref(null); // Reference to the dropdown element

watch(dropdownOpen, async (newValue) => {
  emit('dropdown-change', newValue);

  if (newValue) {
    // Wait for DOM to update before measuring height
    await nextTick();
    updateDropdownHeight();
  } else {
    emit('height-change', 0);
  }
});

const updateDropdownHeight = () => {
  if (dropdownElement.value) {
    const height = dropdownElement.value.offsetHeight;
    emit('height-change', height);
  }
};

const selectWorkspace = (workspace) => {
  tuderaState.setWorkspace(workspace)

  dropdownOpen.value = false;

  emit('select-workspace', selectedWorkspace.value)
  router.get(route('dashboard.index'))
};

const checkSelectedWorkspace = (workspace) => {
  return workspace == selectedWorkspace.value
}

const toCreateWorkspace = () => {
  router.get(route('workspaces.create'))
}

const go = (id) => {
  router.get(route('workspace.table.index', { workspace: id }))
}

</script>

<template>
  <div class="relative w-48">
    <!-- Dropdown Button -->
    <button @click="dropdownOpen = !dropdownOpen"
      class="w-full flex items-center justify-between bg-[#2B2C30] text-white px-4 py-2 border border-gray-600"
      :class="[dropdownOpen ? 'rounded-tl-lg rounded-tr-lg border-b-0' : 'rounded-lg']">
      <span>{{ selectedWorkspace.name }}</span>
      <span class="w-5 h-5 right-0"><img src="../../../assets/openArrow.svg"></span>
    </button>

    <!-- Dropdown Menu with transition -->
    <transition name="slide-down">
      <div v-if="dropdownOpen" ref="dropdownElement"
        class="absolute w-full bg-[#2B2C30] text-white rounded-br-lg rounded-bl-lg shadow-lg py-2">

        <!-- Workspaces List -->
        <div>
          <div v-for="workspace in workspaces" :key="workspace.id" @click="selectWorkspace(workspace)"
            class="flex items-center gap-2 py-2 px-2 mb-3 rounded-lg cursor-pointer hover:bg-gray-700"
            :class="{ 'bg-gray-700': checkSelectedWorkspace(workspace) }">
            <img src="https://placehold.co/20x20" alt="Workspace Icon" class="w-5 h-5" />
            <span class="flex-1">{{ workspace.name }}</span>
            <i v-if="checkSelectedWorkspace(workspace)" class="fas fa-check"></i>
          </div>
        </div>

        <!-- Add New Workspace -->
        <div @click="toCreateWorkspace"
          class="px-4 py-2 text-sm flex justify-center text-gray-400 hover:text-white cursor-pointer">
          Add new workspace
        </div>
      </div>
    </transition>
  </div>
</template>
<style scoped>
.slide-down-enter-active,
.slide-down-leave-active {
  transition: all 0.2s ease;
  transform-origin: top;
}

.slide-down-enter-from,
.slide-down-leave-to {
  transform: translateY(-10px);
  opacity: 0;
}

.slide-down-enter-to,
.slide-down-leave-from {
  transform: translateY(0);
  opacity: 1;
}
</style>