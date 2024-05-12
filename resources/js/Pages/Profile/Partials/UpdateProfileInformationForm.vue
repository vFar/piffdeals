<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <section>

        <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
            leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
            <p v-if="form.recentlySuccessful"
                class="text-lg text-green-500 px-4 py-2 bg-green-100 text-center rounded-lg mb-4 ">Veiksmīgi veiktas izmaiņas!
            </p>
        </Transition>

        <header>

            <h2 class="text-lg font-medium text-gray-900">Profila informācija</h2>

            <p class="mt-1 text-sm text-textColor">
                Šeit Jūs varat atjaunināt sava profila vārdu, uzvārdu un e-pasta adresi.
            </p>
        </header>

        <form @submit.prevent="form.patch(route('profile.update'))" class="mt-6 space-y-6">

            <div>

                <TextInput id="name" type="text" placeholder="Vārds, uzvārds" class="mt-1 block w-2/4" v-model="form.name" required autofocus
                    autocomplete="name" />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>

                <TextInput id="email" type="email" placeholder="E-pasts" class="mt-1 block w-2/4" v-model="form.email" required
                    autocomplete="username" />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="text-sm mt-2 text-gray-800">
                    Your email address is unverified.
                    <Link :href="route('verification.send')" method="post" as="button"
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Click here to re-send the verification email.
                    </Link>
                </p>

                <div v-show="status === 'verification-link-sent'" class="mt-2 font-medium text-sm text-green-600">
                    A new verification link has been sent to your email address.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" :disabled="form.processing"
                    class="bg-primary rounded-xl hover:bg-accent text-white py-2 px-4 hover:scale-105 duration-200 text-md uppercase">
                    Saglabāt </button>
            </div>

        </form>
    </section>
</template>
