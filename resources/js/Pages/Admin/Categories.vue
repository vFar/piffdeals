<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref, computed, watchEffect, onMounted } from "vue";
import { usePage, useForm } from "@inertiajs/vue3";
import { Ziggy } from "@/ziggy";
import route from "ziggy-js";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
import AdminNavbar from "@/Components/AdminNavbar.vue";
import TextInput from "@/Components/TextInput.vue";
import AdminSearchbar from "@/Components/AdminSearchbar.vue";
import AdminFilterDrawer from "@/Components/AdminFilterDrawer.vue";
import SelectInput from "@/Components/SelectInput.vue";
import Tooltip from "@/Components/Tooltip.vue";
import dayjs from "dayjs"; // Import Day.js
import { router } from "@inertiajs/vue3";

const addCategoryShow = ref(false);
const props = defineProps({
    categories: Object,
    totalCategories: Number,
    filters: Object,
});

const search = ref(props.filters.search);
const { errors } = usePage().props;
const statusOptions = [
    { text: "Aktīvs", value: "Aktīvs" },
    { text: "Deaktivizēts", value: "Deaktivizēts" },
];

const editCategoryForm = useForm({
    id: null,
    name: "",
    status: "",
});

const editCategoryShow = ref(false);

function openEditCategoryModal(category) {
    if (category) {
        editCategoryForm.id = category.id;
        editCategoryForm.name = category.name;
        editCategoryForm.status = category.status;
        editCategoryShow.value = true;
    } else {
        console.error("Category data is undefined");
    }
}

function saveCategory() {
    editCategoryForm.patch(
        route("admin.goods.updateCategory", { id: editCategoryForm.id }),
        {
            preserveScroll: true,
            onSuccess: () => {
                editCategoryShow.value = false;
                editCategoryForm.reset();
                fetchCategories(); // Refresh the list after update
            },
        }
    );
}

const addCategoryModal = () => {
    addCategoryShow.value = true;
    // nextTick(() => passwordInput.value.focus());
};

const closeCategoryModal = () => {
    addCategoryShow.value = false;
    createCategoryForm.reset();

    // form.reset();
};

const createCategoryForm = useForm({
    name: "",
    status: "Deaktivizēts",
});

const createCategorySubmitForm = () => {
    createCategoryForm.post(route("admin.goods.storeCategory"), {
        onSuccess: () => {
            addCategoryShow.value = false;
            createCategoryForm.reset();
            fetchCategories(); // Refresh the list
        },
    });
};

// Function to fetch categories
const fetchCategories = () => {
    router.get(route("admin.categories.list"), { search: search.value });
};

const formatDate = (date) => dayjs(date).format("DD.MM.YYYY, HH:mm:ss");
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

        <main class="flex-1 bg-whiter ml-64">
            <!-- ml-64 to offset the width of the sidebar -->
            <section
                class="max-w-full mx-6 border border-gray-200 rounded-xl bg-whiter shadow-md py-3 px-3 pl-6 mb-8"
            >
                <div class="flex justify-between items-center py-2">
                    <div class="flex space-x-3 items-center">
                        <h1
                            class="uppercase font-semibold text-xl text-textColor"
                        >
                            Preču kategorijas
                        </h1>
                        <Tooltip
                            content="Preču hierarhija: Kategorija > Grupa > Atribūts > Prece"
                        >
                            <button
                                class="text-white bg-transparent hover:bg-gray-100 rounded-lg text-base px-2 py-1 text-center"
                            >
                                <i
                                    class="fa-regular fa-circle-question fa-fw text-textColor"
                                ></i>
                            </button>
                        </Tooltip>
                    </div>

                    <div class="flex items-center">
                        <button
                            @click="addCategoryModal"
                            class="flex items-center p-3 rounded-lg text-white bg-accent uppercase font-semibold hover:bg-secondary group navbarElement"
                        >
                            Pievienot kategoriju
                        </button>

                        <Modal
                            :show="addCategoryShow"
                            @close="closeCategoryModal"
                        >
                            <form
                                @submit.prevent="createCategorySubmitForm"
                                class="p-6 bg-white rounded-lg shadow overflow-hidden"
                            >
                                <div
                                    class="flex justify-between items-center border-b border-gray-200 mb-4 pb-4"
                                >
                                    <h3
                                        class="text-xl font-semibold text-gray-800 uppercase"
                                    >
                                        Pievienot jaunu kategoriju
                                    </h3>
                                    <button
                                        @click="closeCategoryModal"
                                        type="button"
                                        class="m-4 bg-transparent hover:bg-gray-300 text-white p-2 rounded-xl"
                                    >
                                        <i
                                            class="fas fa-xmark fa-fw text-textColor hover:text-white"
                                        ></i>
                                    </button>
                                </div>
                                <div class="space-y-6">
                                    <TextInput
                                        type="text"
                                        placeholder="Nosaukums"
                                        v-model="createCategoryForm.name"
                                        class="w-full border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 rounded-md shadow-sm"
                                        required
                                        autofocus
                                    />
                                    <!-- Only display InputError if there is a message for 'name' -->
                                    <InputError
                                        v-if="errors && errors.name"
                                        :message="errors.name"
                                        class="text-red-500 text-sm"
                                    />
                                </div>
                                <div
                                    class="flex justify-end space-x-3 mt-6 border-t border-gray-200 pt-4"
                                >
                                    <SecondaryButton
                                        @click="closeCategoryModal"
                                        class="bg-gray-200 text-gray-800 hover:bg-gray-300"
                                    >
                                        Atcelt
                                    </SecondaryButton>
                                    <PrimaryButton
                                        type="submit"
                                        class="bg-blue-600 text-white hover:bg-blue-700"
                                    >
                                        Pievienot
                                    </PrimaryButton>
                                </div>
                            </form>
                        </Modal>

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

            <section
                class="max-w-full mx-6 border border-gray-200 rounded-xl bg-whiter shadow-md py-3 mb-8"
            >
                <div class="mx-auto max-w-full">
                    <!-- Start coding here -->
                    <div class="relative overflow-hidden">
                        <div
                            class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 mx-4 md:space-x-4 p-4"
                        >
                            <div class="w-full md:w-1/2">
                                <div class="flex space-x-6 items-center">
                                    <!-- <form class="relative">
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
                                    </form> -->
                                    <AdminSearchbar
                                        :initialQuery="props.filters.search"
                                        @update:searchQuery="search = $event"
                                        @search="fetchCategories"
                                    />

                                    <p>
                                        {{ totalCategories }}
                                        {{
                                            totalCategories === 1
                                                ? "rezultāts"
                                                : "rezultāti"
                                        }}
                                    </p>
                                </div>
                            </div>

                            <div
                                class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0"
                            >
                                <div
                                    class="flex items-center space-x-3 w-full md:w-auto"
                                >
                                    <AdminFilterDrawer />
                                </div>
                            </div>
                        </div>

                        <Modal
                            :show="editCategoryShow"
                            @close="editCategoryShow = false"
                        >
                            <form @submit.prevent="saveCategory">
                                <div class="pt-6">
                                    <div
                                        class="flex items-center justify-between border-b mb-4"
                                    >
                                        <h3 class="text-xl p-4 uppercase">
                                            Rediģēt kategoriju
                                        </h3>
                                        <button
                                            @click="editCategoryShow = false"
                                            type="button"
                                            class="m-4 bg-transparent hover:bg-gray-300 text-white p-2 rounded-xl"
                                        >
                                            <i
                                                class="fas fa-xmark fa-fw text-textColor hover:text-white"
                                            ></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-16 px-6">
                                    <div class="mt-12">
                                        <TextInput
                                            type="text"
                                            class="w-full"
                                            placeholder="Category Name"
                                            v-model="editCategoryForm.name"
                                            required
                                            autofocus
                                        />
                                        <InputError
                                            class="mt-2"
                                            :message="errors.name"
                                        />
                                    </div>

                                    <!-- use SelectInput component here -->
                                    <SelectInput
                                        v-model="editCategoryForm.status"
                                        :options="statusOptions"
                                        class="mt-4 w-full"
                                    />
                                </div>
                                <div
                                    class="mx-6 py-6 border-t flex justify-end"
                                >
                                    <SecondaryButton
                                        @click="editCategoryShow = false"
                                        >Atcelt</SecondaryButton
                                    >
                                    <PrimaryButton
                                        :disabled="!editCategoryForm.isDirty"
                                        class="ms-3"
                                        type="submit"
                                        >Saglabāt</PrimaryButton
                                    >
                                </div>
                            </form>
                        </Modal>

                        <div class="overflow-x-auto">
                            <table
                                class="w-full text-sm text-left text-gray-500"
                            >
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50"
                                >
                                    <tr>
                                        <th scope="col" class="px-4 py-3">
                                            ID
                                        </th>
                                        <th scope="col" class="px-4 py-3">
                                            Nosaukums
                                        </th>
                                        <th scope="col" class="px-4 py-3">
                                            Statuss
                                        </th>
                                        <th scope="col" class="px-4 py-3">
                                            Izveidots / Modificēts
                                        </th>
                                        <th scope="col" class="px-4 py-3">
                                            Preču skaits
                                        </th>
                                        <th scope="col" class="px-4 py-3">
                                            <span class="sr-only"
                                                >Darbības</span
                                            >
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="category in props.categories
                                            .data"
                                        :key="category.id"
                                        class="hover:bg-slate-100"
                                    >
                                        <td class="px-4 py-3">
                                            {{ category.id }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <span
                                                class="font-semibold text-md text-textColor"
                                                >{{ category.name }}</span
                                            >
                                        </td>
                                        <td class="px-4 py-3">
                                            <span
                                                :class="{
                                                    'text-green-500 bg-green-100':
                                                        category.status ===
                                                        'Aktīvs',
                                                    'text-red-500 bg-red-100':
                                                        category.status ===
                                                        'Deaktivizēts',
                                                }"
                                                class="p-2 rounded-lg"
                                            >
                                                {{ category.status }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            {{
                                                formatDate(category.created_at)
                                            }}
                                            <br />
                                            {{
                                                formatDate(category.updated_at)
                                            }}
                                        </td>
                                        <td class="px-4 py-3">Preču skaits</td>
                                        <td class="px-4 py-3">
                                            <div
                                                class="space-x-3 flex justify-center"
                                            >
                                                <button
                                                    @click="
                                                        openEditCategoryModal(
                                                            category
                                                        )
                                                    "
                                                    class="text-xl hover:bg-slate-200 rounded-lg p-2"
                                                >
                                                    <i
                                                        class="fa-solid fa-pen-to-square fa-fw"
                                                    ></i>
                                                </button>

                                                <button
                                                    class="text-xl hover:bg-slate-200 rounded-lg p-2"
                                                >
                                                    <i
                                                        class="fas fa-trash fa-fw"
                                                    ></i>
                                                </button>
                                            </div>
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
