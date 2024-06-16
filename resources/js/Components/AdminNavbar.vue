<script setup>
import { Link, usePage, useForm } from "@inertiajs/vue3";
import { ref, watch, computed } from "vue";
import AdminNotifications from "./AdminNotifications.vue";
import AdminSearchbar from "./AdminSearchbar.vue";
import { Popover, Button } from "ant-design-vue";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
import "dayjs/locale/lv"; // Import Latvian locale

dayjs.extend(relativeTime);
dayjs.locale("lv"); // Use Latvian locale

const props = defineProps({
    currentPage: String,
    filters: Array,
});

const catalogDropdownOpen = ref(false); // Reactive property to toggle dropdown
const settingsDropdownOpen = ref(false); // Reactive property to toggle settings dropdown

const toggleCatalogDropdown = () => {
    catalogDropdownOpen.value = !catalogDropdownOpen.value;
};

const toggleSettingsDropdown = () => {
    settingsDropdownOpen.value = !settingsDropdownOpen.value;
};

const isSidebarOpen = ref(false); // State to control sidebar visibility

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value; // Toggle the state
};

const form = useForm({
    // initial form fields
});

const logout = () => {
    form.post(route("logout"));
};

const getImageUrl = (filename) => {
    return `/storage/images/${filename}`;
};

if (
    props.currentPage === "logfiles" ||
    props.currentPage === "administrators"
) {
    settingsDropdownOpen.value = true;
}

if (
    props.currentPage === "goods" ||
    props.currentPage === "categories" ||
    props.currentPage === "groups" ||
    props.currentPage === "attributes"
) {
    catalogDropdownOpen.value = true;
}

const searchQuery = ref("");
const searchResults = ref([]);
const searchType = ref("goods");
const popoverVisible = ref(false);

const handleInput = async () => {
    if (searchQuery.value.length > 1) {
        try {
            const response = await fetch(
                `/admin/search?query=${searchQuery.value}&type=${searchType.value}`
            );
            if (!response.ok) {
                throw new Error(
                    `Server responded with status: ${response.status}`
                );
            }
            const data = await response.json();
            searchResults.value = data[searchType.value] || [];
            popoverVisible.value = true;
        } catch (error) {
            console.error("Error fetching search results:", error);
            searchResults.value = [];
            popoverVisible.value = true;
        }
    } else {
        searchResults.value = [];
        popoverVisible.value = false;
    }
};

const handleFocus = () => {
    if (searchQuery.value.length > 1) {
        popoverVisible.value = true;
    }
};

const handleBlur = () => {
    setTimeout(() => {
        popoverVisible.value = false;
    }, 200);
};

const highlightQuery = (text, query) => {
    if (!text) return ""; // Ensure text is not undefined
    const regex = new RegExp(`(${query})`, "gi");
    return text.replace(
        regex,
        '<span class="bg-yellow-100 font-bold">$1</span>'
    );
};

const popoverContent = computed(() =>
    searchResults.value.length > 0
        ? searchResults.value
              .map((result) => {
                  if (searchType.value === "goods") {
                      return `<div class="py-2 px-4 hover:bg-gray-100 cursor-pointer flex items-center">
                                <Link href="/admin-goods/${result.id}/edit">
                                    <img src="${
                                        result.image
                                    }" class="w-10 h-10 rounded-md shadow-md mr-2" />
                                    <span>${highlightQuery(
                                        result.name,
                                        searchQuery.value
                                    )}</span>
                                </Link>
                              </div>`;
                  } else if (searchType.value === "orders") {
                      return `<div class="py-2 px-4 hover:bg-gray-100 cursor-pointer">
                                <Link href="/admin-orders/${result.id}">
                                    #${result.id}<br>
                                    ${dayjs(result.created_at).format(
                                        "DD.MM.YYYY"
                                    )}, 
                                    ${result.status}, 
                                    ${result.user?.email || "N/A"}
                                </Link>
                              </div>`;
                  } else if (searchType.value === "customers") {
                      return `<div class="py-2 px-4 hover:bg-gray-100 cursor-pointer">
                                <Link href="/admin-users/${result.id}">
                                    ${highlightQuery(
                                        result.name || "",
                                        searchQuery.value
                                    )}<br>
                                    ${highlightQuery(
                                        result.email || "",
                                        searchQuery.value
                                    )}
                                </Link>
                              </div>`;
                  }
              })
              .join("")
        : `<div class="py-2 px-4 text-gray-500">Nav rezultātu</div>`
);

const changeSearchType = (type) => {
    searchType.value = type;
    handleInput();
};
</script>

<style>
.rotate-180 {
    transform: rotate(180deg);
    transition: transform 0.3s ease;
}

.scale-on-hover:hover {
    transform: scale(1.05);
    transition: transform 0.3s ease;
    border-radius: 0.375rem;
    /* 6px */
}

.highlighted {
    background-color: #4f91ed;
    /* Change this color based on your theme */
    color: white;
    /* Ensures the text color is suitable for the highlight */
    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0;
}

.active-button {
    background-color: #4f91ed;
    color: white;
}

aside {
    transition: transform 0.3s ease-out;
}
</style>

<template>
    <div class="flex flex-col h-24">
        <nav
            class="bg-primary border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700 fixed top-0 left-0 right-0 z-50"
        >
            <div
                class="px-3 py-3 lg:px-5 lg:pl-3 flex justify-between items-center"
            >
                <div class="flex items-center space-x-6 w-3/4">
                    <Link
                        href="/admin-orders"
                        class="flex items-center select-none"
                    >
                        <img
                            :src="getImageUrl('piffdeals_text_accent.svg')"
                            alt="Logo"
                            draggable="false"
                            class="h-10 mr-2"
                        />
                    </Link>

                    <Popover
                        v-model:open="popoverVisible"
                        placement="bottom"
                        :arrow="false"
                        :overlay-inner-style="{
                            padding: '0',
                            width: '400px',
                        }"
                    >
                        <template #content>
                            <div v-if="true">
                                <Button.Group
                                    class="flex w-full justify-start items-center"
                                >
                                    <Button
                                        @click="changeSearchType('goods')"
                                        :class="{
                                            'active-button':
                                                searchType.value === 'goods',
                                        }"
                                        class="flex-1"
                                        >PRECES</Button
                                    >
                                    <Button
                                        @click="changeSearchType('orders')"
                                        :class="{
                                            'active-button':
                                                searchType.value === 'orders',
                                        }"
                                        class="flex-1"
                                        >PASŪTĪJUMI</Button
                                    >
                                    <Button
                                        @click="changeSearchType('customers')"
                                        :class="{
                                            'active-button':
                                                searchType.value ===
                                                'customers',
                                        }"
                                        class="flex-1"
                                        >KLIENTI</Button
                                    >
                                </Button.Group>
                                <div v-html="popoverContent"></div>
                            </div>
                        </template>
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
                            <input
                                type="search"
                                v-model="searchQuery"
                                @input="handleInput"
                                @blur="handleBlur"
                                @focus="handleFocus"
                                autocomplete="off"
                                class="w-[400px] py-2 pl-10 pr-4 text-sm text-textColor border border-gray-300 rounded-xl bg-white shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                                :placeholder="`Mega meklēt...`"
                            />
                        </form>
                    </Popover>
                </div>

                <div class="flex items-center mr-4 text-white text-xl">
                    <AdminNotifications />
                    <div class="ml-4">{{ $page.props.auth.user.name }}</div>
                </div>
            </div>
        </nav>

        <aside
            id="logo-sidebar"
            class="w-64 bg-primary fixed top-16 bottom-0 overflow-y-hidden transition-transform duration-300"
            aria-label="Sidebar"
        >
            <div class="h-full px-3 py-4 overflow-y-auto bg-primary">
                <ul class="space-y-2 font-medium">
                    <ul class="space-y-2 font-medium">
                        <!-- <li>
                            <Link
                                href="/admin-orders"
                                :class="{
                                    'navbar-element':
                                        props.currentPage === 'statistics',
                                }"
                                class="flex items-center p-4 rounded-lg text-white hover:bg-secondary group navbarElement"
                            >
                                <i
                                    class="fas fa-sharp fa-chart-pie mr-1.5 text-xl fa-fw"
                                ></i>
                                <span class="ms-3 uppercase">Statistika</span>
                            </Link>
                        </li> -->
                        <li>
                            <Link
                                href="/admin-orders"
                                :class="{
                                    'navbar-element':
                                        props.currentPage === 'orders',
                                }"
                                class="flex items-center p-4 rounded-lg text-white hover:bg-secondary group navbarElement"
                            >
                                <i
                                    class="fas fa-sharp fa-bag-shopping mr-1.5 text-xl fa-fw"
                                ></i>
                                <span class="ms-3 uppercase">Pasūtījumi</span>
                            </Link>
                        </li>
                        <li>
                            <button
                                @click="toggleCatalogDropdown"
                                :class="[
                                    'flex items-center p-4 rounded-lg text-white hover:bg-secondary group w-full scale-on-hover',
                                    { highlighted: catalogDropdownOpen },
                                ]"
                            >
                                <i
                                    class="fa-regular fa-sharp fa-folder-open mr-1.5 text-xl fa-fw"
                                ></i>
                                <span class="ms-3 uppercase">Katalogs</span>
                                <i
                                    :class="{
                                        'rotate-180': catalogDropdownOpen,
                                    }"
                                    class="fas fa-chevron-down ml-auto"
                                ></i>
                            </button>
                            <ul
                                v-show="catalogDropdownOpen"
                                class="pl-4 space-y-1 bg-accent rounded-t-0 rounded-b-md"
                            >
                                <li>
                                    <Link
                                        href="/admin-goods"
                                        class="block p-2 rounded text-white uppercase ms-9"
                                        >Preces
                                    </Link>
                                </li>
                                <li>
                                    <Link
                                        href="/admin-attributes"
                                        class="block p-2 rounded text-white uppercase ms-9"
                                        >Atribūti
                                    </Link>
                                </li>
                                <li>
                                    <Link
                                        href="/admin-groups"
                                        class="block p-2 rounded text-white uppercase ms-9"
                                        >Grupas
                                    </Link>
                                </li>
                                <li>
                                    <Link
                                        href="/admin-categories"
                                        class="block p-2 rounded text-white uppercase ms-9"
                                    >
                                        Kategorijas</Link
                                    >
                                </li>
                            </ul>
                        </li>
                        <li>
                            <Link
                                href="/admin-users"
                                :class="{
                                    'navbar-element':
                                        props.currentPage === 'users',
                                }"
                                class="flex items-center p-4 rounded-lg text-white hover:bg-secondary group navbarElement"
                            >
                                <i
                                    class="far fa-sharp fa-user mr-1.5 text-xl fa-fw"
                                ></i>
                                <span class="ms-3 uppercase">Klienti</span>
                            </Link>
                        </li>
                        <li>
                            <button
                                @click="toggleSettingsDropdown"
                                :class="[
                                    'flex items-center p-4 rounded-lg text-white hover:bg-secondary group w-full scale-on-hover',
                                    { highlighted: settingsDropdownOpen },
                                ]"
                            >
                                <i
                                    class="fas fa-sharp fa-gear mr-1.5 text-xl fa-fw"
                                ></i>
                                <span class="ms-3 uppercase">Iestatījumi</span>
                                <i
                                    :class="{
                                        'rotate-180': settingsDropdownOpen,
                                    }"
                                    class="fas fa-chevron-down ml-auto"
                                ></i>
                            </button>
                            <ul
                                v-show="settingsDropdownOpen"
                                class="pl-4 space-y-1 bg-accent rounded-t-0 rounded-b-md"
                            >
                                <li>
                                    <Link
                                        href="/profile"
                                        class="block p-2 rounded text-white uppercase ms-9"
                                    >
                                        Profils
                                    </Link>
                                </li>
                            </ul>
                        </li>
                    </ul>

                    <ul
                        class="space-y-2 font-medium pt-4 border-t border-gray-200"
                    >
                        <li>
                            <Link
                                href="/"
                                class="flex items-center p-4 rounded-lg text-white hover:bg-secondary group navbarElement"
                            >
                                <i
                                    class="fas fa-sharp fa-house mr-1.5 text-xl fa-fw"
                                ></i>
                                <span class="ms-3 uppercase"
                                    >Uz klienta lapu</span
                                >
                            </Link>
                        </li>

                        <li>
                            <button
                                @click="logout"
                                class="flex items-center p-4 rounded-lg text-white hover:bg-secondary navbarElement w-full group"
                            >
                                <i
                                    class="fas fa-sharp fa-right-from-bracket mr-1.5 text-xl fa-fw"
                                ></i>
                                <span class="ms-3 uppercase">Atslēgties</span>
                            </button>
                        </li>
                    </ul>
                </ul>
            </div>
        </aside>
    </div>
</template>
