<template>
  <div class="relative">
    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
      <svg class="w-5 h-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
      </svg>
    </div>
    <input type="search"
           class="w-full py-2 pl-10 pr-4 text-sm text-textColor border border-gray-300 rounded-xl bg-white shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
           placeholder="Meklēt"
           v-model="searchQuery"
           @input="updateSearch"
           @keypress.enter.prevent="onSearch"/>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
  initialQuery: String
});

const emit = defineEmits(['update:searchQuery', 'search']);

const searchQuery = ref(props.initialQuery || '');

function onSearch() {
  emit('search', searchQuery.value);
}

function updateSearch() {
  emit('update:searchQuery', searchQuery.value);
}

watch(() => props.initialQuery, (newVal) => {
  if (newVal !== searchQuery.value) {
    searchQuery.value = newVal;
  }
});
</script>
