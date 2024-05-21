<script setup>
import { Head, Link } from "@inertiajs/vue3";
import AdminNavbar from "@/Components/AdminNavbar.vue";
import dayjs from "dayjs"; // Import Day.js
import { defineProps } from "vue";
import { ref } from "vue";
import Modal from "@/Components/Modal.vue"; // Assuming this is your modal component
import PrimaryButton from "@/Components/PrimaryButton.vue"; // Assuming this is your modal component
import SecondaryButton from "@/Components/SecondaryButton.vue"; // Assuming this is your modal component

import { router } from "@inertiajs/vue3";

const getImageUrl = (filename) => {
    return `/storage/images/${filename}`;
};

const props = defineProps({
    user: Object,
});

const deleteConfirmationModal = ref(false);

const handleDeleteUser = async () => {
    const userIds = [props.user.id];  // Assuming you are deleting a single user
    try {
        await router.delete('/admin-users/delete', { data: { userIds } });
        // Redirect to the admin users page
    } catch (error) {
        alert("Failed to delete the account.");
        console.error("Deletion error:", error);
    }
};


const formatDate = (date) => {
    return dayjs(date).format("DD.MM.YYYY, HH:mm:ss");
};
</script>

<style scoped>
/* Ensure the full offscreen translate amount is correct */
.drawer {
    transform: translateX(100%);
}
</style>

<template>
    <Head title="KLIENTI" />
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
                            {{ user.email }}
                            <span
                                :class="{
                                    'text-green-500 text-base bg-green-100':
                                        user.status === 'Aktīvs',
                                    'text-red-500 text-base bg-red-100':
                                        user.status === 'Deaktivizēts',
                                }"
                                class="p-2 rounded-lg mx-2"
                            >
                                {{ user.status }}
                            </span>
                        </h1>
                        <p>{{ user.name }}</p>
                        <button
                            @click="deleteConfirmationModal = true"
                            class="w-full md:w-auto flex mt-2 items-center justify-center py-2 px-4 text-base font-medium text-textColor focus:outline-none bg-white rounded-lg hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                        >
                            <i
                                class="far fa-circle-xmark fa-fw mr-2 text-textColor"
                            ></i>
                            DZĒST KONTU
                        </button>
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

            <Modal
            :show="deleteConfirmationModal"
                @close="deleteConfirmationModal = false"
                            >
                                <template #default>
                                    <div class="pt-6">
                                        <div
                                            class="flex items-center justify-between border-b mb-4"
                                        >
                                            <h3 class="text-xl p-4 uppercase">
                                                Apstiprināt konta dzēšanu
                                            </h3>
                                            <button
                                                @click="
                                                    deleteConfirmationModal = false
                                                "
                                                type="button"
                                                class="m-4 bg-transparent hover:bg-gray-300 text-white p-2 rounded-xl"
                                            >
                                                <i
                                                    class="fas fa-xmark fa-fw text-textColor hover:text-white"
                                                ></i>
                                            </button>
                                        </div>

                                        <div class="mb-16 px-6">
                                            <div class="mt-12">
                                                <p class="text-textColor">
                                                    Vai tiešām vēlies dzēst šo kontu?
                                                </p>
                                            </div>
                                        </div>

                                        <div
                                            class="mx-6 py-6 border-t flex justify-end"
                                        >
                                            <SecondaryButton
                                            @click="deleteConfirmationModal = false"
                                                >Atcelt
                                            </SecondaryButton>
                                            <PrimaryButton
                                            @click="handleDeleteUser"
                                                class="ms-3"
                                                >Dzēst
                                            </PrimaryButton>
                                        </div>
                                    </div>
                                </template>
                            </Modal>


            <div class="flex">
                <section
                    class="w-3/4 mx-6 border border-gray-200 rounded-xl bg-whiter shadow-md py-3"
                >
                    <div class="mx-auto max-w-full">
                        <div class="relative overflow-hidden">
                            <div
                                class="relative overflow-hidden flex justify-between mx-6"
                            >
                                <div class="space-x-6">Pasūtījumi (skaits)</div>
                                <div class="space-x-6">Ienākumi (skaits)</div>
                            </div>
                            <div class="overflow-x-auto"></div>
                        </div>
                    </div>
                </section>
                <section
                    class="w-1/4 mr-6 border border-gray-200 rounded-xl bg-whiter shadow-md py-3"
                >
                    <!-- Customer Details Dropdown -->
                    <div class="flex items-center justify-between p-1.5">
                        <div class="flex w-full">
                            <p
                                class="w-full p-2.5 text-textColor text-lg font-semibold uppercase"
                            >
                                Klients
                            </p>
                            <div
                                class="flex gap-1.5 items-center justify-between px-2.5 text-primary uppercase cursor-pointer transition-all hover:underline"
                            >
                                Rediģēt
                            </div>
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
