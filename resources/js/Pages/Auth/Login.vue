<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

const inputType = ref('password');

// Function to toggle the visibility of the password
const togglePasswordVisibility = () => {
    console.log('Current inputType:', inputType.value); // Check the current type
  inputType.value = inputType.value === 'password' ? 'text' : 'password';
  console.log('New inputType:', inputType.value); // Check the new type
}

</script>

<template>
    <div class="cross-patternSVGLight">
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

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">

            <Head title="Pieslēgties" />

            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>

            <div class="flex max-w-4xl p-6 px-2 items-center bg-whiter border border-gray-200 rounded-md shadow-xl">
                <div class="md:w-1/4 xl:w-3/6 px-2 md:px-8">
                    <h2 class="font-bold text-2xl text-primary uppercase">Pieslēgties</h2>

                    <form @submit.prevent="submit" class="flex flex-col gap-4">
                        <input
                            class="p-2 mt-8 rounded-xl border border-gray-200 text-textColor focus:ring-primary focus:border-primary"
                            id="email" type="email" placeholder="E-pasts" v-model="form.email" required autofocus
                            autocomplete="username">

                        <div class="relative">
                            <input
                                class="p-2 rounded-xl border border-gray-200 w-full text-textColor focus:ring-primary focus:border-primary"
                                id="password" v-model="form.password" required
                                autocomplete="current-password" placeholder="Parole" :type="inputType">
                            <!-- <svg @click="togglePasswordVisibility" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="gray"
                                class="bi bi-eye absolute top-1/2 right-3 -translate-y-1/2 pointer" viewBox="0 0 16 16">
                                <path
                                    d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                                <path
                                    d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
                            </svg> -->
                            <button @click="togglePasswordVisibility" class="bi bi-eye absolute top-1/2 right-3 -translate-y-1/2 pointer" type="button">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>

                        <div class="block mt-2">
                            <label class="flex items-center">
                                <Checkbox name="remember" v-model:checked="form.remember" />
                                <span class="ms-2 text-sm text-gray-600">Atcerēties mani</span>
                            </label>
                        </div>
                        <!-- <button
                            class="bg-primary rounded-xl hover:bg-gray-700 text-white py-2 hover:scale-105 duration-300 text-md uppercase"
                            :class="{ 'opacity-25': form.processing }
                            :disabled =" form.processing">
                            Pieslēgties
                        </button> -->
                        <button class="bg-primary rounded-xl hover:bg-gray-700 text-white py-2 hover:scale-105 duration-300 text-md uppercase" :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing">
                            Pieslēgties
                        </button>
                    </form>

                    <div class="mt-6 grid grid-cols-3 items-center text-gray-400">
                        <hr class="border-gray-400">
                        <p class="text-center text-sm">VAI</p>
                        <hr class="border-gray-400">
                    </div>

                    <button
                        class="bg-white border py-2 w-full rounded-xl mt-5 flex justify-center items-center text-sm hover:scale-105 duration-300 text-[#002D74]">
                        <svg class="mr-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="25px">
                            <path fill="#FFC107"
                                d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z" />
                            <path fill="#FF3D00"
                                d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z" />
                            <path fill="#4CAF50"
                                d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z" />
                            <path fill="#1976D2"
                                d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z" />
                        </svg>
                        Login with Google
                    </button>

                    <button
                        class="bg-white border py-2 w-full rounded-xl mt-5 flex justify-center items-center text-sm hover:scale-105 duration-300 text-[#002D74]">
                        <i class="fa-brands fa-facebook"></i>
                        Login with Facebook
                    </button>

                    <div class="mt-5 text-xs border-b border-[#002D74] py-4 text-[#002D74]">
                        <Link v-if="canResetPassword" :href="route('password.request')"
                            class="text-xs text-gray-600 hover:text-primary rounded-md ">
                        Aizmirsi paroli?
                        </Link>
                    </div>

                    <div class="mt-3 text-xs flex justify-between items-center text-[#002D74]">
                        <p>Nav konta?</p>
                        <SecondaryButton class="py-2 px-5 bg-white border rounded-xl hover:scale-110 duration-300">
                            <Link href="/register">Reģistrējies</Link></SecondaryButton>
                    </div>
                </div>

                <div
                    class="flex items-center md:block w-1/2 rounded-2xl bg-gradient-to-b from-primary to-secondary px-4 py-6 text-white md:mx-6 md:p-12 md:block hidden">
                    <h4 class="my-36 text-2xl font-semibold text-left text-[2rem] select-none leading-relaxed">
                        Mūsu biznesa mērķis? Digitalizēt iepirkšanos tiešsaistē.
                    </h4>
                </div>
            </div>

            <!-- <div class="border border-gray-200 rounded-md bg-whiter shadow-md py-3 px-3 pl-6">
                <form @submit.prevent="submit">
                    <div>
                        <InputLabel for="email" value="E-pasts" />

                        <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required
                            autofocus autocomplete="username" />

                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="password" value="Parole" />

                        <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password"
                            required autocomplete="current-password" />

                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <div class="block mt-4">
                        <label class="flex items-center">
                            <Checkbox name="remember" v-model:checked="form.remember" />
                            <span class="ms-2 text-sm text-gray-600">Atcerēties mani</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <Link v-if="canResetPassword" :href="route('password.request')"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Aizmirsi paroli?
                        </Link>


                        <SecondaryButton class="ms-4" :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing">
                            Pieslēgties
                        </SecondaryButton>

                        <Link href="/register">
                        <PrimaryButton class="ms-4">
                            Reģistrēties
                        </PrimaryButton>
                        </Link>

                    </div>
                </form>
            </div> -->
        </div>
    </div>
</template>
