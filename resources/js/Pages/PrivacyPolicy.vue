<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Navbar from './Navbar.vue';
import Footer from './Footer.vue';
import Breadcrumbs from '../Components/Breadcrumbs.vue';
</script>

<template>
    <Head title="Privātuma politika" />
    <Navbar />
    <div class="cross-patternSVGLight">
        <div class="py-8">
            <Breadcrumbs currentPage="Privātuma politika" />
        </div>

        <div class="container mx-auto max-w-screen-xl border border-gray-200 rounded-md bg-whiter shadow-md py-3 px-3 pl-6">
            <section class="legal">
                <br>
                <p>Šī privātuma politika informē par privātuma praksi un personas datu apstrādes principiem
                    saistībā ar <span class="text-primary">[uzņēmuma nosaukums]</span> mājas lapu un pakalpojumiem. Lai
                    sazinātos saistībā ar datu apstrādes jautājumiem, lūdzu rakstiet e-pastu uz
                    <a class="text-primary underline" href="mailto:info@piffdeals.lv">info@piffdeals.lv</a>.
                </p><br>

                <h1>Kādu informāciju mēs iegūstam?</h1><br>

                <p>Mēs iegūstam tādus personas datus, ko Jūs mums brīvprātīgi sniedzat ar e-pasta
                    starpniecību, aizpildot tīmeklī bāzētās anketas un citā tiešā saziņā ar Jums.</p>

                <p>Iesniedzot pasūtījumu, Jums jānorāda vārds, uzvārds, kontaktinformācija, piegādes adrese,
                    <span class="text-primary">[papildus datu uzskaitījums]</span> un cita informācija kuru Jūs vēlaties
                    sniegt.
                </p><br>

                <h1>Kā mēs izmantojam iegūtos personas datus?</h1><br>

                <p>Mēs varam izmantot iegūtos personas datus, lai</p><br>
                <ul class="list-disc px-4 list-inside text-textColor">
                    <li>sniegtu Jums jūsu pieprasītos pakalpojumus un informāciju,</li>
                    <li>apstrādātu Jūsu pasūtījumus un noformētu nepieciešamos dokumentus,</li>
                    <li>sniegtu Jums efektīvu klientu atbalstu,</li>
                    <li>palīdzētu novērst apdraudējumu vai krāpnieciskas darbības,</li>
                    <li>nosūtītu Jums informatīvus ziņojumus, ja esat nepārprotami piekrituši tādus saņemt,</li>
                    <li>ievērotu normatīvo aktu prasības,</li>
                    <li><span class="text-primary">[papildus nolūki, ja tādi ir]</span>.</li>
                </ul><br>

                <p>Mēs varam nodot Jūsu informāciju trešajām personām, lai ievērotu normatīvo aktu prasības,
                    sadarbotos ar uzraudzības iestādēm, palīdzētu novērstu noziedzīgas darbības
                    un aizsargātu mūsu, Jūsu un citu personu likumīgās tiesības.</p><br>

                <h1>Kā mēs aizsargājam personas datus?</h1><br>

                <p>Jūsu personas datu aizsardzībai Mēs izmantojam dažādus tehniskus un organizatoriskus drošības
                    pasākumus. Jūsu personas dati ir pieejami ierobežotam cilvēku skaitam,
                    tikai pilnvarotām personām.</p><br>


                <h1>Cik ilgi mēs glabājam personas datus?</h1><br>

                <p>Mēs glabājam Jūsu personas datus tik ilgi, cik ilgi tie ir mums nepieciešami
                    atbilstoši to ieguves mērķim un kā to pieļauj vai nosaka normatīvo aktu prasības.</p><br>

                <h1>Kā mēs izmantojam sīkdatnes?</h1><br>

                <p>Sīkdatnes ir nelielas teksta datnes, ko Jūsu apmeklētās mājas lapas saglabā jūsu datorā.
                    Tās tiek izmantotas, lai nodrošinātu mājas lapas darbību, kā arī lai sniegtu
                    informāciju vietnes īpašniekiem.</p><br>

                <p>Šī mājas lapa var iestatīt sekojošas sīkdatnes:</p>

                <ul class="list-inside list-disc text-justify text-textColor px-4">
                    <li><b>Funkcionālās sīkdatnes.</b> Šīs sīkdatnes ir nepieciešamas, lai Jūs spētu pārvietoties
                        mājas lapā un lietot tās funkcijas. Bez šim sīkdatnēm mēs nevaram nodrošināt Jūsu
                        pieprasītos pakalpojumus, piemēram, preču groza funkcionalitāti.</li>
                    <li><b>Google Analytics sīkdatnes.</b> Šīs sīkdatnes lieto, lai iegūtu mūsu mājas lapas
                        apmeklējuma statistiku. Mēs lietojam šo informāciju, lai uzlabotu vietnes darbību
                        un reklāmas pasākumus.</li>
                    <li><b>Mērķētas reklāmas rīku sīkdatnes.</b> Šīs sīkdatnes lieto, lai paaugstinātu reklāmas
                        efektivitāti un rādītu reklāmas, kas, visticamāk, Jūs interesēs visvairāk.</li>
                    <li><b>Trešās puses pakalpojumu sniedzēja sīkdatnes.</b> Sīkdatnes var iestatīt šādi šajā
                        mājas lapā lietotie trešo pušu pakalpojumi: Facebook poga "Patīk", YouTube video,
                        <span class="hlight">[citi pakalpojumi]</span>. Dažas no šīm sīkdatnēm var tikt izmantotas, lai
                        sekotu līdzi
                        Jūsu darbībām citās mājas lapās, un mēs tās nevaram kontrolēt, jo šīs sīkdatnes nav
                        iestatījusi mūsu mājas lapa
                    </li>
                </ul><br>

                <h1>Kā atteikties no sīkdatnēm?</h1><br>

                <p>Lai atteiktos no sīkdatņu saņemšanas, Jūs varat izmantojot privātās pārlūkošanas režīmu,
                    kuru nodrošina lielākā daļa pārlūkprogrammu (privāts logs, inkognito logs vai <i>InPrivate</i> logs).
                    Jebkādas sīkdatnes, kas tiek izveidotas, darbojoties privātās pārlūkošanas režīmā, tiek
                    dzēstas, tiklīdz jūs aizverat visus pārlūka logus.</p>

                <p>Lai atteiktos no mērķētas reklāmas rādīšanai nepieciešamās informācijas iegūšanas un
                    lietošanas, Jūs varat izmantot bezmaksas rīku
                    <a class="text-primary underline" href="https://www.youronlinechoices.com/" target="_blank">Your Online
                        Choices</a>
                    vai <a class="text-primary underline" href="http://www.aboutads.info/"
                        target="_blank">YourAdChoices</a>.
                </p><br>

                <h1>Jūsu tiesības saistībā ar Jūsu personas datiem</h1><br>

                <p>Ja Jūs esat datu subjekts saskaņā ar <a class="underline text-primary"
                        href="https://ec.europa.eu/info/law/law-topic/data-protection_lv" target="_blank">ES VDAR</a>
                    (piemēram, Jūs esat ES pilsonis un sniedzat mums savus personas datus), Jums ir turpmāk
                    minētās tiesības saistībā ar saviem personas datiem:</p>

                <ul class="list-disc px-4 list-inside text-textColor text-justify">
                    <li><b>Tiesības piekļūt informācijai.</b> Jums ir tiesības saņemt informāciju par to,
                        kāpēc un kā tiek apstrādāti Jūsu personas dati. Jums ir tiesības bez maksas saņemt
                        mūsu rīcībā esošo jūsu personas datu kopiju plaši izmantotā elektroniskā formātā.</li>
                    <li><b>Tiesības labot.</b> Jums ir tiesības panākt neprecīzu vai nepilnīgu
                        personas datu labošanu vai papildināšanu bez nepamatotas kavēšanās.</li>
                    <li><b>Tiesības „tikt aizmirstam”.</b> Jums ir tiesības atsaukt savu piekrišanu
                        personas datu apstrādei un panākt savu personas datu dzēšanu bez nepamatotas
                        kavēšanās, tiklīdz dati vairs nav nepieciešami, lai sniegtu jūsu pieprasītos pakalpojumus un
                        ievērotu normatīvo aktu prasības.</li>
                    <li><b>Tiesības ierobežot apstrādi.</b> Jums ir tiesības panākt savu personas datu
                        apstrādes ierobežošanu, ja Jūs iebilstat pret to un mums nav leģitīmu pamatu
                        turpināt apstrādi, ja Jūs apstrīdat datu precizitāti, ja apstrāde ir pretlikumīga vai
                        ja Jūs pieprasāt celt, īstenot vai aizstāvēt likumīgas prasības.</li>
                    <li><b>Tiesības iebilst.</b> Jums ir tiesības jebkurā brīdī iebilst pret datu apstrādi,
                        ja vien tas nav nepieciešams sabiedrības interesēs veicamam uzdevumam vai apstrādei
                        nepastāv neapstrīdami likumīgs pamats.</li>
                    <li><b>Citas tiesības saskaņā ar VDAR.</b> Vairāk informācijas skatiet,
                        apmeklējot <a class="underline text-primary"
                            href="https://ec.europa.eu/info/law/law-topic/data-protection/reform/rights-citizens_lv"
                            target="_blank">ES datu aizsardzībai veltīto mājas lapu</a>.</li>
                </ul>

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
        </div>
        <Footer />
    </div>
</template>