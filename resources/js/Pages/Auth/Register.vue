<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import { message } from "ant-design-vue";


const props = defineProps({
    recaptchaSiteKey: String,
    errors: Object // Make sure to define the errors prop
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
    form.post(route("register"), {
        onFinish: () => form.reset(),
        onSuccess: () => {
            form.reset();
        },
        onError: () => {
            if (props.errors) {
                Object.entries(props.errors).forEach(([key, value]) => {
                    // Check if the value is an array
                    if (Array.isArray(value)) {
                        value.forEach(errorMessage => {
                            message.error(errorMessage);
                        });
                    } else { 
                        // Handle single string errors
                        message.error(value); 
                    }
                });
            }
        },
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
                class="w-full flex justify-center max-w-4xl mx-4 p-2 sm:p-6 bg-whiter border border-gray-200 rounded-xl shadow-xl">

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

                        <input
                            class="p-2 rounded-xl border border-gray-200 text-textColor focus:ring-primary focus:border-primary"
                            id="email" type="email" placeholder="E-pasts" v-model="form.email" required
                            autocomplete="username">


                        <div class="relative">
                            <input
                                class="w-full p-2 rounded-xl border border-gray-200 text-textColor focus:ring-primary focus:border-primary"
                                id="password" placeholder="Parole" v-model="form.password" required
                                autocomplete="new-password" :type="passwordType">


                            <button @click="togglePasswordVisibility('password')" class="absolute top-1/2 right-3 -translate-y-1/2"
                                type="button" tabindex="-1">
                                <i :class="passwordType === 'password' ? 'fas fa-eye fa-fw text-gray-400' : 'fas fa-eye-slash fa-fw text-gray-400'"></i>
                            </button>
                        </div>

                        <div class="relative">
                            <input
                                class="p-2 rounded-xl border border-gray-200 w-full text-textColor focus:ring-primary focus:border-primary"
                                id="password_confirmation" v-model="form.password_confirmation" required
                                autocomplete="new-password" placeholder="Parole atkārtoti" :type="confirmPasswordType">


                            <button @click="togglePasswordVisibility('confirmPassword')" class="absolute top-1/2 right-3 -translate-y-1/2"
                                type="button" tabindex="-1">
                                <i :class="confirmPasswordType === 'password' ? 'fas fa-eye fa-fw text-gray-400' : 'fas fa-eye-slash fa-fw text-gray-400'"></i>
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


            <!-- <div class="border border-gray-200 rounded-xl bg-whiter shadow-md py-3 px-3 pl-6">
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
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
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
