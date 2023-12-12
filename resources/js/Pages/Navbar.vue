


<template>
    <header class="bg-lightBlueBackground text-black p-1">
        <div class="container text-textColor mx-auto max-w-screen-xl flex justify-between items-center">
            <div class="flex space-x-12">
                <h1 class="text-sm">info@piffdeals.lv</h1>
                <h1 class="text-sm">+371 29 999 999</h1>
            </div>


            <!-- will need reworking, potential web translators: google translate API -->
            <div>
                <select
                    class="py-1 pr-7 text-sm bg-lightBlueBackground border-none focus:ring-lightBlueBackground rounded-lg">
                    <option selected>LV</option>
                    <option>RU</option>
                    <option>EN</option>
                </select>
            </div>
        </div>
    </header>

    <nav class="bg-primary py-6">
        <div class="container mx-auto max-w-screen-xl flex items-center justify-between">

            <!-- Logo -->
                <a href="#" class="flex">
                    <img :src="getImageUrl('piffdeals_logo_accent.svg')" alt="Logo" class="h-10 mr-2">
                    <img :src="getImageUrl('piffdeals_text_accent.svg')" alt="Text" class="h-10">
                </a>

            <!-- Search Bar -->
            <div class="flex items-center">
                <input type="text" placeholder="Meklēt"
                    class="px-3 py-2 border rounded-xl block focus:outline-none focus:ring focus:border-accent" />
            </div>

            <!-- Navigation Links -->
            <ul class="flex space-x-10">

                <li class="text-white uppercase text-base hover:text-secondary transition duration-400 hover:ease-out"><a
                        href="#"><i class="far fa-circle-user mr-1.5 text-xl"></i><span>Profils</span></a></li>
                <li class="text-white uppercase text-base hover:text-secondary transition duration-400 hover:ease-out"><a
                        href="#"><i class="fas fa-sharp fa-cart-shopping mr-1.5 text-xl"></i><span>Grozs</span></a></li>
                <li class="text-white uppercase text-base hover:text-secondary transition duration-400 hover:ease-out"><a
                        href="#"><i class="far fa-sharp fa-heart mr-1.5 text-xl"></i><span>Vēlmes</span></a></li>
            </ul>

        </div>
    </nav>

    <div class="flex justify-center items-center bg-slate-600 tracking-wider uppercase text-white py-1">
        <h1>Atrodi visu nepieciešamo ar Piffdeals</h1>
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
