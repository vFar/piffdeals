<script setup>
import { Link } from '@inertiajs/vue3';

</script>

<style>
/* Custom styles for the hover animation */

.footer-hrefs {
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
    <div class="bottom-0 static">
        <img :src="getImageUrl('footer_wave_svg.svg')" alt="footerWave" draggable="false" class="select-none w-full mt-12">
        <footer class="p-4 bg-primary">
            <div class="mx-auto max-w-screen-2xl px-6">
                <div class="flex justify-between">
                    <div class="mb-6">
                        <Link href="/" class="flex items-center">
                            <img :src="getImageUrl('piffdeals_logo_text_accent.svg')" draggable="false" alt="footerLogo"
                                class="select-none w-8/12">
                        </Link>
                    </div>
                    <div class="grid grid-cols-4 gap-8">
                        <div>
                            <h2 class="mb-6 text-sm font-bold text-lightBlueBackground uppercase">Internetveikals</h2>
                            <ul class="text-gray-400">
                                <li class="mb-4">
                                    <Link href="/privacy-policy" class="border-grow hover:text-whiter footer-hrefs">
                                    Privātuma politika</Link>
                                </li>
                                <li class="mb-4">
                                    <Link href="/terms-of-use" class="border-grow hover:text-whiter footer-hrefs">Lietošanas
                                    noteikumi</Link>
                                </li>
                                <li class="mb-4">
                                    <Link href="/cookies-information" class="border-grow hover:text-whiter footer-hrefs">
                                    Sīkdatnes</Link>
                                </li>
                            </ul>
                        </div>
                        <div>
                            <h2 class="mb-6 text-sm font-bold text-lightBlueBackground uppercase">Informācija</h2>
                            <ul class="text-gray-400">
                                <li class="mb-4">
                                    <Link href="/contact" class="border-grow hover:text-whiter footer-hrefs">Sazinies ar
                                    mums</Link>
                                </li>
                                <li>
                                    <Link href="/about-us" class="border-grow hover:text-whiter footer-hrefs">Par mums
                                    </Link>
                                </li>
                            </ul>
                        </div>
                        <div>
                            <h2 class="mb-6 text-sm font-bold text-lightBlueBackground uppercase">Profils</h2>
                            <ul class="text-gray-400">
                                <li class="mb-4">
                                    <Link href="/profile" class="border-grow hover:text-whiter footer-hrefs">Profils</Link>
                                </li>
                                <li v-if="$page.props.auth.user && $page.props.auth.user.role_id === 1" class="mb-4">
                                    <Link href="/cart" class="border-grow hover:text-whiter footer-hrefs">Iepirkumu grozs</Link>
                                </li>
                                <!-- Show Vēlmju saraksts for guests and users with role_id = 1 -->
                                <!-- Show ADMIN for users with role_id = 2 -->
                                <li v-if="$page.props.auth.user && $page.props.auth.user.role_id === 2" class="mb-4">
                                    <Link href="/admin-orders" class="border-grow hover:text-whiter footer-hrefs">ADMIN
                                    </Link>
                                </li>
                            </ul>
                        </div>
                        <div>
                            <h2 class="mb-6 text-sm font-bold text-lightBlueBackground uppercase">SIA FGHDKDFGKDFGIK</h2>
                            <ul class="text-gray-400">
                                <li class="mb-4">
                                    <a href="mailto:info@piffdeals.lv"
                                        class="border-grow hover:text-whiter footer-hrefs">info@piffdeals.lv</a>
                                </li>
                                <li class="mb-4">
                                    <a href="tel:+37129999999" class="border-grow hover:text-whiter footer-hrefs">+371 29
                                        999 999</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <hr class="my-6 border-accent sm:mx-auto lg:my-8" />
                <div class="sm:flex sm:items-center sm:justify-between">
                    <span class="text-sm text-gray-400 sm:text-center">© 2024 Piffdeals. Visas tiesības
                        aizsargātas.
                    </span>
                    <div class="flex mt-4 space-x-6 sm:justify-center sm:mt-0">
                        <a href="https://www.linkedin.com/in/markuss-balodis-918389265/" target="_blank"
                            class="text-3xl has-tooltip">
                            <i class="fa-brands fa-linkedin text-lightBlueBackground"></i>
                        </a>
                    </div>
                </div>
            </div>
        </footer>
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
