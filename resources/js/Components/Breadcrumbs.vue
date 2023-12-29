<script setup>
import { Link } from '@inertiajs/vue3'
const props = defineProps(['currentPage'])
</script>

<template>
    <nav class="container mx-auto max-w-screen-xl flex py-3 text-gray-700 border border-gray-200 rounded-md bg-whiter shadow-md"
        aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
            <li class="inline-flex items-center">
                <Link href="/" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary"><i class="fas fa-cart-shopping text-text-gray-400 mr-2"></i>Veikals</Link>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="rtl:rotate-180  w-3 h-3 mx-1 text-gray-400" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 9 4-4-4-4" />
                    </svg>
                    <span class="ms-1 text-sm font-medium text-primary md:ms-2">{{ currentPage }}</span>
                </div>
            </li>
        </ol>
    </nav>
</template>