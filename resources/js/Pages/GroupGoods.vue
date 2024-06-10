<!-- <template>
    <div>
      <h1>Filtered Group Goods</h1> 
      <ul>
        <li v-for="good in filteredGoods" :key="good.id">
          {{ good.name }} 
        </li>
      </ul>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      filteredGoods: Array,
    },
  };
  </script> -->

<script setup>
import { Head, Link } from "@inertiajs/vue3";
import Navbar from "../Components/Navbar.vue";
import Footer from "../Components/Footer.vue";
import ScrollTopBtn from "../Components/ScrollToTopBtn.vue";
import AdminPaginator from "../Components/AdminPaginator.vue";
import Breadcrumbs from "../Components/Breadcrumbs.vue";
import { ref, onMounted } from "vue";
import { usePage } from "@inertiajs/vue3";
import { Carousel, Image, Button, Card } from "ant-design-vue";
import { router } from "@inertiajs/vue3";

// const getImageUrl = (filename) => `/storage/images/${filename}`;

const props = defineProps({
    filteredGoods: Object,
    query: Object, // Receive the query parameters from the backend
});


function handlePageChange(newPageUrl) {
    const url = new URL(newPageUrl, window.location.origin);
    const currentParams = new URLSearchParams(props.query); // Use the received query parameters

    // Set the page parameter based on the new URL
    currentParams.set('page', url.searchParams.get('page'));

    // Navigate to the new URL
    router.get(window.location.pathname + '?' + currentParams.toString());
}

</script>

<template>
    <Head :title="props.filteredGoods.data[0].group.name" />
    <Navbar />
    <ScrollTopBtn />
    <div class="cross-patternSVGLight bg-whiter">
        <div class="py-8">
            <Breadcrumbs
                :currentPage="props.filteredGoods.data[0].group.name"
            />
        </div>

        <div class="space-y-12">
            <div
                class="container max-w-screen-2xl border border-gray-200 rounded-xl bg-whiter shadow-md py-3 px-3 pl-6"
            >
                <div class="flex justify-between space-y-6 items-center">
                    <h1 class="text-textColor font-semibold">
                        <span class="Capitilize">{{
                            props.filteredGoods.data[0].group.name
                        }}</span>
                    </h1>
                    <AdminPaginator :links="props.filteredGoods.links" @page-change="handlePageChange" />
                </div>

                <div
                    class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6 py-4"
                >
                    <div
                        v-for="item in props.filteredGoods.data"
                        :key="item.id"
                        class="p-4 cursor-pointer border border-gray-200 shadow-md"
                    >
                        <Link :href="route('goods.show', { id: item.id })">
                            <div
                                class="flex justify-center items-center overflow-hidden rounded-lg"
                            >
                                <Image
                                    :src="item.image"
                                    alt="goods-image"
                                    class="object-cover transform transition-transform duration-300 hover:scale-110 rounded-lg"
                                    fallback="/images/S-1.png"
                                    :preview="false"
                                    height="200px"
                                    width="200px"
                                />
                            </div>
                            <div>
                                <h2 class="text-md mt-2 text-textColor">
                                    {{ item.name }}
                                </h2>
                                <p
                                    class="text-textColor text-2xl font-semibold"
                                >
                                    <span
                                        class="hover:text-primary transform transition-transform duration-300 hover:scale-110"
                                        >€ {{ item.price }}</span
                                    >
                                </p>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>

            <div
                class="container max-w-screen-2xl border border-gray-200 rounded-xl bg-whiter shadow-md py-3 px-3 pl-6"
            >
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-center">
                    <div class="flex flex-col items-center py-6">
                        <div
                            class="bg-primary text-white rounded-full p-4 mb-2"
                        >
                            <i class="fas fa-headphones text-4xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold pb-4">
                            Tehniskais atbalsts
                        </h3>
                        <p class="text-gray-600 text-center">
                            Piedāvājam tehnisko atbalstu, lai nodrošinātu jums
                            nevainojamu pieredzi.
                        </p>
                    </div>
                    <div
                        class="flex flex-col items-center border-x border-gray-300 py-6"
                    >
                        <div
                            class="bg-primary text-white rounded-full p-4 mb-2"
                        >
                            <i class="fas fa-store text-4xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold pb-4">
                            Plašs preču klāsts
                        </h3>
                        <p class="text-gray-600 px-6 text-center">
                            Plaša produktu izvēle pie konkurētspējīgām cenām.
                        </p>
                    </div>
                    <div
                        class="flex flex-col items-center border-r border-gray-300 py-6"
                    >
                        <div
                            class="bg-primary text-white rounded-full p-4 mb-2"
                        >
                            <i class="fas fa-shipping-fast text-4xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold pb-4">Ātra piegāde</h3>
                        <p class="text-gray-600 text-center">
                            Ātra un uzticama piegāde līdz Jūsu durvīm.
                        </p>
                    </div>
                    <div class="flex flex-col items-center py-6">
                        <div
                            class="bg-primary text-white rounded-full p-4 mb-2"
                        >
                            <i class="fas fa-shield-alt text-4xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold pb-4">
                            Droši maksājumi
                        </h3>
                        <p class="text-gray-600 pb-4 text-center">
                            Jūsu darījumi ir droši ar mūsu drošajiem maksājumu
                            risinājumiem.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <ScrollTopBtn />
        <Footer />
    </div>
</template>
