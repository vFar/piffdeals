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
import { Alert, message, Popconfirm } from "ant-design-vue";

const props = defineProps({
    groups: Object,
    totalGroups: Number,
    filters: Object,
    activeCategories: Array,
    totalUnlinkedGroups: Number, // New prop for the count of unlinked groups
    required: Boolean,
});

const { data: groups, links, meta } = props.groups;
const formatDate = (date) => dayjs(date).format("DD.MM.YYYY, HH:mm:ss");

const selectedCategoryId = ref(null);

// watch(selectedCategoryId, (newValue, oldValue) => {
//     if (newValue !== oldValue) {
//         fetchGroups();
//     }
// });

const search = ref(props.filters.search || "");
const { errors } = usePage().props;

const createGroupForm = useForm({
    name: "",
    category_id: null, // Default to null, which is the value for "NAV"
    status: "Deaktivizēts",
});

const addGroupShow = ref(false);

function fetchGroups() {
    const params = {
        search: search.value, // Include other filters if necessary
        category_id: selectedCategoryId.value,
    };
    router.get(route("admin.groups.index", params));
}

function resetFilter() {
    selectedCategoryId.value = null; // This will automatically hide the button
    fetchGroups(); // Fetch all groups without any filter
}

function openAddGroupModal() {
    createGroupForm.reset(); // Make sure this reset isn't affecting category_id unexpectedly
    createGroupForm.category_id = null; // Explicitly set to null when opening the modal if needed
    addGroupShow.value = true;
}

function closeAddGroupModal() {
    addGroupShow.value = false;
}

function saveGroup() {
    createGroupForm.post(route("admin.groups.store"), {
        onSuccess: () => {
            fetchGroups();
            createGroupForm.reset(); // Make sure this reset isn't affecting category_id unexpectedly
            addGroupShow.value = false;
        },
    });
}

const editGroupForm = useForm({
    id: null,
    name: "",
    category_id: "",
    status: "",
});

const editGroupShow = ref(false);

function openEditGroupModal(group) {
    editGroupForm.id = group.id;
    editGroupForm.name = group.name;
    editGroupForm.category_id = group.category_id || null; // Set to null if undefined
    editGroupForm.status = group.status;
    editGroupShow.value = true;
}

const statusOptions = reactive([
    { value: "Aktīvs", text: "Aktīvs" },
    { value: "Deaktivizēts", text: "Deaktivizēts" },
]);

function updateGroup() {
    if (editGroupForm.category_id === "NAV") {
        editGroupForm.category_id = null;
    }

    editGroupForm.patch(
        route("admin.groups.update", { id: editGroupForm.id }),
        {
            onSuccess: () => {
                // Using router to reload the page or redirect
                router.visit(route("admin.groups.index")); // Refreshing the list by navigating back to the index
                editGroupShow.value = false;
            },
            onError: (error) => {
                console.error("Update failed:", error);
            },
        }
    );
}

// watch(editGroupForm.category_id, (newVal) => {
//     if (newVal === "NAV") {
//         editGroupForm.category_id = null;
//     }
// });

function handleCategoryChange(newValue) {
    // This will handle changes and apply them to the URL directly
    const params = new URLSearchParams(window.location.search);
    if (newValue) {
        params.set("category_id", newValue);
    } else {
        params.delete("category_id");
    }
    router.replace({
        path: window.location.pathname,
        query: params.toString(),
    });
    fetchGroups(); // Optionally refetch groups based on new filters
}

function handlePageChange(url) {
    const baseUrl = url.split("?")[0];
    const params = new URLSearchParams(url.split("?")[1]);

    if (search.value) {
        params.set("search", search.value);
    } else {
        params.delete("search");
    }

    if (selectedCategoryId.value) {
        params.set("category_id", selectedCategoryId.value);
    } else {
        params.delete("category_id");
    }

    router.visit(baseUrl + (params.toString() ? "?" + params.toString() : ""));
}

onMounted(() => {
    const urlParams = new URLSearchParams(window.location.search);
    search.value = urlParams.get("search") || "";
    selectedCategoryId.value = urlParams.get("category_id") || null;
});

const showUnlinkedGroupsAlert = ref(true);

const unlinkedGroups = computed(() => {
    return groups.filter((group) => !group.category_id);
});

const handleDeleteGroup = async (groupId) => {
    try {
        await router.delete(route("admin.groups.destroy", { id: groupId }));
        message.success('Grupa veiksmīgi dzēsta');
        fetchGroups();
    } catch (error) {
        message.error('Kļūda dzēšot grupu');
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
    <Head title="GRUPAS" />
    <div class="cross-patternSVGLight bg-whiter">
        <AdminNavbar currentPage="groups" />

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
                            Preču grupas
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
                            @click="openAddGroupModal"
                            class="flex items-center p-3 rounded-lg text-white bg-accent uppercase font-semibold hover:bg-secondary group navbarElement"
                        >
                            Pievienot grupu
                        </button>

                        <Modal :show="addGroupShow" @close="closeAddGroupModal">
                            <form
                                @submit.prevent="saveGroup"
                                class="p-6 bg-white rounded-lg shadow overflow-hidden"
                            >
                                <div
                                    class="flex justify-between items-center border-b border-gray-200 mb-4 pb-4"
                                >
                                    <h3
                                        class="text-xl font-semibold text-gray-800 uppercase"
                                    >
                                        Pievienot jaunu grupu
                                    </h3>
                                    <button
                                        @click="closeAddGroupModal"
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
                                        v-model="createGroupForm.name"
                                        class="w-full border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 rounded-md shadow-sm"
                                        required
                                        autofocus
                                    />

                                    <SelectInput
                                        :includeNav="false"
                                        v-model="createGroupForm.category_id"
                                        :options="
                                            activeCategories.map(
                                                (category) => ({
                                                    value: category.id,
                                                    text: category.name,
                                                })
                                            )
                                        "
                                        placeholder="Izvēlies kategoriju"
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
                                        @click="closeAddGroupModal"
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
                v-if="props.totalUnlinkedGroups > 0 && showUnlinkedGroupsAlert"
                class="max-w-full mx-6 border border-gray-200 rounded-xl shadow-md bg-whiter py-3 px-3 pl-6 mb-8"
            >
                <div class="flex justify-between items-center py-2">
                    <Alert
                        message="Brīdinājums!"
                        :description="`Kopumā ir ${props.totalUnlinkedGroups} nesaistītas preču grupas pa visu tabulu.`"
                        type="warning"
                        show-icon
                        closable
                        :style="{ width: '100%' }"
                        @close="showUnlinkedGroupsAlert = false"
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
                                        @search="fetchGroups"
                                    />

                                    <p>
                                        {{ totalGroups }}
                                        {{
                                            totalGroups === 1
                                                ? "rezultāts"
                                                : "rezultāti"
                                        }}
                                    </p>
                                </div>

                                <div class="flex space-x-6 items-center">
                                    <AdminPaginator
                                        :links="props.groups.links"
                                        @page-change="handlePageChange"
                                    />

                                    <SelectInput
                                        class="w-60 text-textColor"
                                        placeholder="Izvēlies kategoriju"
                                        :options="
                                            activeCategories.map(
                                                (category) => ({
                                                    value: category.id,
                                                    text: category.name,
                                                })
                                            )
                                        "
                                        v-model="selectedCategoryId"
                                        :includeNav="false"
                                        @change="handleCategoryChange"
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
                            :show="editGroupShow"
                            @close="() => (editGroupShow = false)"
                        >
                            <form @submit.prevent="updateGroup">
                                <div class="pt-6">
                                    <div
                                        class="flex items-center justify-between border-b mb-4"
                                    >
                                        <h3 class="text-xl p-4 uppercase">
                                            Rediģēt grupu
                                        </h3>
                                        <button
                                            @click="editGroupShow = false"
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
                                            v-model="editGroupForm.name"
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
                                            value="Kategorija"
                                            class="mt-6"
                                        />
                                        <SelectInput
                                            :includeNav="true"
                                            v-model="editGroupForm.category_id"
                                            class="w-full text-textColor"
                                            :options="
                                                activeCategories.map((c) => ({
                                                    value: c.id,
                                                    text: c.name,
                                                }))
                                            "
                                            placeholder="Izvēlies kategoriju"
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
                                            :includeNav="false"
                                            v-model="editGroupForm.status"
                                            :options="statusOptions"
                                            placeholder="Izvēlies statusu"
                                            class="w-full text-textColor"
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
                                        @click="editGroupShow = false"
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
                                            Statuss / Kategorija
                                        </th>
                                        <th scope="col" class="px-4 py-3">
                                            Preču skaits
                                        </th>
                                        <th scope="col" class="px-4 py-3 flex justify-end">
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
                                        v-for="group in groups"
                                        :key="group.id"
                                        class="hover:bg-slate-100"
                                    >
                                        <td class="px-4 py-3">
                                            {{ group.id }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <span
                                                class="font-semibold text-md text-textColor"
                                                >{{ group.name }}</span
                                            >
                                        </td>
                                        <td class="px-4 py-3 flex-col">
                                            <div class="pt-2">
                                                <span
                                                    :class="{
                                                        'text-green-500 bg-green-100':
                                                            group.status ===
                                                            'Aktīvs',
                                                        'text-red-500 bg-red-100':
                                                            group.status ===
                                                            'Deaktivizēts',
                                                    }"
                                                    class="p-2 rounded-lg"
                                                >
                                                    {{ group.status }} </span
                                                ><br />
                                                <span class="flex pt-4">
                                                    {{
                                                        group.category
                                                            ? group.category
                                                                  .name
                                                            : "NAV"
                                                    }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">{{ group.goods_count }}</td>
                                        <td class="px-4 py-3">
                                            <div class="pt-2 flex justify-end">
                                                    {{
                                                        formatDate(
                                                            group.created_at
                                                        )
                                                    }}<br>
                                                    {{
                                                        formatDate(
                                                            group.updated_at
                                                        )
                                                    }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div
                                                class="space-x-3 flex justify-center"
                                            >
                                                <button
                                                    @click="
                                                        openEditGroupModal(
                                                            group
                                                        )
                                                    "
                                                    class="text-xl hover:bg-slate-200 rounded-lg p-2"
                                                >
                                                    <i
                                                        class="fa-solid fa-pen-to-square fa-fw"
                                                    ></i>
                                                </button>

                                                <Popconfirm
                                                    title="Vai tiešām vēlaties dzēst šo grupu?"
                                                    okText="Jā"
                                                    cancelText="Nē"
                                                    @confirm="() => handleDeleteGroup(group.id)"
                                                >
                                                    <button
                                                        class="text-xl hover:bg-slate-200 rounded-lg p-2"
                                                    >
                                                        <i
                                                            class="fas fa-trash fa-fw"
                                                        ></i>
                                                    </button>
                                                </Popconfirm>
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
