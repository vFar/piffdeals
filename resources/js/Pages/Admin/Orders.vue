<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Ziggy } from '@/ziggy';
import route from 'ziggy-js';

import AdminNavbar from '@/Components/AdminNavbar.vue';

const getImageUrl = (filename) => {
    return `/storage/images/${filename}`;
};
const flashMessage = ref('');

</script>

<template>

    <Head title="PASŪTĪJUMI" />
    <div class="cross-patternSVGLight bg-whiter">
        <AdminNavbar currentPage="orders"/>
    </div>
</template>
