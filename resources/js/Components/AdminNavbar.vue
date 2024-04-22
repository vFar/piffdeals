<script setup>
import { Link, usePage, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';


const form = useForm({
    // initial form fields
});

const logout = () => {
    form.post(route('logout'));
};


const getImageUrl = (filename) => {
    return `/storage/images/${filename}`;
};

const props = defineProps(['currentPage'])

</script>

<template>
    <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar"
        type="button"
        class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-whiter rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 bg-primary">
        <span class="sr-only">Open sidebar</span>
        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg">
            <path clip-rule="evenodd" fill-rule="evenodd"
                d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
            </path>
        </svg>
    </button>

    <aside id="logo-sidebar"
        class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0"
        aria-label="Sidebar">
        <div class="h-full px-3 py-4 overflow-y-auto bg-primary">
            <Link href="/admin-dashboard" class="flex items-center mb-5 ">
            <img :src="getImageUrl('piffdeals_text_accent.svg')" alt="Text" class="h-10 mr-0">
            </Link>
            <ul class="space-y-2 font-medium pt-4 mb-4 border-t border-gray-200">
                <li>
                    <Link href="/admin-dashboard" :class="{ 'navbar-element': props.currentPage === 'statistics' }"
                        class="flex items-center p-4 rounded-lg text-white hover:bg-accent group navbarElement">
                    <i class="fas fa-sharp fa-chart-pie mr-1.5 text-xl fa-fw"></i>
                    <span class="ms-3 uppercase">Statistika</span>
                    </Link>
                </li>
                <li>
                    <Link href="/admin-orders" :class="{ 'navbar-element': props.currentPage === 'orders' }"
                        class="flex items-center p-4 rounded-lg text-white hover:bg-accent group navbarElement">
                    <i class="fas fa-sharp fa-bag-shopping mr-1.5 text-xl fa-fw"></i>
                    <span class="ms-3 uppercase">Pasūtījumi</span>
                    </Link>
                </li>
                <li>
                    <Link href="/admin-goods" :class="{ 'navbar-element': props.currentPage === 'goods' }"
                        class="flex items-center p-4 rounded-lg text-white hover:bg-accent group navbarElement">
                    <i class="fa-brands fa-sharp fa-buffer mr-1.5 text-xl fa-fw"></i>
                    <span class="ms-3 uppercase">Preces</span>
                    </Link>
                </li>
                <li>
                    <Link href="/admin-users" :class="{ 'navbar-element': props.currentPage === 'users' }"
                        class="flex items-center p-4 rounded-lg text-white hover:bg-accent group navbarElement">
                    <i class="far fa-sharp fa-user mr-1.5 text-xl fa-fw"></i>
                    <span class="ms-3 uppercase">Lietotāji</span>
                    </Link>
                </li>
                <li>
                    <Link href="/admin-logfiles" :class="{ 'navbar-element': props.currentPage === 'logfiles' }"
                        class="flex items-center p-4 rounded-lg text-white hover:bg-accent group navbarElement">
                    <i class="fas fa-sharp fa-file mr-1.5 text-xl fa-fw"></i>
                    <span class="ms-3 uppercase">Žurnālfaili</span>
                    </Link>
                </li>
            </ul>

            <ul class="space-y-2 font-medium pt-4 border-t border-gray-200">
                <li>
                    <Link href="/"
                        class="flex items-center p-4 rounded-lg text-white hover:bg-accent group navbarElement">
                    <i class="fas fa-sharp fa-house mr-1.5 text-xl fa-fw"></i>
                    <span class="ms-3 uppercase">Uz klienta lapu</span>
                    </Link>
                </li>

                <li>
                    <button @click="logout" class="flex items-center p-4 rounded-lg text-white hover:bg-accent group">
                        <i class="fas fa-sharp fa-right-from-bracket mr-1.5 text-xl fa-fw"></i>
                        <span class="ms-3 uppercase">Atslēgties</span>
                    </button>
                </li>
            </ul>
        </div>
    </aside>
</template>
