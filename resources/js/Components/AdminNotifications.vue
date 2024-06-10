<script setup>
import { ref } from 'vue';
import { Popover } from 'ant-design-vue';
import { Link } from "@inertiajs/vue3";

const notificationCount = ref(5); // Initialize with some notifications
const dropdownOpen = ref(false); // State for the dropdown visibility

const notifications = [
  {
    icon: 'fas fa-spinner fa-spin text-xl text-green-600 bg-green-100 rounded-full px-2 py-1',
    message: 'Pasūtījums apstrādājas',
    time: 'pirms 27 minūtēm',
  },
  {
    icon: 'fas fa-times-circle text-xl text-red-600 bg-red-100 rounded-full px-2 py-1',
    message: 'Pasūtījums atcelts',
    time: 'pirms 1 stundas',
  },
  {
    icon: 'fas fa-check-circle text-xl text-blue-600 bg-blue-100 rounded-full px-2 py-1',
    message: 'Pasūtījums pabeigts',
    time: 'pirms 2 stundām',
  },
  {
    icon: 'fas fa-exclamation-circle text-xl text-yellow-600 bg-yellow-100 rounded-full px-2 py-1',
    message: 'Pasūtījums gaida',
    time: 'pirms 3 stundām',
  },
];
</script>

<style scoped>
.notification-popover-content {
    width: 256px;
}

.notification-popover .ant-popover-inner {
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

.notification-popover .ant-popover-inner-content {
    padding: 0;
}
</style>
<template>
  <div class="relative">
    <Popover
      v-model:open="dropdownOpen"
      placement="bottomRight"
      trigger="click"
      overlayClassName="notification-popover"
      :arrow="false"
    >
      <template #content>
        <div class="notification-popover-content">
          <div class="text-base px-3 py-2 text-gray-600 dark:text-gray-300 font-semibold border-b dark:border-gray-800 uppercase">
            Paziņojumi
          </div>
          <div class="divide-y divide-gray-200">
            <Link
              v-for="notification in notifications"
              :key="notification.message"
              href="#"
              class="flex gap-2 items-start p-3 hover:bg-gray-50 dark:hover:bg-gray-700"
            >
              <span :class="notification.icon"></span>
              <div>
                <p class="text-gray-800 text-base dark:text-white">
                  {{ notification.message }}
                </p>
                <p class="text-xs text-gray-600 dark:text-gray-400">
                  {{ notification.time }}
                </p>
              </div>
            </Link>
          </div>
          <div class="flex justify-between items-center py-2 px-3 border-t dark:border-gray-800">
            <Link href="#" class="text-xs text-blue-600 hover:underline">
              Atzīmēt kā izlasītus
            </Link>
          </div>
        </div>
      </template>
      <button class="hover:bg-zinc-700 rounded-lg text-md p-2.5 relative">
        <i class="fas fa-bell fa-fw"></i>
        <!-- Notification badge -->
        <span
          v-if="notificationCount > 0"
          class="absolute top-1 right-1 transform translate-x-1/2 -translate-y-1/2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full"
        >
          {{ notificationCount }}
        </span>
      </button>
    </Popover>
  </div>
</template>
