<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref, computed, onMounted, watch } from "vue";
import { usePage, useForm } from "@inertiajs/vue3";
import { Ziggy } from "@/ziggy";
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
import AdminFilterDrawer from "@/Components/AdminFilterDrawer.vue";
import AdminSearchbar from "@/Components/AdminSearchbar.vue";

const getImageUrl = (filename) => {
    return `/storage/images/${filename}`;
};
const createUserShow = ref(false);

const selectedUsers = ref([]);

const navigateToUserDetails = (userId) => {
    const url = route("admin-users.show", { id: userId });
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
    showPasswordResetModal.value = false;
    passwordResetForm.post(route("admin-users.password.email"), {
        onSuccess: () => {
            alert("Paroles atjaunošanas saite ir veiksmīgi nosūtīta klientam");
            passwordResetForm.reset(); // Optionally reset the form field
        },
        onError: (errors) => {
            alert("Failed to send password reset link.");
            console.error(errors);
        },
    });
};

const { errors } = usePage().props;
const createUserForm = useForm({
    name: "",
    email: "",
    password: "",
});

const createUserSubmitForm = () => {
    createUserForm.post(route("admin-users.storeAdmin"), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: (response) => {
            // Reset the form and hide modal
            createUserForm.reset();
            createUserShow.value = false;
            selectedUsers.value = [];

            // Fetch updated user list
            fetchUsers();
        },
        onError: (error) => {
            console.error("Submission error", error);
            alert("Error: " + error.message);
            if (error.response && error.response.data) {
                console.error("Detailed errors", error.response.data.errors);
            }
        },
    });
};

// Function to fetch users
function fetchUsers() {
    return router.get(route("admin-users.index"), { search: search.value }, {
        preserveState: true,
        replace: true,
    }).then(response => {
        // Handle response
        console.log('Fetched users:', response);
        // Update your state/data here
    }).catch(error => {
        console.error('Failed to fetch users:', error);
    });
}


const search = ref(props.filters.search);
// watch(search, (value) => {
//     router.get(
//         "/admin-users",
//         { search: value },
//         {
//             preserveState: true,
//             replace: true,
//         }
//     );
// });

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

const inputType = ref("password");

// Function to toggle the visibility of the password
const togglePasswordVisibility = () => {
    inputType.value = inputType.value === "password" ? "text" : "password";
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
                                        <div class="mt-6 relative">
                                            <TextInput
                                                :type="inputType"
                                                class="w-full"
                                                placeholder="Parole"
                                                v-model="
                                                    createUserForm.password
                                                "
                                                required
                                            />

                                            <button
                                                @click="
                                                    togglePasswordVisibility
                                                "
                                                class="absolute top-1/2 right-3 -translate-y-1/2"
                                                type="button"
                                            >
                                                <i
                                                    :class="
                                                        inputType === 'password'
                                                            ? 'fas fa-eye fa-fw text-gray-400'
                                                            : 'fas fa-eye-slash fa-fw text-gray-400'
                                                    "
                                                ></i>
                                            </button>
                                            <InputError
                                                class="mt-2"
                                                :message="errors.password"
                                            />
                                        </div>
                                    </div>
                                    <div
                                        class="mx-6 py-6 border-t flex justify-end"
                                    >
                                        <SecondaryButton
                                            @click="closeCreateUserModal"
                                            >Atcelt
                                        </SecondaryButton>
                                        <PrimaryButton
                                            type="submit"
                                            class="ms-3"
                                            >Izveidot kontu
                                        </PrimaryButton>
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
                                <div class="flex space-x-6 items-center">
                                    <!-- <form class="relative">
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
                                    </form> -->
                                    <AdminSearchbar
                                        :initialQuery="props.filters.search"
                                        @update:searchQuery="search = $event"
                                        @search="fetchUsers"
                                    />

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

                                    <!-- <button
                                            @click="toggleDrawer"
                                            class="w-full md:w-auto flex items-center justify-center py-2 px-4 text-sm font-medium text-textColor focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                                            type="button"
                                        >
                                           <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true"
                                            class="h-4 w-4 mr-2 text-gray-400" viewbox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                                                clip-rule="evenodd" />
                                        </svg>
                                            <i
                                                class="fas fa-filter fa-fw mr-2 text-textColor"
                                            ></i>
                                            Filtrēt
                                        </button>
                                        
                                    <div class="relative z-50">

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
                                    </div> -->
                                    <AdminFilterDrawer
                                        v-model:open="drawerOpen"
                                    >
                                        <!-- Additional filters can be added here -->
                                    </AdminFilterDrawer>
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
                                    <tr
                                        v-for="user in props.users.data"
                                        :key="user.id"
                                        class="hover:bg-slate-100"
                                    >
                                        <td class="px-4 py-3">
                                            <span
                                                class="font-semibold text-md text-textColor"
                                                >{{ user.name }}
                                            </span>
                                            <br />{{ user.email }}
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
                                                <template
                                                    v-if="
                                                        user.status !==
                                                        'Deaktivizēts'
                                                    "
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
                                                </template>
                                                <template v-else>
                                                    <span
                                                        class="text-xl text-gray-400 cursor-not-allowed p-2"
                                                    >
                                                        <i
                                                            class="fa-solid fa-envelope fa-fw"
                                                        ></i>
                                                    </span>
                                                </template>

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
                                                <p class="text-textColor">
                                                    Vai tiešām vēlies sūtīt
                                                    paroles atjaunošanas
                                                    vēstuli?
                                                </p>
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
