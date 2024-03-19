<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Navbar from '../Components/Navbar.vue';
import Footer from '../Components/Footer.vue';
import Breadcrumbs from '../Components/Breadcrumbs.vue';
</script>

<template>
    <Head title="Sīkdatņu politika" />
    <Navbar />
    <div class="cross-patternSVGLight bg-whiter">
        <div class="py-8">
            <Breadcrumbs currentPage="Sīkdatnes" />
        </div>

        <section
            class="container mx-auto max-w-screen-xl border border-gray-200 rounded-md bg-whiter shadow-md py-3 px-3 pl-6">
            <br>
            <p>Šī sīkdatņu politika informē par privātuma praksi un personas datu apstrādes principiem
                saistībā ar <span class="text-primary">[uzņēmuma nosaukums]</span> mājas lapu un pakalpojumiem. Lai
                sazinātos saistībā ar datu apstrādes jautājumiem, lūdzu rakstiet e-pastu uz
                <a class="text-primary underline" href="mailto:info@piffdeals.lv">info@piffdeals.lv</a>.
            </p><br>
            <h1>Kas ir sīkdatne?</h1><br>
            <p>Sīkdatne ir neliela teksta datne, kas tiek nosūtīta uz Jūsu datoru vai mobilo iekārtu tīmekļa vietnes
                apmeklēšanās laikā un ko tīmekļa vietne saglabā Jūsu datorā vai mobilajā ierīcē, kad Jūs atverat vietni.
                Katrā turpmākajā apmeklējuma reizē sīkdatnes tiek nosūtītas atpakaļ uz izcelsmes mājas lapu vai uz citu
                mājas lapu, kas atpazīst šo sīkdatni. Sīkdatnes darbojas kā konkrētas mājas lapas atmiņa, ļaujot šai
                lapai atcerēties Jūsu datoru nākamajās apmeklējuma reizēs, tai skaitā sīkdatnes var atcerēties Jūsu
                iestādījumus vai uzlabot lietotāja ērtības. Papildu informāciju par sīkdatnēm, kā arī kā tās iespējams
                pārvaldīt vai izdzēst, varat iegūt mājaslapā <a href="http://www.aboutcookies.org">www.aboutcookies.org</a>.
            </p><br>
            <h1>Kā izslēgt sīkdatnes?</h1><br>
            <p>Pēc vēlēšanās varat sīkdatnes kontrolēt un izdzēst. Plašāk lasiet šeit: <a class="text-primary underline"
                    href="http://www.aboutcookies.org">aboutcookies.org</a>. Jūs varat izdzēst visas sīkdatnes, kuras ir
                Jūsu datorā, un lielāko daļu pārlūkprogrammu var iestatīt tā, lai tiktu bloķēta sīkdatņu ievietošana
                datorā. Taču tādā gadījumā Jums manuāli būs jāpielāgo iestatījumi ikreiz, kad apmeklēsiet tīmekļa
                vietni, un turklāt pastāv iespējamība, ka daži pakalpojumi un funkcijas nedarbosies.</p>

            <Link href="/">
                <button type="button"
                    class="mt-6 inline-flex items-center font-semibold border text-primary border-primary hover:text-whiter hover:bg-primary rounded-lg text-sm px-5 py-2.5 text-center mb-2">
                    <svg class="w-[18px] h-[18px] mr-2 hover:text-whiter" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 14 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.3"
                            d="M13 5H1m0 0 4 4M1 5l4-4" />
                    </svg> Atgriezties uz veikalu
                </button>
            </Link>

        </section>
        <Footer />
    </div>
</template>