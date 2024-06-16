<script setup>
import { Head, Link } from "@inertiajs/vue3";
import AdminNavbar from "@/Components/AdminNavbar.vue";
import dayjs from "dayjs"; // Import Day.js
import { Image } from "ant-design-vue";


const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
    order: {
        type: Object,
        required: true,
    },
    totalRevenue: {
        type: Number,
        default: 0, // Default value as zero
    },
});

const formatDate = (date) => {
    return dayjs(date).format("DD.MM.YYYY, HH:mm:ss");
};

const getStatusClass = (status) => {
    switch (status) {
        case "Gaida":
            return "text-amber-500 bg-orange-100";
        case "Atcelts":
            return "text-red-600 bg-red-100";
        case "Procesā":
            return "text-blue-400 bg-blue-100";
        case "Pabeigts":
            return "text-green-400 bg-green-100";
        default:
            return "text-gray-500 bg-gray-100"; // Default styling for other statuses
    }
};
</script>

<style scoped>
/* Ensure the full offscreen translate amount is correct */
.drawer {
    transform: translateX(100%);
}
</style>

<template>
    <Head title="PASŪTĪJUMS" />
    <div class="cross-patternSVGLight bg-whiter">
        <AdminNavbar currentPage="users" />
        <main class="flex-1 ml-64">
            <section
                class="max-w-full mx-6 border border-gray-200 rounded-xl bg-whiter shadow-md py-3 px-3 pl-6 mb-8"
            >
                <div class="flex justify-between items-center py-2">
                    <div>
                        <h1
                            class="uppercase font-semibold text-xl text-textColor"
                        >
                            #{{ order.id }}
                        </h1>
                        <p>{{ order.order_date }}</p>
                        <span class="px-2 py-1 rounded-lg inline-block" :class="getStatusClass(order.status)">{{ order.status }}</span>
                    </div>

                    <div class="flex items-center">
                        <Link
                            href="/admin-users"
                            class="w-full md:w-auto flex items-center justify-center py-2 px-4 text-sm font-medium text-textColor focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                        >
                            <button>
                                <i class="fa-solid fa-caret-left fa-fw"></i>
                                ATPAKAĻ
                            </button>
                        </Link>
                    </div>
                </div>
            </section>

            <div class="flex">
                <section
                    class="w-3/4 mx-6 border border-gray-200 rounded-xl bg-whiter shadow-md py-3"
                >
                    <div class="mx-auto max-w-full">
                        <div class="relative overflow-hidden">
                            <div
                                class="relative overflow-hidden flex justify-end mx-6 font-semibold text-textColor"
                            >
                                <div class="space-x-6">
                                    Ienākumi - {{ totalRevenue.toFixed(2) }} € 
                                </div>
                            </div>
                            <div class="overflow-x-auto mt-4">
                                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                    <tbody>
                                        <tr
                                            v-for="item in order.items"
                                            :key="item.id"
                                            class="hover:bg-slate-100"
                                        >
                                            <td class="px-6 py-3 flex items-center space-x-4 border-b border-gray-200">
                                                <Image
                                                :src="
                                                    item.good.image ||
                                                    '/images/S-1.png'
                                                "
                                                fallback="/images/S-1.png"
                                                alt="Good image"
                                                style="
                                                    width: 70px;
                                                    height: 70px;
                                                "
                                                class="mr-3 cursor-pointer rounded-md shadow-md"
                                                previewMask="Priekšskatījums"
                                            />
                                                <span>{{ item.good.name }}</span>
                                            </td>
                                            <td class="px-6 py-3 border-b border-gray-200">
                                                {{ item.quantity }}
                                            </td>
                                            <td class="px-6 py-3 border-b border-gray-200 font-semibold">
                                                {{ (item.quantity * item.price).toFixed(2) }} €
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
                <section
                    class="w-1/4 mr-6 border border-gray-200 rounded-xl bg-whiter shadow-md py-3 h-[250px] overflow-auto"
                >
                    <div class="flex items-center justify-between p-1.5">
                        <div class="flex w-full">
                            <p
                                class="w-full p-2.5 text-textColor text-lg font-semibold uppercase"
                            >
                                Klients
                            </p>
                        </div>
                        <span
                            class="text-2xl p-1.5 rounded-md cursor-pointer transition-all hover:bg-gray-100 dark:hover:bg-gray-950 icon-arrow-up"
                        ></span>
                    </div>
                    <div class="px-4 pb-4">
                        <div class="grid gap-y-2.5">
                            <p class="text-textColor font-semibold">
                                {{ user.name }}
                            </p>
                            <p class="text-textColor text-base">
                                E-pasts - {{ user.email }}
                            </p>
                            <p class="text-textColor text-base">
                                Telefons - {{ user.phone }}
                            </p>
                            <p class="text-textColor text-base">
                                Izveidots - {{ formatDate(user.created_at) }}
                            </p>
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </div>
</template>
