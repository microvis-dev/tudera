<script setup>
import { computed } from 'vue';

const props = defineProps({
  color: {
    type: String,
    default: 'gray'
  }
});

// Számított tulajdonság a szín osztályokhoz
const colorClasses = computed(() => {
  return {
    [`bg-${props.color}-100`]: true,
    [`text-${props.color}-700`]: true
  };
});
</script>

<template>
  <div class="px-3 h-6 rounded-full text-xs font-semibold flex items-center" :class="colorClasses">
    <span class="w-2 h-2 rounded-full mr-1" :class="`bg-${color}-400`"></span>
    <span>
      <slot></slot>
    </span>
  </div>
</template>
