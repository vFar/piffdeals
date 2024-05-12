<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Ziggy } from '@/ziggy';
import route from 'ziggy-js';
import Modal from '@/Components/Modal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import AdminNavbar from '@/Components/AdminNavbar.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';

const getImageUrl = (filename) => {
    return `/storage/images/${filename}`;
};
const flashMessage = ref('');
const addCategoryShow = ref(false);

const drawerOpen = ref(false);  // State to control the visibility of the drawer

const toggleDrawer = () => {
    drawerOpen.value = !drawerOpen.value;  // Toggle the state of the drawer
};

const closeDrawer = () => {
    drawerOpen.value = false;
};

const addCategoryModal = () => {
    addCategoryShow.value = true;
    // nextTick(() => passwordInput.value.focus());
};

const closeCategoryModal = () => {
    addCategoryShow.value = false;

    // form.reset();
};

</script>

<style scoped>
/* Ensure the full offscreen translate amount is correct */
.drawer {
    transform: translateX(100%);
}
</style>

<template>

    <Head title="KATEGORIJAS" />
    <div class="cross-patternSVGLight bg-whiter">
        <AdminNavbar currentPage="categories" />

        <!-- <section class="container mx-auto max-w-full border border-gray-200 rounded-xl bg-whiter shadow-md py-3 px-3 pl-6">
        </section> -->

        <main class="flex-1 bg-whiter ml-64"> <!-- ml-64 to offset the width of the sidebar -->
            <section class="max-w-full mx-6 border border-gray-200 rounded-xl bg-whiter shadow-md py-3 px-3 pl-6 mb-8">
                <div class="flex justify-between items-center py-2">
                    <h1 class="uppercase font-semibold text-xl text-textColor">Preču kategorijas</h1>

                    <div class="flex items-center">

                        <Link href="admin-categories/create">
                        <button
                            class="flex items-center p-3 rounded-lg text-white bg-accent uppercase font-semibold hover:bg-secondary group navbarElement">
                            pievienot preču kategoriju
                        </button>
                    </Link>
                        <!-- <Modal :show="addCategoryShow" @close="closeCategoryModal">
                            <div class="pt-6">
                                <div class="flex items-center justify-between border-b mb-4">
                                    <h3 class="text-xl p-4 uppercase">Pievienot jaunu preci</h3>
                                    <button @click="closeCategoryModal"
                                        class="m-4 bg-transparent hover:bg-gray-300 text-white p-2 rounded-xl">
                                        <i class="fas fa-xmark fa-fw text-textColor hover:text-white"></i>
                                    </button>
                                </div>

                                <div class="mb-16 px-6">
                                    <div class="mt-12">
                                        <TextInput type="text" placeholder="Kategorija" class="mt-1 block w-full"  required
                                            autofocus/>
                                        <InputError class="mt-2" />
                                    </div>
                                    <div class="mt-6">
                                        <TextInput type="text" placeholder="SKU" class="mt-1 block w-full"  required
                                            autofocus/>
                                        <InputError class="mt-2" />
                                    </div>
                                </div>

                                <div class="mx-6 py-6 border-t flex justify-end">
                                    <button @click="addGood"
                                        class="flex items-center p-3 rounded-lg text-white bg-primary uppercase font-semibold hover:bg-secondary group navbarElement">
                                        pievienot preču kategoriju
                                    </button>
                                </div>
                            </div>

                        </Modal> -->


                    </div>
                </div>

            </section>

            <section class="max-w-full mx-6 border border-gray-200 rounded-xl bg-whiter shadow-md py-3 mb-8">
                <div class="mx-auto max-w-full">
                    <!-- Start coding here -->
                    <div class="relative overflow-hidden">
                        <div
                            class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 mx-4 md:space-x-4 p-4">
                            <div class="w-full md:w-1/2">
                                <div class="flex-grow">
                                    <form class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-5 h-5 text-textColor" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 20 20">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                            </svg>
                                        </div>
                                        <input type="search" id="default-search"
                                            class="w-full py-2 pl-10 pr-4 text-sm text-textColor border border-gray-300 rounded-xl bg-white shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                                            placeholder="Meklēt" required>
                                    </form>
                                </div>
                            </div>






                            <div
                                class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                                <div class="flex items-center space-x-3 w-full md:w-auto">
                                    <div class="relative z-50">
                                        <!-- Trigger Button -->
                                        <button @click="toggleDrawer"
                                            class="w-full md:w-auto flex items-center justify-center py-2 px-4 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                                            type="button">
                                            <!-- <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true"
                                            class="h-4 w-4 mr-2 text-gray-400" viewbox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                                                clip-rule="evenodd" />
                                        </svg> -->
                                            <i class="fas fa-filter fa-fw mr-2 text-textColor"></i>
                                            Filtrēt
                                        </button>

                                        <!-- Backdrop -->
                                        <div v-show="drawerOpen" @click="closeDrawer"
                                            class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"
                                            :class="{ 'duration-300 ease-in-out': drawerOpen, 'opacity-100': drawerOpen, 'opacity-0': !drawerOpen }">
                                        </div>

                                        <!-- Drawer -->
                                        <div :class="{ 'translate-x-0': drawerOpen, 'translate-x-full': !drawerOpen }"
                                            class="fixed right-0 top-0 w-96 h-full bg-white shadow-xl transition-transform duration-300 ease-in-out">

                                            <div class="flex items-center justify-between border-b mb-4">
                                                <h3 class="text-xl p-4">Filtrēt preces</h3>
                                                <button @click="closeDrawer"
                                                    class="m-4 bg-transparent hover:bg-gray-300 text-white p-2 rounded-xl">
                                                    <i class="fas fa-xmark fa-fw text-textColor hover:text-white"></i>
                                                </button>
                                            </div>

                                            <p class="px-4">Here is some more content...</p>

                                        </div>
                                    </div>





                                </div>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-4 py-3">

                                            <Checkbox /> <span class="ml-6">ID</span>
                                        </th>
                                        <th scope="col" class="px-4 py-3">Nosaukums</th>
                                        <th scope="col" class="px-4 py-3">Statuss</th>
                                        <th scope="col" class="px-4 py-3">Preču skaits</th>
                                        <th scope="col" class="px-4 py-3">
                                            <span class="sr-only">Darbības</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="px-4 py-3">
                                            <Checkbox />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>


                    </div>
                </div>
            </section>
        </main>

    </div>
</template>
