<script setup>
import Checkbox from "@/Components/Checkbox.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    googleLoginUrl: String, // receive Google login URL as a prop
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};

const inputType = ref("password");

// Function to toggle the visibility of the password
const togglePasswordVisibility = () => {
    inputType.value = inputType.value === "password" ? "text" : "password";
};

const redirectToGoogle = () => {
    window.location.href = props.googleLoginUrl; // Use props.googleLoginUrl directly
};
</script>

<template>
    <div class="cross-patternSVGLight">
        <Link href="/" class="static 2xl:fixed">
            <button
                type="button"
                class="mt-6 ml-4 bg-white inline-flex items-center font-semibold border text-primary border-primary hover:text-whiter hover:bg-primary rounded-lg text-xs md:text-sm px-3 md:px-5 py-1.5 md:py-2.5 text-center mb-2"
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

        <div class="min-h-screen flex items-center justify-center">
            <Head title="Pieslēgties" />

            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>

            <div
                class="flex justify-center max-w-4xl mx-4 p-2 sm:p-6 bg-whiter border border-gray-200 rounded-xl shadow-xl w-full"
            >
                <!-- Responsive padding and width -->
                <div class="w-full md:w-1/2 px-2 md:px-8">
                    <h2
                        class="font-bold text-2xl text-primary uppercase text-center md:text-left"
                    >
                        Pieslēgties
                    </h2>

                    <form @submit.prevent="submit" class="flex flex-col gap-4">
                        <div class="mt-6">
                            <InputError :message="form.errors.email" />
                            <InputError :message="form.errors.password" />
                        </div>

                        <input
                            class="p-2 mt-8 rounded-xl border border-gray-200 text-textColor focus:ring-primary focus:border-primary"
                            id="email"
                            type="email"
                            placeholder="E-pasts"
                            v-model="form.email"
                            required
                            autofocus
                            autocomplete="username"
                        />

                        <!-- <InputError :message="form.errors.email" /> -->

                        <div class="relative">
                            <input
                                class="p-2 rounded-xl border border-gray-200 w-full text-textColor focus:ring-primary focus:border-primary"
                                id="password"
                                v-model="form.password"
                                required
                                autocomplete="current-password"
                                placeholder="Parole"
                                :type="inputType"
                            />

                            <!-- <InputError :message="form.errors.password" /> -->

                            <button
                                @click="togglePasswordVisibility"
                                class="absolute top-1/2 right-3 -translate-y-1/2"
                                type="button"
                            >
                                <i
                                    :class="
                                        inputType === 'password'
                                            ? 'fas fa-eye fa-fw text-gray-400'
                                            : 'fas fa-eye-slash fa-fw text-gray-400'
                                    "
                                ></i>
                            </button>
                        </div>

                        <div class="block mt-2">
                            <label class="flex items-center">
                                <Checkbox
                                    name="remember"
                                    v-model:checked="form.remember"
                                />
                                <span class="ms-2 text-sm text-gray-600"
                                    >Atcerēties mani</span
                                >
                            </label>
                        </div>
                        <!-- <button
                            class="bg-primary rounded-xl hover:bg-gray-700 text-white py-2 hover:scale-105 duration-300 text-md uppercase"
                            :class="{ 'opacity-25': form.processing }
                            :disabled =" form.processing">
                            Pieslēgties
                        </button> -->
                        <button
                            class="bg-primary rounded-xl hover:bg-gray-700 text-white py-2 hover:scale-105 duration-300 text-md uppercase"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            Pieslēgties
                        </button>
                    </form>

                    <div
                        class="mt-6 grid grid-cols-3 items-center text-gray-400"
                    >
                        <hr class="border-gray-400" />
                        <p class="text-center text-sm">VAI</p>
                        <hr class="border-gray-400" />
                    </div>

                    <button
                    @click="redirectToGoogle"

                        class="bg-white border py-2 w-full rounded-xl mt-5 flex justify-center items-center text-sm hover:scale-105 hover:text-primary duration-300 text-textColor"
                    >
                        <svg
                            class="mr-3"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 48 48"
                            width="25px"
                        >
                            <path
                                fill="#FFC107"
                                d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"
                            />
                            <path
                                fill="#FF3D00"
                                d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"
                            />
                            <path
                                fill="#4CAF50"
                                d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"
                            />
                            <path
                                fill="#1976D2"
                                d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"
                            />
                        </svg>
                        Google
                    </button>

                    <button
                        class="bg-white border py-2 w-full rounded-xl mt-5 flex justify-center items-center text-sm hover:scale-105 hover:text-primary duration-300 text-textColor"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink"
                            viewBox="0,0,256,256"
                            width="25px"
                            class="mr-3"
                        >
                            <g
                                fill="#1877f2"
                                fill-rule="nonzero"
                                stroke="none"
                                stroke-width="1"
                                stroke-linecap="butt"
                                stroke-linejoin="miter"
                                stroke-miterlimit="10"
                                stroke-dasharray=""
                                stroke-dashoffset="0"
                                font-family="none"
                                font-weight="none"
                                font-size="none"
                                text-anchor="none"
                                style="mix-blend-mode: normal"
                            >
                                <g transform="scale(5.12,5.12)">
                                    <path
                                        d="M25,3c-12.15,0 -22,9.85 -22,22c0,11.03 8.125,20.137 18.712,21.728v-15.897h-5.443v-5.783h5.443v-3.848c0,-6.371 3.104,-9.168 8.399,-9.168c2.536,0 3.877,0.188 4.512,0.274v5.048h-3.612c-2.248,0 -3.033,2.131 -3.033,4.533v3.161h6.588l-0.894,5.783h-5.694v15.944c10.738,-1.457 19.022,-10.638 19.022,-21.775c0,-12.15 -9.85,-22 -22,-22z"
                                    ></path>
                                </g>
                            </g>
                        </svg>
                        Facebook
                    </button>

                    <div
                        class="mt-5 text-xs border-b border-textColor py-4 text-textColor"
                    >
                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="text-xs text-gray-600 hover:text-primary rounded-xl"
                        >
                            Aizmirsi paroli?
                        </Link>
                    </div>

                    <div
                        class="mt-3 text-xs flex justify-between items-center text-red-600"
                    >
                        <p>Nav konta?</p>
                        <Link href="/register">
                            <SecondaryButton
                                class="py-2 px-5 bg-white border rounded-xl hover:scale-110 duration-300"
                            >
                                Reģistrējies
                            </SecondaryButton>
                        </Link>
                    </div>
                </div>

                <div
                    class="hidden md:flex md:w-1/2 items-center rounded-2xl bg-gradient-to-b from-primary to-secondary px-4 py-6 text-white md:mx-6 md:p-12"
                >
                    <h4
                        class="my-36 text-2xl font-semibold text-left text-[2rem] select-none leading-relaxed"
                    >
                        Mūsu biznesa mērķis? Digitalizēt iepirkšanos tiešsaistē.
                    </h4>
                </div>
            </div>

            <!-- <div class="border border-gray-200 rounded-xl bg-whiter shadow-md py-3 px-3 pl-6">
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
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
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
