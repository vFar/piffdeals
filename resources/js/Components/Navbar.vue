<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import { Link } from "@inertiajs/vue3";
import ThemeSwitch from "../Components/ThemeSwitch.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import { Drawer, Button } from "ant-design-vue";

const getImageUrl = (filename) => {
    return `/storage/images/${filename}`;
};

const drawerOpen = ref(false);
const toggleDrawer = () => {
    drawerOpen.value = !drawerOpen.value;
};

watch(drawerOpen, (newValue) => {
    const body = document.body;
    if (newValue) {
        body.style.overflow = "hidden";
    } else {
        body.style.overflow = "";
    }
});

const props = defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});

const page = usePage();

// const pageProps = usePage().props;
// const authUser = computed(() => pageProps.auth.user);

// const isAuthenticated = computed(() => !!authUser.value);
// const isAdmin = computed(() => authUser.value?.role_id === 2);

const { auth } = usePage().props;

const activeCategories = computed(() => page.props.activeCategories);


// Computed property to safely access user information
const user = computed(() => auth.user || {});

// Computed properties for authentication state
const isAuthenticated = computed(() => !!auth.user);
const isAdmin = computed(() => user.value.role_id === 2);

const hasScrolled = ref(false);
function handleScroll() {
    // This checks if the page is scrolled more than 10 pixels
    hasScrolled.value = window.scrollY > 10;
}

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});

const handleLogout = () => {
    router.post(route('logout'), {}, {
        preserveScroll: true,
        preserveState: false,
        onSuccess: () => {
            // This will handle any additional logic you want after logout
            // For example, a redirect if not handled by backend:
            window.location.href = '/';
        }
    });
};

// Function to handle logout
</script>

<style>
/* Custom styles for the hover animation */

.navbar-hrefs {
    display: inline-block;
    text-decoration: none;
    font-family: sans-serif;
    padding: 0.75rem 1rem;
    /* Adjusted padding for better spacing */
    /* Margins for separation between navbar elements */
    border-radius: 0.5rem;
    /* Optional: Rounded corners */
    transition: transform 0.3s ease, box-shadow 0.3s ease,
        background-color 0.3s ease;
    /* Transition for transform, shadow, and background color */
}

.navbar-hrefs:hover,
.navbar-hrefs:focus {
    transform: scale(1.06);
    /* Scale effect on hover/focus */
    box-shadow: 0 4px 8px -2px rgb(0 0 0 / 0.2);
    /* Subtle shadow for depth */
    background: #4f91ed;
    /* Initial background color for navbar elements */
}

.navbar-hrefs:after {
    display: block;
    content: "";
    width: 100%;
    /* Ensures the underline covers the full width of the text */
    border-bottom: solid 3px #ebf3ff;
    /* Color of the underline */
    transform: scaleX(0);
    transition: transform 250ms ease-in-out;
}

.navbar-hrefs:hover:after,
.navbar-hrefs:focus:after {
    transform: scaleX(1);
    /* Ensures underline animation on hover/focus */
}

.navbar-hrefs i,
.navbar-hrefs svg {
    display: inline-block;
    /* Ensures elements are on the same line */
    vertical-align: middle;
    /* Aligns elements vertically */
}

.ant-drawer-content-wrapper {
    transform: translateY(
        var(--drawer-top-offset, 208px)
    ) !important; 
    border-top: 1px solid #FFF; 
    transition: none !important;
    height: 208px !important;
}


::v-deep .ant-drawer-mask {
    top: 208px !important; 
}

::v-deep .ant-drawer-content-wrapper {
    transition: none !important;
    transform: none !important;
}



</style>

<template>
    <!-- Top Header for Navigation Links and Language Options -->
    <header class="bg-whiter text-black">
        <div
            class="container mx-auto flex justify-between items-center px-6 py-1"
        >
            <div class="flex space-x-6">
                <a
                    href="mailto:info@piffdeals.lv"
                    class="text-textColor text-sm hover:underline"
                    >info@piffdeals.lv</a
                >
                <a
                    href="tel:+37129999999"
                    class="text-textColor text-sm hover:underline"
                    >+371 29 999 999</a
                >
            </div>
            <div class="space-x-6">
                <ThemeSwitch />
                <select
                    class="text-textColor bg-transparent border-none text-sm rounded cursor-pointer"
                >
                    <option>LV</option>
                    <option>RU</option>
                    <option>EN</option>
                </select>
            </div>
        </div>
    </header>

    <!-- Main Header with Logo -->
    <header :class="['bg-white text-black sticky top-0 z-50 w-full', hasScrolled ? 'shadow-lg' : '']">
        <div class="container mx-auto flex justify-between py-4 px-6">
            <Link href="/" class="flex items-center shrink-0">
                <img
                    :src="getImageUrl('S-3.png')"
                    alt="Logo"
                    class="h-10 mr-4"
                />
                <img
                    :src="getImageUrl('piffdeals_text_primary.svg')"
                    alt="Piffdeals"
                    class="h-10 mr-0"
                />
            </Link>

            <Link href="/login">
                <button
                    v-if="!isAuthenticated"
                    class="relative h-12 w-40 overflow-hidden border border-primary text-primary shadow-2xl transition-all duration-200 before:absolute rounded-lg before:bottom-0 before:left-0 before:right-0 before:top-0 before:m-auto before:h-0 before:w-0 before:rounded-sm before:bg-primary before:duration-300 before:ease-out hover:text-white hover:shadow-primary hover:before:h-40 hover:before:w-40 hover:before:opacity-80"
                >
                    <span class="relative z-10 uppercase">Pieslēgties</span>
                </button>
            </Link>
        </div>
    </header>

    <nav class="bg-primary py-4 top-0 z-50">
        <div
            class="container mx-auto max-w-screen-2xl px-6 flex justify-between items-center"
        >
            <!-- Good Categories on the left -->
            <div class="flex space-x-8">
                <!-- component -->
                <!-- :href="`/category/${category.id}`" -->

                <button
                    v-for="category in activeCategories"
                    :key="category.id"
                    @click.stop="toggleDrawer"
                    class="relative inline-flex uppercase items-center justify-center leading-normal no-underline pb-1 text-white font-sans font-bold text-base uppercase hover:text-gray-300 transition group"
                >
                    {{ category.name }}
                    <span
                        class="absolute bottom-0 left-0 w-full h-0.5 bg-neutral-700 origin-bottom-right transform transition duration-200 ease-out scale-x-0 group-hover:scale-x-100 group-hover:origin-bottom-left"
                    ></span>
                </button>
            </div>

            <!-- Search Bar and Navigation Links on the right -->
            <div class="flex items-center">
                <!-- Search Bar -->
                <div class="flex w-80">
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
                            id="default-search"
                            class="w-72 py-2 pl-10 pr-4 text-sm text-textColor border border-gray-300 rounded-xl bg-white shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                            placeholder="Meklēt starp *skaits* precēm"
                            required
                        />
                    </form>
                </div>

                <!-- Navigation Links -->

                <ul class="flex items-center space-x-3">
                    <!-- Always show Profile link -->

                    <!-- Show Grozs and Vēlmes only for guests (no user logged in) or users with role_id = 1 -->
                    <li v-if="!isAdmin" class="text-white uppercase text-base">
                        <Link href="/cart" class="navbar-hrefs"
                            ><i
                                class="fas fa-sharp fa-cart-shopping text-xl fa-fw"
                            ></i
                            ><span></span
                        ></Link>
                    </li>
                    <li v-if="!isAdmin" class="text-white uppercase text-base">
                        <Link href="/wishlist" class="navbar-hrefs"
                            ><i class="far fa-sharp fa-heart text-xl fa-fw"></i
                            ><span></span
                        ></Link>
                    </li>

                    <!-- Show Admin only for users with role_id = 2 -->
                    <li v-if="isAdmin" class="text-white uppercase text-base">
                        <Link href="/admin-dashboard" class="navbar-hrefs"
                            ><i class="fa fa-tools text-xl fa-fw"></i
                            ><span></span>
                        </Link>
                    </li>

                    <li class="text-white uppercase text-base">
                        <!-- Conditionally render Dropdown if user is authenticated -->
                        <div v-if="isAuthenticated">
                            <Dropdown align="right" width="60">
                                <template #trigger>
                                    <button
                                        type="button"
                                        class="inline-flex items-center px-3 py-2 border border-transparent navbar-hrefs text-sm leading-4 font-medium rounded-xl text-gray-500"
                                    >
                                        <i
                                            class="far fa-circle-user text-xl fa-fw text-white"
                                        ></i>
                                        <svg
                                            class="ms-2 -me-0.5 h-4 w-4 text-white"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20"
                                            fill="currentColor"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </button>
                                </template>
                                <template #userinfo>
                                    <div>{{ $page.props.auth.user.name }}</div>
                                    <div class="font-medium truncate">
                                        {{ $page.props.auth.user.email }}
                                    </div>
                                </template>
                                <template #links>
                                    <DropdownLink
                                        :href="route('profile.edit')"
                                        class="uppercase"
                                        >IEPIRKUMU GROZS</DropdownLink
                                    >
                                    <DropdownLink
                                        :href="route('profile.edit')"
                                        class="uppercase"
                                        >VĒLMJU SARAKSTS</DropdownLink
                                    >
                                    <DropdownLink
                                        :href="route('profile.edit')"
                                        class="uppercase"
                                        >PROFILS</DropdownLink
                                    >
                                </template>
                                <template #actions>
                                    <DropdownLink
                                        href="/admin-dashboard"
                                        v-if="
                                            $page.props.auth.user &&
                                            $page.props.auth.user.role_id === 2
                                        "
                                        class="uppercase"
                                        >ADMIN</DropdownLink
                                    >
                                    <DropdownLink
                                    @click="handleLogout"

                                        as="button"
                                        class="uppercase"
                                    >
                                        ATSLĒGTIES
                                    </DropdownLink>
                                </template>
                            </Dropdown>
                        </div>

                        <!-- Show Link to /login if user is not authenticated -->
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- <div id="drawer-top-example" class="fixed top-0 left-0 right-0 z-40 w-full p-4 bg-white dark:bg-gray-800"
         :class="{'translate-y-0': drawerOpen, '-translate-y-full': !drawerOpen}"
         transition="transform ease 0.3s"
         aria-labelledby="drawer-top-label">
        <h5 id="drawer-top-label" class="text-base font-semibold text-gray-500 dark:text-gray-400">
            Category Details
        </h5>
        <p class="max-w-lg mb-6 text-sm text-gray-500 dark:text-gray-400">
            Here you can include more detailed information about the selected category, 
            like linked groups and attributes.
        </p>
        <a href="#" class="px-4 py-2 text-sm font-medium text-center text-gray-900 bg-white border border-gray-200 rounded-lg focus:outline-none hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
            Learn more
        </a>
    </div> -->

    <Drawer
        v-model:visible="drawerOpen"
        placement="top"
        :closable="false"
        :style="{ backgroundColor: '#0068FF' }"
        :maskStyle="{ top: '208px' }"
        @close="() => (drawerOpen = false)"
        
    >
        <div class="container mx-auto max-w-screen-2xl flex justify-between items-center">
            <p class="text-whiter font-semibold">
                Here you can include more detailed information about the selected
                category, like linked groups and attributes.
            </p>
        </div>
    </Drawer>
</template>
