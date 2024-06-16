<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import DeleteUserForm from "./Partials/DeleteUserForm.vue";
import UpdatePasswordForm from "./Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.vue";
import { Head } from "@inertiajs/vue3";
import Footer from "@/Components/Footer.vue";
import Navbar from "@/Components/Navbar.vue";
import AdminSearchbar from "@/Components/AdminSearchbar.vue";
import AdminPaginator from "@/Components/AdminPaginator.vue";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import Modal from "@/Components/Modal.vue";
import { computed, ref } from "vue";
import { usePage, Link } from "@inertiajs/vue3";
import { Button, Image } from "ant-design-vue";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
import "dayjs/locale/lv"; // Import Latvian locale

dayjs.extend(relativeTime);
dayjs.locale("lv"); // Use Latvian locale

const { order } = usePage().props;
const { auth } = usePage().props;

const currency = (value) => {
    return `${parseFloat(value).toFixed(2)} €`;
};
const userFirstLetter = computed(() => auth.user.name.charAt(0).toUpperCase());
console.log(order.items)
</script>
<template>
    <Head title="PASŪTĪJUMS" />

    <Navbar />
    <AuthenticatedLayout>
        <div class="py-8">
            <Breadcrumbs currentPage="Profils" />
        </div>
        <!-- border border-gray-200 rounded-xl bg-whiter shadow-md py-3 px-3 pl-6 -->
        <div
            class="flex gap-10 items-start mt-8 max-lg:gap-5 max-md:grid container max-w-screen-2xl mb-24"
        >
            <div
                class="panel-side grid grid-cols-[1fr] gap-8 max-w-[380px] max-h-[1320px] overflow-y-auto overflow-x-hidden journal-scroll min-w-[342px] max-xl:min-w-[270px] max-md:max-w-full"
            >
                <div
                    class="grid grid-cols-[auto_1fr] gap-4 items-center px-5 py-[25px] border border-gray-200 rounded-xl bg-whiter shadow-md"
                >
                    <div
                        class="flex items-center justify-center w-[60px] h-[60px] bg-gray-100 text-primary text-2xl font-bold rounded-full"
                    >
                        {{ userFirstLetter }}
                    </div>

                    <div class="flex flex-col justify-between">
                        <p class="text-2xl font-medium">
                            Sveiki, {{ $page.props.auth.user.name }}!
                        </p>
                        <p class="text-textColor">
                            {{ $page.props.auth.user.email }}
                        </p>
                    </div>
                </div>
                <div
                    class="max-md:border max-md:border-t-0 max-md:border-r max-md:border-l-[1px] max-md:border-b max-md:border-gray-200 max-md:rounded-md"
                >
                    <div class="">
                        <div
                            class="bg-whiter rounded-t-xl border border-gray-200 max-md:flex max-md:gap-x-4 max-md:justify-between max-md:items-center py-4 px-8 max-md:bg-gray-100 max-md:px-6 max-md:py-5 max-md:rounded-tl-[6px] max-md:rounded-tr-[6px] accordian-toggle md:pointer-events-none select-none"
                        >
                            <p class="text-xl md:font-medium">Mans konts</p>
                            <span
                                class="icon-arrow-right text-2xl md:hidden"
                            ></span>
                        </div>
                        <div
                            class="bg-whiter shadow-md grid border border-t-0 border-r border-l-[1px] border-b border-gray-200 rounded-b-xl max-md:hidden max-md:border-none accordian-content"
                        >
                            <Link href="/profile">
                                <div
                                    class="flex justify-between px-6 py-5 border-t border-gray-200 hover:bg-[#f3f4f682] cursor-pointer"
                                >
                                    <p
                                        class="flex gap-x-4 items-center text-lg font-medium"
                                    >
                                        <span
                                            class="icon-users text-2xl"
                                        ></span>
                                        Profils
                                    </p>
                                    <span
                                        class="icon-arrow-right text-2xl max-md:hidden"
                                    ></span>
                                </div>
                            </Link>

                            <Link href="/profile/orders">
                                <div
                                    class="flex justify-between px-6 py-5 border-t border-gray-200 hover:bg-[#f3f4f682] cursor-pointer"
                                >
                                    <p
                                        class="flex gap-x-4 items-center text-lg font-medium"
                                    >
                                        <span
                                            class="icon-orders text-2xl"
                                        ></span>
                                        Pasūtījumu vēsture
                                    </p>
                                    <span
                                        class="icon-arrow-right text-2xl max-md:hidden"
                                    ></span>
                                </div>
                            </Link>
                            <Link href="/cart">
                                <div
                                    class="flex justify-between px-6 py-5 border-t border-gray-200 hover:bg-[#f3f4f682] cursor-pointer"
                                >
                                    <p
                                        class="flex gap-x-4 items-center text-lg font-medium"
                                    >
                                        <span class="icon-star text-2xl"></span>
                                        Iepirkumu grozs
                                    </p>
                                    <span
                                        class="icon-arrow-right text-2xl max-md:hidden"
                                    ></span>
                                </div>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="flex-auto bg-whiter border border-gray-200 shadow-md rounded-xl"
            >
                <div class="container mx-auto">
                    <h2 class="text-2xl font-medium pl-8 text-textColor py-6">
                        Pasūtījums (#{{ order.id }})
                    </h2>

                    <table
                        class="min-w-full table-auto text-sm text-left text-gray-500"
                    >
                        <thead
                            class="text-xs text-gray-700 uppercase bg-gray-50"
                        >
                            <tr>
                                <th scope="col" class="px-6 py-3">Prece</th>
                                <th scope="col" class="px-6 py-3">Skaits</th>
                                <th scope="col" class="px-6 py-3">Cena</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="item in order.items"
                                :key="item.id"
                                class="bg-white border-b hover:bg-gray-50"
                            >
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <Image
                                            :src="
                                                item.good.image ||
                                                '/images/S-1.png'
                                            "
                                            fallback="/images/S-1.png"
                                            alt="Good image"
                                            class="rounded-md object-cover mr-4 cursor-pointer"
                                            width="60px"
                                            height="60px"
                                            previewMask="Priekšskatījums"
                                        />
                                    </div>
                                </td>
                                <td class="px-6 py-4">{{ item.quantity }}</td>
                                <td class="px-6 py-4">
                                    {{ currency(item.price) }}
                                </td>

                                {{ console.log(order.items) }}
                            </tr>
                        </tbody>
                    </table>
                    <div class="text-right my-4 pr-6">
                        <strong>Kopā:</strong> {{ currency(order.total) }}
                    </div>
                </div>
            </div>
        </div>

        <div
            class="container max-w-screen-2xl border border-gray-200 rounded-xl bg-whiter shadow-md py-3 px-3 pl-6"
        >
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-center">
                <div class="flex flex-col items-center py-6">
                    <div class="bg-primary text-white rounded-full p-4 mb-2">
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
                    <div class="bg-primary text-white rounded-full p-4 mb-2">
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
                    <div class="bg-primary text-white rounded-full p-4 mb-2">
                        <i class="fas fa-shipping-fast text-4xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold pb-4">Ātra piegāde</h3>
                    <p class="text-gray-600 text-center">
                        Ātra un uzticama piegāde līdz Jūsu durvīm.
                    </p>
                </div>
                <div class="flex flex-col items-center py-6">
                    <div class="bg-primary text-white rounded-full p-4 mb-2">
                        <i class="fas fa-shield-alt text-4xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold pb-4">Droši maksājumi</h3>
                    <p class="text-gray-600 pb-4 text-center">
                        Jūsu darījumi ir droši ar mūsu drošajiem maksājumu
                        risinājumiem.
                    </p>
                </div>
            </div>
        </div>
        <Footer />
    </AuthenticatedLayout>
</template>
