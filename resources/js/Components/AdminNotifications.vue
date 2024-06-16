<script setup>
import { ref, onMounted } from "vue";
import { Popover } from "ant-design-vue";
import { Link } from "@inertiajs/vue3";
import axios from "axios";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
import "dayjs/locale/lv"; // Import Latvian locale

dayjs.extend(relativeTime);
dayjs.locale("lv"); // Use Latvian locale

const notifications = ref([]);
const notificationCount = ref(0);
const dropdownOpen = ref(false);
const fetchingOrders = ref(false); // New flag to manage fetching state

function determineIcon(status) {
    switch (status) {
        case "Procesā":
            return "fas fa-spinner fa-spin text-xl text-blue-600 bg-blue-100 rounded-full px-2 py-1";
        case "Atcelts":
            return "fas fa-times-circle text-xl text-red-600 bg-red-100 rounded-full px-2 py-1";
        case "Pabeigts":
            return "fas fa-check-circle text-xl text-green-600 bg-green-100 rounded-full px-2 py-1";
        case "Gaida":
            return "fas fa-exclamation-circle text-xl text-yellow-600 bg-yellow-100 rounded-full px-2 py-1";
        default:
            return "fas fa-exclamation-circle text-xl text-yellow-600 bg-yellow-100 rounded-full px-2 py-1";
    }
}

async function fetchRecentOrders() {
    if (fetchingOrders.value) return; // Prevent fetching if the flag is set
    fetchingOrders.value = true;
    try {
        const { data } = await axios.get("/admin/recent-orders");
        notifications.value = data.map((order) => ({
            icon: determineIcon(order.status),
            message: `Jauns pasūtījums #${order.id}`,
            time: dayjs(order.created_at).fromNow(),
        }));
        notificationCount.value = notifications.value.length;
        saveNotificationsToLocalStorage();
    } catch (error) {
        console.error("Kļūda, iegūstot nesenos pasūtījumus:", error);
    } finally {
        fetchingOrders.value = false; // Reset the flag
    }
}

function markAllAsRead() {
    notifications.value = [];
    notificationCount.value = 0;
    saveNotificationsToLocalStorage();
}

function saveNotificationsToLocalStorage() {
    localStorage.setItem("notifications", JSON.stringify(notifications.value));
}

function loadNotificationsFromLocalStorage() {
    const storedNotifications = localStorage.getItem("notifications");
    if (storedNotifications) {
        notifications.value = JSON.parse(storedNotifications);
        notificationCount.value = notifications.value.length;
    }
}

onMounted(() => {
    loadNotificationsFromLocalStorage();
    fetchRecentOrders();
    setInterval(fetchRecentOrders, 60000); // Check for new orders every minute
});
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
                    <div
                        class="text-base px-3 py-2 text-gray-600 dark:text-gray-300 font-semibold border-b dark:border-gray-800 uppercase"
                    >
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
                                <p
                                    class="text-gray-800 text-base text-left dark:text-white"
                                >
                                    {{ notification.message }}
                                </p>
                                <p
                                    class="text-xs text-gray-600 dark:text-gray-400"
                                >
                                    {{ notification.time }}
                                </p>
                            </div>
                        </Link>
                    </div>
                    <div
                        class="flex justify-between items-center py-2 px-3 border-t dark:border-gray-800"
                    >
                        <Link
                            href="#"
                            class="text-xs text-blue-600 hover:underline"
                            @click.prevent="markAllAsRead"
                        >
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
