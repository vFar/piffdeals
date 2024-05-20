<template>
  <div class="relative inline-block" @mouseenter="visible = true" @mouseleave="visible = false">
    <slot></slot>
    <transition name="fade">
      <div v-show="visible"
           :class="['absolute z-10 px-2 py-1 text-sm text-white bg-accent rounded shadow-lg', positionClasses]"
           style="min-width: 120px; white-space: nowrap;">
        {{ content }}
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  content: String,
  position: {
      type: String,
      default: 'top-center' // Default position
  }
});

const visible = ref(false);

// Compute class for tooltip position
const positionClasses = computed(() => {
  switch (props.position) {
      case 'top-center':
          return 'left-1/2 -translate-x-1/2 -top-full mt-2';
      case 'bottom-center':
          return 'left-1/2 -translate-x-1/2 top-full mt-2';
      default:
          return 'left-1/2 -translate-x-1/2 -top-full mt-2'; // Default to top-center if no valid position is provided
  }
});
</script>

<style>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.2s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>
