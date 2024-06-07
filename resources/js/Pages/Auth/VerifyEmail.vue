<script setup>
import { computed, watchEffect } from "vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { message } from "ant-design-vue";

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route("verification.send"));
};

const verificationLinkSent = computed(
    () => props.status === "verification-link-sent"
);

watchEffect(() => {
    if (verificationLinkSent.value) {
        message.success(
            "Jauna verifikācijas saite ir nosūtīta uz jūsu reģistrācijas laikā norādīto e-pasta adresi."
        );
    }
});

const getImageUrl = (filename) => {
    return `/storage/images/${filename}`;
};
</script>

<template>
    <div class="cross-patternSVGLight">
        <Head title="E-pasta verifikācija" />
        <Link href="/" class="fixed">
            <button
                type="button"
                class="mt-6 ml-4 bg-white inline-flex items-center font-semibold border text-primary border-primary hover:text-whiter hover:bg-primary rounded-lg text-sm px-5 py-2.5 text-center mb-2"
            >
                <svg
                    class="w-[18px] h-[18px] mr-2 hover:text-whiter"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 14 10"
                >
                    <path
                        stroke="currentColor"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.3"
                        d="M13 5H1m0 0 4 4M1 5l4-4"
                    />
                </svg>
                Atgriezties uz veikalu
            </button>
        </Link>

        <div
            class="flex min-h-screen justify-center items-center mx-auto px-6 sm:px-6 lg:px-8"
        >
            <div
                class="w-full max-w-md px-6 py-4 mt-12 bg-white shadow-xl overflow-hidden sm:rounded-lg border border-gray-200 rounded-xl"
            >
                <Link
                    href="/"
                    class="flex items-center justify-center select-none mb-12 mt-6"
                >
                    <img
                        :src="getImageUrl('S-3.png')"
                        alt="Logo"
                        class="h-auto w-full max-w-[150px] sm:max-w-[150px]"
                    />
                </Link>

                <div class="mb-4 text-sm text-gray-600">
                    Paldies par reģistrāciju! Pirms sākat, lūdzu, apstipriniet
                    savu e-pasta adresi, noklikšķinot uz saites, kuru mēs tikko
                    nosūtījām uz Jūsu e-pastu. Ja Jūs nesaņēmāt e-pastu, mēs ar
                    prieku nosūtīsim citu.
                </div>

                <form @submit.prevent="submit">
                    <div class="mt-4 flex items-center justify-between">
                        <PrimaryButton
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            Nosūtīt verifikācijas e-pastu vēlreiz
                        </PrimaryButton>

                        <Link
                            :href="route('logout')"
                            method="post"
                            as="button"
                            class="underline uppercase text-sm text-textColor hover:text-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            Atslēgties
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
