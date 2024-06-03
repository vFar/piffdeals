<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';
import Footer from '@/Components/Footer.vue';
import Navbar from '@/Components/Navbar.vue';
import Breadcrumbs from '@/Components/Breadcrumbs.vue';
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Button } from "ant-design-vue";
import dayjs from "dayjs";
import relativeTime from 'dayjs/plugin/relativeTime';
import 'dayjs/locale/lv'; // Import Latvian locale

dayjs.extend(relativeTime);
dayjs.locale('lv'); // Use Latvian locale



defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const page = usePage();

// const pageProps = usePage().props;
// const authUser = computed(() => pageProps.auth.user);

// const isAuthenticated = computed(() => !!authUser.value);
// const isAdmin = computed(() => authUser.value?.role_id === 2);

const { auth } = usePage().props;

const formatRelativeDate = (date) => {
    return dayjs(date).fromNow();
};

const formatDate = (date) => {
    return dayjs(date).format("DD.MM.YYYY");
};

</script>

<template>

    <Head title="PROFILS" />


    <Navbar />
    <AuthenticatedLayout>
        <div class="py-8">
            <Breadcrumbs currentPage="Profils" />

        </div>
        <!-- border border-gray-200 rounded-xl bg-whiter shadow-md py-3 px-3 pl-6 -->
        <div class="flex gap-10 items-start mt-8 max-lg:gap-5 max-md:grid container max-w-screen-2xl ">
            <div class="panel-side grid grid-cols-[1fr] gap-8 max-w-[380px] max-h-[1320px] overflow-y-auto overflow-x-hidden journal-scroll min-w-[342px] max-xl:min-w-[270px] max-md:max-w-full">
                <div class="grid grid-cols-[auto_1fr] gap-4 items-center px-5 py-[25px] border border-gray-200 rounded-xl bg-whiter shadow-md">
                    <div class="">
                        <img src="https://demo.bagisto.com/bagisto-common/themes/shop/default/build/assets/user-placeholder-4670a452.png" class="w-[60px] h-[60px] rounded-full" alt="Profile Image">
                        


                    </div>
                    <div class="flex flex-col justify-between">
                        <p class="text-2xl font-medium">Sveiks, {{ $page.props.auth.user.name }}!</p>
                        <p class="text-textColor">{{ $page.props.auth.user.email }}</p>
                    </div>
                </div>
                <div class="max-md:border max-md:border-t-0 max-md:border-r max-md:border-l-[1px] max-md:border-b max-md:border-gray-200 max-md:rounded-md">
                    <div>
                        <div class="bg-whiter rounded-t-xl border border-gray-200 max-md:flex max-md:gap-x-4 max-md:justify-between max-md:items-center py-4 px-8 max-md:bg-gray-100 max-md:px-6 max-md:py-5 max-md:rounded-tl-[6px] max-md:rounded-tr-[6px] accordian-toggle md:pointer-events-none select-none">
                            <p class="text-xl md:font-medium">Mans konts</p>
                            <span class="icon-arrow-right text-2xl md:hidden"></span>
                        </div>
                        <div class="bg-whiter shadow-md grid border border-t-0 border-r border-l-[1px] border-b border-gray-200 rounded-b-xl max-md:hidden max-md:border-none accordian-content">
                            <a href="#">
                                <div class="flex justify-between px-6 py-5 border-t border-gray-200 hover:bg-[#f3f4f682] cursor-pointer bg-gray-100">
                                    <p class="flex gap-x-4 items-center text-lg font-medium">
                                        <span class="icon-users text-2xl"></span> Profils
                                    </p>
                                    <span class="icon-arrow-right text-2xl max-md:hidden"></span>
                                </div>
                            </a>
                            <a href="#">
                                <div class="flex justify-between px-6 py-5 border-t border-gray-200 hover:bg-[#f3f4f682] cursor-pointer">
                                    <p class="flex gap-x-4 items-center text-lg font-medium">
                                        <span class="icon-location text-2xl"></span> Adreses
                                    </p>
                                    <span class="icon-arrow-right text-2xl max-md:hidden"></span>
                                </div>
                            </a>
                            <a href="#">
                                <div class="flex justify-between px-6 py-5 border-t border-gray-200 hover:bg-[#f3f4f682] cursor-pointer">
                                    <p class="flex gap-x-4 items-center text-lg font-medium">
                                        <span class="icon-orders text-2xl"></span> Pasūtījumu vēsture
                                    </p>
                                    <span class="icon-arrow-right text-2xl max-md:hidden"></span>
                                </div>
                            </a>
                            <a href="#">
                                <div class="flex justify-between px-6 py-5 border-t border-gray-200 hover:bg-[#f3f4f682] cursor-pointer">
                                    <p class="flex gap-x-4 items-center text-lg font-medium">
                                        <span class="icon-download text-2xl"></span> Vēlmju saraksts
                                    </p>
                                    <span class="icon-arrow-right text-2xl max-md:hidden"></span>
                                </div>
                            </a>
                            <a href="#">
                                <div class="flex justify-between px-6 py-5 border-t border-gray-200 hover:bg-[#f3f4f682] cursor-pointer">
                                    <p class="flex gap-x-4 items-center text-lg font-medium">
                                        <span class="icon-star text-2xl"></span> Iepirkumu grozs
                                    </p>
                                    <span class="icon-arrow-right text-2xl max-md:hidden"></span>
                                </div>
                            </a>
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex-auto bg-whiter border border-gray-200 shadow-md rounded-xl ">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-medium pl-8 text-textColor"> Profils </h2>
                    <a href="#" class="secondary-button py-3 px-5 border-gray-200 font-normal"> Rediģēt </a>
                </div>
                <div class="grid grid-cols-1 gap-y-6 mt-8">
                    <div class="grid grid-cols-[2fr_3fr] w-full px-8 py-3 border-b border-[#E9E9E9]">
                        <p class="text-sm font-medium"> Vārds, uzvārds </p>
                        <p class="text-sm text-[#6E6E6E] font-medium"> {{ $page.props.auth.user.name }}</p>
                    </div>
                    <div class="grid grid-cols-[2fr_3fr] w-full px-8 py-3 border-b border-[#E9E9E9]">
                        <p class="text-sm font-medium"> E-pasts </p>
                        <p class="text-sm font-medium text-[#6E6E6E]"> {{ $page.props.auth.user.email }} </p>
                    </div>
                    <div class="grid grid-cols-[2fr_3fr] w-full px-8 py-3 border-b border-[#E9E9E9]">
                        <p class="text-sm font-medium"> Izveidots </p>
                        <p class="text-sm text-[#6E6E6E] font-medium"> {{ formatRelativeDate($page.props.auth.user.created_at) }} </p>
                    </div>

                     <DeleteUserForm class="py-3 px-8" />
                </div>
            </div>
        </div>

        <div class=" py-24">


            <div
                class="container mx-auto max-w-screen-2xl border border-gray-200 rounded-xl bg-whiter shadow-md mb-8 px-3 pl-6">
                <div class="py-4 sm:py-8">
                    <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status"
                        class="max-w-full" />
                </div>
            </div>

            <div
                class="container mx-auto max-w-screen-2xl border border-gray-200 rounded-xl bg-whiter shadow-md mb-8 px-3 pl-6">
                <div class="py-4 sm:py-8">
                    <UpdatePasswordForm class="max-w-full " />
                </div>
            </div>

            <div
                class="container mx-auto max-w-screen-2xl border border-gray-200 rounded-xl bg-whiter shadow-md mb-8 px-3 pl-6">
                <div class="py-4 sm:py-8">
                    <DeleteUserForm class="max-w-full" />

                </div>
            </div>
        </div>
        <Footer />
    </AuthenticatedLayout>
</template>
