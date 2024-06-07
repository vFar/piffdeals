<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Link, useForm, usePage } from "@inertiajs/vue3";
import { message } from "ant-design-vue";

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

const sendVerificationEmail = () => {
    form.post(route("verification.send"), {
        onSuccess: () => {
            message.success(
                "Jauna verifikācijas saite ir nosūtīta uz Jūsu e-pasta adresi."
            );
        },
        onError: () => {
            message.error("Kļūda, sūtot verifikācijas e-pastu.");
        },
    });
};

const updateProfile = () => {
    form.patch(route("profile.update"), {
        onSuccess: () => {
            message.success("Profila informācija atjaunināta veiksmīgi");
        },
        onError: () => {
            message.error("Kļūda, atjauninot profila informāciju.");
        },
    });
};
</script>

<template>
    <section>
        <form @submit.prevent="updateProfile" class="mt-6 space-y-6">
            <div>
                <InputLabel for="name" value="Vārds, uzvārds"/>
                <TextInput
                    id="name"
                    type="text"
                    placeholder="Vārds, uzvārds"
                    class="mt-1 block w-2/4"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="email" value="E-pasts"/>

                <TextInput
                    id="email"
                    type="email"
                    placeholder="E-pasts"
                    class="mt-1 block w-2/4"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="text-sm mt-2 text-gray-800">
                    Jūsu e-pasts nav verificēts.
                    <Link
                        @click="sendVerificationEmail"
                        as="button"
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Noklikšķiniet šeit, lai atkārtoti nosūtītu verifikācijas
                        e-pastu.
                    </Link>
                </p>
            </div>

            <div class="flex items-center gap-4 justify-end">
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="bg-primary rounded-xl hover:bg-accent text-white py-2 px-4 hover:scale-105 duration-200 text-md uppercase"
                >
                    Saglabāt
                </button>
            </div>
        </form>
    </section>
</template>
