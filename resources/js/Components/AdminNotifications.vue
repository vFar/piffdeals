<template>
  <div class="relative">
    <button @click="toggleDropdown" class="hover:bg-zinc-700 rounded-lg text-md p-2.5 relative">
      <i class="fas fa-bell fa-fw"></i>
      <!-- Notification badge -->
      <span v-if="notificationCount > 0" class="absolute top-0 right-0 transform translate-x-1/2 -translate-y-1/2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">
        {{ notificationCount }}
      </span>
    </button>
    <!-- Dropdown with Transition for notifications -->
    <Transition name="fade" enter-active-class="transition-opacity duration-300" leave-active-class="transition-opacity duration-300" enter-from-class="opacity-0" leave-to-class="opacity-0">
      <div v-show="dropdownOpen" @click.away="closeDropdown" class="absolute right-0 mt-2 py-2 w-64 bg-white rounded-md shadow-xl z-50">
        <!-- Header -->
        <div class="text-base px-3 py-2 text-gray-600 dark:text-gray-300 font-semibold border-b dark:border-gray-800 uppercase">
          Paziņojumi
        </div>
        <!-- Notification items -->
        <div class="divide-y divide-gray-200">
          <a href="#" class="flex gap-2 items-start p-3 hover:bg-gray-50 dark:hover:bg-gray-700">
            <span class="fas fa-spinner fa-spin text-xl text-green-600 bg-green-100 rounded-full px-2 py-1"></span>
            <div>
              <p class="text-gray-800 text-base dark:text-white">Pasūtījums apstrādājas</p>
              <p class="text-xs text-gray-600 dark:text-gray-400">pirms 27 minūtēm</p>
            </div>
          </a>
          <a href="#" class="flex gap-2 items-start p-3 hover:bg-gray-50 dark:hover:bg-gray-700">
            <span class="fas fa-times-circle text-xl text-red-600 bg-red-100 rounded-full px-2 py-1"></span>
            <div>
              <p class="text-gray-800 text-base dark:text-white">Pasūtījums atcelts</p>
              <p class="text-xs text-gray-600 dark:text-gray-400">pirms 1 stundas</p>
            </div>
          </a>
          <a href="#" class="flex gap-2 items-start p-3 hover:bg-gray-50 dark:hover:bg-gray-700">
            <span class="fas fa-check-circle text-xl text-blue-600 bg-blue-100 rounded-full px-2 py-1"></span>
            <div>
              <p class="text-gray-800 text-base dark:text-white">Pasūtījums pabeigts</p>
              <p class="text-xs text-gray-600 dark:text-gray-400">pirms 2 stundām</p>
            </div>
          </a>
          <a href="#" class="flex gap-2 items-start p-3 hover:bg-gray-50 dark:hover:bg-gray-700">
            <span class="fas fa-exclamation-circle text-xl text-yellow-600 bg-yellow-100 rounded-full px-2 py-1"></span>
            <div>
              <p class="text-gray-800 text-base dark:text-white">Pasūtījums gaida</p>
              <p class="text-xs text-gray-600 dark:text-gray-400">pirms 3 stundām</p>
            </div>
          </a>
        </div>
        <!-- Footer -->
        <div class="flex justify-between items-center py-2 px-3 border-t dark:border-gray-800">
          <a href="#" class="text-xs text-blue-600 hover:underline">Skatīt visus</a>
          <a href="#" class="text-xs text-blue-600 hover:underline">Atzīmēt kā izlasītus</a>
        </div>
      </div>
    </Transition>
  </div>
</template>





<script setup>
import { ref } from 'vue';

const notificationCount = ref(5); // Initialize with some notifications
const dropdownOpen = ref(false); // State for the dropdown visibility

const toggleDropdown = () => {
  dropdownOpen.value = !dropdownOpen.value;
};

const closeDropdown = () => {
  dropdownOpen.value = false;
};
</script>



