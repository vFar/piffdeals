<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Navbar from '../Components/Navbar.vue';
import Footer from '../Components/Footer.vue';
import Breadcrumbs from '../Components/Breadcrumbs.vue';
import ScrollTopBtn from '../Components/ScrollToTopBtn.vue';
</script>

<template>
    <Head title="Par mums" />
    <Navbar />
    <div class="cross-patternSVGLight">
        <div class="py-8">
            <Breadcrumbs currentPage="Par mums" />
        </div>

        <section
            class="container mx-auto max-w-screen-xl border border-gray-200 rounded-xl bg-whiter shadow-md py-3 px-3 pl-6">
            <section class="">
                <h1>about us page hsedrfghsdfghsdfghj</h1>
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
        <ScrollTopBtn />
        <Footer />
</div></template>