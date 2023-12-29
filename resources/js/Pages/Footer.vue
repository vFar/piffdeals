<script setup>
    import { Link } from '@inertiajs/vue3';

</script>

<style>
/* Custom styles for the hover animation */

.footer-hrefs{
    display: inline-block;
    text-decoration: none;
    font-family: sans-serif;
}

.footer-hrefs:after {
    display: block;
    content: '';
    width: inherit;
    border-bottom: solid 3px #83AEEA;
    transform: scaleX(0);
    transition: transform 250ms ease-in-out;
}

.footer-hrefs:hover:after {
    transform: scaleX(1);
}
</style>


<template>
            <img :src="getImageUrl('footer_wave_svg.svg')" alt="Text" class="w-full mt-12">

    <footer class="p-4 bg-primary">
        <div class="mx-auto max-w-screen-xl">
            <div class="flex justify-between">
                <div class="mb-6">
                    <a href="/" class="flex items-center">
                        <img :src="getImageUrl('piffdeals_logo_text_accent.svg')" alt="Text" class="w-8/12">
                    </a>
                </div>
                <div class="grid grid-cols-4 gap-8">
                    <div>
                        <h2 class="mb-6 text-sm font-bold text-lightBlueBackground uppercase">Internetveikals</h2>
                        <ul class="text-gray-400">
                            <li class="mb-4">
                                <Link href="/privacy-policy" class="border-grow hover:text-whiter footer-hrefs">Privātuma politika</Link>
                            </li>
                            <li class="mb-4">
                                <Link href="/terms-of-use" class="border-grow hover:text-whiter footer-hrefs">Lietošanas noteikumi</Link>
                            </li>
                            <li class="mb-4">
                                <Link href="/cookies-information" class="border-grow hover:text-whiter footer-hrefs">Sīkdatnes</Link>
                            </li>

                        </ul>
                    </div>

                    <div>
                        <h2 class="mb-6 text-sm font-bold text-lightBlueBackground uppercase">Informācija</h2>
                        <ul class="text-gray-400">
                            <li class="mb-4">
                                <Link href="/contact" class="border-grow hover:text-whiter footer-hrefs">Sazinies ar mums</Link>
                            </li>
                            <li>
                                <Link href="/about-us" class="border-grow hover:text-whiter footer-hrefs">Par mums</Link>                            </li>
                        </ul>
                    </div>
                    <div>
                        <h2 class="mb-6 text-sm font-bold text-lightBlueBackground uppercase">Profils</h2>
                        <ul class="text-gray-400">
                            <li class="mb-4">
                                <a href="#" class="border-grow hover:text-whiter footer-hrefs">Profils</a>
                            </li>
                            <li class="mb-4">
                                <a href="#" class="border-grow hover:text-whiter footer-hrefs">Vēlmju saraksts</a>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h2 class="mb-6 text-sm font-bold text-lightBlueBackground uppercase">SIA FGHDKDFGKDFGIK</h2>
                        <ul class="text-gray-400">
                            <li class="mb-4">
                                <a href="mailto:info@piffdeals.lv" class="border-grow hover:text-whiter footer-hrefs">info@piffdeals.lv</a>
                            </li>
                            <li class="mb-4">
                                <a href="tel:+37129999999" class="border-grow hover:text-whiter footer-hrefs">+371 29 999 999</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <hr class="my-6 border-accent sm:mx-auto lg:my-8" />
            <div class="sm:flex sm:items-center sm:justify-between">
                <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">© 2024 Piffdeals. Visas tiesības
                    aizsargātas.
                </span>
                <div class="flex mt-4 space-x-6 sm:justify-center sm:mt-0">
                    <a href="https://www.linkedin.com/in/markuss-balodis-918389265/" class="text-3xl has-tooltip">
                        <i class="fa-brands fa-linkedin text-lightBlueBackground"></i>
                        <span
                            class='tooltip rounded shadow-lg p-1 bg-gray-100 text-xs text-textColor -mt-6 -ml-10'>Developer</span>
                    </a>
                </div>
            </div>
        </div>
    </footer>
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
