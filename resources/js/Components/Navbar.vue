<script setup>
import { Link } from '@inertiajs/vue3';
import ThemeSwitch from '../Components/ThemeSwitch.vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});

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
    transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;
    /* Transition for transform, shadow, and background color */
}

.navbar-hrefs:hover,
.navbar-hrefs:focus {
    transform: scale(1.06);
    /* Scale effect on hover/focus */
    box-shadow: 0 4px 8px -2px rgb(0 0 0 / 0.2);
    /* Subtle shadow for depth */
    background: #4F91ED;
    /* Initial background color for navbar elements */
}

.navbar-hrefs:after {
    display: block;
    content: '';
    width: 100%;
    /* Ensures the underline covers the full width of the text */
    border-bottom: solid 3px #EBF3FF;
    /* Color of the underline */
    transform: scaleX(0);
    transition: transform 250ms ease-in-out;
}

.navbar-hrefs:hover:after,
.navbar-hrefs:focus:after {
    transform: scaleX(1);
    /* Ensures underline animation on hover/focus */
}
</style>

<template>
    <header class="bg-whiter text-black p-1">
        <div class="container text-textColor mx-auto max-w-screen-xl flex justify-between items-center">
            <div class="flex space-x-12">
                <a href="mailto:info@piffdeals.lv" class="hover:underline">
                    <h1 class="text-sm text-textColor">info@piffdeals.lv</h1>
                </a>
                <a href="tel:+37129999999" class="hover:underline">
                    <h1 class="text-sm text-textColor">+371 29 999 999</h1>
                </a>
            </div>

            <!-- will need reworking, potential web translators: google translate API -->
            <div class="flex space-x-4">

                <ThemeSwitch />

                <select
                    class="py-1 pr-7 text-sm bg-whiter border-none font-semibold hover:bg-lightBlueBackground focus:ring-whiter rounded-lg">
                    <option selected>LV</option>
                    <option>RU</option>
                    <option>EN</option>
                </select>

            </div>
        </div>
    </header>

    <nav class="bg-primary py-4 sticky top-0 z-50 shadow-md">
        <div class="container mx-auto max-w-screen-xl grid grid-cols-3 justify-between items-center gap-6">

            <!-- Logo -->
            <Link href="/" class="flex items-center shrink-0">
            <img :src="getImageUrl('S-2.png')" alt="Logo" class="h-10 mr-4">
            <img :src="getImageUrl('piffdeals_text_accent.svg')" alt="Text" class="h-10 mr-0">
            </Link>

            <!-- Search Bar -->
            <div class="flex-grow">
                <form class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-textColor" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                        </svg>
                    </div>
                    <input type="search" id="default-search"
                        class="w-full py-2 pl-10 pr-4 text-sm text-textColor border border-gray-300 rounded-xl bg-lightBlueBackground shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                        placeholder="Meklēt" required>
                </form>
            </div>

            <!-- Navigation Links -->
            <ul class="flex space-x-4 items-center justify-evenly">
                <!-- Always show Profile link -->
                <li class="text-white uppercase text-base ">
                    <Link v-if="$page.props.auth.user" href="/profile" class="navbar-hrefs">
                        <i class="far fa-circle-user text-xl fa-fw"></i>
                    </Link>
                    <Link v-else href="/login" class="navbar-hrefs">
                        <i class="fas fa-right-to-bracket text-xl fa-fw"></i>
                    </Link>
                </li>

                <!-- Show Grozs and Vēlmes only for guests (no user logged in) or users with role_id = 1 -->
                <li v-if="!$page.props.auth.user || $page.props.auth.user.role_id === 1"
                    class="text-white uppercase text-base">
                    <Link href="/cart" class="navbar-hrefs"><i class="fas fa-sharp fa-cart-shopping text-xl fa-fw"></i><span></span></Link>
                </li>
                <li v-if="!$page.props.auth.user || $page.props.auth.user.role_id === 1"
                    class="text-white uppercase text-base">
                    <Link href="/wishlist" class="navbar-hrefs"><i class="far fa-sharp fa-heart text-xl fa-fw"></i><span></span></Link>
                </li>

                <!-- Show Admin only for users with role_id = 2 -->
                <li v-if="$page.props.auth.user && $page.props.auth.user.role_id === 2"
                    class="text-white uppercase text-base">
                    <Link href="/admin-dashboard" class="navbar-hrefs"><i class="fa fa-tools text-xl"></i><span></span></Link>
                </li>
            </ul>
        </div>
    </nav>

    <div class="flex justify-center items-center bg-slate-600 tracking-wider uppercase text-white py-1">
        <h1 class="font-semibold text-whiter">Atrodi visu nepieciešamo ar Piffdeals</h1>
    </div>
</template>

<script>
import { ref } from 'vue';

const getImageUrl = (filename) => {
    return `/storage/images/${filename}`;
};
</script>
