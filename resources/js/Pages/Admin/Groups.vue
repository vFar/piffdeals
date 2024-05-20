<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref, onMounted, nextTick } from "vue";
import { usePage, useForm } from "@inertiajs/vue3";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import InputError from "@/Components/InputError.vue";
import AdminNavbar from "@/Components/AdminNavbar.vue";
import AdminSearchbar from "@/Components/AdminSearchbar.vue";
import Tooltip from "@/Components/Tooltip.vue";
import dayjs from "dayjs"; // Import Day.js
import { router } from "@inertiajs/vue3";

const props = defineProps({
    groups: Object,
    totalGroups: Number,
    filters: Object,
});

const { data: groups, links, meta } = props.groups;
const formatDate = (date) => dayjs(date).format("DD.MM.YYYY, HH:mm:ss");

const search = ref(props.filters.search || "");
const { errors } = usePage().props;

const createGroupForm = useForm({
    name: "",
    category_id: null,
    status: "Deaktivizēts",
});

const addGroupShow = ref(false);

function fetchGroups() {
    router.get(route("admin.groups.index"), {
        params: { search: search.value },
    });
}

function openAddGroupModal() {
    createGroupForm.reset(); // Reset the form fields
    addGroupShow.value = true;
}

function closeAddGroupModal() {
    addGroupShow.value = false;
}

function saveGroup() {
    console.log(createGroupForm);
    createGroupForm.post(route("admin.groups.store"), {
        onSuccess: () => {
            fetchGroups();
            addGroupShow.value = false;
        },
    });
}

const editGroupForm = useForm({
    id: null,
    name: "",
    category_id: null,
    status: "",
});

const editGroupShow = ref(false);

function openEditGroupModal(group) {
    editGroupForm.fill(group);
    editGroupShow.value = true;
}

function updateGroup() {
    editGroupForm.put(route("admin.groups.update", editGroupForm.id), {
        onSuccess: () => {
            fetchGroups();
            editGroupShow.value = false;
        },
    });
}
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
                class="max-w-full mx-6 border border-gray-200 rounded-xl bg-whiter shadow-md py-3 mb-8"
            >
                <div class="mx-auto max-w-full">
                    <!-- Start coding here -->
                    <div class="relative overflow-hidden">
                        <div
                            class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 mx-4 p-4"
                        >
                            <div class="w-full md:w-1/2">
                                <div class="flex space-x-6 items-center">
                                    <AdminSearchbar />

                                    <p>
                                        {{ totalGroups }}
                                        {{
                                            totalGroups === 1
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
                                    <div class="relative z-50">
                                        <!-- Trigger Button -->
                                    </div>
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
                                            ID
                                        </th>
                                        <th scope="col" class="px-4 py-3">
                                            Nosaukums
                                        </th>
                                        <th scope="col" class="px-4 py-3">
                                            Statuss / Kategorija
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
                                    <!-- <tr
                                        v-for="group in groups"
                                        :key="group.id"
                                        class="hover:bg-gray-200"
                                    >
                                        <td>{{ group.id }}</td>
                                        <td>{{ group.name }}</td>
                                        <td>{{ group.status }}</td>
                                        <td>
                                            {{
                                                group.category
                                                    ? group.category.name
                                                    : "NAV"
                                            }}
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

                                                <button
                                                    @click="
                                                        deleteGroup(group.id)
                                                    "
                                                    class="text-xl hover:bg-slate-200 rounded-lg p-2"
                                                >
                                                    <i
                                                        class="fas fa-trash fa-fw"
                                                    ></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr> -->

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
                                                        {{ group.status }}
                                                    </span><br>
                                                    <span class="flex ml-7 pt-4">
                                                        {{
                                                            group.category
                                                                ? group.category.name
                                                                : "NAV"
                                                        }}
                                                    </span>
                                                </div>
                                        </td>
                                        <td class="px-4 py-3">

                                            <div class="pt-2">
                                                    <span
                                                        
                                                        class="p-2 rounded-lg"
                                                    >
                                                    {{ formatDate(group.created_at) }}
                                                    </span><br>
                                                    <span class=" p-2 flex">
                                                        {{ formatDate(group.updated_at) }}

                                                    </span>
                                                </div>
                                        </td>
                                        <td class="px-4 py-3">Preču skaits</td>
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

                                                <button
                                                    @click="
                                                        deleteGroup(group.id)
                                                    "
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
