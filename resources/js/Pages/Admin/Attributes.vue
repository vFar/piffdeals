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
import { router } from "@inertiajs/vue3";
import { Alert, Popconfirm, message } from "ant-design-vue";

const props = defineProps({
    attributes: Object,
    totalAttributes: Number,
    filters: Object,
    activeGroups: Array,
    totalUnlinkedAttributes: Number, // New prop for the count of unlinked attributes
    required: Boolean,
});

const { data: attributes, links, meta } = props.attributes;
const formatDate = (date) => dayjs(date).format("DD.MM.YYYY, HH:mm:ss");

const selectAttributeId = ref(null);
const selectedGroupId = ref(null); // Define the reactive property here

const search = ref("");
const { errors } = usePage().props;

const createAttributeForm = useForm({
    name: "",
    group_id: null, // Default to null, which is the value for "NAV"
    status: "Deaktivizēts",
});

// onMounted(() => {
//     const queryParams = new URLSearchParams(window.location.search);

//     // Set the search query from URL parameters
//     search.value = queryParams.get("search") || "";

//     // Set the selected group ID from URL parameters
//     selectedGroupId.value = queryParams.get("group_id") || null;

//     // Fetch attributes based on the current search and selected group ID
//     fetchAttributes();
// });

const addAttributeShow = ref(false);

function fetchAttributes() {
    // Assume router.get is a method to fetch data
    const params = {
        search: search.value,
        group_id: selectedGroupId.value,
    };
    router.get(route("admin.attributes.index", params));
}

function resetFilter() {
    selectAttributeId.value = null; // This will automatically hide the button
    fetchAttributes(); // Fetch all attributes without any filter
}

function openAddAttributeModal() {
    createAttributeForm.reset(); // Make sure this reset isn't affecting group_id unexpectedly
    createAttributeForm.group_id = null; // Explicitly set to null when opening the modal if needed
    addAttributeShow.value = true;
}

function closeAddAttributeModal() {
    addAttributeShow.value = false;
}

function saveAttribute() {
    createAttributeForm.post(route("admin.attributes.store"), {
        onSuccess: () => {
            fetchAttributes();
            createAttributeForm.reset(); // Make sure this reset isn't affecting group_id unexpectedly
            addAttributeShow.value = false;
        },
    });
}

const editAttributeForm = useForm({
    id: null,
    name: "",
    group_id: "",
    status: "",
});

const editAttributeShow = ref(false);

function openEditAttributeModal(group) {
    editAttributeForm.id = group.id;
    editAttributeForm.name = group.name;
    editAttributeForm.group_id = group.group_id || null; // Set to null if undefined
    editAttributeForm.status = group.status;
    editAttributeShow.value = true;
}

const statusOptions = reactive([
    { value: "Aktīvs", text: "Aktīvs" },
    { value: "Deaktivizēts", text: "Deaktivizēts" },
]);

function updateAttribute() {
    if (editAttributeForm.group_id === "NAV") {
        editAttributeForm.group_id = null; // Ensure 'NAV' is not sent as a group_id
    }
    editAttributeForm.patch(
        route("admin.attributes.update", { id: editAttributeForm.id }),
        {
            onSuccess: () => {
                router.visit(route("admin.attributes.index")); // Refresh the list
                editAttributeShow.value = false;
            },
            onError: (error) => {
                console.error("Update failed:", error);
            },
        }
    );
}

// watch(editAttributeForm.group_id, (newVal) => {
//     if (newVal === "NAV") {
//         editAttributeForm.group_id = null;
//     }
// });

function handleGroupChange(newValue) {
    const params = new URLSearchParams(window.location.search);
    if (newValue) {
        params.set("group_id", newValue); // Correct parameter to match your backend and URL handling
    } else {
        params.delete("group_id");
    }
    router.replace({
        path: window.location.pathname,
        query: params.toString(),
    });
    fetchAttributes(); // Optionally refetch attributes based on new filters
}

function handlePageChange(url) {
    const baseUrl = url.split("?")[0];
    const params = new URLSearchParams(url.split("?")[1]);

    if (search.value) {
        params.set("search", search.value);
    } else {
        params.delete("search");
    }

    if (selectAttributeId.value) {
        params.set("group_id", selectAttributeId.value);
    } else {
        params.delete("group_id");
    }

    router.visit(baseUrl + (params.toString() ? "?" + params.toString() : ""));
}

onMounted(() => {
    const urlParams = new URLSearchParams(window.location.search);
    search.value = urlParams.get("search") || "";
    selectAttributeId.value = urlParams.get("group_id") || null;
});

const showUnlinkedAttributesAlert = ref(true);

const unlinkedAttributes = computed(() => {
    return attributes.filter((attribute) => !attribute.attribute_id);
});

const handleDeleteAttribute = async (attributeId) => {
    try {
        // Use Inertia's router to send a DELETE request
        router.delete(route("admin.attributes.destroy", { id: attributeId }), {
            preserveScroll: true,
            preserveState: false,
            onSuccess: () => {
                message.success("Atribūts veiksmīgi dzēsts!");
                fetchAttributes(); // Refresh the list after deletion
            },
            onError: (errors) => {
                message.error("Neizdevās izdzēst atribūtu!");
                console.log(errors);
            },
        });
    } catch (error) {
        message.error("An error occurred while deleting the attribute.");
        console.log(error);
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
    <Head title="ATRIBŪTI" />
    <div class="cross-patternSVGLight bg-whiter">
        <AdminNavbar currentPage="attributes" />

        <!-- <section class="container mx-auto max-w-full border border-gray-200 rounded-xl bg-whiter shadow-md py-3 px-3 pl-6">
        </section> -->

        <main class="flex-1 ml-64">
            <!-- ml-64 to offset the width of the sidebar -->
            <section
                class="max-w-full mx-6 border border-gray-200 rounded-xl bg-whiter shadow-md py-3 px-3 pl-6 mb-8"
            >
                <div class="flex justify-between items-center py-2">
                    <div class="flex space-x-3 items-center">
                        <h1
                            class="uppercase font-semibold text-xl text-textColor"
                        >
                            Preču atribūti
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
                            @click="openAddAttributeModal"
                            class="flex items-center p-3 rounded-lg text-white bg-accent uppercase font-semibold hover:bg-secondary group navbarElement"
                        >
                            Pievienot atribūtu
                        </button>

                        <Modal
                            :show="addAttributeShow"
                            @close="closeAddAttributeModal"
                        >
                            <form
                                @submit.prevent="saveAttribute"
                                class="p-6 bg-white rounded-lg shadow overflow-hidden"
                            >
                                <div
                                    class="flex justify-between items-center border-b border-gray-200 mb-4 pb-4"
                                >
                                    <h3
                                        class="text-xl font-semibold text-gray-800 uppercase"
                                    >
                                        Pievienot jaunu atribūtu
                                    </h3>
                                    <button
                                        @click="closeAddAttributeModal"
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
                                        v-model="createAttributeForm.name"
                                        class="w-full border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 rounded-md shadow-sm"
                                        required
                                        autofocus
                                    />

                                    <SelectInput
                                        :includeNav="false"
                                        v-model="createAttributeForm.group_id"
                                        :options="
                                            activeGroups.map((group) => ({
                                                value: group.id,
                                                text: group.name,
                                            }))
                                        "
                                        placeholder="Izvēlies grupu"
                                        class="mt-4 w-full text-textColor"
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
                                        @click="closeAddAttributeModal"
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
                    </div>
                </div>
            </section>

            <section
                v-if="
                    props.totalUnlinkedAttributes > 0 &&
                    showUnlinkedAttributesAlert
                "
                class="max-w-full mx-6 border border-gray-200 rounded-xl shadow-md bg-whiter py-3 px-3 pl-6 mb-8"
            >
                <div class="flex justify-between items-center py-2">
                    <Alert
                        message="Brīdinājums!"
                        :description="`Kopumā ir ${props.totalUnlinkedAttributes} nesaistīti preču atribūti pa visu tabulu.`"
                        type="warning"
                        show-icon
                        closable
                        :style="{ width: '100%' }"
                        @close="showUnlinkedAttributesAlert = false"
                    />
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
                                        placeholderSearch="Meklēt"
                                        :initialQuery="props.filters.search"
                                        @update:searchQuery="search = $event"
                                        @search="fetchAttributes"
                                    />

                                    <p>
                                        {{ totalAttributes }}
                                        {{
                                            totalAttributes === 1
                                                ? "rezultāts"
                                                : "rezultāti"
                                        }}
                                    </p>
                                </div>

                                <div class="flex space-x-6 items-center">
                                    <AdminPaginator
                                        :links="props.attributes.links"
                                        @page-change="handlePageChange"
                                    />

                                    <SelectInput
                                        class="w-60 text-textColor"
                                        placeholder="Izvēlies grupu"
                                        :options="
                                            activeGroups.map((group) => ({
                                                value: group.id,
                                                text: group.name,
                                            }))
                                        "
                                        v-model="selectedGroupId"
                                        @change="handleGroupChange"
                                    />

                                    <button
                                        @click="resetFilter"
                                        class="flex items-center justify-center py-2 px-4 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                                    >
                                        <i
                                            class="fas fa-times text-textColor"
                                        ></i>
                                    </button>
                                </div>
                            </div>

                            <div
                                class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0"
                            >
                                <div
                                    class="flex items-center space-x-3 w-full md:w-auto"
                                >
                                    <div class="relative z-50">
                                        <!-- Trigger Button -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <Modal
                            :show="editAttributeShow"
                            @close="() => (editAttributeShow = false)"
                        >
                            <form @submit.prevent="updateAttribute">
                                <div class="pt-6">
                                    <div
                                        class="flex items-center justify-between border-b mb-4"
                                    >
                                        <h3 class="text-xl p-4 uppercase">
                                            Rediģēt grupu
                                        </h3>
                                        <button
                                            @click="editAttributeShow = false"
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
                                        <InputLabel
                                            for="text"
                                            value="Nosaukums"
                                            class="mb-1"
                                        />
                                        <TextInput
                                            type="text"
                                            class="w-full"
                                            placeholder="Nosaukums"
                                            v-model="editAttributeForm.name"
                                            required
                                            autofocus
                                        />
                                        <InputError
                                            class="mt-2"
                                            :message="errors.name"
                                        />
                                    </div>

                                    <div class="">
                                        <InputLabel
                                            for="text"
                                            value="Grupa"
                                            class="mt-6"
                                        />
                                        <SelectInput
                                            :includeNav="true"
                                            v-model="editAttributeForm.group_id"
                                            class="w-full"
                                            :options="
                                                activeGroups.map((c) => ({
                                                    value: c.id,
                                                    text: c.name,
                                                }))
                                            "
                                            placeholder="Izvēlies grupu"
                                        />
                                    </div>

                                    <!-- use SelectInput component here -->
                                    <div class="mb-12">
                                        <InputLabel
                                            for="text"
                                            value="Statuss"
                                            class="mt-6"
                                        />
                                        <SelectInput
                                            :options="statusOptions"
                                            v-model="editAttributeForm.status"
                                            placeholder="Izvēlies statusu"
                                            class="w-full"
                                        />
                                        <InputError
                                            class="mt-2"
                                            :message="errors.status"
                                        />
                                    </div>
                                </div>
                                <div
                                    class="mx-6 py-6 border-t flex justify-end"
                                >
                                    <SecondaryButton
                                        @click="editAttributeShow = false"
                                        >Atcelt</SecondaryButton
                                    >
                                    <PrimaryButton class="ms-3" type="submit"
                                        >Saglabāt</PrimaryButton
                                    >
                                </div>
                            </form>
                        </Modal>

                        <div class="overflow-x-auto">
                            <table
                                class="w-full text-sm text-left text-gray-500 dark:text-gray-400"
                            >
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                                >
                                    <tr>
                                        <th scope="col" class="px-4 py-3">
                                            ID
                                        </th>
                                        <th scope="col" class="px-4 py-3">
                                            Nosaukums
                                        </th>
                                        <th scope="col" class="px-4 py-3">
                                            Grupa / Kategorija
                                        </th>
                                        <th scope="col" class="px-4 py-3">
                                            Statuss
                                        </th>
                                        <th scope="col" class="px-4 py-3">
                                            Preču skaits
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-4 py-3 flex justify-end"
                                        >
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
                                        v-for="attribute in attributes"
                                        :key="attribute.id"
                                        class="hover:bg-slate-100"
                                    >
                                        <td class="px-4 py-3">
                                            {{ attribute.id }}
                                        </td>
                                        <td class="px-4 py-3 font-semibold">
                                            {{ attribute.name }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <div>
                                                <!-- Display the group name if it exists -->
                                                <template
                                                    v-if="attribute.group"
                                                >
                                                    {{ attribute.group.name }}
                                                    <!-- Display the category name if it exists within the group -->
                                                    <template
                                                        v-if="
                                                            attribute.group
                                                                .category
                                                        "
                                                    >
                                                        ({{
                                                            attribute.group
                                                                .category.name
                                                        }})
                                                    </template>
                                                    <template v-else>
                                                        (NAV)
                                                    </template>
                                                </template>
                                                <template v-else>
                                                    NAV
                                                </template>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span
                                                :class="{
                                                    'text-green-500 bg-green-100':
                                                        attribute.status ===
                                                        'Aktīvs',
                                                    'text-red-500 bg-red-100':
                                                        attribute.status ===
                                                        'Deaktivizēts',
                                                }"
                                                class="p-2 rounded-lg"
                                            >
                                                {{ attribute.status }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ attribute.goods.length }}
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            {{ formatDate(attribute.created_at)
                                            }}<br />
                                            {{
                                                formatDate(attribute.updated_at)
                                            }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <button
                                                @click="
                                                    openEditAttributeModal(
                                                        attribute
                                                    )
                                                "
                                                class="text-xl hover:bg-slate-200 rounded-lg p-2"
                                            >
                                                <i
                                                    class="fa-solid fa-pen-to-square"
                                                ></i>
                                            </button>
                                            <Popconfirm
                                                title="Vai tiešām vēlies dzēst šo preču atribūtu?"
                                                okText="Jā"
                                                cancelText="Nē"
                                                @confirm="
                                                    () =>
                                                        handleDeleteAttribute(
                                                            attribute.id
                                                        )
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
