<script setup>
import { Link, usePage, useForm } from "@inertiajs/vue3";
import { ref } from "vue";

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

if (props.currentPage === "goods" || props.currentPage === "categories") {
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

<!-- <template>

    <nav class="bg-primary h-1 z-40">

    </nav>

    <button @click="toggleSidebar"
        class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-whiter rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 bg-primary">
        <span class="sr-only">Toggle sidebar</span>
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
            <path d="M3 6h14M3 12h14M3 18h14"></path>
        </svg>
    </button>

    <aside id="logo-sidebar" :class="{ 'translate-x-0': isSidebarOpen, '-translate-x-full': !isSidebarOpen }"
        class="fixed top-0 left-0 w-64 sm:w-64 h-screen transition-transform sm:translate-x-0" aria-label="Sidebar">
        <div class="h-full px-3 py-4 overflow-y-auto bg-primary">


            <Link href="/admin-dashboard" class="flex items-center mb-5 ">
            <img :src="getImageUrl('piffdeals_text_accent.svg')" alt="Text" class="h-10 mr-0 select-none"
                draggable="false">
            </Link>
            <ul class="space-y-2 font-medium">
                <li>
                    <Link href="/admin-dashboard" :class="{ 'navbar-element': props.currentPage === 'statistics' }"
                        class="flex items-center p-4 rounded-lg text-white hover:bg-secondary group navbarElement">
                    <i class="fas fa-sharp fa-chart-pie mr-1.5 text-xl fa-fw"></i>
                    <span class="ms-3 uppercase">Statistika</span>
                    </Link>
                </li>
                <li>
                    <Link href="/admin-orders" :class="{ 'navbar-element': props.currentPage === 'orders' }"
                        class="flex items-center p-4 rounded-lg text-white hover:bg-secondary group navbarElement">
                    <i class="fas fa-sharp fa-bag-shopping mr-1.5 text-xl fa-fw"></i>
                    <span class="ms-3 uppercase">Pasūtījumi</span>
                    </Link>
                </li>
                <li>
                    <button @click="toggleCatalogDropdown"
                        :class="['flex items-center p-4 rounded-lg text-white hover:bg-secondary group w-full scale-on-hover', { 'highlighted': catalogDropdownOpen }]">
                        <i class="fa-regular fa-sharp fa-folder-open mr-1.5 text-xl fa-fw"></i>
                        <span class="ms-3 uppercase">Katalogs</span>
                        <i :class="{ 'rotate-180': catalogDropdownOpen }" class="fas fa-chevron-down ml-auto"></i>
                    </button>
                    <ul v-show="catalogDropdownOpen" class="pl-4 space-y-1 bg-accent rounded-t-0 rounded-b-md">
                        <li>
                            <Link href="/admin-goods" class="block p-2 rounded text-white uppercase ms-9">Preces</Link>
                        </li>
                        <li>
                            <Link href="/admin-categories" class="block p-2 rounded text-white uppercase ms-9">
                            Kategorijas</Link>
                        </li>
                    </ul>
                </li>
                <li>
                    <Link href="/admin-users" :class="{ 'navbar-element': props.currentPage === 'users' }"
                        class="flex items-center p-4 rounded-lg text-white hover:bg-secondary group navbarElement">
                    <i class="far fa-sharp fa-user mr-1.5 text-xl fa-fw"></i>
                    <span class="ms-3 uppercase">Klienti</span>
                    </Link>
                </li>
                <li>
                    <button @click="toggleSettingsDropdown"
                        :class="['flex items-center p-4 rounded-lg text-white hover:bg-secondary group w-full scale-on-hover', { 'highlighted': settingsDropdownOpen }]">
                        <i class="fas fa-sharp fa-gear mr-1.5 text-xl fa-fw"></i>
                        <span class="ms-3 uppercase">Iestatījumi</span>
                        <i :class="{ 'rotate-180': settingsDropdownOpen }" class="fas fa-chevron-down ml-auto"></i>
                    </button>
                    <ul v-show="settingsDropdownOpen" class="pl-4 space-y-1 bg-accent rounded-t-0 rounded-b-md">
                        <li>
                            <Link href="/admin-administrators" class="block p-2 rounded text-white uppercase ms-9">
                            Administratori</Link>
                        </li>
                        <li>
                            <Link href="/admin-logfiles" class="block p-2 rounded text-white uppercase ms-9">Žurnālfaili
                            </Link>
                        </li>
                    </ul>
                </li>
            </ul>

            <ul class="space-y-2 font-medium pt-4 border-t border-gray-200">
                <li>
                    <Link href="/"
                        class="flex items-center p-4 rounded-lg text-white hover:bg-secondary group navbarElement">
                    <i class="fas fa-sharp fa-house mr-1.5 text-xl fa-fw"></i>
                    <span class="ms-3 uppercase">Uz klienta lapu</span>
                    </Link>
                </li>

                <li>
                    <button @click="logout"
                        class="flex items-center p-4 rounded-lg text-white hover:bg-secondary navbarElement w-full group">
                        <i class="fas fa-sharp fa-right-from-bracket mr-1.5 text-xl fa-fw"></i>
                        <span class="ms-3 uppercase">Atslēgties</span>
                    </button>
                </li>
            </ul>
        </div>
    </aside>
</template> -->

<template>
    <nav class="fixed top-0 z-50 w-full bg-primary border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start rtl:justify-end">
                    <button @click="toggleSidebar"
                        class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd" fill-rule="evenodd"
                                d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                            </path>
                        </svg>
                    </button>
                    <Link href="/admin-dashboard" class="flex items-center">
                    <img :src="getImageUrl('piffdeals_text_accent.svg')" alt="Text" class="h-10 mr-0 select-none"
                        draggable="false" />
                    </Link>
                </div>
                <div class="flex items-center">
                    <div class="flex items-center ms-3 text-white text-2xl">
                        {{ $page.props.auth.user.name }}
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <aside id="logo-sidebar" :class="{
                        'translate-x-0': isSidebarOpen,
                        '-translate-x-full': !isSidebarOpen,
                    }"
        class="fixed top-0 left-0 z-40 w-64 h-screen pt-16 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700"
        aria-label="Sidebar">
        <div class="h-full px-3 py-4 overflow-y-auto bg-primary">
            <ul class="space-y-2 font-medium">
                <ul class="space-y-2 font-medium">
                    <li>
                        <Link href="/admin-dashboard" :class="{
                        'navbar-element':
                            props.currentPage === 'statistics',
                    }" class="flex items-center p-4 rounded-lg text-white hover:bg-secondary group navbarElement">
                        <i class="fas fa-sharp fa-chart-pie mr-1.5 text-xl fa-fw"></i>
                        <span class="ms-3 uppercase">Statistika</span>
                        </Link>
                    </li>
                    <li>
                        <Link href="/admin-orders" :class="{
                        'navbar-element':
                            props.currentPage === 'orders',
                    }" class="flex items-center p-4 rounded-lg text-white hover:bg-secondary group navbarElement">
                        <i class="fas fa-sharp fa-bag-shopping mr-1.5 text-xl fa-fw"></i>
                        <span class="ms-3 uppercase">Pasūtījumi</span>
                        </Link>
                    </li>
                    <li>
                        <button @click="toggleCatalogDropdown" :class="[
                        'flex items-center p-4 rounded-lg text-white hover:bg-secondary group w-full scale-on-hover',
                        { highlighted: catalogDropdownOpen },
                    ]">
                            <i class="fa-regular fa-sharp fa-folder-open mr-1.5 text-xl fa-fw"></i>
                            <span class="ms-3 uppercase">Katalogs</span>
                            <i :class="{ 'rotate-180': catalogDropdownOpen }" class="fas fa-chevron-down ml-auto"></i>
                        </button>
                        <ul v-show="catalogDropdownOpen" class="pl-4 space-y-1 bg-accent rounded-t-0 rounded-b-md">
                            <li>
                                <Link href="/admin-goods" class="block p-2 rounded text-white uppercase ms-9">Preces
                                </Link>
                            </li>
                            <li>
                                <Link href="/admin-categories" class="block p-2 rounded text-white uppercase ms-9">
                                Kategorijas</Link>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <Link href="/admin-users" :class="{
                        'navbar-element': props.currentPage === 'users',
                    }" class="flex items-center p-4 rounded-lg text-white hover:bg-secondary group navbarElement">
                        <i class="far fa-sharp fa-user mr-1.5 text-xl fa-fw"></i>
                        <span class="ms-3 uppercase">Klienti</span>
                        </Link>
                    </li>
                    <li>
                        <button @click="toggleSettingsDropdown" :class="[
                        'flex items-center p-4 rounded-lg text-white hover:bg-secondary group w-full scale-on-hover',
                        { highlighted: settingsDropdownOpen },
                    ]">
                            <i class="fas fa-sharp fa-gear mr-1.5 text-xl fa-fw"></i>
                            <span class="ms-3 uppercase">Iestatījumi</span>
                            <i :class="{ 'rotate-180': settingsDropdownOpen }" class="fas fa-chevron-down ml-auto"></i>
                        </button>
                        <ul v-show="settingsDropdownOpen" class="pl-4 space-y-1 bg-accent rounded-t-0 rounded-b-md">
                            <li>
                                <Link href="/admin-administrators" class="block p-2 rounded text-white uppercase ms-9">
                                Administratori</Link>
                            </li>
                            <li>
                                <Link href="/admin-logfiles" class="block p-2 rounded text-white uppercase ms-9">
                                Žurnālfaili
                                </Link>
                            </li>
                        </ul>
                    </li>
                </ul>

                <ul class="space-y-2 font-medium pt-4 border-t border-gray-200">
                    <li>
                        <Link href="/"
                            class="flex items-center p-4 rounded-lg text-white hover:bg-secondary group navbarElement">
                        <i class="fas fa-sharp fa-house mr-1.5 text-xl fa-fw"></i>
                        <span class="ms-3 uppercase">Uz klienta lapu</span>
                        </Link>
                    </li>

                    <li>
                        <Link href="/profile"
                            class="flex items-center p-4 rounded-lg text-white hover:bg-secondary group navbarElement">
                        <i class="fa-regular fa-sharp fa-address-card mr-1.5 text-xl fa-fw"></i>
                        <span class="ms-3 uppercase">Profils</span>
                        </Link>
                    </li>

                    <li>
                        <button @click="logout"
                            class="flex items-center p-4 rounded-lg text-white hover:bg-secondary navbarElement w-full group">
                            <i class="fas fa-sharp fa-right-from-bracket mr-1.5 text-xl fa-fw"></i>
                            <span class="ms-3 uppercase">Atslēgties</span>
                        </button>
                    </li>
                </ul>
            </ul>
        </div>
    </aside>
</template>
