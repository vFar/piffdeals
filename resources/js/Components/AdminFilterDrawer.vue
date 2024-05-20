<template>
    <div class="relative z-50">
      <button @click="toggleDrawer"
              class="flex items-center justify-center py-2 px-4 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
        <i class="fas fa-filter fa-fw mr-2 text-textColor"></i>
        Filtrēt
      </button>
      <div v-show="drawerOpen" @click="toggleDrawer"
           class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"
           :class="{'opacity-100': drawerOpen, 'opacity-0': !drawerOpen}">
      </div>
      <div :class="{'translate-x-0': drawerOpen, 'translate-x-full': !drawerOpen}"
           class="fixed right-0 top-0 w-96 h-full bg-white shadow-xl transition-transform duration-300 ease-in-out">
        <div class="flex items-center justify-between border-b mb-4 p-4">
          <h3>Filtrēt preces</h3>
          <button @click="toggleDrawer" class="text-white p-2 rounded-xl hover:bg-gray-300">
            <i class="fas fa-xmark fa-fw text-textColor hover:text-white"></i>
          </button>
        </div>
        <p class="px-4">Here is some more content...</p>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  
  const drawerOpen = ref(false);
  
  function toggleDrawer() {
    drawerOpen.value = !drawerOpen.value;
  }
  </script>
  