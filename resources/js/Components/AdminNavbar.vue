<script setup>
import { Link, usePage, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import AdminThemeSwitch from "./AdminThemeSwitch.vue";
import AdminNotifications from "./AdminNotifications.vue";
import AdminSearchbar from "./AdminSearchbar.vue";

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

const props = defineProps(["currentPage"]);

if (
    props.currentPage === "logfiles" ||
    props.currentPage === "administrators"
) {
    settingsDropdownOpen.value = true;
}

if (props.currentPage === "goods" || props.currentPage === "categories" || props.currentPage === 'groups' || props.currentPage === 'attributes') {
    catalogDropdownOpen.value = true;
}
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
                <div class="flex items-center space-x-6">
                    <Link
                        href="/admin-dashboard"
                        class="flex items-center select-none"
                    >
                        <img
                            :src="getImageUrl('piffdeals_text_accent.svg')"
                            alt="Logo"
                            draggable="false"
                            class="h-10 mr-2"
                        />
                    </Link>
                    <AdminSearchbar placeholderSearch="Mega meklēt"/>
                </div>

                <div class="flex items-center mr-4 text-white text-xl">
                    <AdminThemeSwitch />
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
                        <li>
                            <Link
                                href="/admin-dashboard"
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
                        </li>
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
                                        href="/admin-administrators"
                                        class="block p-2 rounded text-white uppercase ms-9"
                                    >
                                        Administratori</Link
                                    >
                                </li>
                                <li>
                                    <Link
                                        href="/admin-logfiles"
                                        class="block p-2 rounded text-white uppercase ms-9"
                                    >
                                        Žurnālfaili
                                    </Link>
                                </li>

                                <li>
                                    <Link
                                        href="/admin-banners"
                                        class="block p-2 rounded text-white uppercase ms-9"
                                    >
                                        Reklāmu baneri
                                    </Link>
                                </li>

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
