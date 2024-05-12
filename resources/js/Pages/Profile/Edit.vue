<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';
import Footer from '@/Components/Footer.vue';
import Navbar from '@/Components/Navbar.vue';
import Breadcrumbs from '@/Components/Breadcrumbs.vue';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>

    <Head title="PROFILS" />


    <Navbar />
    <AuthenticatedLayout>

        <div class="">
            <div class="py-8">
                <Breadcrumbs currentPage="Profils" />
            </div>

            <div
                class="container mx-auto max-w-screen-xl border border-gray-200 rounded-xl bg-whiter shadow-md mb-8 px-3 pl-6">
                <div class="py-4 sm:py-8">
                    <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status"
                        class="max-w-full" />
                </div>
            </div>

            <div
                class="container mx-auto max-w-screen-xl border border-gray-200 rounded-xl bg-whiter shadow-md mb-8 px-3 pl-6">
                <div class="py-4 sm:py-8">
                    <UpdatePasswordForm class="max-w-full " />
                </div>
            </div>

            <div
                class="container mx-auto max-w-screen-xl border border-gray-200 rounded-xl bg-whiter shadow-md mb-8 px-3 pl-6">
                <div class="py-4 sm:py-8">
                    <DeleteUserForm class="max-w-full" />

                </div>
            </div>
        </div>
        <Footer />
    </AuthenticatedLayout>
</template>
