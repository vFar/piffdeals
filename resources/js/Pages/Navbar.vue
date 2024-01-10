<script setup>
    import { Link } from '@inertiajs/vue3';
    import ThemeSwitch from '../Components/ThemeSwitch.vue';
</script>

<template>
    <header class="bg-whiter text-black p-1">
        <div class="container text-textColor mx-auto max-w-screen-xl flex justify-between items-center">
            <div class="flex space-x-12">
                <a href="mailto:info@piffdeals.lv" class="hover:underline"><h1 class="text-sm text-textColor">info@piffdeals.lv</h1></a>
                <a href="tel:+37129999999" class="hover:underline"><h1 class="text-sm text-textColor">+371 29 999 999</h1></a>
            </div>


            <!-- will need reworking, potential web translators: google translate API -->
            <div class="flex space-x-4">
                
                <ThemeSwitch />
                
                <select
                    class="py-1 pr-7 text-sm bg-whiter border-none font-semibold focus:ring-whiter rounded-lg">
                    <option selected>LV</option>
                    <option>RU</option>
                    <option>EN</option>
                </select>

            </div>
        </div>
    </header>

    <nav class="bg-primary py-4 sticky top-0 z-50">
        <div class="container mx-auto max-w-screen-xl grid grid-cols-3 justify-between items-center gap-6">

            <!-- Logo -->
            <Link href="/" class="flex items-center">
                <img :src="getImageUrl('piffdeals_logo_accent.svg')" alt="Logo" class="h-10 mr-2">
                <img :src="getImageUrl('piffdeals_text_accent.svg')" alt="Text" class="h-10 mr-0">
            </Link>

            <!-- Search Bar -->
            <form class="mx-full ml-[-80px]">
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-textColor"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                        </svg>
                    </div>
                    <input type="search" id="default-search"
                        class="w-full p-2 ps-10 text-sm text-textColor border border-gray-300 rounded-md bg-lightBlueBackground focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Meklēt" required>
                </div>
            </form>

            <!-- Navigation Links -->
            <ul class="flex space-x-10">
                <li class="text-white uppercase text-base hover:text-secondary"><a
                        href="#"><i class="far fa-circle-user mr-1.5 text-xl"></i><span>Profils</span></a></li>
                <li class="text-white uppercase text-base hover:text-secondary"><a
                        href="#"><i class="fas fa-sharp fa-cart-shopping mr-1.5 text-xl"></i><span>Grozs</span></a></li>
                <li class="text-white uppercase text-base hover:text-secondary"><a
                        href="#"><i class="far fa-sharp fa-heart mr-1.5 text-xl"></i><span>Vēlmes</span></a></li>
            </ul>
        </div>
    </nav>

    <div class="flex justify-center items-center bg-slate-600 tracking-wider uppercase text-white py-1">
        <h1 class="font-semibold text-whiter">Atrodi visu nepieciešamo ar Piffdeals</h1>
    </div>
</template>

<script>
export default {
    methods: {
        getImageUrl(filename) {
            return `/storage/images/${filename}`;
        },
    },
};
</script>
