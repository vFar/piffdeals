<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref, onMounted, nextTick, reactive, watch, computed } from "vue";
import { usePage, useForm } from "@inertiajs/vue3";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import InputError from "@/Components/InputError.vue";
import AdminNavbar from "@/Components/AdminNavbar.vue";
import InputLabel from "@/Components/InputLabel.vue";
import AdminSearchbar from "@/Components/AdminSearchbar.vue";
import AdminPaginator from "@/Components/AdminPaginator.vue";
import Tooltip from "@/Components/Tooltip.vue";
import dayjs from "dayjs"; // Import Day.js
import { message, Popconfirm, Image } from "ant-design-vue";
import { router } from "@inertiajs/vue3";

// Define properties and reactive data specific to goods
const props = defineProps({
    goods: Object, // Assumed data structure for goods
    totalGoods: Number,
    filters: Object,
    activeCategories: Array, // Assuming categories replace groups
    activeGroups: Array, // Assuming categories replace groups
    activeAttributes: Array,
});

const { errors } = usePage().props;
const addGoodShow = ref(false);

const createGoodForm = useForm({
    sku: "",
    group_id: null,
    attribute_id: null,
    status: "Deaktivizēts",
});

function saveGood() {
    createGoodForm.post(route("admin.goods.store"), {
        preserveScroll: true,
        onSuccess: (page) => {
            // Check both 'flash' and 'flash.url' before trying to access 'url'
            if (page.props.flash && page.props.flash.url) {
                router.replace(page.props.flash.url);
            } else {
                addGoodShow.value = false; // Close modal or handle the else case
            }
        },
    });
}

let search = ref(props.filters.search || "");

watch(search, (newValue) => {
    router.visit(route("admin.goods.index"), {
        method: "get",
        data: { search: newValue },
        preserveState: true,
        replace: true,
    });
});

function editGood(id) {
    router.visit(route("admin.goods.edit", { id }));
}

function openAddGoodModal() {
    createGoodForm.reset(); // Resets the form fields to initial values
    addGoodShow.value = true; // Opens the modal
}

// Function to close the modal
function closeAddGoodModal() {
    addGoodShow.value = false; // Closes the modal
}

const formatDate = (date) => dayjs(date).format("DD.MM.YYYY, HH:mm:ss");

const deleteGood = async (id) => {
    try {
        await router.delete(route("admin.goods.destroy", { id }), {
            onSuccess: () => {
                message.success("Prece veiksmīgi dzēsta!");
            },
            onError: (error) => {
                message.error("Neizdevās dzēst preci.");
            },
        });
    } catch (error) {
        message.error("Neizdevās dzēst preci.");
    }
};

const previewGood = (goodId) => {
    router.visit(route("goods.show", { id: goodId }));
};
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
        <AdminNavbar currentPage="goods" />

        <!-- <section class="container mx-auto max-w-full border border-gray-200 rounded-xl bg-whiter shadow-md py-3 px-3 pl-6">
        </section> -->

        <main class="flex-1 ml-64">
            <!-- ml-64 to offset the width of the sidebar -->
            <section
                class="max-w-full mx-6 border border-gray-200 rounded-xl bg-whiter shadow-md py-3 px-3 pl-6 mb-8"
            >
                <div class="flex justify-between items-center py-2">
                    <div class="flex space-x-3">
                        <h1
                            class="uppercase font-semibold text-xl text-textColor"
                        >
                            Visas Preces
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
                            class="w-full md:w-auto flex items-center justify-center py-2 px-4 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 mx-8"
                        >
                            <i
                                class="fas fa-download py-1 mr-2 fa-fw text-textColor"
                            ></i>
                            Eksportēt
                        </button>

                        <button
                            @click="openAddGoodModal"
                            class="flex items-center p-3 rounded-lg text-white bg-accent uppercase font-semibold hover:bg-secondary group navbarElement"
                        >
                            pievienot preci
                        </button>

                        <Modal :show="addGoodShow" @close="closeAddGoodModal">
                            <form
                                @submit.prevent="saveGood"
                                class="p-6 bg-white rounded-lg shadow overflow-hidden"
                            >
                                <div
                                    class="flex justify-between items-center border-b border-gray-200 mb-4 pb-4"
                                >
                                    <h3
                                        class="text-xl font-semibold text-gray-800 uppercase"
                                    >
                                        Pievienot jaunu preci
                                    </h3>
                                    <button
                                        @click="closeAddGoodModal"
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
                                        placeholder="SKU"
                                        v-model="createGoodForm.sku"
                                        class="w-full border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 rounded-md shadow-sm"
                                        required
                                        autofocus
                                    />

                                    <SelectInput
                                        :includeNav="false"
                                        v-model="createGoodForm.attribute_id"
                                        :options="
                                            activeAttributes.map(
                                                (attribute) => ({
                                                    value: attribute.id,
                                                    text: attribute.name,
                                                })
                                            )
                                        "
                                        placeholder="Izvēlies atribūtu"
                                        class="mt-4 w-full text-textColor"
                                    />

                                    <SelectInput
                                        :includeNav="false"
                                        v-model="createGoodForm.group_id"
                                        :options="
                                            activeGroups.map((group) => ({
                                                value: group.id,
                                                text: group.name,
                                            }))
                                        "
                                        placeholder="Izvēlies grupu"
                                        class="mt-4 w-full text-textColor"
                                    />

                                    <InputError
                                        v-if="errors.sku"
                                        :message="errors.sku"
                                        class="text-red-500 text-sm"
                                    />
                                </div>
                                <div
                                    class="flex justify-end space-x-3 mt-6 border-t border-gray-200 pt-4"
                                >
                                    <SecondaryButton
                                        @click="closeAddGoodModal"
                                        class="bg-gray-200 text-gray-800 hover:bg-gray-300"
                                        >Atcelt</SecondaryButton
                                    >
                                    <PrimaryButton
                                        type="submit"
                                        class="bg-blue-600 text-white hover:bg-blue-700"
                                        >Pievienot</PrimaryButton
                                    >
                                </div>
                            </form>
                        </Modal>
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
                            class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 mx-4 p-4"
                        >
                            <div class="w-full flex justify-between">
                                <div class="flex space-x-6 items-center">
                                    <AdminSearchbar
                                        :initialQuery="props.filters.search"
                                        @update:searchQuery="search = $event"
                                        placeholderSearch="Meklēt"
                                    />

                                    <p>
                                        {{ totalGoods }}
                                        {{
                                            totalGoods === 1
                                                ? "rezultāts"
                                                : "rezultāti"
                                        }}
                                    </p>
                                </div>

                                <div class="flex space-x-6 items-center">
                                    <AdminPaginator
                                        :links="props.goods.links"
                                    />
                                </div>
                            </div>

                            <div
                                class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0"
                            >
                                <div
                                    class="flex items-center space-x-3 w-full md:w-auto"
                                >
                                    <!-- <div class="relative z-50">
                                        <button @click="toggleDrawer"
                                            class="w-full md:w-auto flex items-center justify-center py-2 px-4 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                                            type="button">
                                            <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true"
                                            class="h-4 w-4 mr-2 text-gray-400" viewbox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                                                clip-rule="evenodd" />
                                        </svg>
                                            <i class="fas fa-filter fa-fw mr-2 text-textColor"></i>
                                            Filtrēt
                                        </button>

                                        <div v-show="drawerOpen" @click="closeDrawer"
                                            class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"
                                            :class="{ 'duration-300 ease-in-out': drawerOpen, 'opacity-100': drawerOpen, 'opacity-0': !drawerOpen }">
                                        </div>

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
                                    </div> -->
                                </div>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table
                                class="w-full text-sm text-left text-gray-500 dark:text-gray-400"
                            >
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                                >
                                    <tr>
                                        <th scope="col" class="px-4 py-3">
                                            <span
                                                >PRECES NOSAUKUMS / SKU /
                                                KATEGORIJA</span
                                            >
                                        </th>
                                        <th scope="col" class="px-4 py-3">
                                            Attēls / Cena / Skaits / ID
                                        </th>
                                        <th scope="col" class="px-4 py-3">
                                            Statuss
                                        </th>
                                        <th scope="col" class="px-4 py-3">
                                            Atribūts / Grupa
                                        </th>
                                        <th scope="col" class="px-4 py-3">
                                            Izveidots / Modificēts
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
                                        v-for="good in goods.data"
                                        :key="good.id"
                                    >
                                        <td class="px-4 py-3">
                                            <span
                                                class="text-xl font-semibold text-textColor"
                                                >{{ good.name }}</span
                                            >
                                            <br />
                                            SKU - {{ good.sku }} <br />
                                            Kategorija -
                                            {{
                                                good.group &&
                                                good.group.category
                                                    ? good.group.category.name
                                                    : "NAV"
                                            }}
                                        </td>
                                        <td class="px-4 py-3 flex">
                                            <Image
                                                :src="
                                                    good.image ||
                                                    '/images/S-1.png'
                                                "
                                                fallback="/images/S-1.png"
                                                alt="Good image"
                                                style="
                                                    width: 60px;
                                                    height: 60px;
                                                "
                                                :preview="false"
                                                class="mr-3"
                                            />
                                            € {{ good.price }} <br />
                                            Skaits - {{ good.stock_quantity }}
                                            <br />
                                            ID -
                                            {{ good.id }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <span
                                                :class="{
                                                    'text-green-500 bg-green-100':
                                                        good.status ===
                                                        'Aktīvs',
                                                    'text-red-500 bg-red-100':
                                                        good.status ===
                                                        'Deaktivizēts',
                                                }"
                                                class="p-2 rounded-lg"
                                            >
                                                {{ good.status }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            {{
                                                good.attribute &&
                                                good.attribute.name
                                                    ? good.attribute.name
                                                    : "NAV"
                                            }}
                                            /
                                            {{
                                                good.group && good.group.name
                                                    ? good.group.name
                                                    : "NAV"
                                            }}
                                        </td>

                                        <td class="px-4 py-3">
                                            {{ formatDate(good.created_at)
                                            }}<br />
                                            {{ formatDate(good.updated_at) }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <button
                                                @click="previewGood(good.id)"
                                                class="text-xl hover:bg-slate-200 rounded-lg p-2"
                                            >
                                                <i
                                                    class="fa-solid fa-magnifying-glass fa-fw"
                                                ></i>
                                            </button>
                                            <Popconfirm
                                                title="Vai tiešām vēlaties dzēst šo preci?"
                                                okText="Jā"
                                                cancelText="Nē"
                                                @confirm="
                                                    () => deleteGood(good.id)
                                                "
                                            >
                                                <button
                                                    class="text-xl hover:bg-slate-200 rounded-lg p-2"
                                                >
                                                    <i
                                                        class="fa-solid fa-trash fa-fw"
                                                    ></i>
                                                </button>
                                            </Popconfirm>

                                            <button
                                                @click="editGood(good.id)"
                                                class="text-xl hover:bg-slate-200 rounded-lg p-2"
                                            >
                                                <i
                                                    class="fas fa-caret-right fa-fw"
                                                ></i>
                                            </button>
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
