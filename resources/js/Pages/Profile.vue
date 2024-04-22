<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Navbar from '../Components/Navbar.vue';
import Footer from '../Components/Footer.vue';
import Breadcrumbs from '../Components/Breadcrumbs.vue';
</script>

<template>

    <Head title="Profils" />
    <Navbar />
    <div class="cross-patternSVGLight bg-whiter">
        <section
            class="container mx-auto max-w-screen-xl border border-gray-200 rounded-xl bg-whiter shadow-md py-3 px-3 pl-6">
        </section>
        <Footer />
    </div>
</template>