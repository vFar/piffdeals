<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref, computed, onMounted, watch } from "vue";
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
import { router } from "@inertiajs/vue3";

const getImageUrl = (filename) => {
    return `/storage/images/${filename}`;
};
const createUserShow = ref(false);

const isAnySelected = computed(() => selectedUsers.value.length > 0);
const actionSelected = ref("");

const navigateToUserDetails = (userId) => {
    const url = route("admin.users.show", { id: userId });
    window.location.href = url;
};

const props = defineProps({
    users: Object,
    totalUsers: Number,
    filters: Object,
});

const passwordResetForm = useForm({ email: "" });
const showPasswordResetModal = ref(false);

const openPasswordResetModal = (email) => {
    passwordResetForm.email = email;
    showPasswordResetModal.value = true;
};

const sendPasswordResetEmail = () => {
    passwordResetForm.post(route("password.email"), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (response) => {
            alert(
                "Password reset link sent successfully to " +
                    passwordResetForm.email
            );
            console.log(passwordResetForm.email);
            showPasswordResetModal.value = false;
            passwordResetForm.reset(); // Reset the form to clear the email field
        },
        onError: (errors) => {
            if (errors.email) {
                alert(errors.email);
            } else {
                alert("Error sending password reset email.");
            }
            console.error(errors);
        },
    });
};

const selectedUsers = ref([]);

const allChecked = computed({
    get: () =>
        props.users.data &&
        props.users.data.length > 0 &&
        props.users.data.every((user) => selectedUsers.value.includes(user.id)),
    set: (value) => {
        console.log("Setting allChecked to: ", value);
        if (value) {
            selectedUsers.value = props.users.data.map((user) => user.id);
        } else {
            selectedUsers.value = [];
        }
        console.log(
            "selectedUsers after setting allChecked: ",
            selectedUsers.value
        );
    },
});

const toggleSelection = (id) => {
    const index = selectedUsers.value.indexOf(id);
    if (index > -1) {
        selectedUsers.value.splice(index, 1);
    } else {
        selectedUsers.value.push(id);
    }
};

const selectionText = computed(
    () => `${selectedUsers.value.length} no ${props.totalUsers} izvēlēti`
);

const { errors } = usePage().props;
const createUserForm = useForm({
    name: "",
    email: "",
    password: "",
});

const createUserSubmitForm = () => {
    createUserForm.post(route("admin.users.storeAdmin"), {
        preserveState: true, // This option prevents the page from fully reloading
        preserveScroll: true, // Keeps the current scroll position after the request
        onSuccess: (response) => {
            createUserForm.reset();
            createUserShow.value = false;
            selectedUsers.value = [];
            // Optionally, fetch the updated list of users if not automatically handled by Inertia
        },
        onError: (error) => {
            console.log("createUserForm submission error", error);
        },
    });
};

const search = ref(props.filters.search);
// watch(search, (newValue) => {
//     // Only navigate if there's a change in the search value and it's not empty
//     if (newValue.trim()) {
//         router.get(`/admin-users?search=${newValue}`, {
//             preserveState: true,
//             replace: true,  // This replaces the current entry in the history stack, not pushing a new one.
//             only: ['users'], // Adjust depending on what parts of the page you want updated
//         });
//     }
// }, { immediate: true });

watch(search, (value) => {
    router.get(
        "/admin-users",
        { search: value },
        {
            preserveState: true,
            replace: true,
        }
    );
});

// const mainDropdownOpen = ref(false);
// const nestedDropdownOpen = ref(false);

// function toggleMainDropdown() {
//     mainDropdownOpen.value = !mainDropdownOpen.value;
//     if (mainDropdownOpen.value === false) {
//         nestedDropdownOpen.value = false;  // Automatically close nested dropdown when main closes
//     }
// }

// function toggleNestedDropdown() {
//     nestedDropdownOpen.value = !nestedDropdownOpen.value;
// }

// function setStatus(status) {
//     console.log(`Setting status to: ${status}`);
//     mainDropdownOpen.value = false;
//     nestedDropdownOpen.value = false;
// }

// function deleteAction() {
//     console.log('Performing delete action');
//     mainDropdownOpen.value = false;
// }

// Display text for selected count

const applyAction = () => {
    if (actionSelected.value === "delete") {
        console.log("Deleting:", selectedUsers.value);
        // Additional delete functionality as required
    } else if (
        actionSelected.value === "active" ||
        actionSelected.value === "inactive"
    ) {
        updateUsersStatus(
            actionSelected.value === "active" ? "Aktīvs" : "Deaktivizēts"
        );
    }

    // Clear selections and reset dropdown
    selectedUsers.value = [];
    actionSelected.value = "";
};

const updateUsersStatus = async (status) => {
    const userIds = selectedUsers.value;
    await router
        .patch(route("admin.users.updateStatus"), {
            userIds,
            status,
        })
        .then(() => {
            console.log("Status updated successfully");
            props.users.data.forEach((user) => {
                if (userIds.includes(user.id)) {
                    user.status = status;
                }
            });
        })
        .catch((error) => {
            console.error("Failed to update status:", error);
        });
};

const formatDate = (date) => {
    return dayjs(date).format("DD.MM.YYYY, HH:mm:ss");
};

const drawerOpen = ref(false); // State to control the visibility of the drawer

const toggleDrawer = () => {
    drawerOpen.value = !drawerOpen.value; // Toggle the state of the drawer
};

const closeDrawer = () => {
    drawerOpen.value = false;
};

const createUserModal = () => {
    createUserShow.value = true;
    // nextTick(() => passwordInput.value.focus());
};

const closeCreateUserModal = () => {
    createUserShow.value = false;
    createUserForm.reset();

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
    <Head title="KLIENTI" />
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
                    <h1 class="uppercase font-semibold text-xl text-textColor">
                        Klientu konti
                    </h1>

                    <div class="flex items-center">
                        <button
                            @click="createUserModal"
                            class="flex items-center p-3 rounded-lg text-white bg-accent uppercase font-semibold hover:bg-secondary group navbarElement"
                        >
                            Izveidot kontu
                        </button>

                        <Modal
                            :show="createUserShow"
                            @close="closeCreateUserModal"
                        >
                            <form @submit.prevent="createUserSubmitForm">
                                <div class="pt-6">
                                    <div
                                        class="flex items-center justify-between border-b mb-4"
                                    >
                                        <h3 class="text-xl p-4 uppercase">
                                            Izveidot jaunu kontu
                                        </h3>
                                        <button
                                            @click="closeCreateUserModal"
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
                                            <TextInput
                                                type="text"
                                                class="w-full"
                                                placeholder="Vārds, uzvārds"
                                                v-model="createUserForm.name"
                                                required
                                                autofocus
                                            />
                                            <InputError
                                                class="mt-2"
                                                :message="errors.name"
                                            />
                                        </div>
                                        <div class="mt-6">
                                            <TextInput
                                                type="email"
                                                class="w-full"
                                                placeholder="E-pasts"
                                                v-model="createUserForm.email"
                                                required
                                            />
                                            <InputError
                                                class="mt-2"
                                                :message="errors.email"
                                            />
                                        </div>
                                        <div class="mt-6">
                                            <TextInput
                                                type="password"
                                                class="w-full"
                                                placeholder="Parole"
                                                v-model="
                                                    createUserForm.password
                                                "
                                                required
                                            />
                                            <InputError
                                                class="mt-2"
                                                :message="errors.password"
                                            />
                                        </div>
                                    </div>
                                    <div
                                        class="mx-6 py-6 border-t flex justify-end"
                                    >
                                        <button
                                            type="submit"
                                            class="flex items-center p-3 rounded-lg text-white bg-primary uppercase font-semibold hover:bg-secondary"
                                        >
                                            Izveidot kontu
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </Modal>
                    </div>
                </div>
            </section>

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
                                        <option
                                            disabled
                                            value=""
                                            selected="selected"
                                        >
                                            Izvēlēties darbību
                                        </option>
                                        <optgroup label="Statuss">
                                            <option value="active">
                                                Aktivizēt
                                            </option>
                                            <option value="inactive">
                                                Deaktivizēt
                                            </option>
                                        </optgroup>
                                        <option value="delete">Dzēst</option>
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
                                            placeholder="Meklēt"
                                            @keypress.enter.prevent
                                            v-model="search"
                                            class="w-[300px] py-2 pl-10 pr-4 text-sm text-textColor rounded-xl bg-white"
                                        />
                                    </form>

                                    <p>
                                        {{ totalUsers }}
                                        {{
                                            totalUsers === 1
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
                                    class="flex items-center space-x-6 w-full md:w-auto"
                                >
                                    <!-- PAGINATOR -->

                                    <div class="flex items-center space-x-6">
                                        <AdminPaginator :links="users.links" />
                                    </div>

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

                                        <!-- Backdrop -->
                                        <div
                                            v-show="drawerOpen"
                                            @click="closeDrawer"
                                            class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"
                                            :class="{
                                                'duration-300 ease-in-out':
                                                    drawerOpen,
                                                'opacity-100': drawerOpen,
                                                'opacity-0': !drawerOpen,
                                            }"
                                        ></div>

                                        <!-- Drawer -->
                                        <div
                                            :class="{
                                                'translate-x-0': drawerOpen,
                                                'translate-x-full': !drawerOpen,
                                            }"
                                            class="fixed right-0 top-0 w-96 h-full bg-white shadow-xl transition-transform duration-300 ease-in-out"
                                        >
                                            <div
                                                class="flex items-center justify-between border-b mb-4"
                                            >
                                                <h3 class="text-xl p-4">
                                                    Filtrēt preces
                                                </h3>
                                                <button
                                                    @click="closeDrawer"
                                                    class="m-4 bg-transparent hover:bg-gray-300 text-white p-2 rounded-xl"
                                                >
                                                    <i
                                                        class="fas fa-xmark fa-fw text-textColor hover:text-white"
                                                    ></i>
                                                </button>
                                            </div>

                                            <p class="px-4">
                                                Here is some more content...
                                            </p>
                                        </div>
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
                                            <Checkbox
                                                :checked="allChecked"
                                                @update:checked="
                                                    allChecked = $event
                                                "
                                            />
                                        </th>
                                        <th scope="col" class="px-4 py-3">
                                            Vārds, uzvārds / E-pasts / Telefons
                                        </th>
                                        <th scope="col" class="px-4 py-3">
                                            Statuss / Pasūtījumu skaits / Adrešu
                                            skaits
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
                                    <tr v-if="!props.users.data.length">
                                        <td
                                            colspan="5"
                                            class="text-center py-4"
                                        >
                                            Nav pieejamu ierakstu
                                        </td>
                                    </tr>
                                    <tr
                                        v-for="user in props.users.data"
                                        :key="user.id"
                                        class="hover:bg-slate-100"
                                    >
                                        <td class="px-4 py-3">
                                            <Checkbox
                                                :value="user.id"
                                                :checked="
                                                    selectedUsers.value
                                                        ? selectedUsers.value.includes(
                                                              user.id
                                                          )
                                                        : false
                                                "
                                                @update:checked="
                                                    () =>
                                                        toggleSelection(user.id)
                                                "
                                            />
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ user.name }} <br />{{
                                                user.email
                                            }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <span
                                                :class="{
                                                    'text-green-500 bg-green-100':
                                                        user.status ===
                                                        'Aktīvs',
                                                    'text-red-500 bg-red-100':
                                                        user.status ===
                                                        'Deaktivizēts',
                                                }"
                                                class="p-2 rounded-lg"
                                            >
                                                {{ user.status }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ formatDate(user.created_at) }}
                                            <br />{{
                                                formatDate(user.updated_at)
                                            }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <div
                                                class="space-x-3 flex justify-center"
                                            >
                                                <button
                                                    @click="
                                                        openPasswordResetModal(
                                                            user.email
                                                        )
                                                    "
                                                    class="text-xl hover:bg-slate-200 rounded-lg p-2"
                                                >
                                                    <i
                                                        class="fa-solid fa-envelope fa-fw"
                                                    ></i>
                                                </button>

                                                <button
                                                    @click="
                                                        navigateToUserDetails(
                                                            user.id
                                                        )
                                                    "
                                                    class="text-xl hover:bg-slate-200 rounded-lg p-2"
                                                >
                                                    <i
                                                        class="fa-solid fa-caret-right fa-fw"
                                                    ></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <Modal
                                :show="showPasswordResetModal"
                                @close="showPasswordResetModal = false"
                            >
                                <template #default>
                                    <div class="pt-6">
                                        <div
                                            class="flex items-center justify-between border-b mb-4"
                                        >
                                            <h3 class="text-xl p-4 uppercase">
                                                Apstiprināt paroles atjaunošanu
                                            </h3>
                                            <button
                                                @click="
                                                    showPasswordResetModal = false
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
                                                <h1 class="text-2xl bg-red-600">
                                                    NOT WORKIUNG ATM
                                                </h1>
                                                <h1>
                                                    Vai tiešām vēlies sūtīt
                                                    paroles atjaunošanas
                                                    vēstuli?
                                                </h1>
                                            </div>
                                        </div>

                                        <div
                                            class="mx-6 py-6 border-t flex justify-end"
                                        >
                                            <SecondaryButton
                                                @click="
                                                    showPasswordResetModal = false
                                                "
                                                >Atcelt
                                            </SecondaryButton>
                                            <PrimaryButton
                                                @click="sendPasswordResetEmail"
                                                class="ms-3"
                                                >Sūtīt
                                            </PrimaryButton>
                                        </div>
                                    </div>
                                </template>
                            </Modal>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</template>
