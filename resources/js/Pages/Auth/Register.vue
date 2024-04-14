<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';


const props = defineProps({
    recaptchaSiteKey: String
});

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    recaptcha: ''
});

const recaptchaWidgetId = ref(null);

const onLoadCallback = () => {
    recaptchaWidgetId.value = grecaptcha.render('recaptcha-container', {
        'sitekey': props.recaptchaSiteKey, // Replace with your site key
        'callback': verifyCallback,
    });
};

const verifyCallback = (response) => {
    form.recaptcha = response; // Assign the response token to the form data
};

onMounted(() => {
    if (window.grecaptcha && window.grecaptcha.render) {
        onLoadCallback();
    } else {
        window.onloadCallback = onLoadCallback;
    }
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

const passwordType = ref('password');
const confirmPasswordType = ref('password');

// This function now accepts an identifier to toggle the appropriate input field
const togglePasswordVisibility = (field) => {
    if (field === 'password') {
        passwordType.value = passwordType.value === 'password' ? 'text' : 'password';
    } else if (field === 'confirmPassword') {
        confirmPasswordType.value = confirmPasswordType.value === 'password' ? 'text' : 'password';
    }
}

</script>

<template>
    <div class="cross-patternSVGLight">
        <Link href="/" class="static 2xl:fixed">
        <button type="button"
            class="mt-6 ml-4 bg-white inline-flex items-center font-semibold border text-primary border-primary hover:text-whiter hover:bg-primary rounded-lg text-xs md:text-sm px-3 md:px-5 py-1.5 md:py-2.5 text-center mb-2">
            <svg class="w-[18px] h-[18px] mr-2 hover:text-whiter" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.3"
                    d="M13 5H1m0 0 4 4M1 5l4-4" />
            </svg> Atgriezties uz veikalu
        </button>
        </Link>

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 px-4 sm:px-6">

            <Head title="Reģistrēties" />

            <div
                class="w-full flex justify-center max-w-4xl mx-4 p-2 sm:p-6 bg-whiter border border-gray-200 rounded-md shadow-xl">

                <div
                    class="hidden md:flex md:w-1/2 items-center rounded-2xl bg-gradient-to-b from-primary to-secondary px-4 py-6 text-white md:mx-6 md:p-12">
                    <div class="my-36">
                        <h4 class="font-semibold text-left text-[2rem] select-none leading-relaxed">
                            Laiks kļūt par dalībnieku. <br>Viss sākas šeit!
                        </h4>
                    </div>
                </div>

                <div class="w-full md:w-1/2 px-2 md:px-8">
                    <h2 class="font-bold text-2xl text-primary uppercase text-center md:text-right">Reģistrēties</h2>

                    <form @submit.prevent="submit" class="flex flex-col gap-4">
                        <input
                            class="p-2 mt-8 rounded-xl border border-gray-200 text-textColor focus:ring-primary focus:border-primary"
                            id="name" type="text" placeholder="Vārds, uzvārds" v-model="form.name" required autofocus
                            autocomplete="name">
                        <InputError class="mt-2" :message="form.errors.name" />

                        <input
                            class="p-2 rounded-xl border border-gray-200 text-textColor focus:ring-primary focus:border-primary"
                            id="email" type="email" placeholder="E-pasts" v-model="form.email" required
                            autocomplete="username">

                        <InputError class="mt-2" :message="form.errors.email" />

                        <div class="relative">
                            <input
                                class="w-full p-2 rounded-xl border border-gray-200 text-textColor focus:ring-primary focus:border-primary"
                                id="password" placeholder="Parole" v-model="form.password" required
                                autocomplete="new-password" :type="passwordType">

                            <InputError class="mt-2" :message="form.errors.password" />

                            <button @click="togglePasswordVisibility('password')" class="absolute top-1/2 right-3 -translate-y-1/2"
                                type="button">
                                <i :class="passwordType === 'password' ? 'fas fa-eye fa-fw' : 'fas fa-eye-slash fa-fw'"></i>
                            </button>
                        </div>

                        <div class="relative">
                            <input
                                class="p-2 rounded-xl border border-gray-200 w-full text-textColor focus:ring-primary focus:border-primary"
                                id="password_confirmation" v-model="form.password_confirmation" required
                                autocomplete="new-password" placeholder="Parole atkārtoti" :type="confirmPasswordType">

                            <InputError class="mt-2" :message="form.errors.password_confirmation" />

                            <button @click="togglePasswordVisibility('confirmPassword')" class="absolute top-1/2 right-3 -translate-y-1/2"
                                type="button">
                                <i :class="confirmPasswordType === 'password' ? 'fas fa-eye fa-fw' : 'fas fa-eye-slash fa-fw'"></i>
                            </button>
                        </div>
                        <!-- <button
                            class="bg-primary rounded-xl hover:bg-gray-700 text-white py-2 hover:scale-105 duration-300 text-md uppercase"
                            :class="{ 'opacity-25': form.processing }
                            :disabled =" form.processing">
                            Pieslēgties
                        </button> -->
                        <div id="recaptcha-container" class="w-full flex justify-center px-2 md:px-0">
                            <!-- reCAPTCHA widget will render here -->
                        </div>
                        <button type="submit"
                            class="bg-primary rounded-xl hover:bg-gray-700 text-white py-2 hover:scale-105 duration-300 text-md uppercase"
                            :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Reģistrēties
                        </button>
                    </form>

                    <div class="mt-6 grid grid-cols-3 items-center text-gray-400">
                        <hr class="border-gray-400">
                        <p class="text-center text-sm">VAI</p>
                        <hr class="border-gray-400">
                    </div>

                    <button
                        class="bg-white border py-2 w-full rounded-xl mt-5 flex justify-center items-center text-sm hover:scale-105 hover:text-primary duration-300 text-textColor">
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
                        class="bg-white border py-2 w-full rounded-xl mt-5 flex justify-center items-center text-sm hover:scale-105 hover:text-primary duration-300 text-textColor">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            viewBox="0,0,256,256" width="25px" class="mr-3">
                            <g fill="#1877f2" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt"
                                stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0"
                                font-family="none" font-weight="none" font-size="none" text-anchor="none"
                                style="mix-blend-mode: normal">
                                <g transform="scale(5.12,5.12)">
                                    <path
                                        d="M25,3c-12.15,0 -22,9.85 -22,22c0,11.03 8.125,20.137 18.712,21.728v-15.897h-5.443v-5.783h5.443v-3.848c0,-6.371 3.104,-9.168 8.399,-9.168c2.536,0 3.877,0.188 4.512,0.274v5.048h-3.612c-2.248,0 -3.033,2.131 -3.033,4.533v3.161h6.588l-0.894,5.783h-5.694v15.944c10.738,-1.457 19.022,-10.638 19.022,-21.775c0,-12.15 -9.85,-22 -22,-22z">
                                    </path>
                                </g>
                            </g>
                        </svg>

                        Login with Facebook
                    </button>

                    <hr class="mt-5 text-xs border-gray-400 py-2 text-gray-400">
                    </hr>

                    <div class="text-xs flex justify-between items-center text-gray-400">
                        <p>Konts pastāv?</p>

                        <Link href="/login">
                        <SecondaryButton class="py-2 px-5 bg-white border rounded-xl hover:scale-110 duration-300">
                            Pieslēdzies
                        </SecondaryButton>
                        </Link>
                    </div>
                </div>
            </div>


            <!-- <div class="border border-gray-200 rounded-md bg-whiter shadow-md py-3 px-3 pl-6">
                <form @submit.prevent="submit">
                    <div>
                        <InputLabel for="name" value="Vārds, uzvārds" />

                        <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required
                            autofocus autocomplete="name" />

                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="email" value="E-pasts" />

                        <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required
                            autocomplete="username" />

                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="password" value="Parole" />

                        <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password"
                            required autocomplete="new-password" />

                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="password_confirmation" value="Apstiprināt paroli" />

                        <TextInput id="password_confirmation" type="password" class="mt-1 block w-full"
                            v-model="form.password_confirmation" required autocomplete="new-password" />

                        <InputError class="mt-2" :message="form.errors.password_confirmation" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <Link :href="route('login')"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Jau reģistrēts?
                        </Link>

                        <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing">
                            Reģistrēties
                        </PrimaryButton>
                    </div>
                </form>
            </div> -->
        </div>
    </div>
</template>
