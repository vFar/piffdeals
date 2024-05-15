<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref, computed, onMounted } from "vue";
import { usePage, useForm } from "@inertiajs/vue3";
import { Ziggy } from "@/ziggy";
import route from "ziggy-js";
import Modal from "@/Components/Modal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import AdminNavbar from "@/Components/AdminNavbar.vue";
import AdminPaginator from "@/Components/AdminPaginator.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import Checkbox from "@/Components/Checkbox.vue";
import dayjs from "dayjs"; // Import Day.js
import { defineProps } from 'vue';

const getImageUrl = (filename) => {
    return `/storage/images/${filename}`;
};

const props = defineProps({
  user: Object,
});
</script>

<style scoped>
/* Ensure the full offscreen translate amount is correct */
.drawer {
    transform: translateX(100%);
}
</style>

<template>
    <Head title="PRECES" />
    <div class="cross-patternSVGLight bg-whiter">
        <AdminNavbar currentPage="users" />

        <!-- <section class="container mx-auto max-w-full border border-gray-200 rounded-xl bg-whiter shadow-md py-3 px-3 pl-6">
        </section> -->

        <main class="flex-1 bg-whiter ml-64">
            <!-- ml-64 to offset the width of the sidebar -->
            <section
                class="max-w-full mx-6 border border-gray-200 rounded-xl bg-whiter shadow-md py-3 px-3 pl-6 mb-8"
            >
                <div class="flex justify-between items-center py-2">
                    <h1 class="uppercase font-semibold text-xl bg-red-600 text-textColor">
                        Klientu konti
                    </h1>

                    <div class="flex items-center">
                        <button
                            @click="createUserModal"
                            class="flex items-center p-3 rounded-lg text-white bg-accent uppercase font-semibold hover:bg-secondary group navbarElement"
                        >
                            Izveidot kontu
                        </button>
                    </div>
                </div>
            </section>

            <div>
    <h1>User Details</h1>
    <p><strong>Name:</strong> {{ user.name }}</p>
    <p><strong>Email:</strong> {{ user.email }}</p>
    <p><strong>Created At:</strong> {{ user.created_at }}</p>
    <!-- Add more fields as necessary -->
  </div>

            <section
                class="max-w-full mx-6 border border-gray-200 rounded-xl bg-whiter shadow-md py-3"
            >
                <div class="mx-auto max-w-full">
                    <!-- Start coding here -->
                    <div class="relative overflow-hidden">
                        <div
                            class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 mx-4 p-4"
                        >
                            <div class="relative overflow-hidden">
                                <div
                                    v-if="isAnySelected"
                                    class="flex space-x-6 items-center"
                                >
                                    <select
                                        v-model="actionSelected"
                                        @change="applyAction"
                                        class="rounded-lg border-gray-300 text-sm text-gray-700"
                                    >
                                        <option disabled value="">
                                            Izvēlēties darbību
                                        </option>
                                        <optgroup label="Statuss">
                                            <option value="active">
                                                Set Active
                                            </option>
                                            <option value="inactive">
                                                Set Inactive
                                            </option>
                                        </optgroup>
                                        <option value="delete">Delete</option>
                                    </select>

                                    <div>
                                        <p>{{ selectionText }}</p>
                                    </div>
                                </div>

                                <div v-else class="flex space-x-6 items-center">
                                    <form class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                                        >
                                            <svg
                                                class="w-5 h-5 text-textColor"
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 20 20"
                                            >
                                                <path
                                                    stroke="currentColor"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"
                                                />
                                            </svg>
                                        </div>
                                        <TextInput
                                            type="search"
                                            placeholder="Meklēt"
                                            required
                                            class="w-[300px] py-2 pl-10 pr-4 text-sm text-textColor rounded-xl bg-white"
                                        />
                                    </form>
                                </div>
                            </div>

                            <div
                                class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0"
                            >
                                <div
                                    class="flex items-center space-x-6 w-full md:w-auto"
                                >
                                    <!-- PAGINATOR -->

                                    <div class="relative z-50">
                                        <!-- Trigger Button -->
                                        <button
                                            @click="toggleDrawer"
                                            class="w-full md:w-auto flex items-center justify-center py-2 px-4 text-sm font-medium text-textColor focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                                            type="button"
                                        >
                                            <!-- <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true"
                                            class="h-4 w-4 mr-2 text-gray-400" viewbox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                                                clip-rule="evenodd" />
                                        </svg> -->
                                            <i
                                                class="fas fa-filter fa-fw mr-2 text-textColor"
                                            ></i>
                                            Filtrēt
                                        </button>

                                       
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                           
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</template>
