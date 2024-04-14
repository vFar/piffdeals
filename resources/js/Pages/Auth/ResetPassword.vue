<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

const getImageUrl = (filename) => {
    return `/storage/images/${filename}`;
};
</script>

<template>
    <div class="cross-patternSVGLight">

        <Head title="Atjaunināt paroli" />
        <Link href="/" class="fixed">
        <button type="button"
            class="mt-6 ml-4 bg-white inline-flex items-center font-semibold border text-primary border-primary hover:text-whiter hover:bg-primary rounded-lg text-sm px-5 py-2.5 text-center mb-2">
            <svg class="w-[18px] h-[18px] mr-2 hover:text-whiter" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.3"
                    d="M13 5H1m0 0 4 4M1 5l4-4" />
            </svg> Atgriezties uz veikalu
        </button>
        </Link>

        <div class="flex min-h-screen justify-center items-center mx-auto px-6 sm:px-6 lg:px-8">
            <div
                class="w-full max-w-md px-6 py-4 mt-12 bg-white shadow-xl overflow-hidden sm:rounded-lg border border-gray-200 rounded-xl">

                <Link href="/" class="flex items-center justify-center select-none mb-12 mt-6">
                <img :src="getImageUrl('S-3.png')" alt="Logo" class="h-auto w-full max-w-[150px] sm:max-w-[150px]">
                </Link>

                <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                    {{ status }}
                </div>
                <form @submit.prevent="submit">
                    <div>
                        <input id="email" type="email" placeholder="E-pasts"
                            class="p-2 rounded-xl border border-gray-200 w-full text-textColor focus:ring-primary focus:border-primary"
                            v-model="form.email" required autofocus autocomplete="username" />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>
                    <div class="mt-4">
                        <TextInput id="password" type="password" placeholder="Parole"
                            class="p-2 rounded-xl border border-gray-200 w-full text-textColor focus:ring-primary focus:border-primary"
                            v-model="form.password" required autocomplete="new-password" />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>
                    <div class="mt-4">
                        <TextInput id="password_confirmation" type="password" placeholder="Parole atkārtoti"
                            class="p-2 rounded-xl border border-gray-200 w-full text-textColor focus:ring-primary focus:border-primary"
                            v-model="form.password_confirmation" required autocomplete="new-password" />
                        <InputError class="mt-2" :message="form.errors.password_confirmation" />
                    </div>
                    <div class="flex items-center justify-end mt-4">
                        <button class="bg-primary w-full rounded-xl hover:bg-gray-700 text-white py-2 hover:scale-105 duration-300 text-md uppercase" :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing">
                            Atjaunināt paroli
                        </button>
                    </div>
                </form>
            </div>

            <!-- <div
                class="w-full max-w-md px-6 py-4 mt-12 bg-white shadow-xl overflow-hidden sm:rounded-lg border border-gray-200 rounded-xl">
            <form @submit.prevent="submit">
                <div>
                    <input
                        id="email"
                        type="email"
                        placeholder="E-pasts"
                        class="p-2 rounded-xl border border-gray-200 w-full text-textColor focus:ring-primary focus:border-primary"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                    />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>
                <div class="mt-4">
                    <TextInput
                        id="password"
                        type="password"
                        placeholder="Parole"
                        class="p-2 rounded-xl border border-gray-200 w-full text-textColor focus:ring-primary focus:border-primary"
                        v-model="form.password"
                        required
                        autocomplete="new-password"
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>
                <div class="mt-4">
                    <TextInput
                        id="password_confirmation"
                        type="password"
                        placeholder="Parole atkārtoti"
                        class="p-2 rounded-xl border border-gray-200 w-full text-textColor focus:ring-primary focus:border-primary"
                        v-model="form.password_confirmation"
                        required
                        autocomplete="new-password"
                    />
                    <InputError class="mt-2" :message="form.errors.password_confirmation" />
                </div>
                <div class="flex items-center justify-end mt-4">
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Reset Password
                    </PrimaryButton>
                </div>
            </form>
            </div> -->
        </div>
    </div>
</template>
