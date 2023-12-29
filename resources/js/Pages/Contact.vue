<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Navbar from './Navbar.vue';
import Footer from './Footer.vue';
import Breadcrumbs from '../Components/Breadcrumbs.vue';
</script>

<template>
    <Head title="Sīkdatņu politika" />
    <Navbar />
    <div class="cross-patternSVGLight">
        <div class="py-8">
            <Breadcrumbs currentPage="Kontakti" />
        </div>

        <section
            class="container mx-auto max-w-screen-xl border border-gray-200 rounded-md bg-whiter shadow-md py-3 px-3 pl-6">
            <section class="">
                <div class="py-8 lg:py-16 px-4 mx-auto max-w-screen-md">
                    <h1 class="mb-4 text-4xl tracking-tight text-center">Sazinies ar mums</h1>
                    <p class="mb-8 lg:mb-16 font-light text-center text-gray-500 dark:text-gray-400 sm:text-xl">
                        Saskārāties ar tehniskām grūtībām, lietojot mūsu tiešsaistes e-veikalu? Vēlaties dalīties ar
                        atsauksmēm par mūsu tīmekļa vietnes funkcijām? Mēs esam šeit, lai jums palīdzētu!</p>
                    <form action="#" class="space-y-8">
                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-textColor">E-pasts: <span
                                    class="text-red-600">*</span></label>
                            <input type="email" id="email"
                                class="placeholder-gray-300 shadow-sm bg-whiter border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 "
                                placeholder="piemers@epasts.lv" required>
                        </div>
                        <div>
                            <label for="subject" class="block mb-2 text-sm font-medium text-textColor">Temats: <span
                                    class="text-red-600">*</span></label>
                            <input type="text" id="subject"
                                class="placeholder-gray-300 block p-3 w-full text-sm text-gray-900 bg-whiter rounded-lg border border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500"
                                placeholder="Kā mēs varam palīdzēt?" required>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="message" class="block mb-2 text-sm font-medium text-textColor">Jūsu ziņojums: <span
                                    class="text-red-600">*</span></label>
                            <textarea id="message" rows="6"
                                class="placeholder-gray-300 block p-2.5 w-full text-sm text-gray-900 bg-whiter rounded-lg shadow-sm border border-gray-300 focus:ring-primary-500 focus:border-primary-500"
                                placeholder="Leave a comment..."></textarea>
                        </div>
                        <button type="submit"
                            class="mt-6 inline-flex items-center font-semibold border text-primary border-primary hover:text-whiter hover:bg-primary rounded-lg text-sm px-5 py-2.5 text-center mb-2">Sūtīt
                            ziņu</button>
                    </form>
                </div>
            </section>


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
</div></template>