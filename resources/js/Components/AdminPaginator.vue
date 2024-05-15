<template>
    <ul class="inline-flex items-center -space-x-px text-sm">
        <li v-for="link in props.links" :key="link.label">
            <Link :href="link.url || '#'" 
                  v-html="getLabel(link)"
                  class="px-4 py-2 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                  :class="{
                      'rounded-l-lg': link.label === 'pagination.previous',
                      'rounded-r-lg': link.label === 'pagination.next',
                      'bg-blue-50 text-blue-600 border-blue-600': link.active,
                      'cursor-default opacity-50': !link.url
                  }">
            </Link>
        </li>
    </ul>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    links: Array
});

const getLabel = (link) => {
    if (!link.url) {
        return '<i class="fas fa-times"></i>';  // FontAwesome icon for defective links
    }
    switch (link.label) {
        case 'pagination.next':
            return '<i class="fas fa-caret-right"></i>';  // FontAwesome icon for "Next"
        case 'pagination.previous':
            return '<i class="fas fa-caret-left"></i>';  // FontAwesome icon for "Previous"
        default:
            return link.label;  // Display the page number or label as is
    }
};
</script>
